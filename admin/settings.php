<?php
/**
 * Admin Settings Management
 * PHP 7.4 Compatible
 */

require_once dirname(__DIR__) . '/config/constants.php';
requireAdmin();

$page_title = 'Site Ayarları | ' . SITE_NAME;
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $settings_data = [
        'site_name' => sanitize($_POST['site_name'] ?? ''),
        'site_description' => sanitize($_POST['site_description'] ?? ''),
        'phone' => sanitize($_POST['phone'] ?? ''),
        'email' => sanitize($_POST['email'] ?? '', 'email'),
        'address' => sanitize($_POST['address'] ?? ''),
        'whatsapp_number' => sanitize($_POST['whatsapp_number'] ?? ''),
        'facebook_url' => sanitize($_POST['facebook_url'] ?? '', 'url'),
        'instagram_url' => sanitize($_POST['instagram_url'] ?? '', 'url'),
        'twitter_url' => sanitize($_POST['twitter_url'] ?? '', 'url'),
        'youtube_url' => sanitize($_POST['youtube_url'] ?? '', 'url'),
        'linkedin_url' => sanitize($_POST['linkedin_url'] ?? '', 'url'),
        'business_hours' => sanitize($_POST['business_hours'] ?? ''),
        'google_analytics_id' => sanitize($_POST['google_analytics_id'] ?? ''),
        'seo_title' => sanitize($_POST['seo_title'] ?? ''),
        'seo_description' => sanitize($_POST['seo_description'] ?? ''),
        'seo_keywords' => sanitize($_POST['seo_keywords'] ?? ''),
    ];
    
    // Handle file uploads
    if (isset($_FILES['logo']) && $_FILES['logo']['size'] > 0) {
        $upload = uploadFile($_FILES['logo']);
        if ($upload['success']) {
            $settings_data['logo'] = $upload['path'];
        } else {
            $error = 'Logo yüklenemedi';
        }
    }
    
    if (isset($_FILES['favicon']) && $_FILES['favicon']['size'] > 0) {
        $upload = uploadFile($_FILES['favicon']);
        if ($upload['success']) {
            $settings_data['favicon'] = $upload['path'];
        } else {
            $error = 'Favicon yüklenemedi';
        }
    }
    
    if (empty($error)) {
        if (updateSettings($settings_data)) {
            $success = 'Ayarlar başarıyla güncellendi';
            logAction('UPDATE', 'settings', 1);
        } else {
            $error = 'Ayarlar güncellenirken hata oluştu';
        }
    }
}

$settings = getSettings();
include dirname(__DIR__) . '/includes/header.php';
?>

<div class="container-fluid">
    <div class="row mt-4">
        <div class="col-12">
            <h2>Site Ayarları</h2>
            
            <?php if (!empty($error)): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <?php echo htmlspecialchars($error); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>
            
            <?php if (!empty($success)): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <?php echo htmlspecialchars($success); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>
            
            <form method="POST" enctype="multipart/form-data" class="mt-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Genel Bilgiler</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="site_name" class="form-label">Site Adı</label>
                                <input type="text" class="form-control" id="site_name" name="site_name" value="<?php echo htmlspecialchars($settings['site_name']); ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Telefon</label>
                                <input type="tel" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($settings['phone']); ?>">
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">E-mail</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($settings['email']); ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="whatsapp_number" class="form-label">WhatsApp Numarası</label>
                                <input type="text" class="form-control" id="whatsapp_number" name="whatsapp_number" value="<?php echo htmlspecialchars($settings['whatsapp_number']); ?>">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="address" class="form-label">Adres</label>
                            <textarea class="form-control" id="address" name="address" rows="3"><?php echo htmlspecialchars($settings['address']); ?></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label for="business_hours" class="form-label">Çalışma Saatleri</label>
                            <textarea class="form-control" id="business_hours" name="business_hours" rows="3"><?php echo htmlspecialchars($settings['business_hours']); ?></textarea>
                        </div>
                    </div>
                </div>
                
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="mb-0">Logo ve Favicon</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="logo" class="form-label">Logo</label>
                                <input type="file" class="form-control" id="logo" name="logo" accept="image/*">
                                <?php if (!empty($settings['logo'])): ?>
                                <small class="text-muted">Mevcut: <img src="<?php echo SITE_URL . '/' . htmlspecialchars($settings['logo']); ?>" style="height: 30px; margin-top: 5px;"></small>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="favicon" class="form-label">Favicon</label>
                                <input type="file" class="form-control" id="favicon" name="favicon" accept="image/*">
                                <?php if (!empty($settings['favicon'])): ?>
                                <small class="text-muted">Mevcut: <img src="<?php echo SITE_URL . '/' . htmlspecialchars($settings['favicon']); ?>" style="height: 30px; margin-top: 5px;"></small>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="mb-0">Sosyal Medya</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="facebook_url" class="form-label">Facebook</label>
                                <input type="url" class="form-control" id="facebook_url" name="facebook_url" value="<?php echo htmlspecialchars($settings['facebook_url']); ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="instagram_url" class="form-label">Instagram</label>
                                <input type="url" class="form-control" id="instagram_url" name="instagram_url" value="<?php echo htmlspecialchars($settings['instagram_url']); ?>">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="twitter_url" class="form-label">Twitter</label>
                                <input type="url" class="form-control" id="twitter_url" name="twitter_url" value="<?php echo htmlspecialchars($settings['twitter_url']); ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="youtube_url" class="form-label">YouTube</label>
                                <input type="url" class="form-control" id="youtube_url" name="youtube_url" value="<?php echo htmlspecialchars($settings['youtube_url']); ?>">
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="mb-0">SEO Ayarları</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="seo_title" class="form-label">SEO Title</label>
                            <input type="text" class="form-control" id="seo_title" name="seo_title" value="<?php echo htmlspecialchars($settings['seo_title']); ?>" maxlength="60">
                            <small class="text-muted">Maksimum 60 karakter</small>
                        </div>
                        <div class="mb-3">
                            <label for="seo_description" class="form-label">SEO Description</label>
                            <textarea class="form-control" id="seo_description" name="seo_description" rows="2" maxlength="160"><?php echo htmlspecialchars($settings['seo_description']); ?></textarea>
                            <small class="text-muted">Maksimum 160 karakter</small>
                        </div>
                        <div class="mb-3">
                            <label for="seo_keywords" class="form-label">SEO Keywords</label>
                            <textarea class="form-control" id="seo_keywords" name="seo_keywords" rows="2"><?php echo htmlspecialchars($settings['seo_keywords']); ?></textarea>
                            <small class="text-muted">Virgülle ayırılmış anahtar kelimeler</small>
                        </div>
                        <div class="mb-3">
                            <label for="google_analytics_id" class="form-label">Google Analytics ID</label>
                            <input type="text" class="form-control" id="google_analytics_id" name="google_analytics_id" value="<?php echo htmlspecialchars($settings['google_analytics_id']); ?>">
                        </div>
                    </div>
                </div>
                
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-save"></i> Kaydet
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include dirname(__DIR__) . '/includes/footer.php'; ?>
