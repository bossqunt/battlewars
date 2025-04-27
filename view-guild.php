<?php
require_once 'controller/database.php';
require_once 'controller/authCheck.php'; // Sets $playerId

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('Invalid guild ID.');
}
$guildId = intval($_GET['id']);

$conn = new database();
$db = $conn->getConnection();

// Fetch guild info
$guild = $conn->fetch("SELECT * FROM guilds WHERE id = ?", [$guildId]);
if (!$guild) {
    die('Guild not found.');
}

// Check if current user is owner
$isOwner = ($guild['owner_id'] == $playerId);

// Handle management actions (owner only)
if ($isOwner && $_SERVER['REQUEST_METHOD'] === 'POST') {
    // Disband guild
    if (isset($_POST['disband_guild'])) {
        $conn->execute("DELETE FROM guild_members WHERE guild_id = ?", [$guildId]);
        $conn->execute("DELETE FROM guilds WHERE id = ?", [$guildId]);
        header("Location: guilds.php");
        exit;
    }
    // Kick member
    if (isset($_POST['kick_user_id'])) {
        $kickId = intval($_POST['kick_user_id']);
        if ($kickId !== $playerId) { // Can't kick self
            $conn->execute("DELETE FROM guild_members WHERE guild_id = ? AND user_id = ?", [$guildId, $kickId]);
        }
    }
    // Accept join request
    if (isset($_POST['accept_user_id'])) {
        $acceptId = intval($_POST['accept_user_id']);
        $conn->execute("UPDATE guild_members SET is_pending = 0 WHERE guild_id = ? AND user_id = ?", [$guildId, $acceptId]);
    }
    // Reject join request
    if (isset($_POST['reject_user_id'])) {
        $rejectId = intval($_POST['reject_user_id']);
        $conn->execute("DELETE FROM guild_members WHERE guild_id = ? AND user_id = ? AND is_pending = 1", [$guildId, $rejectId]);
    }
    // Refresh after action
    header("Location: view-guild.php?id=$guildId");
    exit;
}

