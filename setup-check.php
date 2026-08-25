<?php
/**
 * .htaccess Configuration Test
 * Check if mod_rewrite is enabled
 */

require_once dirname(__FILE__) . '/config/database.php';

?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kurulum Kontrol</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1>Fire Protection CMS - Kurulum Kontrol</h1>
    
    <div class="row mt-4">
        <?php
        // Check PHP Version
        $php_version = phpversion();
        $php_ok = version_compare($php_version, '7.4', '>=');
        ?>
        <div class="col-md-6 mb-3">
            <div class="card <?php echo $php_ok ? 'border-success' : 'border-danger'; ?>">
                <div class="card-body">
                    <h5 class="card-title">PHP Versi</h5>
                    <p class="card-text"><?php echo $php_version; ?></p>
                    <p><?php echo $php_ok ? '<span class="badge bg-success">✓ Tamam</span>' : '<span class="badge bg-danger">✗ 7.4+ gerekli</span>'; ?></p>
                </div>
            </div>
        </div>
        
        <?php
        // Check MySQL
        $mysql_ok = extension_loaded('mysqli') || extension_loaded('mysql');
        ?>
        <div class="col-md-6 mb-3">
            <div class="card <?php echo $mysql_ok ? 'border-success' : 'border-danger'; ?>">
                <div class="card-body">
                    <h5 class="card-title">MySQL Uzantısı</h5>
                    <p class="card-text"><?php echo $mysql_ok ? 'Yüklü' : 'Yüklü Değil'; ?></p>
                    <p><?php echo $mysql_ok ? '<span class="badge bg-success">✓ Tamam</span>' : '<span class="badge bg-danger">✗ Gerekli</span>'; ?></p>
                </div>
            </div>
        </div>
        
        <?php
        // Check mod_rewrite
        $mod_rewrite = (function_exists('apache_get_modules') && in_array('mod_rewrite', apache_get_modules())) || getenv('HTTP_MOD_REWRITE') === 'On';
        ?>
        <div class="col-md-6 mb-3">
            <div class="card <?php echo $mod_rewrite ? 'border-success' : 'border-warning'; ?>">
                <div class="card-body">
                    <h5 class="card-title">mod_rewrite</h5>
                    <p class="card-text"><?php echo $mod_rewrite ? 'Aktif' : 'Pasif'; ?></p>
                    <p><?php echo $mod_rewrite ? '<span class="badge bg-success">✓ Tamam</span>' : '<span class="badge bg-warning">⚠ Önerilir</span>'; ?></p>
                </div>
            </div>
        </div>
        
        <?php
        // Check file permissions
        $uploads_writable = is_writable('uploads/');
        $logs_writable = is_writable('logs/');
        $permissions_ok = $uploads_writable && $logs_writable;
        ?>
        <div class="col-md-6 mb-3">
            <div class="card <?php echo $permissions_ok ? 'border-success' : 'border-danger'; ?>">
                <div class="card-body">
                    <h5 class="card-title">Dosya İzinleri</h5>
                    <p class="card-text">
                        uploads/: <?php echo $uploads_writable ? '<span class="badge bg-success">✓</span>' : '<span class="badge bg-danger">✗</span>'; ?><br>
                        logs/: <?php echo $logs_writable ? '<span class="badge bg-success">✓</span>' : '<span class="badge bg-danger">✗</span>'; ?>
                    </p>
                </div>
            </div>
        </div>
        
        <?php
        // Check database connection
        try {
            $test_db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
            if ($test_db->connect_error) {
                throw new Exception($test_db->connect_error);
            }
            $db_ok = true;
            $db_message = 'Bağlı';
            $test_db->close();
        } catch (Exception $e) {
            $db_ok = false;
            $db_message = $e->getMessage();
        }
        ?>
        <div class="col-md-6 mb-3">
            <div class="card <?php echo $db_ok ? 'border-success' : 'border-danger'; ?>">
                <div class="card-body">
                    <h5 class="card-title">Veritabanı</h5>
                    <p class="card-text"><?php echo $db_message; ?></p>
                    <p><?php echo $db_ok ? '<span class="badge bg-success">✓ Tamam</span>' : '<span class="badge bg-danger">✗ Hata</span>'; ?></p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="alert alert-info mt-4">
        <h5>Kurulum Adımları</h5>
        <ol>
            <li>Veritabanını import edin: <code>database/fire_protection_db.sql</code></li>
            <li>config/database.php dosyasını düzenleyin</li>
            <li>Admin paneline gidin: <code>/admin/login.php</code></li>
            <li>Varsayılan hesap: admin / password</li>
            <li>Parolanızı değiştirin!</li>
        </ol>
    </div>
    
    <?php if ($php_ok && $mysql_ok && $db_ok): ?>
    <div class="alert alert-success">
        <h4>✓ Kurulum Hazır!</h4>
        <p>Sistem başlamaya hazır. <a href="index.php" class="btn btn-success">Ana Sayfaya Git</a></p>
    </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
