<?php
require_once __DIR__ . '/includes/db.php';

echo "Updating database with valid fallback images...\n";

// These are the images we KNOW exist
$valid_fallback = 'static/Noor_e_mahal_ png (6).png';
$valid_hero = 'static/Noor_e_mahal_ png (3).png';

// Update all entries in image_assets
$stmt = $db->query("SELECT id, category, url FROM image_assets");
foreach ($stmt->fetchAll() as $row) {
    if (!file_exists(__DIR__ . '/' . $row['url'])) {
        $new_url = $valid_fallback;
        if (strpos($row['category'], 'hero') !== false || strpos($row['category'], 'slider') !== false) {
            $new_url = $valid_hero;
        }
        
        $update = $db->prepare("UPDATE image_assets SET url = ? WHERE id = ?");
        $update->execute([$new_url, $row['id']]);
        echo "Updated Asset ID {$row['id']} ({$row['category']}) to valid fallback.\n";
    }
}

// Also check facilities table
$stmt = $db->query("SELECT id, image_url, name FROM facilities");
foreach ($stmt->fetchAll() as $row) {
    if (!empty($row['image_url']) && !file_exists(__DIR__ . '/' . $row['image_url'])) {
        $update = $db->prepare("UPDATE facilities SET image_url = '' WHERE id = ?");
        $update->execute([$row['id']]);
        echo "Cleared invalid image_url for Facility: {$row['name']}\n";
    }
}

echo "Database alignment complete.\n";