// Fetch guild members (not pending)
$members = $conn->fetchAll("
    SELECT gm.*, p.name, p.level, p.image_path, p.id as user_id
    FROM guild_members gm
    JOIN players p ON gm.user_id = p.id
    WHERE gm.guild_id = ? AND (gm.is_pending IS NULL OR gm.is_pending = 0)
    ORDER BY gm.is_officer DESC, p.level DESC, p.name ASC
", [$guildId]);

// Fetch pending join requests (if any)
$pending = [];
if ($isOwner) {
    $pending = $conn->fetchAll("
        SELECT gm.*, p.name, p.level, p.image_path, p.id as user_id
        FROM guild_members gm
        JOIN players p ON gm.user_id = p.id
        WHERE gm.guild_id = ? AND gm.is_pending = 1
        ORDER BY p.level DESC, p.name ASC
    ", [$guildId]);
}

include 'includes/sidebar.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title><?=htmlspecialchars($guild['name'])?> - Guild</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
<div class="py-6 max-w-6xl mx-auto px-4 sm:px-6 md:px-8">
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
            <li>
                <a href="guilds.php" class="text-gray-500 hover:text-gray-700">Guilds</a>
            </li>
            <li>
                <span class="mx-2 text-gray-400 flex items-center" aria-hidden="true">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4"><path fill-rule="evenodd" d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd"></path></svg>
                </span>
            </li>
            <li class="inline-flex items-center">
                <span class="text-gray-700 font-semibold"><?=htmlspecialchars($guild['name'])?></span>
            </li>
        </ol>
    </nav>
    <!-- End Breadcrumb -->

    <div class="bg-white border border-slate-200 rounded-lg w-full mb-6">
        <div class="p-6">
            <div class="flex items-center justify-between">
                <div>
                    <span class="font-bold text-2xl"><?=htmlspecialchars($guild['name'])?></span>
                    <span class="ml-2 bg-gray-200 px-2 py-0.5 rounded text-xs font-mono"><?=htmlspecialchars($guild['tag'])?></span>
                    <div class="text-gray-600 mt-1"><?=htmlspecialchars($guild['description'])?></div>
                </div>
                <div>
                    <span class="text-xs text-gray-400">Created: <?=date('Y-m-d', strtotime($guild['created_at']))?></span>
                </div>
            </div>
            <?php if ($isOwner): ?>
                <form method="post" onsubmit="return confirm('Are you sure you want to disband the guild? This cannot be undone.');">
                    <button type="submit" name="disband_guild" class="mt-4 px-3 py-1 bg-red-600 text-white rounded text-xs">Disband Guild</button>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <?php if ($isOwner && count($pending)): ?>
    <div class="bg-white border border-slate-200 rounded-lg w-full mb-6">
        <div class="p-6">
            <h2 class="text-lg font-semibold mb-2">Pending Join Requests (<?=count($pending)?>)</h2>
            <table class="min-w-full text-sm">
                <thead>
                    <tr>
                        <th class="text-left py-1">Name</th>
                        <th class="text-left py-1">Level</th>
                        <th class="text-left py-1">Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($pending as $p): ?>
                    <tr class="border-t">
                        <td class="py-2 pr-3 text-sm font-medium text-gray-900 flex items-center gap-2">
                            <?php if (!empty($p['image_path'])): ?>
                                <img src="<?=htmlspecialchars($p['image_path'])?>" alt="avatar" class="w-6 h-6 rounded-full border border-gray-300">
                            <?php endif; ?>
                            <a href="profile.php?id=<?=$p['user_id']?>" class="hover:underline"><?=htmlspecialchars($p['name'])?></a>
                        </td>
                        <td class="py-2 pr-3 text-sm text-gray-700"><?=$p['level']?></td>
                        <td class="py-2 pr-3 text-sm text-gray-700">
                            <form method="post" class="inline">
                                <input type="hidden" name="accept_user_id" value="<?=$p['user_id']?>">
                                <button type="submit" class="px-2 py-1 bg-green-600 text-white rounded text-xs">Accept</button>
                            </form>
                            <form method="post" class="inline ml-1">
                                <input type="hidden" name="reject_user_id" value="<?=$p['user_id']?>">
                                <button type="submit" class="px-2 py-1 bg-red-600 text-white rounded text-xs">Reject</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>

    <div class="bg-white border border-slate-200 rounded-lg w-full mb-6">
        <div class="p-6">
            <h2 class="text-lg font-semibold mb-2">Members (<?=count($members)?>)</h2>
            <table class="min-w-full text-sm">
                <thead>
                    <tr>
                        <th class="text-left py-1">Name</th>
                        <th class="text-left py-1">Level</th>
                        <th class="text-left py-1">Role</th>
                        <?php if ($isOwner): ?><th class="text-left py-1">Actions</th><?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($members as $m): ?>
                    <tr class="border-t">
                        <td class="py-2 pr-3 text-sm font-medium text-gray-900 flex items-center gap-2">
                            <?php if (!empty($m['image_path'])): ?>
                                <img src="<?=htmlspecialchars($m['image_path'])?>" alt="avatar" class="w-6 h-6 rounded-full border border-gray-300">
                            <?php endif; ?>
                            <a href="profile.php?id=<?=$m['user_id']?>" class="hover:underline"><?=htmlspecialchars($m['name'])?></a>
                        </td>
                        <td class="py-2 pr-3 text-sm text-gray-700"><?=$m['level']?></td>
                        <td class="py-2 pr-3 text-sm text-gray-700">
                            <?php
                                if ($guild['owner_id'] == $m['user_id']) {
                                    echo '<span class="text-indigo-700 font-semibold">Owner</span>';
                                } elseif ($m['is_officer']) {
                                    echo '<span class="text-green-700 font-semibold">Officer</span>';
                                } else {
                                    echo '<span class="text-gray-600">Member</span>';
                                }
                            ?>
                        </td>
                        <?php if ($isOwner): ?>
                        <td class="py-2 pr-3 text-sm text-gray-700">
                            <?php if ($m['user_id'] != $playerId): ?>
                            <form method="post" onsubmit="return confirm('Kick this member?');">
                                <input type="hidden" name="kick_user_id" value="<?=$m['user_id']?>">
                                <button type="submit" class="px-2 py-1 bg-red-600 text-white rounded text-xs">Kick</button>
                            </form>
                            <?php endif; ?>
                        </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>
