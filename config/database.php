<?php
/**
 * Database Configuration
 * PHP 7.4 Compatible
 */

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'fire_protection_cms');
define('DB_CHARSET', 'utf8mb4');

// Site Configuration
define('SITE_URL', 'http://localhost/fire-protection-cms');
define('SITE_NAME', 'Fire Protection TR');
define('ADMIN_PATH', '/admin');
define('UPLOADS_PATH', '/uploads');
define('UPLOADS_DIR', __DIR__ . '/../uploads');

// Security
define('SESSION_TIMEOUT', 1800); // 30 minutes
define('MAX_UPLOAD_SIZE', 5242880); // 5MB
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx']);
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/png', 'image/gif', 'image/webp']);

// API Configuration
define('ENABLE_API', true);
define('API_RATE_LIMIT', 100);
define('CACHE_ENABLED', true);
define('CACHE_TIME', 3600);

// Email Configuration (Optional)
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USER', 'your-email@gmail.com');
define('SMTP_PASS', 'your-password');
define('SMTP_FROM', 'info@fireprotection.com.tr');

// Logging
define('LOG_ENABLED', true);
define('LOG_DIR', __DIR__ . '/../logs');

// Environment
define('ENVIRONMENT', 'development'); // development, production
define('DEBUG_MODE', (ENVIRONMENT === 'development'));

// Pagination
define('ITEMS_PER_PAGE', 12);
define('ITEMS_PER_ADMIN_PAGE', 25);

// SEO
define('SEO_ENABLED', true);
define('SCHEMA_ORG_ENABLED', true);
define('XML_SITEMAP_ENABLED', true);

// WhatsApp API
define('WHATSAPP_ENABLED', true);
define('WHATSAPP_DEFAULT_NUMBER', '+90532XXXXXXX');

// Google Analytics
define('GOOGLE_ANALYTICS_ID', 'UA-XXXXXXXXX-X');

// Database Connection Class
class Database {
    private $connection;
    private $stmt;
    
    public function __construct() {
        try {
            $this->connection = new mysqli(
                DB_HOST,
                DB_USER,
                DB_PASS,
                DB_NAME
            );
            
            if ($this->connection->connect_error) {
                throw new Exception('Database Connection Error: ' . $this->connection->connect_error);
            }
            
            // Set charset
            $this->connection->set_charset(DB_CHARSET);
            
        } catch (Exception $e) {
            if (DEBUG_MODE) {
                die('Database Error: ' . $e->getMessage());
            } else {
                die('Database connection failed. Please contact administrator.');
            }
        }
    }
    
    /**
     * Prepared statement query
     */
    public function query($sql) {
        try {
            $this->stmt = $this->connection->prepare($sql);
            if (!$this->stmt) {
                throw new Exception($this->connection->error);
            }
            return $this;
        } catch (Exception $e) {
            if (DEBUG_MODE) {
                die('Query Error: ' . $e->getMessage());
            }
            return false;
        }
    }
    
    /**
     * Bind values
     */
    public function bind($param, $value, $type = null) {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = MYSQLI_TYPE_LONG;
                    break;
                case is_float($value):
                    $type = MYSQLI_TYPE_DECIMAL;
                    break;
                case is_bool($value):
                    $type = MYSQLI_TYPE_TINY;
                    break;
                default:
                    $type = MYSQLI_TYPE_STRING;
            }
        }
        $this->stmt->bind_param($type, $value);
        return $this;
    }
    
    /**
     * Execute query
     */
    public function execute() {
        try {
            return $this->stmt->execute();
        } catch (Exception $e) {
            if (DEBUG_MODE) {
                die('Execute Error: ' . $e->getMessage());
            }
            return false;
        }
    }
    
    /**
     * Get results
     */
    public function resultSet() {
        $this->execute();
        $result = $this->stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    /**
     * Get single result
     */
    public function single() {
        $this->execute();
        $result = $this->stmt->get_result();
        return $result->fetch_assoc();
    }
    
    /**
     * Get row count
     */
    public function rowCount() {
        return $this->stmt->affected_rows;
    }
    
    /**
     * Get last insert ID
     */
    public function lastId() {
        return $this->connection->insert_id;
    }
    
    /**
     * Close connection
     */
    public function close() {
        if ($this->stmt) {
            $this->stmt->close();
        }
        $this->connection->close();
    }
}

// Initialize database instance
$db = new Database();
