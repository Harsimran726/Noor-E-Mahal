<?php
require_once __DIR__ . '/includes/db.php';

echo "Final Comprehensive Database URL Cleanup...\n";

function deep_clean($str) {
    if (!$str) return '';
    // 1. Remove ANY control characters, newlines, tabs
    $str = preg_replace('/[\x00-\x1F\x7F]/', '', $str);
    // 2. Trim excess spaces
    $str = trim($str);
    // 3. Remove weird suffixes like 'ng'png' or similar from previous corruption
    if (preg_match('/\.png.+$/i', $str)) {
        $str = preg_replace('/\.png.+$/i', '.png', $str);
    }
    return $str;
}

// Fix image_assets
$stmt = $db->query("SELECT id, url, category FROM image_assets");
$assets = $stmt->fetchAll();
foreach ($assets as $a) {
    $clean = deep_clean($a['url']);
    if ($clean !== $a['url']) {
        $update = $db->prepare("UPDATE image_assets SET url = ? WHERE id = ?");
        $update->execute([$clean, $a['id']]);
        echo "CLEANED -> Asset ID {$a['id']} ({$a['category']}): [{$clean}]\n";
    }
}

// Fix facilities
$stmt = $db->query("SELECT id, image_url, name FROM facilities");
$facilities = $stmt->fetchAll();
foreach ($facilities as $f) {
    $clean = deep_clean($f['image_url']);
    if ($clean !== $f['image_url']) {
        $update = $db->prepare("UPDATE facilities SET image_url = ? WHERE id = ?");
        $update->execute([$clean, $f['id']]);
        echo "CLEANED -> Facility ID {$f['id']} ({$f['name']}): [{$clean}]\n";
    }
}

echo "Final Cleanup complete.\n";
