<?php
require_once __DIR__ . '/config/koneksi.php';
require_once __DIR__ . '/includes/functions.php';

$menus = [];
$result = mysqli_query($conn, 'SELECT nama_menu, harga, deskripsi, gambar FROM menu_nasi_bakar ORDER BY id DESC LIMIT 6');
if ($result) {
    $menus = mysqli_fetch_all($result, MYSQLI_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventaris Nasi Bakar</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header class="site-header">
        <nav class="landing-nav">
            <a class="brand" href="#home">
                <span class="brand-mark">NB</span>
                <span>Inventaris Nasi Bakar</span>
            </a>
            <button class="nav-toggle" type="button" data-nav-toggle aria-label="Buka menu">
                <span></span><span></span><span></span>
            </button>
            <div class="nav-links" data-nav-menu>
                <a href="#home">Home</a>
                <a href="#tentang">Tentang</a>
                <a href="#produk">Produk</a>
                <a class="nav-login" href="auth/login.php">Login</a>
            </div>
        </nav>
    </header>

    <main>
        <section class="hero-section" id="home">
            <div class="hero-content">
                <p class="eyebrow">Manajemen stok untuk dapur yang rapi</p>
                <h1>Sistem Inventaris Nasi Bakar</h1>
                <p class="hero-description">
                    Kelola bahan baku, menu, supplier, dan arus stok harian dalam satu sistem sederhana yang cepat digunakan oleh admin usaha.
                </p>
                <div class="hero-actions">
                    <a class="btn btn-primary" href="#produk">Lihat Produk</a>
                    <a class="btn btn-secondary" href="auth/login.php">Login Admin</a>
                </div>
            </div>
            <div class="hero-visual" aria-hidden="true">
                <div class="plate-card">
                    <div class="rice-wrap"></div>
                    <div class="stock-note note-one">Beras +25 kg</div>
                    <div class="stock-note note-two">Daun pisang menipis</div>
                </div>
            </div>
        </section>

        <section class="section" id="tentang">
            <div class="section-heading">
                <p class="eyebrow">Tentang Usaha</p>
                <h2>Dapur nasi bakar butuh stok yang selalu terpantau.</h2>
            </div>
            <div class="about-grid">
                <article>
                    <h3>Bahan baku terkendali</h3>
                    <p>Admin dapat memantau stok beras, ayam, ikan, daun pisang, dan bumbu utama sebelum produksi dimulai.</p>
                </article>
                <article>
                    <h3>Supplier tercatat</h3>
                    <p>Data pemasok bahan disimpan rapi sehingga pembelian ulang bisa dilakukan lebih cepat.</p>
                </article>
                <article>
                    <h3>Transaksi jelas</h3>
                    <p>Setiap stok masuk dan keluar tercatat dengan tanggal, jumlah, dan keterangan penggunaan.</p>
                </article>
            </div>
        </section>

        <section class="section section-soft" id="produk">
            <div class="section-heading">
                <p class="eyebrow">Daftar Menu</p>
                <h2>Menu Nasi Bakar Favorit</h2>
            </div>
            <div class="menu-grid">
                <?php if ($menus): ?>
                    <?php foreach ($menus as $menu): ?>
                        <article class="menu-card">
                            <div class="menu-image">
                                <span><?= e(substr($menu['nama_menu'], 0, 2)); ?></span>
                            </div>
                            <div>
                                <h3><?= e($menu['nama_menu']); ?></h3>
                                <p><?= e($menu['deskripsi']); ?></p>
                                <strong><?= rupiah($menu['harga']); ?></strong>
                            </div>
                        </article>
                    <?php endforeach; ?>
                <?php else: ?>
                    <?php
                    $fallbackMenus = [
                        ['Nasi Bakar Ayam Suwir', 'Ayam suwir pedas dengan aroma daun pisang.', 18000],
                        ['Nasi Bakar Cumi', 'Cumi berbumbu gurih pedas untuk menu favorit.', 22000],
                        ['Nasi Bakar Teri', 'Teri medan, kemangi, dan sambal khas dapur.', 17000],
                    ];
                    ?>
                    <?php foreach ($fallbackMenus as $menu): ?>
                        <article class="menu-card">
                            <div class="menu-image"><span><?= e(substr($menu[0], 0, 2)); ?></span></div>
                            <div>
                                <h3><?= e($menu[0]); ?></h3>
                                <p><?= e($menu[1]); ?></p>
                                <strong><?= rupiah($menu[2]); ?></strong>
                            </div>
                        </article>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <footer class="landing-footer">
        <p>&copy; 2026 Inventaris Nasi Bakar. Sistem stok sederhana untuk operasional dapur.</p>
    </footer>

    <script src="assets/js/app.js"></script>
</body>
</html>
