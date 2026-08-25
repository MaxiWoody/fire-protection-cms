-- Fire Protection CMS Database Schema
-- Compatible with PHP 7.4 and MySQL 5.7+

CREATE DATABASE IF NOT EXISTS `fire_protection_cms`;
USE `fire_protection_cms`;

-- 1. AYARLAR (Site Settings)
CREATE TABLE `settings` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `site_name` VARCHAR(255) NOT NULL DEFAULT 'Fire Protection',
  `site_description` TEXT,
  `site_url` VARCHAR(255),
  `logo` VARCHAR(255) DEFAULT 'assets/images/logo.png',
  `favicon` VARCHAR(255) DEFAULT 'assets/images/favicon.ico',
  `phone` VARCHAR(20),
  `email` VARCHAR(100),
  `address` TEXT,
  `map_latitude` DECIMAL(10, 8),
  `map_longitude` DECIMAL(11, 8),
  `whatsapp_number` VARCHAR(20),
  `google_analytics_id` VARCHAR(50),
  `facebook_url` VARCHAR(255),
  `instagram_url` VARCHAR(255),
  `twitter_url` VARCHAR(255),
  `youtube_url` VARCHAR(255),
  `linkedin_url` VARCHAR(255),
  `business_hours` TEXT,
  `seo_title` VARCHAR(255),
  `seo_description` TEXT,
  `seo_keywords` TEXT,
  `meta_robots` VARCHAR(100) DEFAULT 'index, follow',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. SAYFALAR (Pages)
CREATE TABLE `pages` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) NOT NULL UNIQUE,
  `content` LONGTEXT,
  `short_description` TEXT,
  `featured_image` VARCHAR(255),
  `page_type` ENUM('static', 'service', 'product', 'blog') DEFAULT 'static',
  `parent_id` INT DEFAULT NULL,
  `order_position` INT DEFAULT 0,
  `meta_title` VARCHAR(255),
  `meta_description` TEXT,
  `meta_keywords` TEXT,
  `is_published` BOOLEAN DEFAULT TRUE,
  `is_featured` BOOLEAN DEFAULT FALSE,
  `view_count` INT DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`parent_id`) REFERENCES `pages`(`id`) ON DELETE SET NULL,
  KEY `slug` (`slug`),
  KEY `is_published` (`is_published`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. HİZMETLER (Services)
CREATE TABLE `services` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) NOT NULL UNIQUE,
  `description` LONGTEXT,
  `short_description` TEXT,
  `icon` VARCHAR(255),
  `image` VARCHAR(255),
  `category_id` INT,
  `order_position` INT DEFAULT 0,
  `price` DECIMAL(10, 2),
  `meta_keywords` TEXT,
  `is_published` BOOLEAN DEFAULT TRUE,
  `is_featured` BOOLEAN DEFAULT FALSE,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY `slug` (`slug`),
  KEY `is_published` (`is_published`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. HİZMET KATEGORİLERİ (Service Categories)
CREATE TABLE `service_categories` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) NOT NULL UNIQUE,
  `description` TEXT,
  `icon` VARCHAR(255),
  `order_position` INT DEFAULT 0,
  `is_published` BOOLEAN DEFAULT TRUE,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  KEY `slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. ÜRÜNLER (Products)
CREATE TABLE `products` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) NOT NULL UNIQUE,
  `description` LONGTEXT,
  `short_description` TEXT,
  `price` DECIMAL(10, 2),
  `category_id` INT,
  `image` VARCHAR(255),
  `gallery_images` JSON,
  `specifications` JSON,
  `meta_keywords` TEXT,
  `is_published` BOOLEAN DEFAULT TRUE,
  `is_featured` BOOLEAN DEFAULT FALSE,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY `slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. ÜRÜN KATEGORİLERİ (Product Categories)
