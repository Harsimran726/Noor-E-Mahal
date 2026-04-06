<?php
// includes/db.php

$dbFile = __DIR__ . '/../nooremahal.db';

try {
    $db = new PDO("sqlite:$dbFile");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

/**
 * Fetch all site content as a key-value array
 */
function getSiteContent($db) {
    $stmt = $db->query("SELECT key, value FROM site_content");
    $content = [];
    while ($row = $stmt->fetch()) {
        $content[$row['key']] = $row['value'];
    }
    return $content;
}

/**
 * Fetch images as a category-to-url array
 */
function getSiteImages($db) {
    $stmt = $db->query("SELECT category, url FROM image_assets");
    $images = [];
    while ($row = $stmt->fetch()) {
        $url = trim($row['url']);
        $url = preg_replace('/[\x00-\x1F\x7F]/', '', $url);
        $images[$row['category']] = str_replace(' ', '%20', $url);
    }
    return $images;
}

/**
 * Fetch all image assets
 */
function getAllImageAssets($db) {
    $assets = $db->query("SELECT * FROM image_assets")->fetchAll();
    foreach ($assets as &$asset) {
        $asset['url'] = str_replace(' ', '%20', $asset['url']);
    }
    return $assets;
}
/**
 * Fetch all gallery categories
 */
function getGalleryCategories($db) {
    return $db->query("SELECT * FROM gallery_categories ORDER BY id ASC")->fetchAll();
}

/**
 * Build a cache-busted asset URL using file modification time.
 */
function assetUrl($path) {
    $fullPath = __DIR__ . '/../' . ltrim($path, '/');
    $version = file_exists($fullPath) ? filemtime($fullPath) : time();
    return $path . '?v=' . $version;
}
?>
