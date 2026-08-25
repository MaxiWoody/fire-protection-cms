<?php
/**
 * Header Template
 * Used across all pages
 */

if (!isset($page_title)) {
    $page_title = getSettings()['seo_title'] ?? SITE_NAME;
}
if (!isset($page_description)) {
    $page_description = getSettings()['seo_description'] ?? 'Profesyonel yangın koruması hizmetleri';
}
if (!isset($page_keywords)) {
    $page_keywords = getSettings()['seo_keywords'] ?? 'yangın koruması';
}

$settings = getSettings();
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    <!-- SEO Meta Tags -->
    <title><?php echo htmlspecialchars($page_title); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($page_description); ?>">
    <meta name="keywords" content="<?php echo htmlspecialchars($page_keywords); ?>">
    <meta name="robots" content="<?php echo $settings['meta_robots'] ?? 'index, follow'; ?>">
    <meta name="author" content="<?php echo htmlspecialchars($settings['site_name']); ?>">
    <meta name="creator" content="<?php echo htmlspecialchars($settings['site_name']); ?>">
    
    <!-- Open Graph Tags -->
    <meta property="og:title" content="<?php echo htmlspecialchars($page_title); ?>">
    <meta property="og:description" content="<?php echo htmlspecialchars($page_description); ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo SITE_URL . ($_SERVER['REQUEST_URI'] ?? ''); ?>">
    <meta property="og:image" content="<?php echo SITE_URL . '/' . ($og_image ?? 'assets/images/og-image.jpg'); ?>">
    <meta property="og:site_name" content="<?php echo htmlspecialchars($settings['site_name']); ?>">
    
    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo htmlspecialchars($page_title); ?>">
    <meta name="twitter:description" content="<?php echo htmlspecialchars($page_description); ?>">
    
    <!-- Canonical URL -->
    <link rel="canonical" href="<?php echo SITE_URL . ($_SERVER['REQUEST_URI'] ?? '/'); ?>">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?php echo SITE_URL . '/' . $settings['favicon']; ?>">
    <link rel="apple-touch-icon" href="<?php echo SITE_URL . '/' . $settings['favicon']; ?>">
    
    <!-- Stylesheets -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/style.css">
    
    <!-- Google Analytics -->
    <?php if (!empty($settings['google_analytics_id'])): ?>
    <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo htmlspecialchars($settings['google_analytics_id']); ?>"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '<?php echo htmlspecialchars($settings['google_analytics_id']); ?>');
    </script>
    <?php endif; ?>
    
    <!-- Schema.org Markup -->
    <?php if (SCHEMA_ORG_ENABLED): ?>
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "LocalBusiness",
        "name": "<?php echo htmlspecialchars($settings['site_name']); ?>",
        "description": "<?php echo htmlspecialchars($settings['site_description']); ?>",
        "url": "<?php echo SITE_URL; ?>",
        "telephone": "<?php echo htmlspecialchars($settings['phone']); ?>",
        "email": "<?php echo htmlspecialchars($settings['email']); ?>",
        "address": {
            "@type": "PostalAddress",
            "streetAddress": "<?php echo htmlspecialchars($settings['address']); ?>"
        }
    }
    </script>
    <?php endif; ?>
</head>
<body>
    <!-- Navigation -->
    <?php include 'navigation.php'; ?>
    
    <!-- Main Content -->
    <main class="main-content">
