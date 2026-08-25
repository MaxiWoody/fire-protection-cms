<?php
/**
 * Navigation Menu
 */

$settings = getSettings();
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
    <div class="container-fluid">
        <!-- Logo -->
        <a class="navbar-brand" href="<?php echo SITE_URL; ?>">
            <img src="<?php echo SITE_URL . '/' . $settings['logo']; ?>" alt="<?php echo htmlspecialchars($settings['site_name']); ?>" height="40">
            <span class="ms-2"><?php echo htmlspecialchars($settings['site_name']); ?></span>
        </a>
        
        <!-- Toggle Button -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <!-- Menu Items -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo SITE_URL; ?>/">Ana Sayfa</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="servicesDropdown" role="button" data-bs-toggle="dropdown">
                        Hizmetler
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="servicesDropdown">
                        <?php
                        $categories = getServiceCategories();
                        foreach ($categories as $cat):
                        ?>
                        <li><a class="dropdown-item" href="<?php echo SITE_URL; ?>/pages/hizmetler?category=<?php echo $cat['id']; ?>">
                            <?php echo htmlspecialchars($cat['name']); ?>
                        </a></li>
                        <?php endforeach; ?>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo SITE_URL; ?>/pages/urunler">Ürünler</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo SITE_URL; ?>/pages/blog">Blog</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo SITE_URL; ?>/pages/galeri">Galeri</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo SITE_URL; ?>/pages/hakkimizda">Hakkında</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo SITE_URL; ?>/pages/iletisim">İletişim</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo SITE_URL; ?>/admin/">
                        <i class="fas fa-lock"></i> Admin
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
