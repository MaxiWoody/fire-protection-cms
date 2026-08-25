    </main>
    <!-- End Main Content -->
    
    <!-- Footer -->
    <footer class="footer bg-dark text-white mt-5 pt-5">
        <div class="container-fluid">
            <div class="row pb-5">
                <!-- Company Info -->
                <div class="col-md-3 mb-4">
                    <h5><?php echo htmlspecialchars($settings['site_name']); ?></h5>
                    <p class="text-muted"><?php echo htmlspecialchars($settings['site_description']); ?></p>
                    <div class="social-links mt-3">
                        <?php if (!empty($settings['facebook_url'])): ?>
                        <a href="<?php echo htmlspecialchars($settings['facebook_url']); ?>" class="text-white me-3" title="Facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <?php endif; ?>
                        <?php if (!empty($settings['instagram_url'])): ?>
                        <a href="<?php echo htmlspecialchars($settings['instagram_url']); ?>" class="text-white me-3" title="Instagram">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <?php endif; ?>
                        <?php if (!empty($settings['twitter_url'])): ?>
                        <a href="<?php echo htmlspecialchars($settings['twitter_url']); ?>" class="text-white me-3" title="Twitter">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <?php endif; ?>
                        <?php if (!empty($settings['youtube_url'])): ?>
                        <a href="<?php echo htmlspecialchars($settings['youtube_url']); ?>" class="text-white me-3" title="YouTube">
                            <i class="fab fa-youtube"></i>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Quick Links -->
                <div class="col-md-3 mb-4">
                    <h5>Hızlı Bağlantılar</h5>
                    <ul class="list-unstyled">
                        <li><a href="<?php echo SITE_URL; ?>/" class="text-muted text-decoration-none">Ana Sayfa</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/pages/hakkimizda" class="text-muted text-decoration-none">Hakkında</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/pages/hizmetler" class="text-muted text-decoration-none">Hizmetler</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/pages/iletisim" class="text-muted text-decoration-none">İletişim</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/pages/blog" class="text-muted text-decoration-none">Blog</a></li>
                    </ul>
                </div>
                
                <!-- Contact Info -->
                <div class="col-md-3 mb-4">
                    <h5>İletişim Bilgileri</h5>
                    <p class="text-muted">
                        <i class="fas fa-map-marker-alt"></i>
                        <?php echo htmlspecialchars($settings['address']); ?>
                    </p>
                    <p class="text-muted">
                        <i class="fas fa-phone"></i>
                        <a href="tel:<?php echo htmlspecialchars($settings['phone']); ?>" class="text-muted text-decoration-none">
                            <?php echo htmlspecialchars($settings['phone']); ?>
                        </a>
                    </p>
                    <p class="text-muted">
                        <i class="fas fa-envelope"></i>
                        <a href="mailto:<?php echo htmlspecialchars($settings['email']); ?>" class="text-muted text-decoration-none">
                            <?php echo htmlspecialchars($settings['email']); ?>
                        </a>
                    </p>
                    <?php if (!empty($settings['whatsapp_number'])): ?>
                    <p class="text-muted">
                        <i class="fab fa-whatsapp"></i>
                        <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $settings['whatsapp_number']); ?>" class="text-muted text-decoration-none" target="_blank">
                            WhatsApp İletişim
                        </a>
                    </p>
                    <?php endif; ?>
                </div>
                
                <!-- Business Hours -->
                <div class="col-md-3 mb-4">
                    <h5>Çalışma Saatleri</h5>
                    <div class="text-muted">
                        <?php echo nl2br(htmlspecialchars($settings['business_hours'] ?? 'Pazartesi - Cuma: 09:00 - 18:00\nCumartesi: 10:00 - 16:00\nPazar: Kapalı')); ?>
                    </div>
                </div>
            </div>
            
            <!-- Bottom Footer -->
            <hr class="bg-secondary">
            <div class="row py-3">
                <div class="col-md-6">
                    <p class="text-muted small">&copy; <?php echo date('Y'); ?> <?php echo htmlspecialchars($settings['site_name']); ?>. Tüm hakları saklıdır.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <a href="<?php echo SITE_URL; ?>/pages/gizlilik-politikasi" class="text-muted text-decoration-none small me-3">Gizlilik Politikası</a>
                    <a href="<?php echo SITE_URL; ?>/pages/kullanim-sartlari" class="text-muted text-decoration-none small">Kullanım Şartları</a>
                </div>
            </div>
        </div>
    </footer>
    
    <!-- WhatsApp Floating Button -->
    <?php if (WHATSAPP_ENABLED && !empty($settings['whatsapp_number'])): ?>
    <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $settings['whatsapp_number']); ?>" class="whatsapp-btn" target="_blank" title="WhatsApp ile Bize Ulaşın">
        <i class="fab fa-whatsapp"></i>
    </a>
    <?php endif; ?>
    
    <!-- Back to Top Button -->
    <button id="backToTop" class="back-to-top" title="Yukarı Çık">
        <i class="fas fa-arrow-up"></i>
    </button>
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo SITE_URL; ?>/assets/js/main.js"></script>
    <script src="<?php echo SITE_URL; ?>/assets/js/ajax-handler.js"></script>
</body>
</html>
