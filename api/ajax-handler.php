<?php
/**
 * AJAX Handler for Form Submissions
 * PHP 7.4 Compatible
 */

require_once dirname(__DIR__) . '/config/constants.php';

header('Content-Type: application/json; charset=utf-8');

$response = [
    'success' => false,
    'message' => 'Bilinmeyen hata',
    'data' => []
];

try {
    $action = $_POST['action'] ?? $_GET['action'] ?? '';
    
    switch ($action) {
        case 'contact_form':
            // Handle contact form submission
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Invalid request method');
            }
            
            // Verify CSRF token
            $csrf_token = $_POST['csrf_token'] ?? '';
            if (!verifyCSRFToken($csrf_token)) {
                throw new Exception('CSRF token validation failed');
            }
            
            $name = sanitize($_POST['name'] ?? '', 'string');
            $email = sanitize($_POST['email'] ?? '', 'email');
            $phone = sanitize($_POST['phone'] ?? '', 'string');
            $subject = sanitize($_POST['subject'] ?? '', 'string');
            $message = sanitize($_POST['message'] ?? '', 'string');
            $service_type = sanitize($_POST['service_type'] ?? '', 'string');
            
            // Validation
            if (empty($name) || empty($email) || empty($subject) || empty($message)) {
                throw new Exception('Tüm gerekli alanları doldurunuz');
            }
            
            if (!validateEmail($email)) {
                throw new Exception('Geçersiz e-mail adresi');
            }
            
            // Save to database
            $db->query('INSERT INTO contact_messages (name, email, phone, subject, message, service_type, ip_address, user_agent) 
                       VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
            $db->bind(1, $name);
            $db->bind(2, $email);
            $db->bind(3, $phone);
            $db->bind(4, $subject);
            $db->bind(5, $message);
            $db->bind(6, $service_type);
            $db->bind(7, getClientIP());
            $db->bind(8, $_SERVER['HTTP_USER_AGENT'] ?? '');
            
            if ($db->execute()) {
                // Send email notification (optional)
                $settings = getSettings();
                if (!empty($settings['email'])) {
                    $to = $settings['email'];
                    $subject_email = "Yeni İletişim Mesajı: " . $subject;
                    $message_email = "Ad: $name\nE-mail: $email\nTelefon: $phone\n\nMesaj:\n$message";
                    sendEmail($to, $subject_email, $message_email);
                }
                
                $response['success'] = true;
                $response['message'] = 'Mesajınız başarıyla gönderildi. En kısa sürede sizinle iletişime geçeceğiz.';
            } else {
                throw new Exception('Mesaj gönderilirken hata oluştu');
            }
            break;
            
        case 'subscribe':
            // Handle newsletter subscription
            $email = sanitize($_POST['email'] ?? '', 'email');
            
            if (empty($email) || !validateEmail($email)) {
                throw new Exception('Geçersiz e-mail adresi');
            }
            
            // Check if already subscribed
            $db->query('SELECT id FROM newsletter_subscribers WHERE email = ?');
            $db->bind(1, $email);
            $existing = $db->single();
            
            if ($existing) {
                $response['success'] = true;
                $response['message'] = 'Bu e-mail zaten abone olmuş';
            } else {
                $db->query('INSERT INTO newsletter_subscribers (email, ip_address, created_at) VALUES (?, ?, NOW())');
                $db->bind(1, $email);
                $db->bind(2, getClientIP());
                
                if ($db->execute()) {
                    $response['success'] = true;
                    $response['message'] = 'Başarıyla abone oldunuz!';
                } else {
                    throw new Exception('Abonelik işlemi başarısız');
                }
            }
            break;
            
        case 'search':
            // Handle search
            $query = sanitize($_GET['q'] ?? '', 'string');
            
            if (strlen($query) < 2) {
                throw new Exception('Arama terimi çok kısa');
            }
            
            $results = [];
            
            // Search in pages
            $db->query('SELECT id, title, slug, "page" as type FROM pages 
                       WHERE is_published = 1 AND title LIKE ? LIMIT 5');
            $db->bind(1, "%$query%");
            $pages = $db->resultSet();
            $results = array_merge($results, $pages);
            
            // Search in services
            $db->query('SELECT id, title, slug, "service" as type FROM services 
                       WHERE is_published = 1 AND title LIKE ? LIMIT 5');
            $db->bind(1, "%$query%");
            $services = $db->resultSet();
            $results = array_merge($results, $services);
            
            $response['success'] = true;
            $response['data'] = array_slice($results, 0, 10);
            break;
            
        default:
            throw new Exception('Geçersiz işlem');
    }
    
} catch (Exception $e) {
    $response['success'] = false;
    $response['message'] = $e->getMessage();
    http_response_code(400);
}

echo json_encode($response, JSON_UNESCAPED_UNICODE);
exit;
