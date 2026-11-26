<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

if (isLoggedIn()) {
    header('Location: kelola.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCsrf($_POST['csrf_token'] ?? null)) {
        $error = 'Sesi formulir tidak valid. Silakan muat ulang halaman.';
    } else {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        $pdo = getDB();
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? LIMIT 1");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_nama'] = $user['nama'];
            $_SESSION['user_role'] = $user['role'];
            setFlash('success', 'Selamat datang, ' . $user['nama'] . '! Anda sekarang berada di mode Portal Terpadu.');
            header('Location: kelola.php');
            exit;
        } else {
            $error = 'Username atau password salah.';
        }
    }
}

$pageTitle = 'Masuk Portal';
require_once __DIR__ . '/includes/header.php';
?>

<section class="section">
    <div class="container">
        <div class="login-wrapper" style="margin: 0 auto;">
            <div class="login-card">
                <div class="login-header">
                    <div class="brand-logo lg"><span>BNB</span></div>
                    <h1>Masuk Portal <?= e(SITE_SHORT) ?></h1>
                    <p>Login untuk menambah, mengedit, dan menghapus artikel serta informasi sekolah langsung dari website ini.</p>
                </div>

                <?php if ($error): ?>
                    <div class="alert alert-error"><?= e($error) ?></div>
                <?php endif; ?>

                <form method="POST" action="login.php" class="login-form">
                    <input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">

                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" required autofocus placeholder="Masukkan username">
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" required placeholder="Masukkan password">
                    </div>

                    <button type="submit" class="btn btn-primary btn-block btn-lg">Masuk Portal</button>
                </form>

                <div class="login-hint">
                    <small>Akun default: <code>admin</code> / <code>admin123</code></small>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
