<?php
/**
 * Fire Protection CMS - Main Entry Point
 * PHP 7.4 Compatible
 * 
 * This file serves as the main router for the application.
 * It handles URL routing and dispatches requests to appropriate handlers.
 */

require_once 'config/constants.php';

// Get the requested URI
$request_uri = $_SERVER['REQUEST_URI'] ?? '/';
$request_method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

// Remove base path and query string
$path = parse_url($request_uri, PHP_URL_PATH);
$path = str_replace(SITE_URL, '', $path);
$path = trim($path, '/');

// Route handlers
if (empty($path) || $path === 'index.php') {
    // Homepage
    include 'index.php';
} elseif (strpos($path, 'admin') === 0) {
    // Admin panel
    include 'admin/login.php';
} elseif (strpos($path, 'api') === 0) {
    // API endpoints
    include 'api/ajax-handler.php';
} elseif (strpos($path, 'pages') === 0) {
    // Dynamic pages
    $page_slug = str_replace('pages/', '', $path);
    $page_slug = explode('?', $page_slug)[0]; // Remove query string
    
    // Map common pages
    $page_map = [
        'iletisim' => 'pages/iletisim.php',
        'hizmetler' => 'pages/hizmetler.php',
        'blog' => 'pages/blog.php',
        'galeri' => 'pages/galeri.php',
    ];
    
    if (isset($page_map[$page_slug])) {
        include $page_map[$page_slug];
    } else {
        // Load dynamic page from database
        include 'pages/page-detail.php';
    }
} elseif ($path === 'sitemap.xml') {
    include 'sitemap.php';
} elseif ($path === 'robots.txt') {
    include 'robots.txt';
} else {
    // 404 Not Found
    http_response_code(404);
    include 'pages/404.php';
}
