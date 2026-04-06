<?php
require_once __DIR__ . '/includes/db.php';

echo "Hard Resetting Hero Imagery...\n";

$heros = [
    'home_hero_1' => 'static/Noor_e_mahal_ png (1).png',
    'home_hero_2' => 'static/Noor_e_mahal_ png (5).png',
    'about_hero_1' => 'static/Noor_e_mahal_ png (1).png',
    'about_hero_2' => 'static/Noor_e_mahal_ png (5).png',
    'facilities_hero_1' => 'static/Noor_e_mahal_ png (1).png',
    'facilities_hero_2' => 'static/Noor_e_mahal_ png (5).png',
    'gallery_hero_1' => 'static/Noor_e_mahal_ png (1).png',
    'gallery_hero_2' => 'static/Noor_e_mahal_ png (5).png',
    'contact_hero_1' => 'static/Noor_e_mahal_ png (1).png',
    'contact_hero_2' => 'static/Noor_e_mahal_ png (5).png',
];

foreach ($heros as $cat => $url) {
    $stmt = $db->prepare("UPDATE image_assets SET url = ? WHERE category = ?");
    $stmt->execute([$url, $cat]);
}

// Check for any facilities_card_X slots in image_assets that might be broken
$stmt = $db->query("SELECT id, url FROM image_assets WHERE category LIKE 'facilities_card_%'");
foreach ($stmt->fetchAll() as $a) {
    if (strlen($a['url']) > 100 || strpos($a['url'], "\n") !== false) {
        $update = $db->prepare("UPDATE image_assets SET url = ? WHERE id = ?");
        $update->execute(['static/Noor_e_mahal_ png (5).png', $a['id']]);
        echo "Reset image asset ID {$a['id']}\n";
    }
}

// Clear facility image_url if broken
$stmt = $db->query("SELECT id, image_url FROM facilities");
foreach ($stmt->fetchAll() as $f) {
    if (strlen($f['image_url'] ?? '') > 100) {
        $update = $db->prepare("UPDATE facilities SET image_url = '' WHERE id = ?");
        $update->execute([$f['id']]);
        echo "Cleared facility image_url for ID {$f['id']}\n";
    }
}

echo "Hard reset complete.\n";
