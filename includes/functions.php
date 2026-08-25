<?php
/**
 * General Helper Functions
 * PHP 7.4 Compatible
 */

/**
 * Get settings from database
 */
function getSettings() {
    global $db;
    static $settings = null;
    
    if ($settings === null) {
        if (CACHE_ENABLED) {
            $cache_file = sys_get_temp_dir() . '/fire_protection_settings.cache';
            if (file_exists($cache_file) && (time() - filemtime($cache_file)) < CACHE_TIME) {
                $settings = unserialize(file_get_contents($cache_file));
                return $settings;
            }
        }
        
        $db->query('SELECT * FROM settings LIMIT 1');
        $settings = $db->single();
        
        if (CACHE_ENABLED && $settings) {
            file_put_contents(sys_get_temp_dir() . '/fire_protection_settings.cache', serialize($settings));
        }
    }
    
    return $settings;
}

/**
 * Update settings
 */
function updateSettings($data) {
    global $db;
    
    $fields = [];
    $values = [];
    $types = '';
    
    foreach ($data as $key => $value) {
        $fields[] = "$key = ?";
        $values[] = $value;
        $types .= is_int($value) ? 'i' : 's';
    }
    
    $sql = 'UPDATE settings SET ' . implode(', ', $fields);
    $db->query($sql);
    
    $type_index = 1;
    foreach ($values as $value) {
        $db->bind($type_index++, $value);
    }
    
    $result = $db->execute();
    
    // Clear cache
    if (CACHE_ENABLED) {
        @unlink(sys_get_temp_dir() . '/fire_protection_settings.cache');
    }
    
    return $result;
}

/**
 * Get page by slug
 */
function getPageBySlug($slug) {
    global $db;
    $db->query('SELECT * FROM pages WHERE slug = ? AND is_published = 1');
    $db->bind(1, $slug);
    return $db->single();
}

/**
 * Get page by ID
 */
function getPageById($id) {
    global $db;
    $db->query('SELECT * FROM pages WHERE id = ?');
    $db->bind(1, $id, 'i');
    return $db->single();
}

/**
 * Get all published pages
 */
function getAllPages($parent_id = null) {
    global $db;
    
    if ($parent_id === null) {
        $db->query('SELECT * FROM pages WHERE is_published = 1 ORDER BY order_position ASC');
    } else {
        $db->query('SELECT * FROM pages WHERE is_published = 1 AND parent_id = ? ORDER BY order_position ASC');
        $db->bind(1, $parent_id, 'i');
    }
    
    return $db->resultSet();
}

/**
 * Get services with pagination
 */