CREATE TABLE `product_categories` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) NOT NULL UNIQUE,
  `description` TEXT,
  `icon` VARCHAR(255),
  `order_position` INT DEFAULT 0,
  `is_published` BOOLEAN DEFAULT TRUE,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  KEY `slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 7. SLİD GÖRSELLER (Sliders/Carousel)
CREATE TABLE `sliders` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `title` VARCHAR(255),
  `description` TEXT,
  `image` VARCHAR(255) NOT NULL,
  `image_alt_text` VARCHAR(255),
  `button_text` VARCHAR(100),
  `button_url` VARCHAR(255),
  `order_position` INT DEFAULT 0,
  `is_published` BOOLEAN DEFAULT TRUE,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  KEY `is_published` (`is_published`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 8. İLETİŞİM FORMLARI (Contact Forms)
CREATE TABLE `contact_messages` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `phone` VARCHAR(20),
  `subject` VARCHAR(255),
  `message` LONGTEXT NOT NULL,
  `service_type` VARCHAR(100),
  `ip_address` VARCHAR(45),
  `user_agent` TEXT,
  `is_read` BOOLEAN DEFAULT FALSE,
  `is_replied` BOOLEAN DEFAULT FALSE,
  `reply_message` LONGTEXT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY `email` (`email`),
  KEY `is_read` (`is_read`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 9. REFERANSLAR/TESTIMONIALS (Testimonials)
CREATE TABLE `testimonials` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `company` VARCHAR(255),
  `position` VARCHAR(255),
  `image` VARCHAR(255),
  `content` LONGTEXT NOT NULL,
  `rating` INT DEFAULT 5,
  `is_published` BOOLEAN DEFAULT TRUE,
  `order_position` INT DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  KEY `is_published` (`is_published`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 10. SSS (FAQ)
CREATE TABLE `faq` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `question` VARCHAR(500) NOT NULL,
  `answer` LONGTEXT NOT NULL,
  `category` VARCHAR(100),
  `order_position` INT DEFAULT 0,
  `is_published` BOOLEAN DEFAULT TRUE,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  KEY `is_published` (`is_published`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 11. BLOG/YAZILAR (Blog Posts)
CREATE TABLE `blog_posts` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) NOT NULL UNIQUE,
  `content` LONGTEXT NOT NULL,
  `excerpt` TEXT,
  `featured_image` VARCHAR(255),
  `featured_image_alt` VARCHAR(255),
  `author_id` INT,
  `category_id` INT,
  `tags` JSON,
  `view_count` INT DEFAULT 0,
  `meta_title` VARCHAR(255),
  `meta_description` TEXT,
  `meta_keywords` TEXT,
  `is_published` BOOLEAN DEFAULT TRUE,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY `slug` (`slug`),
  KEY `is_published` (`is_published`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 12. BLOG KATEGORİLERİ (Blog Categories)
CREATE TABLE `blog_categories` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) NOT NULL UNIQUE,
  `description` TEXT,
  `order_position` INT DEFAULT 0,
  `is_published` BOOLEAN DEFAULT TRUE,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  KEY `slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 13. GALERİ RESIMLERI (Gallery Images)
