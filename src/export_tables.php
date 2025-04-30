<?php
require_once '..controller/Database.php'; // Adjust the path as needed

$db = new Database(); // Assuming your class is named Database and handles connection internally

$tables = [];
$res = $db->fetch("SHOW TABLES");
while ($row = $res->fetch_all()) {
    $tables[] = $row[0];
}

$sql = "";
foreach ($tables as $table) {
    $res = $db->fetch("SHOW CREATE TABLE `$table`");
    $row = $res->fetch_all();
    $sql .= $row['Create Table'] . ";\n\n";
}

// Save to file
file_put_contents('tables.sql', $sql);
echo "Exported table schemas to tables.sql\n";
?>
