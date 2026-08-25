<?php
/**
 * Admin SEO Management
 * PHP 7.4 Compatible
 */

require_once dirname(__DIR__) . '/config/constants.php';
requireAdmin();

$page_title = 'SEO Ayarları | ' . SITE_NAME;
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'add_keyword') {
        $keyword = sanitize($_POST['keyword'] ?? '');
        $category = sanitize($_POST['category'] ?? '');
        $description = sanitize($_POST['description'] ?? '');
        
        if (!empty($keyword)) {
            $db->query('INSERT INTO seo_keywords (keyword, category, description, is_active) VALUES (?, ?, ?, 1)');
            $db->bind(1, $keyword);
            $db->bind(2, $category);
            $db->bind(3, $description);
            
            if ($db->execute()) {
                $success = 'Anahtar kelime eklendi';
                logAction('CREATE', 'seo_keyword', $db->lastId());
            } else {
                $error = 'Anahtar kelime eklenirken hata oluştu';
            }
        } else {
            $error = 'Anahtar kelime boş olamaz';
        }
    }
}

$delete_id = isset($_GET['delete']) ? (int)$_GET['delete'] : null;
if ($delete_id) {
    $db->query('DELETE FROM seo_keywords WHERE id = ?');
    $db->bind(1, $delete_id, 'i');
    if ($db->execute()) {
        $success = 'Anahtar kelime silindi';
        logAction('DELETE', 'seo_keyword', $delete_id);
    } else {
        $error = 'Silme işlemi başarısız';
    }
}

include dirname(__DIR__) . '/includes/header.php';
?>

<div class="container-fluid">
    <div class="row mt-4">
        <div class="col-12">
            <h2>SEO Ayarları</h2>
            
            <?php if (!empty($error)): ?>
            <div class="alert alert-danger alert-dismissible fade show mt-3">
                <?php echo htmlspecialchars($error); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>
            
            <?php if (!empty($success)): ?>
            <div class="alert alert-success alert-dismissible fade show mt-3">
                <?php echo htmlspecialchars($success); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>
            
            <!-- Add Keyword Form -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">Yeni Anahtar Kelime Ekle</h5>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <input type="hidden" name="action" value="add_keyword">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="keyword" class="form-label">Anahtar Kelime *</label>
                                <input type="text" class="form-control" id="keyword" name="keyword" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="category" class="form-label">Kategori</label>
                                <select class="form-select" id="category" name="category">
                                    <option value="">Seçiniz</option>
                                    <option value="hizmetler">Hizmetler</option>
                                    <option value="ürünler">Ürünler</option>
                                    <option value="bilgi">Bilgi</option>
                                    <option value="genel">Genel</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Açıklama</label>
                            <textarea class="form-control" id="description" name="description" rows="2"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Ekle
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Keywords List -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">Anahtar Kelimeler</h5>
                </div>
                <div class="card-body">
                    <?php
                    $db->query('SELECT * FROM seo_keywords ORDER BY keyword ASC');
                    $keywords = $db->resultSet();
                    
                    if (!empty($keywords)):
                    ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Anahtar Kelime</th>
                                    <th>Kategori</th>
                                    <th>Açıklama</th>
                                    <th>Durum</th>
                                    <th>İşlem</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($keywords as $kw): ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($kw['keyword']); ?></strong></td>
                                    <td><?php echo htmlspecialchars($kw['category'] ?? '-'); ?></td>
                                    <td><?php echo htmlspecialchars(truncateText($kw['description'] ?? '', 50)); ?></td>
                                    <td>
                                        <?php if ($kw['is_active']): ?>
                                        <span class="badge bg-success">Aktif</span>
                                        <?php else: ?>
                                        <span class="badge bg-secondary">Pasif</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="<?php echo SITE_URL; ?>/admin/seo.php?delete=<?php echo $kw['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Silmek istediğinizden emin misiniz?')">Sil</a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php else: ?>
                    <p class="text-muted">Henüz anahtar kelime yok.</p>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Sitemap Info -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">Site Haritası (Sitemap)</h5>
                </div>
                <div class="card-body">
                    <p>Site haritanız şu adreste bulunmaktadır:</p>
                    <p>
                        <a href="<?php echo SITE_URL; ?>/sitemap.xml" target="_blank" class="btn btn-info">
                            <?php echo SITE_URL; ?>/sitemap.xml
                        </a>
                    </p>
                    <p class="text-muted small">Sitemap.xml Google ve diğer arama motorlarına sitenizin yapısını tanıtır.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include dirname(__DIR__) . '/includes/footer.php'; ?>
