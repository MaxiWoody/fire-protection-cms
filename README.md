<?php
/**
 * README.md
 * Fire Protection CMS Documentation
 */

$readme = <<<'EOF'
# Fire Protection CMS

Profesyonel yangın koruma ve söndürme sistemleri hizmetleri için PHP tabanlı İçerik Yönetim Sistemi.

## Özellikler

### Frontend
- Responsive ve Modern Tasarım (Bootstrap 5)
- SEO Optimize Edilmiş Yapı
- Dinamik Sayfa Yönetimi
- Hizmet ve Ürün Katalogları
- Blog Sistemi
- Galeri Yönetimi
- İletişim Formu
- WhatsApp Entegrasyonu
- Google Analytics Entegrasyonu
- Sitemap ve Robots.txt Otomasyonu

### Admin Panel
- Güvenli Admin Girişi
- Sayfa Yönetimi (CRUD)
- Hizmet Yönetimi
- Ürün Yönetimi
- İletişim Mesajları
- SEO Ayarları
- Site Ayarları
- Kullanıcı Yönetimi
- Sistem Günlükleri

### Teknik Özellikler
- PHP 7.4+ Uyumlu
- MySQL/MariaDB Veritabanı
- Prepared Statements (SQL Injection Koruması)
- CSRF Token Koruması
- Şifreli Oturum Yönetimi
- Dosya Yükleme Kontrolü
- Gzip Sıkıştırma
- Lazy Loading Desteği
- AJAX Form Submissions

## Kurulum

### Gereksinimler
- PHP 7.4 veya üstü
- MySQL 5.7 veya üstü / MariaDB
- Apache/Nginx Web Server
- OpenSSL (HTTPS için)

### Adımlar

1. **Dosyaları Sunucuya Yükleyin**
   ```bash
   git clone https://github.com/MaxiWoody/fire-protection-cms.git
   cd fire-protection-cms
   ```

2. **Veritabanını Oluşturun**
   ```sql
   mysql -u root -p < database/fire_protection_db.sql
   ```

3. **Yapılandırmayı Ayarlayın**
   - `config/database.php` dosyasını açın
   - Veritabanı bilgilerini güncelleyin
   - Site URL'sini ayarlayın

4. **Dosya İzinlerini Ayarlayın**
   ```bash
   chmod 755 uploads/
   chmod 755 logs/
   chmod 644 config/database.php
   ```

5. **Admin Hesabı Oluşturun**
   - Admin paneline `/admin/` adresiyle gidin
   - Varsayılan hesap: `admin` / `password`
   - Hesap parolasını değiştirin

## Kullanım

### Frontend
- **Ana Sayfa**: `/` - Site ana sayfası
- **Hizmetler**: `/pages/hizmetler.php` - Hizmet listesi
- **İletişim**: `/pages/iletisim.php` - İletişim formu
- **Blog**: `/pages/blog.php` - Blog yazıları

### Admin Panel
- **Giriş**: `/admin/login.php`
- **Dashboard**: `/admin/dashboard.php`
- **Sayfa Yönetimi**: `/admin/pages.php`
- **Hizmet Yönetimi**: `/admin/services.php`
- **Mesajlar**: `/admin/messages.php`
- **SEO**: `/admin/seo.php`
- **Ayarlar**: `/admin/settings.php`

## Dosya Yapısı

```
fire-protection-cms/
├── admin/                    # Admin panel dosyaları
│   ├── login.php
│   ├── dashboard.php
│   ├── pages.php
│   ├── services.php
│   ├── messages.php
│   ├── seo.php
│   ├── settings.php
│   └── logout.php
├── api/                      # API dosyaları
│   └── ajax-handler.php
├── assets/                   # Statik dosyalar
│   ├── css/
│   │   └── style.css
│   ├── js/
│   │   ├── main.js
│   │   └── ajax-handler.js
│   └── images/
├── config/                   # Konfigürasyon dosyaları
│   ├── database.php
│   ├── security.php
│   └── constants.php
├── database/                 # Veritabanı şemaları
│   └── fire_protection_db.sql
├── includes/                 # İçerik dosyaları
│   ├── header.php
│   ├── footer.php
│   ├── navigation.php
│   └── functions.php
├── pages/                    # Sayfa dosyaları
│   ├── iletisim.php
│   └── hizmetler.php
├── uploads/                  # Yüklenen dosyalar
├── logs/                     # Sistem günlükleri
├── index.php                 # Ana giriş dosyası
├── sitemap.php               # XML Sitemap
├── robots.txt                # Robots dosyası
└── README.md                 # Bu dosya
```

