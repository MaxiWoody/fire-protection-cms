<?php
/**
 * Sitemap Generator
 * Generates XML sitemap for SEO
 */

require_once dirname(__DIR__) . '/config/constants.php';

header('Content-Type: application/xml; charset=utf-8');

if (!XML_SITEMAP_ENABLED) {
    http_response_code(404);
    exit;
}

$xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
$xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

// Homepage
$xml .= '  <url>' . "\n";
$xml .= '    <loc>' . htmlspecialchars(SITE_URL) . '</loc>' . "\n";
$xml .= '    <lastmod>' . date('Y-m-d') . '</lastmod>' . "\n";
$xml .= '    <changefreq>weekly</changefreq>' . "\n";
$xml .= '    <priority>1.0</priority>' . "\n";
$xml .= '  </url>' . "\n";

// Pages
$db->query('SELECT slug, updated_at FROM pages WHERE is_published = 1 ORDER BY updated_at DESC');
$pages = $db->resultSet();
foreach ($pages as $page) {
    $xml .= '  <url>' . "\n";
    $xml .= '    <loc>' . htmlspecialchars(SITE_URL . '/pages/' . $page['slug']) . '</loc>' . "\n";
    $xml .= '    <lastmod>' . date('Y-m-d', strtotime($page['updated_at'])) . '</lastmod>' . "\n";
    $xml .= '    <changefreq>monthly</changefreq>' . "\n";
    $xml .= '    <priority>0.8</priority>' . "\n";
    $xml .= '  </url>' . "\n";
}

// Services
$db->query('SELECT slug, updated_at FROM services WHERE is_published = 1 ORDER BY updated_at DESC');
$services = $db->resultSet();
foreach ($services as $service) {
    $xml .= '  <url>' . "\n";
    $xml .= '    <loc>' . htmlspecialchars(SITE_URL . '/pages/service/' . $service['slug']) . '</loc>' . "\n";
    $xml .= '    <lastmod>' . date('Y-m-d', strtotime($service['updated_at'])) . '</lastmod>' . "\n";
    $xml .= '    <changefreq>monthly</changefreq>' . "\n";
    $xml .= '    <priority>0.8</priority>' . "\n";
    $xml .= '  </url>' . "\n";
}

// Blog Posts
$db->query('SELECT slug, updated_at FROM blog_posts WHERE is_published = 1 ORDER BY updated_at DESC');
$posts = $db->resultSet();
foreach ($posts as $post) {
    $xml .= '  <url>' . "\n";
    $xml .= '    <loc>' . htmlspecialchars(SITE_URL . '/pages/blog/' . $post['slug']) . '</loc>' . "\n";
    $xml .= '    <lastmod>' . date('Y-m-d', strtotime($post['updated_at'])) . '</lastmod>' . "\n";
    $xml .= '    <changefreq>weekly</changefreq>' . "\n";
    $xml .= '    <priority>0.7</priority>' . "\n";
    $xml .= '  </url>' . "\n";
}

$xml .= '</urlset>';

echo $xml;
