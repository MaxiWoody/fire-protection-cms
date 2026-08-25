<?php
/**
 * Admin Logout
 */

require_once dirname(__DIR__) . '/config/constants.php';
requireLogin();

logAction('LOGOUT', 'user', $_SESSION['user_id']);

session_destroy();
header('Location: ' . SITE_URL . '/admin/login.php?logout=1');
exit;
