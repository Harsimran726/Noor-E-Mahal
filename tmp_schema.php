<?php
require_once __DIR__ . '/includes/db.php';
function printTable($db, $table) {
    echo "TABLE: $table\n";
    $stmt = $db->query("PRAGMA table_info($table)");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "{$row['cid']}: {$row['name']} ({$row['type']})\n";
    }
    echo "\n";
}
printTable($db, 'facilities');
printTable($db, 'image_assets');
?>
