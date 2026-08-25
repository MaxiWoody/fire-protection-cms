<?php
/**
 * Admin Messages Management
 * PHP 7.4 Compatible
 */

require_once dirname(__DIR__) . '/config/constants.php';
requireAdmin();

$page_title = 'İletişim Mesajları | ' . SITE_NAME;
$error = '';
$success = '';

$view_id = isset($_GET['view']) ? (int)$_GET['view'] : null;
$delete_id = isset($_GET['delete']) ? (int)$_GET['delete'] : null;

// Mark as read
if ($view_id) {
    $db->query('UPDATE contact_messages SET is_read = 1 WHERE id = ?');
    $db->bind(1, $view_id, 'i');
    $db->execute();
}

// Delete message
if ($delete_id && isset($_GET['confirm']) && $_GET['confirm'] === '1') {
    $db->query('DELETE FROM contact_messages WHERE id = ?');
    $db->bind(1, $delete_id, 'i');
    if ($db->execute()) {
        $success = 'Mesaj silindi';
        logAction('DELETE', 'contact_message', $delete_id);
    } else {
        $error = 'Mesaj silinirken hata oluştu';
    }
}

include dirname(__DIR__) . '/includes/header.php';
?>

<div class="container-fluid">
    <div class="row mt-4">
        <div class="col-12">
            <h2>İletişim Mesajları</h2>
            
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
            
            <?php if ($view_id): ?>
            <!-- Message Detail View -->
            <?php
            $db->query('SELECT * FROM contact_messages WHERE id = ?');
            $db->bind(1, $view_id, 'i');
            $message = $db->single();
            
            if ($message):
            ?>
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">Mesaj Detayı</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Ad Soyad:</strong>
                            <p><?php echo htmlspecialchars($message['name']); ?></p>
                        </div>
                        <div class="col-md-6">
                            <strong>E-mail:</strong>
                            <p><a href="mailto:<?php echo htmlspecialchars($message['email']); ?>"><?php echo htmlspecialchars($message['email']); ?></a></p>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Telefon:</strong>
                            <p><?php echo htmlspecialchars($message['phone'] ?? '-'); ?></p>
                        </div>
                        <div class="col-md-6">
                            <strong>Hizmet Türü:</strong>
                            <p><?php echo htmlspecialchars($message['service_type'] ?? '-'); ?></p>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-12">
                            <strong>Konu:</strong>
                            <p><?php echo htmlspecialchars($message['subject']); ?></p>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-12">
                            <strong>Mesaj:</strong>
                            <p><?php echo nl2br(htmlspecialchars($message['message'])); ?></p>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-12">
                            <small class="text-muted">
                                <strong>Tarih:</strong> <?php echo formatDate($message['created_at'], 'd.m.Y H:i'); ?>
                            </small>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <a href="<?php echo SITE_URL; ?>/admin/messages.php" class="btn btn-secondary">Geri Dön</a>
                        <a href="<?php echo SITE_URL; ?>/admin/messages.php?delete=<?php echo $message['id']; ?>" class="btn btn-danger" onclick="return confirm('Silmek istediğinizden emin misiniz?')">Sil</a>
                    </div>
                </div>
            </div>
            <?php else: ?>
            <div class="alert alert-warning mt-3">Mesaj bulunamadı</div>
            <?php endif; ?>
            
            <?php else: ?>
            <!-- Messages List -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">Tüm Mesajlar</h5>
                </div>
                <div class="card-body">
                    <?php
                    $db->query('SELECT * FROM contact_messages ORDER BY created_at DESC');
                    $messages = $db->resultSet();
                    
                    if (!empty($messages)):
                    ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Ad</th>
                                    <th>E-mail</th>
                                    <th>Konu</th>
                                    <th>Tarih</th>
                                    <th>Durum</th>
                                    <th>İşlem</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($messages as $msg): ?>
                                <tr class="<?php echo !$msg['is_read'] ? 'table-light fw-bold' : ''; ?>">
                                    <td><?php echo htmlspecialchars($msg['name']); ?></td>
                                    <td><?php echo htmlspecialchars($msg['email']); ?></td>
                                    <td><?php echo htmlspecialchars(truncateText($msg['subject'], 30)); ?></td>
                                    <td><?php echo formatDate($msg['created_at'], 'd.m.Y'); ?></td>
                                    <td>
                                        <?php if ($msg['is_read']): ?>
                                        <span class="badge bg-success">Okundu</span>
                                        <?php else: ?>
                                        <span class="badge bg-warning">Okunmadı</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="<?php echo SITE_URL; ?>/admin/messages.php?view=<?php echo $msg['id']; ?>" class="btn btn-sm btn-info">Görüntüle</a>
                                        <a href="<?php echo SITE_URL; ?>/admin/messages.php?delete=<?php echo $msg['id']; ?>&confirm=1" class="btn btn-sm btn-danger" onclick="return confirm('Silmek istediğinizden emin misiniz?')">Sil</a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php else: ?>
                    <p class="text-muted">Henüz mesaj yok.</p>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include dirname(__DIR__) . '/includes/footer.php'; ?>
