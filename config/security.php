<?php
/**
 * Security Functions
 * PHP 7.4 Compatible
 */

/**
 * Start secure session
 */
function startSecureSession() {
    session_start([
        'use_strict_mode' => true,
        'httponly' => true,
        'secure' => (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on'),
        'samesite' => 'Strict'
    ]);
}

/**
 * Check if user is logged in
 */
function isUserLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * Check if user is admin
 */
function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

/**
 * Require login
 */
function requireLogin() {
    if (!isUserLoggedIn()) {
        header('Location: ' . SITE_URL . '/admin/login.php');
        exit;
    }
}

/**
 * Require admin
 */
function requireAdmin() {
    requireLogin();
    if (!isAdmin()) {
        http_response_code(403);
        die('Access Denied');
    }
}

/**
 * Sanitize input
 */
function sanitize($input, $type = 'string') {
    switch ($type) {
        case 'int':
            return filter_var($input, FILTER_SANITIZE_NUMBER_INT);
        case 'email':
            return filter_var($input, FILTER_SANITIZE_EMAIL);
        case 'url':
            return filter_var($input, FILTER_SANITIZE_URL);
        case 'html':
            return htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
        default:
            return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }
}

/**
 * Validate email
 */
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Validate URL
 */
function validateUrl($url) {
    return filter_var($url, FILTER_VALIDATE_URL) !== false;
}

/**
 * Generate CSRF token
 */
function generateCSRFToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Verify CSRF token
 */
function verifyCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Hash password
 */
function hashPassword($password) {
    return password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
}

/**
 * Verify password
 */
function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

/**
 * Generate random string
 */
function generateRandomString($length = 32) {
    return bin2hex(random_bytes($length / 2));
}

/**
 * Create slug from string
 */
function createSlug($string) {
    $string = mb_strtolower($string, 'UTF-8');
    $string = str_replace(['ş', 'ğ', 'ı', 'ö', 'ü', 'ç'], ['s', 'g', 'i', 'o', 'u', 'c'], $string);
    $string = preg_replace('/[^a-z0-9]+/', '-', $string);
    $string = trim($string, '-');
    return preg_replace('/-+/', '-', $string);
}

/**
 * Validate file upload
 */
function validateFileUpload($file, $type = 'image') {
    $errors = [];
    
    if (!isset($file['tmp_name']) || empty($file['tmp_name'])) {
        $errors[] = 'Dosya yüklenmedi';
        return ['valid' => false, 'errors' => $errors];
    }
    
    if ($file['size'] > MAX_UPLOAD_SIZE) {
        $errors[] = 'Dosya çok büyük (maksimum ' . (MAX_UPLOAD_SIZE / 1024 / 1024) . 'MB)';
    }
    
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    
    if (!in_array($ext, ALLOWED_EXTENSIONS)) {
        $errors[] = 'Dosya türü izin verilmiş değil';
    }
    
    if ($type === 'image' && !in_array($file['type'], ALLOWED_IMAGE_TYPES)) {
        $errors[] = 'Geçersiz resim dosyası';
    }
    
    return [
        'valid' => empty($errors),
        'errors' => $errors,
        'extension' => $ext
    ];
}

/**
 * Upload file
 */
function uploadFile($file) {
    $validation = validateFileUpload($file);
    
    if (!$validation['valid']) {
        return ['success' => false, 'errors' => $validation['errors']];
    }
    
    if (!is_dir(UPLOADS_DIR)) {
        mkdir(UPLOADS_DIR, 0755, true);
    }
    
    $filename = generateRandomString() . '.' . $validation['extension'];
    $destination = UPLOADS_DIR . '/' . $filename;
    
    if (move_uploaded_file($file['tmp_name'], $destination)) {
        return [
            'success' => true,
            'filename' => $filename,
            'path' => UPLOADS_PATH . '/' . $filename
        ];
    }
    
    return ['success' => false, 'errors' => ['Dosya yüklenemedi']];
}

/**
 * Delete file
 */
function deleteFile($filename) {
    $filepath = UPLOADS_DIR . '/' . $filename;
    if (file_exists($filepath)) {
        return unlink($filepath);
    }
    return false;
}

/**
 * Get client IP
 */
function getClientIP() {
    $ip = '';
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } elseif (!empty($_SERVER['REMOTE_ADDR'])) {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return filter_var($ip, FILTER_VALIDATE_IP) ? $ip : '0.0.0.0';
}

/**
 * Log action
 */
function logAction($action, $entity_type = '', $entity_id = 0, $old_value = '', $new_value = '') {
    global $db;
    
    if (!LOG_ENABLED || !isUserLoggedIn()) {
        return false;
    }
    
    $user_id = $_SESSION['user_id'];
    $ip_address = getClientIP();
    
    $db->query('INSERT INTO system_logs (user_id, action, entity_type, entity_id, old_value, new_value, ip_address) 
               VALUES (?, ?, ?, ?, ?, ?, ?)');
    $db->bind(1, $user_id, 'i');
    $db->bind(2, $action);
    $db->bind(3, $entity_type);
    $db->bind(4, $entity_id, 'i');
    $db->bind(5, $old_value);
    $db->bind(6, $new_value);
    $db->bind(7, $ip_address);
    
    return $db->execute();
}

/**
 * Compress output
 */
function enableGzipCompression() {
    if (strpos($_SERVER['HTTP_ACCEPT_ENCODING'] ?? '', 'gzip') !== false) {
        ob_start('ob_gzhandler');
    }
}

/**
 * Add security headers
 */
function addSecurityHeaders() {
    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: SAMEORIGIN');
    header('X-XSS-Protection: 1; mode=block');
    header('Referrer-Policy: strict-origin-when-cross-origin');
    header('Permissions-Policy: geolocation=(), microphone=(), camera=()');
}
