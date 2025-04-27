<?php
require_once 'controller/database.php'; // Your Database class
require_once 'controller/authCheck.php'; // Sets $playerId

$conn = new database();
$db = $conn->getConnection();

// Fetch all guilds with member count
$guilds = $conn->fetchAll("
    SELECT g.*, 
        (SELECT COUNT(*) FROM guild_members gm WHERE gm.guild_id = g.id) AS member_count
    FROM guilds g
    ORDER BY g.created_at DESC
");

// Fetch my guild (if any)
$myGuild = $conn->fetch("
    SELECT g.*, gm.is_officer
    FROM guild_members gm
    JOIN guilds g ON gm.guild_id = g.id
    WHERE gm.user_id = ?
    LIMIT 1
", [$playerId]);

// Handle create guild POST
$createError = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_guild'])) {
    $name = trim($_POST['name']);
    $tag = strtoupper(trim($_POST['tag']));
    $desc = trim($_POST['description']);

    if (!$name || !$tag || strlen($tag) !== 4) {
        $createError = "Name and 4-letter tag are required.";
    } else {
        // Check if already in a guild
        $inGuild = $conn->fetch("SELECT 1 FROM guild_members WHERE user_id = ?", [$playerId]);
        if ($inGuild) {
            $createError = "You are already in a guild.";
        } else {
            // Try to create guild
            try {
                $conn->beginTransaction();
                $conn->execute("INSERT INTO guilds (name, tag, description, owner_id) VALUES (?, ?, ?, ?)", [$name, $tag, $desc, $playerId]);
                $guildId = $conn->lastInsertId();
                $conn->execute("INSERT INTO guild_members (guild_id, user_id, is_officer) VALUES (?, ?, 1)", [$guildId, $playerId]);
                $conn->commit();
                header("Location: guilds.php");
                exit;
            } catch (Exception $e) {
                $conn->rollBack();
                $createError = "Guild name or tag already exists.";
            }
        }
    }
}

// Handle join/leave actions
if (isset($_GET['join'])) {
    $guildId = intval($_GET['join']);
    // Only join if not already in a guild
    $inGuild = $conn->fetch("SELECT 1 FROM guild_members WHERE user_id = ?", [$playerId]);
    if (!$inGuild) {
        $conn->execute("INSERT INTO guild_members (guild_id, user_id) VALUES (?, ?)", [$guildId, $playerId]);
        header("Location: guilds.php");
        exit;
    }
}
if (isset($_GET['leave'])) {
    // Only allow leave if not owner
    if ($myGuild && $myGuild['owner_id'] != $playerId) {
        $conn->execute("DELETE FROM guild_members WHERE user_id = ?", [$playerId]);
        header("Location: guilds.php");
        exit;
    }
}

// Now include sidebar and output HTML
include 'includes/sidebar.php'; // Sidebar HTML
?>
<!DOCTYPE html>
<html>
<head>
    <title>Guilds</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<?php mainContainer('main-container', 'max-w-6xl mx-auto', '', true); ?>
        <!-- Breadcrumb -->
        <nav class="flex mb-4 text-sm text-gray-500" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-2">
                <li class="inline-flex items-center">
                    <a href="dashboard.php" class="text-gray-500 hover:text-gray-700 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9.293 2.293a1 1 0 0 1 1.414 0l7 7A1 1 0 0 1 17 11h-1v6a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1v-3a1 1 0 0 0-1-1H9a1 1 0 0 0-1 1v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-6H3a1 1 0 0 1-.707-1.707l7-7Z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="sr-only">Home</span>
                    </a>
                </li>
                <li>
                    <span class="mx-2 text-gray-400 flex items-center" aria-hidden="true">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4"><path fill-rule="evenodd" d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd"></path></svg>
                    </span>
                </li>
                <li class="inline-flex items-center">
                    <span class="text-gray-700 font-semibold">Guilds</span>
                </li>
            </ol>
        </nav>
        <!-- End Breadcrumb -->


        <!-- My Guild Section -->
        <?php if ($myGuild): ?>
            <div class="bg-white border border-slate-200 rounded-lg w-full mb-6">
      <div class="p-6">
        <div class="w-full overflow-x-hidden space-y-4">
                <h2 class="text-lg font-semibold mb-2">My Guild</h2>
                <div class="flex items-center justify-between">
                    <div>
                        <span class="font-bold text-xl"><?=htmlspecialchars($myGuild['name'])?></span>
                        <span class="ml-2 bg-gray-200 px-2 py-0.5 rounded text-xs font-mono"><?=htmlspecialchars($myGuild['tag'])?></span>
                        <div class="text-gray-600"><?=htmlspecialchars($myGuild['description'])?></div>
                    </div>
                    <div>
                        <?php if ($myGuild['owner_id'] == $playerId): ?>
                            <a href="manage-guild.php?id=<?=$myGuild['id']?>" class="px-3 py-1 bg-blue-600 text-white rounded">Manage</a>
                        <?php else: ?>
                            <a href="guilds.php?leave=1" class="px-3 py-1 bg-red-600 text-white rounded" onclick="return confirm('Leave this guild?')">Leave</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div></div></div>
        <?php endif; ?>

        <!-- Create Guild Form -->
        <?php if (!$myGuild): ?>
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 mt-8 pb-4">
  <div class="bg-white border border-slate-200 rounded-lg w-full mb-6">
    <div class="p-6">
                <h2 class="text-lg font-semibold mb-2">Create a Guild</h2>
                <?php if ($createError): ?>
                    <div class="text-red-600 mb-2"><?=$createError?></div>
                <?php endif; ?>
                <form method="post" class="space-y-2">
                    <input type="hidden" name="create_guild" value="1">
                    <div>
                        <label class="block text-sm">Guild Name</label>
                        <input type="text" name="name" maxlength="64" required class="border rounded px-2 py-1 w-full">
                    </div>
                    <div>
                        <label class="block text-sm">Tag (4 letters)</label>
                        <input type="text" name="tag" maxlength="4" minlength="4" required class="border rounded px-2 py-1 w-24 uppercase font-mono">
                    </div>
                    <div>
                        <label class="block text-sm">Description</label>
                        <textarea name="description" maxlength="255" class="border rounded px-2 py-1 w-full"></textarea>
                    </div>
                    <button type="submit" class="px-4 py-1 bg-green-600 text-white rounded">Create Guild</button>
                </form>
            </div>
                </div>
        <?php endif; ?>

        <!-- All Guilds List -->
  
  <div class="bg-white border border-slate-200 rounded-lg w-full mb-6">
    <div class="p-6">
            <h2 class="text-lg font-semibold mb-2">All Guilds</h2>
            <table class="min-w-full text-sm">
                <thead>
                    <tr>
                        <th class="text-left py-1">Name</th>
                        <th class="text-left py-1">Tag</th>
                        <th class="text-left py-1">Members</th>
                        <th class="text-left py-1">Created</th>
                        <th class="text-left py-1"></th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($guilds as $g): ?>
                    <tr class="border-t">
                        <td class="sm:whitespace-nowrap py-3 pr-3 text-sm font-medium text-gray-900 sm:pl-0"><?=htmlspecialchars($g['name'])?></td>
                        <td class="sm:whitespace-nowrap py-3 pr-3 text-sm font-medium text-gray-900 sm:pl-0"><span class="bg-gray-200 px-2 py-0.5 rounded font-mono"><?=htmlspecialchars($g['tag'])?></span></td>
                        <td class="sm:whitespace-nowrap py-3 pr-3 text-sm font-medium text-gray-900 sm:pl-0"><?=$g['member_count']?></td>
                        <td class="sm:whitespace-nowrap py-3 pr-3 text-sm font-medium text-gray-900 sm:pl-0"><?=date('Y-m-d', strtotime($g['created_at']))?></td>
                        <td class="sm:whitespace-nowrap py-3 pr-3 text-sm font-medium text-gray-900 sm:pl-0">
                            <div class="flex justify-end space-x-1">
                                <a href="view-guild.php?id=<?=$g['id']?>" class="px-2 py-1 border border-gray-400 text-black bg-white rounded text-xs">View</a>
                                <?php if (!$myGuild): ?>
                                    <a href="guilds.php?join=<?=$g['id']?>" class="px-2 py-1 border border-gray-400 text-black bg-white rounded text-xs">Join</a>
                                <?php endif; ?>
                                <?php if ($myGuild && $myGuild['owner_id'] == $playerId && $myGuild['id'] == $g['id']): ?>
                                    <a href="manage-guild.php?id=<?=$g['id']?>" class="px-2 py-1 bg-indigo-600 text-white rounded text-xs">Manage</a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
