<?php
session_start();

header('Content-Type: text/html');
include 'controller/authCheck.php'; // Adjust the path as needed
include 'controller/Database.php'; // Adjust the path as needed
include 'includes/sidebar.php'; // Adjust the path as needed
require_once 'controller/Database.php'; // Adjust the path as needed

// Connect to the database
$conn = new database();
$db = $conn->getConnection();

// Fetch player data
$players = $conn->fetchAll("SELECT p.id, p.name, p.level, p.image_path, g.name AS guild_name, p.token_expire
          FROM players p
          LEFT JOIN guild_members gm ON p.id = gm.player_id
          LEFT JOIN guilds g ON gm.guild_id = g.id
          GROUP BY 1,2,3,4,5,6");
        

?>

<?php mainContainer('main-container', 'max-w-6xl mx-auto', '', true); ?>
    <nav class="flex mb-4 text-sm text-gray-500" aria-label="Breadcrumb">
      <ol class="inline-flex items-center space-x-1 md:space-x-2">
        <li class="inline-flex items-center">
          <a href="dashboard.php" class="text-gray-500 hover:text-gray-700 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd"
                d="M9.293 2.293a1 1 0 0 1 1.414 0l7 7A1 1 0 0 1 17 11h-1v6a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1v-3a1 1 0 0 0-1-1H9a1 1 0 0 0-1 1v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-6H3a1 1 0 0 1-.707-1.707l7-7Z"
                clip-rule="evenodd"></path>
            </svg>
            <span class="sr-only">Home</span>
          </a>
        </li>
        <li>
          <span class="mx-2 text-gray-400 flex items-center" aria-hidden="true">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
              <path fill-rule="evenodd"
                d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z"
                clip-rule="evenodd"></path>
            </svg>
          </span>
        </li>
        <li class="inline-flex items-center">
          <span class="text-gray-700 font-semibold">Players</span>
        </li>
      </ol>
    </nav>
    <!-- End Breadcrumb -->

    <div class="bg-white border border-slate-200 rounded-lg w-full mb-6">
      <div class="p-6">
   


      <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800">
        <thead>
          <tr>
            <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0"></th>
            <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0">Name</th>
            <th scope="col" class="py-3.5 px-3 text-left text-sm font-semibold text-gray-900 hidden sm:table-cell">Level</th>
            <th scope="col" class="py-3.5 px-3 text-left text-sm font-semibold text-gray-900 hidden sm:table-cell">Last Active (Leader)</th>
            <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-0">
              <span class="sr-only">Actions</span>
            </th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 dark:divide-gray-800 px-2 sm:px-0">
          <?php foreach ($players as $player) : ?>
            <tr>
              <td class="sm:whitespace-nowrap py-3 sm:pl-4 text-sm font-medium text-gray-900 sm:pl-0 sm:px-0 sm:w-18 pr-6 sm:mr-0">
                <img class="h-6 inline-block rounded-full w-auto mx-4 sm:mx-0" src="<?php echo $player['image_path']; ?>" alt="">
              </td>
              <td class="sm:whitespace-nowrap py-3 pr-3 text-sm font-medium text-gray-900 sm:pl-0">
                <a href="/guilds/view/<?php echo $player['id']; ?>">
                  <!-- <span style="color:<?php echo $player['guild_color']; ?>; background:<?php echo $player['guild_gradient']; ?>;"> -->
                    <?php echo $player['name']; ?>
                  </span>
                </a>
              </td>
              <td class="sm:whitespace-nowrap py-3 px-3 text-sm font-medium text-gray-900 hidden sm:table-cell">
                <?php echo $player['level']; ?>
              </td>

              <td class="sm:whitespace-nowrap py-3 px-3 text-sm font-medium text-gray-900 hidden sm:table-cell">
                <?php echo $player['token_expire']; ?>
              </td>
              <td class="relative py-3.5 pl-3 pr-4 sm:pr-0">
                <a href="/players/profile/<?php echo $player['id']; ?>" class="inline-flex items-center px-2 text-xs font-semibold leading-5 text-gray-900 transition duration-150 ease-in-out hover:text-gray-500 focus:text-gray-500 focus:outline-none">
                  View
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</body>
</html>