function getServices($page = 1, $category_id = null) {
    global $db;
    
    $limit = ITEMS_PER_PAGE;
    $offset = ($page - 1) * $limit;
    
    if ($category_id !== null) {
        $db->query('SELECT * FROM services WHERE is_published = 1 AND category_id = ? 
                   ORDER BY order_position ASC LIMIT ?, ?');
        $db->bind(1, $category_id, 'i');
        $db->bind(2, $offset, 'i');
        $db->bind(3, $limit, 'i');
    } else {
        $db->query('SELECT * FROM services WHERE is_published = 1 
                   ORDER BY order_position ASC LIMIT ?, ?');
        $db->bind(1, $offset, 'i');
        $db->bind(2, $limit, 'i');
    }
    
    return $db->resultSet();
}

/**
 * Get featured services
 */
function getFeaturedServices($limit = 4) {
    global $db;
    $db->query('SELECT * FROM services WHERE is_published = 1 AND is_featured = 1 
               ORDER BY order_position ASC LIMIT ?');
    $db->bind(1, $limit, 'i');
    return $db->resultSet();
}

/**
 * Get service by slug
 */
function getServiceBySlug($slug) {
    global $db;
    $db->query('SELECT * FROM services WHERE slug = ? AND is_published = 1');
    $db->bind(1, $slug);
    return $db->single();
}

/**
 * Get service categories
 */
function getServiceCategories() {
    global $db;
    $db->query('SELECT * FROM service_categories WHERE is_published = 1 ORDER BY order_position ASC');
    return $db->resultSet();
}

/**
 * Get testimonials
 */
function getTestimonials($limit = 6) {
    global $db;
    $db->query('SELECT * FROM testimonials WHERE is_published = 1 ORDER BY order_position ASC LIMIT ?');
    $db->bind(1, $limit, 'i');
    return $db->resultSet();
}

/**
 * Get FAQ
 */
function getFAQ($category = null) {
    global $db;
    
    if ($category !== null) {
        $db->query('SELECT * FROM faq WHERE is_published = 1 AND category = ? ORDER BY order_position ASC');
        $db->bind(1, $category);
    } else {
        $db->query('SELECT * FROM faq WHERE is_published = 1 ORDER BY order_position ASC');
    }
    
    return $db->resultSet();
}

/**
 * Get blog posts with pagination
 */
function getBlogPosts($page = 1, $category_id = null) {
    global $db;
    
    $limit = ITEMS_PER_PAGE;
    $offset = ($page - 1) * $limit;
    
    if ($category_id !== null) {
        $db->query('SELECT * FROM blog_posts WHERE is_published = 1 AND category_id = ? 
                   ORDER BY created_at DESC LIMIT ?, ?');
        $db->bind(1, $category_id, 'i');
        $db->bind(2, $offset, 'i');
        $db->bind(3, $limit, 'i');
    } else {
        $db->query('SELECT * FROM blog_posts WHERE is_published = 1 
                   ORDER BY created_at DESC LIMIT ?, ?');
        $db->bind(1, $offset, 'i');
        $db->bind(2, $limit, 'i');
    }
    
    return $db->resultSet();
}

/**
 * Get blog post by slug
 */
function getBlogPostBySlug($slug) {
    global $db;
    $db->query('SELECT * FROM blog_posts WHERE slug = ? AND is_published = 1');
    $db->bind(1, $slug);
    return $db->single();
}

/**
 * Increment view count
 */
function incrementViewCount($type, $id) {
    global $db;
    
    $table = '';
    switch ($type) {
        case 'page':
            $table = 'pages';
            break;
        case 'blog':
            $table = 'blog_posts';
            break;
        default:
            return false;
    }
    
    $db->query("UPDATE $table SET view_count = view_count + 1 WHERE id = ?");
    $db->bind(1, $id, 'i');
    return $db->execute();
}

/**
 * Get gallery images
 */
function getGalleryImages($category = null, $limit = null) {
    global $db;
    
    if ($category !== null) {
        $query = 'SELECT * FROM gallery WHERE is_published = 1 AND category = ? ORDER BY order_position ASC';
        if ($limit !== null) {
            $query .= ' LIMIT ?';
        }
        $db->query($query);
        $db->bind(1, $category);
        if ($limit !== null) {
            $db->bind(2, $limit, 'i');
        }
    } else {
        $query = 'SELECT * FROM gallery WHERE is_published = 1 ORDER BY order_position ASC';
        if ($limit !== null) {
            $query .= ' LIMIT ?';
        }
        $db->query($query);
        if ($limit !== null) {
            $db->bind(1, $limit, 'i');
        }
    }
    
    return $db->resultSet();
}

/**
 * Get sliders
 */
function getSliders($limit = 5) {
    global $db;
    $db->query('SELECT * FROM sliders WHERE is_published = 1 ORDER BY order_position ASC LIMIT ?');
    $db->bind(1, $limit, 'i');
    return $db->resultSet();
}

/**
 * Format date
 */
function formatDate($date, $format = 'd.m.Y') {
    return date($format, strtotime($date));
}

/**
 * Format time
 */
function formatTime($date, $format = 'H:i') {
    return date($format, strtotime($date));
}

/**
 * Truncate text
 */
function truncateText($text, $length = 100, $suffix = '...') {
    if (strlen($text) > $length) {
        return substr($text, 0, $length - strlen($suffix)) . $suffix;
    }
    return $text;
}

/**
 * Remove HTML tags
 */
function stripHTMLTags($text) {
    return strip_tags($text);
}

/**
 * Format bytes to human readable
 */
function formatBytes($bytes, $precision = 2) {
    $units = ['B', 'KB', 'MB', 'GB'];
    for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
        $bytes /= 1024;
    }
    return round($bytes, $precision) . ' ' . $units[$i];
}

/**
 * Get page count
 */
function getPageCount($items_per_page = ITEMS_PER_PAGE) {
    global $db;
    $db->query('SELECT COUNT(*) as count FROM pages WHERE is_published = 1');
    $result = $db->single();
    return ceil($result['count'] / $items_per_page);
}

/**
 * Send email (optional)
 */
function sendEmail($to, $subject, $message, $headers = []) {
    $default_headers = [
        'From' => SMTP_FROM,
        'Reply-To' => SMTP_FROM,
        'Content-Type' => 'text/html; charset=UTF-8'
    ];
    
    $headers = array_merge($default_headers, $headers);
    $header_string = '';
    foreach ($headers as $key => $value) {
        $header_string .= "$key: $value\r\n";
    }
    
    return mail($to, $subject, $message, $header_string);
}

/**
 * Format Turkish phone number
 */
function formatPhoneNumber($phone) {
    $phone = preg_replace('/[^0-9]/', '', $phone);
    if (strlen($phone) == 10) {
        return '0' . $phone;
    }
    return $phone;
}

/**
 * Get SEO keywords for page
 */
function getSEOKeywords($limit = 15) {
    global $db;
    $db->query('SELECT * FROM seo_keywords WHERE is_active = 1 ORDER BY RAND() LIMIT ?');
    $db->bind(1, $limit, 'i');
    return $db->resultSet();
}
