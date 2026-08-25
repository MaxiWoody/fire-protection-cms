<?php
/**
 * Admin Login Page
 * PHP 7.4 Compatible
 */

require_once dirname(__DIR__) . '/config/constants.php';

// If already logged in, redirect to dashboard
if (isUserLoggedIn()) {
    header('Location: ' . SITE_URL . '/admin/dashboard.php');
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize($_POST['username'] ?? '', 'string');
    $password = $_POST['password'] ?? '';
    
    if (empty($username) || empty($password)) {
        $error = 'Kullanıcı adı ve şifre gerekli';
    } else {
        $db->query('SELECT * FROM users WHERE username = ? OR email = ?');
        $db->bind(1, $username);
        $db->bind(2, $username);
        $user = $db->single();
        
        if ($user && verifyPassword($password, $user['password']) && $user['is_active']) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['full_name'] = $user['full_name'];
            $_SESSION['last_activity'] = time();
            
            // Update last login
            $db->query('UPDATE users SET last_login = NOW() WHERE id = ?');
            $db->bind(1, $user['id'], 'i');
            $db->execute();
            
            logAction('LOGIN', 'user', $user['id']);
            
            header('Location: ' . SITE_URL . '/admin/dashboard.php');
            exit;
        } else {
            $error = 'Geçersiz kullanıcı adı veya şifre';
        }
    }
}

$page_title = 'Admin Giriş';
include dirname(__DIR__) . '/includes/header.php';
?>

<div class="container">
    <div class="row justify-content-center mt-5">
        <div class="col-md-6">
            <div class="card shadow-lg">
                <div class="card-body p-5">
                    <h2 class="card-title text-center mb-4">Admin Panel</h2>
                    
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
                    
                    <form method="POST">
                        <div class="mb-3">
                            <label for="username" class="form-label">Kullanıcı Adı / E-mail</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">Şifre</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100 btn-lg">
                            Giriş Yap
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include dirname(__DIR__) . '/includes/footer.php'; ?>