CREATE TABLE `gallery` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `title` VARCHAR(255),
  `description` TEXT,
  `image` VARCHAR(255) NOT NULL,
  `image_alt_text` VARCHAR(255),
  `image_thumbnail` VARCHAR(255),
  `category` VARCHAR(100),
  `order_position` INT DEFAULT 0,
  `is_published` BOOLEAN DEFAULT TRUE,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  KEY `is_published` (`is_published`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 14. KULLANICI HESAPLARI (User Accounts)
CREATE TABLE `users` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `username` VARCHAR(100) NOT NULL UNIQUE,
  `email` VARCHAR(100) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `full_name` VARCHAR(255),
  `role` ENUM('admin', 'editor', 'user') DEFAULT 'user',
  `avatar` VARCHAR(255),
  `is_active` BOOLEAN DEFAULT TRUE,
  `last_login` DATETIME,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY `email` (`email`),
  KEY `role` (`role`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 15. REKLAM ALANLARI (Banners/Advertisements)
CREATE TABLE `banners` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `title` VARCHAR(255),
  `image` VARCHAR(255) NOT NULL,
  `image_alt_text` VARCHAR(255),
  `link` VARCHAR(255),
  `placement` VARCHAR(50),
  `order_position` INT DEFAULT 0,
  `is_published` BOOLEAN DEFAULT TRUE,
  `start_date` DATETIME,
  `end_date` DATETIME,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  KEY `is_published` (`is_published`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 16. SEO AYARLARI (SEO Settings per Page)
CREATE TABLE `seo_settings` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `page_id` INT,
  `page_slug` VARCHAR(255),
  `meta_title` VARCHAR(255),
  `meta_description` TEXT,
  `meta_keywords` TEXT,
  `canonical_url` VARCHAR(255),
  `og_title` VARCHAR(255),
  `og_description` TEXT,
  `og_image` VARCHAR(255),
  `schema_type` VARCHAR(100),
  `schema_data` JSON,
  `robots_index` BOOLEAN DEFAULT TRUE,
  `robots_follow` BOOLEAN DEFAULT TRUE,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY `page_slug` (`page_slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 17. SEO ANAHTAR KELİMELER (SEO Keywords Database)
CREATE TABLE `seo_keywords` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `keyword` VARCHAR(255) NOT NULL UNIQUE,
  `category` VARCHAR(100),
  `difficulty` INT DEFAULT 0,
  `search_volume` INT DEFAULT 0,
  `description` TEXT,
  `related_pages` JSON,
  `is_active` BOOLEAN DEFAULT TRUE,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  KEY `keyword` (`keyword`),
  KEY `category` (`category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 18. SİTEMAPİ KAŞESİ (Sitemap Cache)
CREATE TABLE `sitemap_cache` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `page_type` VARCHAR(50),
  `page_id` INT,
  `url` VARCHAR(255),
  `lastmod` DATETIME,
  `changefreq` VARCHAR(20),
  `priority` DECIMAL(2, 1),
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY `page_type_id` (`page_type`, `page_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 19. SİSTEM KAYITLARI (System Logs)
CREATE TABLE `system_logs` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `user_id` INT,
  `action` VARCHAR(255),
  `entity_type` VARCHAR(100),
  `entity_id` INT,
  `old_value` LONGTEXT,
  `new_value` LONGTEXT,
  `ip_address` VARCHAR(45),
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL,
  KEY `user_id` (`user_id`),
  KEY `created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- TABLO İNDEKSLERİ OLUŞTUR (Create Additional Indexes for Performance)
CREATE INDEX `idx_pages_published` ON `pages`(`is_published`, `created_at`);
CREATE INDEX `idx_services_featured` ON `services`(`is_featured`, `is_published`);
CREATE INDEX `idx_products_featured` ON `products`(`is_featured`, `is_published`);
CREATE INDEX `idx_blog_published` ON `blog_posts`(`is_published`, `created_at`);
CREATE INDEX `idx_contact_created` ON `contact_messages`(`created_at`);
CREATE INDEX `idx_users_role` ON `users`(`role`, `is_active`);

-- AYAR DEĞERLERİ EKLE (Insert Default Settings)
INSERT INTO `settings` (`site_name`, `site_description`, `phone`, `email`, `address`, `whatsapp_number`, `seo_title`, `seo_description`, `seo_keywords`) VALUES
('Fire Protection TR', 'Profesyonel İtfaiye ve Yangın Koruması Hizmetleri - Türkiyenin Lider Yangın Koruma Uzmanı', '+90 212 XXX XX XX', 'info@fireprotection.com.tr', 'İstanbul, Türkiye', '+90 532 XXX XX XX', 'Yangın Koruması | Fire Protection TR - Profesyonel Hizmetler', 'Yangın koruma, yangın söndürme sistemleri ve profesyonel danışmanlık hizmetleri. 24/7 acil hizmet hattı.', 'yangın koruması, yangın söndürme, fm200 sistemi, sprinkler sistemi, yangın algılama');

-- HİZMET KATEGORİLERİ EKLE (Insert Service Categories)
INSERT INTO `service_categories` (`name`, `slug`, `description`, `order_position`) VALUES
('Yangın Söndürme Sistemleri', 'yangin-sondurme-sistemleri', 'Profesyonel yangın söndürme sistemleri ve ürünleri', 1),
('Yangın Algılama', 'yangin-algılama', 'Modern yangın algılama teknolojileri ve sistemleri', 2),
('Yangın Koruma Danışmanlığı', 'yangin-koruma-danismanlik', 'Uzman yangın koruma danışmanlığı ve tasarım hizmetleri', 3),
('Acil Durum Planlaması', 'acil-durum-planlama', 'Acil durum planlaması ve tatbikat hizmetleri', 4),
('Bakım ve Onarım', 'bakim-ve-onarim', 'Sistemlerin periyodik bakım ve onarım hizmetleri', 5);

-- ÖRNEK HİZMETLER EKLE (Insert Sample Services)
INSERT INTO `services` (`title`, `slug`, `description`, `short_description`, `category_id`, `order_position`, `is_published`, `is_featured`) VALUES
('FM200 Yangın Söndürme Sistemi', 'fm200-yangin-sondurme-sistemi', '<h2>FM200 Yangın Söndürme Sistemi Nedir?</h2><p>FM200, çevre dostu ve insana zararlı olmayan yüksek teknoloji yangın söndürme sistemidir. Veri merkezleri, sunucu odaları, arşivler ve hassas elektronik cihazların bulunduğu alanlarda idealtir.</p><h3>Özellikleri:</h3><ul><li>Çevre dostu ODP=0 ozon aşındırıcı değeri yok</li><li>Halon\'un yerini almak üzere geliştirilmiş</li><li>Elektrikli cihazları hasar görmeden söndürür</li><li>Yangında insan hasarı minimize eder</li></ul>', 'FM200 ile profesyonel yangın söndürme çözümü', 1, 1, TRUE, TRUE),
('Sprinkler Sistemleri', 'sprinkler-sistemleri', '<h2>Otomatik Sprinkler Sistemleri</h2><p>Otomatik sprinkler sistemleri, yangınları tespit edip su spreyleri ile söndürür. Ticari alanlar, fabrikalar, depolar ve konut alanlarında yaygın olarak kullanılır.</p><h3>Avantajları:</h3><ul><li>Yangını hızlı şekilde söndürür</li><li>Mal kaybını minimuma indirir</li><li>İnsanlar kurtulmak için daha fazla zaman kazanır</li><li>Sigorta primlerini azaltır</li></ul>', 'Otomatik sprinkler sistemi kurulumu ve bakımı', 1, 2, TRUE, TRUE),
('Yangın Dedektörü ve Algılama', 'yangin-detektoru', '<h2>Gelişmiş Yangın Dedektörleri</h2><p>Profesyonel yangın dedektörleri erken aşamada yangını tespit ederek acil müdahaleyi sağlar. Duman, ısı ve alev dedektörleri kullanılır.</p><h3>Türleri:</h3><ul><li>Duman dedektörleri (Smoke detectors)</li><li>Isı dedektörleri (Heat detectors)</li><li>Alev dedektörleri (Flame detectors)</li><li>Çok sensörlü detektörler (Multi-sensor)</li></ul>', 'Yangın dedektörleri ve algılama sistemleri', 2, 3, TRUE, TRUE),
('Yangın Söndürücü Cihazlar', 'yangin-sondurme-cihazlari', '<h2>Portatif Yangın Söndürücüler</h2><p>Taşınabilir yangın söndürücü cihazları, yangının başlangıç aşamasında hızlı müdahale için kullanılır. Farklı türde yangınlara karşı uygun söndürücüler vardır.</p><h3>Söndürücü Türleri:</h3><ul><li>ABC (Kuru Toz) - Genel amaçlı</li><li>CO2 - Elektrikli yangınlar için</li><li>Su Köpüğü - Yağlı yangınlar için</li><li>Toz (Ağır Metal) - Özel uygulamalar için</li></ul>', 'Portatif yangın söndürücü cihazları ve ürünleri', 1, 4, TRUE, TRUE),
('Yangın Koruma Danışmanlığı', 'yangin-koruma-danismanlik', '<h2>Profesyonel Yangın Koruma Danışmanlığı</h2><p>Binaların ve tesislerin yangın riskini değerlendirip, uygun koruma çözümlerini dizayn etmek. Yasal standartlara uyumlu tasarımlar yapılır.</p><h3>Hizmetler:</h3><ul><li>Risk değerlendirmesi</li><li>Sistem tasarımı</li><li>Yasal uyum kontrolü</li><li>Eğitim ve danışmanlık</li></ul>', 'Uzman yangın koruma danışmanlığı ve tasarım', 3, 5, TRUE, FALSE),
('Acil Durum Planlaması ve Tatbikat', 'acil-durum-tabitkatı', '<h2>Acil Durum Planlaması</h2><p>İşletmenizin yangın ve diğer acil durumlara hazırlıklı olması için kapsamlı planlar hazırlanır ve tatbikat yapılır.</p><h3>İçerikler:</h3><ul><li>Acil tahliye planı</li><li>İletişim protokolü</li><li>Düzenli tatbikatlar</li><li>Personel eğitimi</li></ul>', 'Acil durum planlaması ve tatbikat hizmetleri', 4, 6, TRUE, FALSE);

-- ÜRÜN KATEGORİLERİ EKLE (Insert Product Categories)
INSERT INTO `product_categories` (`name`, `slug`, `description`, `order_position`) VALUES
('Yangın Söndürme Akışkanları', 'yangin-sondurme-akiskanları', 'FM200, HFC, HC ve diğer söndürme akışkanları', 1),
('Dedektörler ve Algılama', 'dedektörler-algılama', 'Duman, ısı ve alev dedektörleri', 2),
('Sprinkler Başlıkları', 'sprinkler-başlıkları', 'Otomatik sprinkler sistemleri için başlıklar', 3),
('Boru ve Fittingler', 'boru-fittinglar', 'Yangın sistemi tesisatı için ürünler', 4),
('Söndürücü Cihazlar', 'sondurme-cihazlari', 'Taşınabilir yangın söndürücüleri', 5),
('Kontrol ve Göstergeler', 'kontrol-gostergeleri', 'Sistem kontrol panoları ve göstergeleri', 6);

-- BLOG KATEGORİLERİ EKLE (Insert Blog Categories)
INSERT INTO `blog_categories` (`name`, `slug`, `description`, `order_position`) VALUES
('Yangın Güvenliği İpuçları', 'yangin-guvenligi-ipuclari', 'Evde ve işletmede yangın güvenliği ipuçları', 1),
('Teknik Makaleler', 'teknik-makaleler', 'Yangın koruma sistemleri hakkında teknik bilgiler', 2),
('Endüstriyel Uygulamalar', 'endustriyel-uygulamalar', 'Fabrika ve endüstriyel alanlarda yangın koruması', 3),
('Teknoloji Haberleri', 'teknoloji-haberleri', 'Yangın koruma teknolojileri hakkında haberler', 4),
('Yasal Standartlar', 'yasal-standartlar', 'Yangın koruma ile ilgili yasal mevzuat bilgileri', 5);

-- SEO ANAHTAR KELİMELERİ EKLE (Insert SEO Keywords)
INSERT INTO `seo_keywords` (`keyword`, `category`, `description`) VALUES
('yangın koruması türkiye', 'hizmetler', 'Yangın koruması hizmetleri ve ürünleri Türkiye'),
('yangın söndürme sistemi', 'ürünler', 'Profesyonel yangın söndürme sistemleri'),
('fm200 sistem', 'ürünler', 'FM200 yangın söndürme sistemi fiyatı'),
('sprinkler sistemi kurulumu', 'hizmetler', 'Sprinkler sistemi kurulması ve bakımı'),
('yangın detektörü fiyat', 'ürünler', 'Yangın dedektörleri ve algılama cihazları'),
('acil durum planlaması', 'hizmetler', 'Acil durum planlaması danışmanlığı'),
('yangın söndürücü cihaz', 'ürünler', 'Taşınabilir yangın söndürücüler'),
('endüstriyel yangın koruması', 'hizmetler', 'Fabrika ve endüstri için yangın koruma çözümleri'),
('yangın güvenliği ev', 'bilgi', 'Evde yangın güvenliği ve önlemler'),
('itfaiye uyumlu sistemler', 'hizmetler', 'İtfaiye standartlarına uygun yangın sistemleri'),
('yangın algılama uyarı', 'ürünler', 'Otomatik yangın algılama ve uyarı sistemleri'),
('yangın söndürücü bakımı', 'hizmetler', 'Yangın söndürücü bakım ve kontrol hizmetleri'),
('binadaki yangın sistemleri', 'bilgi', 'Binalarda yangın koruma sistemleri'),
('profesyonel yangın koruması', 'hizmetler', 'Profesyonel yangın koruma danışmanlığı'),
('yangın sistemi tesisatı', 'hizmetler', 'Yangın sistemi kurulumu ve tesisatı');
