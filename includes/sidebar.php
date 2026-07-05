<?php
$currentPath = str_replace('\\', '/', $_SERVER['PHP_SELF']);
$role = current_role();
$pesananBaruDapur = 0;

if (isset($conn) && role_can_access(['dapur'])) {
    $notifResult = mysqli_query($conn, "SELECT COUNT(*) AS total FROM pesanan WHERE status IN ('baru', 'menunggu')");
    $notifRow = $notifResult ? mysqli_fetch_assoc($notifResult) : null;
    $pesananBaruDapur = (int) ($notifRow['total'] ?? 0);
}

function nav_active(string $needle, string $currentPath): string
{
    return strpos($currentPath, $needle) !== false ? 'active' : '';
}

function nav_allowed(array $roles): bool
{
    return role_can_access($roles);
}
?>
<aside class="sidebar" data-sidebar>
    <a class="sidebar-brand" href="<?= e($basePath); ?>dashboard/index.php">
        <span class="brand-mark">NB</span>
        <span>Inventaris<br>Nasi Bakar</span>
    </a>
    <nav class="sidebar-nav">
        <a class="<?= nav_active('/dashboard/index.php', $currentPath); ?>" href="<?= e($basePath); ?>dashboard/index.php">Dashboard</a>
        <?php if (nav_allowed(['kasir'])): ?>
            <a class="<?= nav_active('/dashboard/kasir/', $currentPath); ?>" href="<?= e($basePath); ?>dashboard/kasir/index.php">Kasir & Pemesanan</a>
        <?php endif; ?>
        <?php if (nav_allowed(['kasir'])): ?>
            <a class="<?= nav_active('/dashboard/menu/', $currentPath); ?>" href="<?= e($basePath); ?>dashboard/menu/index.php">Menu Nasi Bakar</a>
        <?php endif; ?>
        <?php if (nav_allowed(['kasir'])): ?>
            <a class="<?= nav_active('/dashboard/transaksi/', $currentPath); ?>" href="<?= e($basePath); ?>dashboard/transaksi/index.php">Riwayat Transaksi</a>
        <?php endif; ?>
        <?php if (nav_allowed(['dapur'])): ?>
            <a class="<?= nav_active('/dashboard/dapur/', $currentPath); ?>" href="<?= e($basePath); ?>dashboard/dapur/index.php">
                <span>Pesanan Dapur</span>
                <?php if ($pesananBaruDapur > 0): ?>
                    <span class="nav-badge"><?= $pesananBaruDapur; ?></span>
                <?php endif; ?>
            </a>
        <?php endif; ?>
        <?php if (nav_allowed(['dapur', 'gudang'])): ?>
            <a class="<?= nav_active('/dashboard/bahan/', $currentPath); ?>" href="<?= e($basePath); ?>dashboard/bahan/index.php"><?= nav_allowed(['gudang']) && !nav_allowed(['dapur']) ? 'Data Bahan Baku' : 'Bahan Baku'; ?></a>
        <?php endif; ?>
        <?php if (nav_allowed(['gudang'])): ?>
            <a class="<?= nav_active('/dashboard/supplier/', $currentPath); ?>" href="<?= e($basePath); ?>dashboard/supplier/index.php">Supplier</a>
            <a class="<?= nav_active('/dashboard/masuk/', $currentPath); ?>" href="<?= e($basePath); ?>dashboard/masuk/index.php">Stok Masuk</a>
        <?php endif; ?>
        <?php if (nav_allowed(['dapur'])): ?>
            <a class="<?= nav_active('/dashboard/keluar/', $currentPath); ?>" href="<?= e($basePath); ?>dashboard/keluar/index.php">Stok Keluar</a>
        <?php endif; ?>
        <?php if (nav_allowed(['dapur', 'gudang'])): ?>
            <a class="<?= nav_active('/dashboard/permintaan/', $currentPath); ?>" href="<?= e($basePath); ?>dashboard/permintaan/index.php">Permintaan Restok</a>
        <?php endif; ?>
        <?php if (nav_allowed(['gudang', 'dapur', 'kasir'])): ?>
            <a class="<?= nav_active('/dashboard/laporan/', $currentPath); ?>" href="<?= e($basePath); ?>dashboard/laporan/index.php">Laporan</a>
        <?php endif; ?>
    </nav>
    <a class="logout-link" href="<?= e($basePath); ?>auth/logout.php">Logout</a>
</aside>
