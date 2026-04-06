<?php
require_once __DIR__ . '/includes/db.php';
try {
    $db->exec("ALTER TABLE facilities ADD COLUMN image_url VARCHAR(255)");
    echo "Column added successfully\n";
} catch (Exception $e) {
    echo "Error (might already exist): " . $e->getMessage() . "\n";
}
try {
    $db->exec("ALTER TABLE facilities RENAME COLUMN description TO `desc` ");
    echo "Renamed description to desc successfully\n";
} catch (Exception $e) {
    echo "Error (might already be desc): " . $e->getMessage() . "\n";
}
?>
