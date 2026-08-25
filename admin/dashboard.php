<?php
/**
 * Admin Dashboard
 * PHP 7.4 Compatible
 */

require_once dirname(__DIR__) . '/config/constants.php';
requireAdmin();

$page_title = 'Admin Dashboard | ' . SITE_NAME;
include dirname(__DIR__) . '/includes/header.php';

// Get statistics
$db->query('SELECT COUNT(*) as count FROM pages WHERE is_published = 1');
$pages_count = $db->single()['count'];

$db->query('SELECT COUNT(*) as count FROM services WHERE is_published = 1');
$services_count = $db->single()['count'];

$db->query('SELECT COUNT(*) as count FROM contact_messages WHERE is_read = 0');
$unread_messages = $db->single()['count'];

$db->query('SELECT COUNT(*) as count FROM users');
$users_count = $db->single()['count'];
?>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <nav class="col-md-2 d-md-block bg-light sidebar">
            <div class="position-sticky pt-3">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link active" href="<?php echo SITE_URL; ?>/admin/dashboard.php">
                            <i class="fas fa-dashboard"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo SITE_URL; ?>/admin/pages.php">
                            <i class="fas fa-file"></i> Sayfalar
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo SITE_URL; ?>/admin/services.php">
                            <i class="fas fa-cog"></i> Hizmetler
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo SITE_URL; ?>/admin/messages.php">
                            <i class="fas fa-envelope"></i> Mesajlar
                            <?php if ($unread_messages > 0): ?>
                            <span class="badge bg-danger"><?php echo $unread_messages; ?></span>
                            <?php endif; ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo SITE_URL; ?>/admin/seo.php">
                            <i class="fas fa-search"></i> SEO Ayarları
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo SITE_URL; ?>/admin/settings.php">
                            <i class="fas fa-cogs"></i> Site Ayarları
                        </a>
                    </li>
                    <?php if ($_SESSION['role'] === 'admin'): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo SITE_URL; ?>/admin/users.php">
                            <i class="fas fa-users"></i> Kullanıcılar
                        </a>
                    </li>
                    <?php endif; ?>
                    <li class="nav-item mt-3 border-top pt-3">
                        <a class="nav-link text-danger" href="<?php echo SITE_URL; ?>/admin/logout.php">
                            <i class="fas fa-sign-out-alt"></i> Çıkış Yap
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
        
        <!-- Main Content -->
        <main class="col-md-10 ms-sm-auto px-md-4">
            <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
                <h1>Dashboard</h1>
                <div>
                    <span class="me-3">Hoş geldiniz, <strong><?php echo htmlspecialchars($_SESSION['full_name'] ?? $_SESSION['username']); ?></strong></span>
                </div>
            </div>
            
            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <i class="fas fa-file fa-3x text-primary mb-3"></i>
                            <h5 class="card-title">Sayfalar</h5>
                            <p class="card-text display-6"><?php echo $pages_count; ?></p>
                            <a href="<?php echo SITE_URL; ?>/admin/pages.php" class="btn btn-primary btn-sm">Yönet</a>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <i class="fas fa-cog fa-3x text-success mb-3"></i>
                            <h5 class="card-title">Hizmetler</h5>
                            <p class="card-text display-6"><?php echo $services_count; ?></p>
                            <a href="<?php echo SITE_URL; ?>/admin/services.php" class="btn btn-success btn-sm">Yönet</a>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <i class="fas fa-envelope fa-3x text-warning mb-3"></i>
                            <h5 class="card-title">Okunmamış Mesajlar</h5>
                            <p class="card-text display-6"><?php echo $unread_messages; ?></p>
                            <a href="<?php echo SITE_URL; ?>/admin/messages.php" class="btn btn-warning btn-sm">Görüntüle</a>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <i class="fas fa-users fa-3x text-danger mb-3"></i>
                            <h5 class="card-title">Kullanıcılar</h5>
                            <p class="card-text display-6"><?php echo $users_count; ?></p>
                            <a href="<?php echo SITE_URL; ?>/admin/users.php" class="btn btn-danger btn-sm">Yönet</a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Recent Messages -->
            <div class="card mt-5">
                <div class="card-header">
                    <h5 class="mb-0">Son İletişim Mesajları</h5>
                </div>
                <div class="card-body">
                    <?php
                    $db->query('SELECT * FROM contact_messages ORDER BY created_at DESC LIMIT 5');
                    $messages = $db->resultSet();
                    
                    if (!empty($messages)):
                    ?>
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Ad</th>
                                <th>E-mail</th>
                                <th>Konu</th>
                                <th>Tarih</th>
                                <th>İşlem</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($messages as $msg): ?>
                            <tr class="<?php echo $msg['is_read'] ? '' : 'table-light'; ?>">
                                <td><?php echo htmlspecialchars($msg['name']); ?></td>
                                <td><?php echo htmlspecialchars($msg['email']); ?></td>
                                <td><?php echo htmlspecialchars($msg['subject']); ?></td>
                                <td><?php echo formatDate($msg['created_at']); ?></td>
                                <td>
                                    <a href="<?php echo SITE_URL; ?>/admin/messages.php?view=<?php echo $msg['id']; ?>" class="btn btn-sm btn-info">Görüntüle</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php else: ?>
                    <p class="text-muted">Henüz mesaj yok.</p>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>
</div>

<?php include dirname(__DIR__) . '/includes/footer.php'; ?>
