<?php
require_once __DIR__ . '/../includes/functions.php';

if (!empty($_SESSION['user_id'])) {
    redirect(dashboard_home_for_role($_SESSION['role'] ?? ''));
}

$rememberedEmail = $_COOKIE['remember_email'] ?? '';
$flash = get_flash();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Operasional | Inventaris Nasi Bakar</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/login.css">
</head>
<body>
    <main class="login-page">
        <section class="login-panel">
            <a class="back-link" href="../index.php">Kembali ke Beranda</a>
            <div class="login-heading">
                <span class="brand-mark">NB</span>
                <h1>Login Operasional</h1>
                <p>Masuk sesuai role untuk mengelola kasir, dapur, gudang, dan pemesanan.</p>
            </div>

            <?php if ($flash): ?>
                <div class="alert alert-<?= e($flash['type']); ?>"><?= e($flash['message']); ?></div>
            <?php endif; ?>

            <form action="proses_login.php" method="post" class="login-form" data-validate>
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?= e($rememberedEmail); ?>" placeholder="admin@nasibakar.test" required>
                <small class="error-message"></small>

                <label for="password">Password</label>
                <div class="password-field">
                    <input type="password" id="password" name="password" placeholder="Masukkan password" required minlength="6">
                    <button type="button" data-toggle-password aria-label="Tampilkan password">Lihat</button>
                </div>
                <small class="error-message"></small>

                <label class="remember-row">
                    <input type="checkbox" name="remember" value="1" <?= $rememberedEmail ? 'checked' : ''; ?>>
                    <span>Remember Me</span>
                </label>

                <button class="login-button" type="submit">Masuk Dashboard</button>
            </form>
        </section>
        <section class="login-aside">
            <div>
                <p class="eyebrow">Inventaris Nasi Bakar</p>
                <h2>Stok rapi, produksi lebih tenang.</h2>
                <p>Gunakan akun dummy sesuai role setelah database diimpor. Password semua akun: password123.</p>
            </div>
        </section>
    </main>
    <script src="../assets/js/app.js"></script>
</body>
</html>
