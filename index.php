<?php
/**
 * Homepage - Main Page
 * PHP 7.4 Compatible
 */

require_once 'config/constants.php';

$page_title = getSettings()['seo_title'] ?? SITE_NAME . ' - Profesyonel Yangın Koruması';
$page_description = getSettings()['seo_description'] ?? 'Yangın koruması, yangın söndürme sistemleri ve profesyonel danışmanlık hizmetleri';
$page_keywords = getSettings()['seo_keywords'] ?? 'yangın koruması, yangın söndürme, fm200';

include 'includes/header.php';
?>

<!-- Hero Section -->
<section class="hero-section bg-primary text-white py-5">
    <div class="container py-5">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4">Profesyonel Yangın Koruması Hizmetleri</h1>
                <p class="lead mb-4">Türkiye'nin lider yangın koruma ve söndürme sistemleri uzmanı. 24/7 acil hizmet.</p>
                <div class="d-flex gap-3">
                    <a href="<?php echo SITE_URL; ?>/pages/iletisim" class="btn btn-light btn-lg">
                        İletişime Geçin
                    </a>
                    <a href="<?php echo SITE_URL; ?>/pages/hizmetler" class="btn btn-outline-light btn-lg">
                        Hizmetlerimiz
                    </a>
                </div>
            </div>
            <div class="col-lg-6">
                <img src="<?php echo SITE_URL; ?>/assets/images/hero-image.jpg" alt="Yangın Koruması" class="img-fluid rounded">
            </div>
        </div>
    </div>
</section>

<!-- Sliders Section -->
<?php
$sliders = getSliders(5);
if (!empty($sliders)):
?>
<section class="sliders-section py-5">
    <div class="container">
        <div id="sliderCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <?php foreach ($sliders as $index => $slider): ?>
                <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                    <img src="<?php echo SITE_URL . '/' . htmlspecialchars($slider['image']); ?>" 
                         alt="<?php echo htmlspecialchars($slider['image_alt_text'] ?? $slider['title']); ?>" 
                         class="d-block w-100" style="height: 400px; object-fit: cover;">
                    <div class="carousel-caption d-none d-md-block">
                        <h5><?php echo htmlspecialchars($slider['title']); ?></h5>
                        <p><?php echo htmlspecialchars($slider['description']); ?></p>
                        <?php if (!empty($slider['button_url'])): ?>
                        <a href="<?php echo htmlspecialchars($slider['button_url']); ?>" class="btn btn-primary">
                            <?php echo htmlspecialchars($slider['button_text'] ?? 'Devamını Oku'); ?>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#sliderCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#sliderCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Featured Services -->
<section class="services-section py-5 bg-light">
    <div class="container">
        <div class="row mb-5">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="mb-3">Hizmetlerimiz</h2>
                <p class="lead text-muted">Yangın koruması ve söndürme sistemlerinde en kapsamlı hizmetleri sunuyoruz</p>
            </div>
        </div>
        
        <div class="row g-4">
            <?php
            $featured_services = getFeaturedServices(4);
            foreach ($featured_services as $service):
            ?>
            <div class="col-md-6 col-lg-3">
                <div class="service-card card h-100 shadow-sm">
                    <?php if (!empty($service['image'])): ?>
                    <img src="<?php echo SITE_URL . '/' . htmlspecialchars($service['image']); ?>" 
                         alt="<?php echo htmlspecialchars($service['title']); ?>" 
                         class="card-img-top" style="height: 200px; object-fit: cover;">
                    <?php endif; ?>
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($service['title']); ?></h5>
                        <p class="card-text text-muted"><?php echo truncateText(stripHTMLTags($service['description']), 100); ?></p>
                        <a href="<?php echo SITE_URL; ?>/pages/service/<?php echo htmlspecialchars($service['slug']); ?>" class="btn btn-primary btn-sm">
                            Detayları Gör
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <div class="row mt-5">
            <div class="col-12 text-center">
                <a href="<?php echo SITE_URL; ?>/pages/hizmetler" class="btn btn-primary btn-lg">
                    Tüm Hizmetleri Görün
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials -->
<?php
$testimonials = getTestimonials(6);
if (!empty($testimonials)):
?>
<section class="testimonials-section py-5">
    <div class="container">
        <div class="row mb-5">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="mb-3">Müşteri Yorumları</h2>
                <p class="lead text-muted">Müşterilerimizin bize duyduğu güven ve memnuniyet</p>
            </div>
        </div>
        
        <div class="row g-4">
            <?php foreach ($testimonials as $testimonial): ?>
            <div class="col-md-6 col-lg-4">
                <div class="testimonial-card card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="rating mb-3">
                            <?php for ($i = 0; $i < $testimonial['rating']; $i++): ?>
                            <i class="fas fa-star text-warning"></i>
                            <?php endfor; ?>
                        </div>
                        <p class="card-text italic text-muted">"<?php echo htmlspecialchars(truncateText($testimonial['content'], 150)); ?>"</p>
                        <div class="mt-3">
                            <strong><?php echo htmlspecialchars($testimonial['name']); ?></strong>
                            <?php if (!empty($testimonial['company'])): ?>
                            <br><small class="text-muted"><?php echo htmlspecialchars($testimonial['company']); ?></small>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- FAQ Section -->
<?php
$faq_items = getFAQ(null, 6);
if (!empty($faq_items)):
?>
<section class="faq-section py-5 bg-light">
    <div class="container">
        <div class="row mb-5">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="mb-3">Sık Sorulan Sorular</h2>
                <p class="lead text-muted">Yangın koruması hakkında sıkça sorulan sorular</p>
            </div>
        </div>
        
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="accordion" id="faqAccordion">
                    <?php foreach ($faq_items as $index => $faq): ?>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="heading<?php echo $index; ?>">
                            <button class="accordion-button <?php echo $index > 0 ? 'collapsed' : ''; ?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo $index; ?>">
                                <?php echo htmlspecialchars($faq['question']); ?>
                            </button>
                        </h2>
                        <div id="collapse<?php echo $index; ?>" class="accordion-collapse collapse <?php echo $index === 0 ? 'show' : ''; ?>" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                <?php echo $faq['answer']; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- CTA Section -->
<section class="cta-section py-5 bg-danger text-white">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="mb-4">Hemen Teklif İsteyin</h2>
                <p class="lead mb-4">Yangın koruma çözümleri için profesyonel danışmanlık alın</p>
                <a href="<?php echo SITE_URL; ?>/pages/iletisim" class="btn btn-light btn-lg">
                    Bize Ulaşın
                </a>
                <?php if (!empty(getSettings()['whatsapp_number'])): ?>
                <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', getSettings()['whatsapp_number']); ?>" class="btn btn-outline-light btn-lg ms-2" target="_blank">
                    <i class="fab fa-whatsapp"></i> WhatsApp
                </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