## Güvenlik

### Önemli Uyarılar
1. `config/database.php` dosyasını her zaman sunucudan yedekleyin
2. Admin paneline erişimi güvenli hale getirin (.htaccess veya IP filtrelemesi)
3. Düzenli olarak sistem günlüklerini kontrol edin
4. Veritabanını düzenli olarak yedekleyin
5. SSL/HTTPS kullanın

### Implemented Security Features
- CSRF Token Koruması
- SQL Injection Koruması (Prepared Statements)
- XSS Koruması (HTML Entities)
- Session Timeout
- Şifreli Parola Depolama (bcrypt)
- IP Logging
- Rate Limiting
- Security Headers

## Veritabanı Şeması

### Ana Tablolar
- `settings` - Site ayarları
- `pages` - İçerik sayfaları
- `services` - Hizmetler
- `products` - Ürünler
- `blog_posts` - Blog yazıları
- `contact_messages` - İletişim mesajları
- `users` - Kullanıcı hesapları
- `testimonials` - Müşteri referansları
- `gallery` - Galeri resimleri
- `sliders` - Ana sayfanın slaytları
- `seo_keywords` - SEO anahtar kelimeleri
- `system_logs` - Sistem günlükleri

## API Endpoints

### Contact Form
```
POST /api/ajax-handler.php?action=contact_form
Parameters: name, email, phone, subject, message, service_type, csrf_token
```

### Newsletter Subscribe
```
POST /api/ajax-handler.php?action=subscribe
Parameters: email
```

### Search
```
GET /api/ajax-handler.php?action=search&q=query
```

## Yapılandırma Seçenekleri

### config/database.php
```php
define('DB_HOST', 'localhost');     // Veritabanı sunucusu
define('DB_USER', 'root');          // Veritabanı kullanıcısı
define('DB_PASS', '');              // Veritabanı şifresi
define('DB_NAME', 'fire_protection_cms'); // Veritabanı adı

define('SITE_URL', 'http://localhost/fire-protection-cms');
define('ENVIRONMENT', 'development'); // development, production
define('DEBUG_MODE', true);          // Hata ayıklama modu

define('CACHE_ENABLED', true);       // Önbelleği etkinleştir
define('CACHE_TIME', 3600);          // Önbellek süresi (saniye)

define('LOG_ENABLED', true);         // Günlüklemeyi etkinleştir
define('WHATSAPP_ENABLED', true);    // WhatsApp entegrasyonu
```

## Sorun Giderme

### Beyaz Ekran (White Screen)
1. `error_reporting(E_ALL)` etkinleştirin
2. PHP error_log dosyasını kontrol edin
3. Veritabanı bağlantısını test edin

### Veritabanı Bağlantı Hatası
1. Veritabanı bilgilerini kontrol edin
2. Kullanıcı izinlerini kontrol edin
3. MySQL sunucusunun çalışıp çalışmadığını kontrol edin

### Dosya Yükleme Hatası
1. `uploads/` klasörünün izinlerini kontrol edin (755)
2. Dosya türünü ve boyutunu kontrol edin
3. PHP ini ayarlarını kontrol edin (upload_max_filesize)

## Geliştirme

### Test Etme
```bash
# Veritabanı testi
mysql -u root -p -e "SELECT * FROM fire_protection_cms.settings;"

# PHP Syntax Kontrolü
php -l config/database.php
```

### Yerel Geliştirme
```bash
php -S localhost:8000
# Tarayıcıda: http://localhost:8000
```

## Lisans

Bu proje Fatih Köse tarafından geliştirilmiştir. Ticari kullanım için lisans alınması gerekir.

## Destek

Sorunlar ve öneriler için GitHub Issues kısmına rapor edin.

E-mail: fatihkose09@gmail.com

---

**Sürüm**: 1.0.0
**Son Güncelleme**: 2026-08-25
**Geliştirici**: MaxiWoody (Fatih Köse)
EOF;

header('Content-Type: text/markdown; charset=utf-8');
echo $readme;
