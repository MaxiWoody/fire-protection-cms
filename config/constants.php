<?php
/**
 * Application Constants
 * PHP 7.4 Compatible
 */

// Include configuration files
require_once __DIR__ . '/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/security.php';

// Start session
startSecureSession();

// Add security headers
addSecurityHeaders();

// Enable gzip compression
if (ENVIRONMENT === 'production') {
    enableGzipCompression();
}

// Session timeout check
if (isUserLoggedIn()) {
    $session_timeout = time() - $_SESSION['last_activity'] ?? 0;
    if ($session_timeout > SESSION_TIMEOUT) {
        session_destroy();
        header('Location: ' . SITE_URL . '/admin/login.php?timeout=1');
        exit;
    }
    $_SESSION['last_activity'] = time();
}

// Date and timezone
date_default_timezone_set('Europe/Istanbul');

// Locale settings
setlocale(LC_ALL, 'tr_TR.UTF-8');
