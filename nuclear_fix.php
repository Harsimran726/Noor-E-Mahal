<?php
require_once __DIR__ . '/includes/db.php';

echo "Nuclear Database Cleanup (Delete & Re-insert)...\n";

$heros = [
    ['category' => 'home_hero_1', 'url' => 'static/Noor_e_mahal_ png (1).png'],
    ['category' => 'home_hero_2', 'url' => 'static/Noor_e_mahal_ png (5).png'],
    ['category' => 'about_hero_1', 'url' => 'static/Noor_e_mahal_ png (1).png'],
    ['category' => 'about_hero_2', 'url' => 'static/Noor_e_mahal_ png (5).png'],
    ['category' => 'facilities_hero_1', 'url' => 'static/Noor_e_mahal_ png (1).png'],
    ['category' => 'facilities_hero_2', 'url' => 'static/Noor_e_mahal_ png (5).png'],
    ['category' => 'gallery_hero_1', 'url' => 'static/Noor_e_mahal_ png (1).png'],
    ['category' => 'gallery_hero_2', 'url' => 'static/Noor_e_mahal_ png (5).png'],
    ['category' => 'contact_hero_1', 'url' => 'static/Noor_e_mahal_ png (1).png'],
    ['category' => 'contact_hero_2', 'url' => 'static/Noor_e_mahal_ png (5).png'],
    ['category' => 'home_slider', 'url' => 'static/Noor_e_mahal_ png (1).png'],
];

// 1. Delete all hero/slider entries first to be safe
echo "Deleting old entries...\n";
$db->exec("DELETE FROM image_assets WHERE category LIKE '%hero%' OR category = 'home_slider'");

// 2. Re-insert clean ones
echo "Re-inserting clean entries...\n";
foreach ($heros as $h) {
    $stmt = $db->prepare("INSERT INTO image_assets (category, url, alt_text) VALUES (?, ?, '')");
    $stmt->execute([$h['category'], $h['url']]);
}

// 3. Trim all facility image tags too
echo "Trimming facility URLs...\n";
$db->exec("UPDATE facilities SET image_url = trim(image_url)");
$db->exec("UPDATE facilities SET image_url = '' WHERE length(image_url) > 200"); // kill super-corrupted ones

echo "Clean slate achieved.\n";
