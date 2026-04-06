<?php
require_once __DIR__ . '/includes/db.php';
$images = getSiteImages($db);
echo "CHECKING HERO 1: [" . $images['home_hero_1'] . "]\n";
echo "CHECKING HERO 2: [" . $images['home_hero_2'] . "]\n";
echo "LENGTH OF HERO 1: " . strlen($images['home_hero_1']) . "\n";
