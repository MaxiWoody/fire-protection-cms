<?php
/**
 * Services Page
 * PHP 7.4 Compatible
 */

require_once dirname(__DIR__) . '/config/constants.php';

$page_title = 'Hizmetler | ' . SITE_NAME;
$page_description = 'Yangın koruması, söndürme sistemleri, dedektörleri ve profesyonel danışmanlık hizmetleri';
$page_keywords = 'yangın hizmetleri, yangın söndürme, fm200, sprinkler, yangın koruma';

$category_id = isset($_GET['category']) ? (int)$_GET['category'] : null;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

include dirname(__DIR__) . '/includes/header.php';
?>

<!-- Page Header -->
<section class="page-header bg-primary text-white py-5">
    <div class="container">
        <h1 class="mb-2">Hizmetlerimiz</h1>
        <p class="lead">Kapsamlı yangın koruma ve söndürme sistemleri</p>
    </div>
</section>

<section class="services-list py-5">
    <div class="container">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-3 mb-4">
                <div class="list-group">
                    <a href="<?php echo SITE_URL; ?>/pages/hizmetler" class="list-group-item list-group-item-action <?php echo $category_id === null ? 'active' : ''; ?>">
                        Tüm Hizmetler
                    </a>
                    <?php
                    $categories = getServiceCategories();
                    foreach ($categories as $cat):
                    ?>
                    <a href="<?php echo SITE_URL; ?>/pages/hizmetler?category=<?php echo $cat['id']; ?>" 
                       class="list-group-item list-group-item-action <?php echo $category_id == $cat['id'] ? 'active' : ''; ?>">
                        <?php echo htmlspecialchars($cat['name']); ?>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <!-- Services Grid -->
            <div class="col-lg-9">
                <div class="row g-4">
                    <?php
                    $services = getServices($page, $category_id);
                    if (!empty($services)):
                        foreach ($services as $service):
                    ?>
                    <div class="col-md-6">
                        <div class="service-card card h-100 shadow-sm">
                            <?php if (!empty($service['image'])): ?>
                            <img src="<?php echo SITE_URL . '/' . htmlspecialchars($service['image']); ?>" 
                                 alt="<?php echo htmlspecialchars($service['title']); ?>" 
                                 class="card-img-top" style="height: 250px; object-fit: cover;">
                            <?php endif; ?>
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($service['title']); ?></h5>
                                <p class="card-text text-muted"><?php echo stripHTMLTags($service['short_description']); ?></p>
                                <?php if (!empty($service['price'])): ?>
                                <p class="text-primary fw-bold">Başlangıç: <?php echo number_format($service['price'], 2, ',', '.'); ?> TL</p>
                                <?php endif; ?>
                                <a href="<?php echo SITE_URL; ?>/pages/service/<?php echo htmlspecialchars($service['slug']); ?>" class="btn btn-primary">
                                    Detayları Gör
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php
                        endforeach;
                    else:
                    ?>
                    <div class="col-12">
                        <p class="text-center text-muted">Hizmet bulunamadı.</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include dirname(__DIR__) . '/includes/footer.php'; ?>
