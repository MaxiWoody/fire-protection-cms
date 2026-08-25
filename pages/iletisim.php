<?php
/**
 * Contact Page
 * PHP 7.4 Compatible
 */

require_once dirname(__DIR__) . '/config/constants.php';

$page_title = 'İletişim | ' . SITE_NAME;
$page_description = 'Bize ulaşın ve profesyonel danışmanlık alın';
$page_keywords = 'iletişim, teklif, danışmanlık';

$settings = getSettings();

include dirname(__DIR__) . '/includes/header.php';
?>

<!-- Page Header -->
<section class="page-header bg-primary text-white py-5">
    <div class="container">
        <h1 class="mb-2">İletişim</h1>
        <p class="lead">Bize ulaşın ve yanıtlarınızı öğrenin</p>
    </div>
</section>

<section class="contact-section py-5">
    <div class="container">
        <div class="row">
            <!-- Contact Info -->
            <div class="col-lg-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title mb-4">
                            <i class="fas fa-map-marker-alt text-primary"></i> Adres
                        </h5>
                        <p class="card-text"><?php echo htmlspecialchars($settings['address']); ?></p>
                        
                        <h5 class="card-title mb-3 mt-4">
                            <i class="fas fa-phone text-primary"></i> Telefon
                        </h5>
                        <p class="card-text">
                            <a href="tel:<?php echo htmlspecialchars($settings['phone']); ?>" class="text-decoration-none">
                                <?php echo htmlspecialchars($settings['phone']); ?>
                            </a>
                        </p>
                        
                        <h5 class="card-title mb-3 mt-4">
                            <i class="fas fa-envelope text-primary"></i> E-mail
                        </h5>
                        <p class="card-text">
                            <a href="mailto:<?php echo htmlspecialchars($settings['email']); ?>" class="text-decoration-none">
                                <?php echo htmlspecialchars($settings['email']); ?>
                            </a>
                        </p>
                        
                        <h5 class="card-title mb-3 mt-4">
                            <i class="fab fa-whatsapp text-success"></i> WhatsApp
                        </h5>
                        <p class="card-text">
                            <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $settings['whatsapp_number']); ?>" 
                               class="btn btn-success btn-sm" target="_blank">
                                WhatsApp ile Yazın
                            </a>
                        </p>
                        
                        <h5 class="card-title mb-3 mt-4">
                            <i class="fas fa-clock text-primary"></i> Çalışma Saatleri
                        </h5>
                        <p class="card-text"><?php echo nl2br(htmlspecialchars($settings['business_hours'])); ?></p>
                    </div>
                </div>
            </div>
            
            <!-- Contact Form -->
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Mesaj Gönder</h5>
                        <form id="contactForm" class="contact-form">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Ad Soyad *</label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">E-mail *</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label">Telefon</label>
                                    <input type="tel" class="form-control" id="phone" name="phone">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="service_type" class="form-label">Hizmet Türü</label>
                                    <select class="form-select" id="service_type" name="service_type">
                                        <option value="">Seçiniz</option>
                                        <option value="yangin-sondurme">Yangın Söndürme</option>
                                        <option value="yangín-algílama">Yangın Algılama</option>
                                        <option value="danismanlik">Danışmanlık</option>
                                        <option value="bakım">Bakım ve Onarım</option>
                                        <option value="diger">Diğer</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="subject" class="form-label">Konu *</label>
                                <input type="text" class="form-control" id="subject" name="subject" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="message" class="form-label">Mesaj *</label>
                                <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                            </div>
                            
                            <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                            
                            <button type="submit" class="btn btn-primary btn-lg">
                                Mesaj Gönder
                            </button>
                        </form>
                        <div id="formMessage" class="alert mt-3" style="display: none;"></div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Google Maps -->
        <?php if (!empty($settings['map_latitude']) && !empty($settings['map_longitude'])): ?>
        <div class="row mt-5">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h5 class="mb-0">Konumumuz</h5>
                    </div>
                    <div class="card-body p-0">
                        <iframe width="100%" height="400" style="border:0;" 
                                src="https://www.google.com/maps/embed/v1/place?key=YOUR_GOOGLE_MAPS_API_KEY&q=<?php echo urlencode($settings['address']); ?>" 
                                allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php include dirname(__DIR__) . '/includes/footer.php'; ?>
