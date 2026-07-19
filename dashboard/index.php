<?php
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../includes/functions.php';
require_login_dashboard();

$pageTitle = 'Dashboard';
$basePath = '../';
$role = current_role();

$totalBahan = db_count($conn, 'bahan_baku');
$totalMenu = db_count($conn, 'menu_nasi_bakar');
$totalSupplier = db_count($conn, 'supplier');
$stokMenipis = db_count($conn, 'bahan_baku', 'stok <= stok_minimum');
$totalPesanan = db_count($conn, 'pesanan');
$pesananHariIni = db_count($conn, 'pesanan', "DATE(created_at) = CURDATE()");
$pesananBaruDapur = db_count($conn, 'pesanan', "status IN ('baru', 'menunggu')");
$pesananDiproses = db_count($conn, 'pesanan', "status = 'diproses'");
$pendapatanHariIni = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COALESCE(SUM(total), 0) AS total FROM pesanan WHERE DATE(created_at) = CURDATE()"));

$pendapatanBulanIni = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COALESCE(SUM(total), 0) AS total FROM pesanan WHERE status = 'selesai' AND MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE())"));
$pengeluaranBulanIni = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COALESCE(SUM(jumlah * harga_beli), 0) AS total FROM stok_masuk WHERE MONTH(tanggal) = MONTH(CURDATE()) AND YEAR(tanggal) = YEAR(CURDATE())"));
$untungRugiBulanIni = (float) $pendapatanBulanIni['total'] - (float) $pengeluaranBulanIni['total'];

$latest = mysqli_query($conn, 'SELECT nama_bahan, stok, satuan, harga, created_at FROM bahan_baku ORDER BY created_at DESC LIMIT 8');
$stokTerbaru = $latest ? mysqli_fetch_all($latest, MYSQLI_ASSOC) : [];

include __DIR__ . '/../includes/header.php';
?>
<section class="stats-grid">
    <?php if ($role === 'kasir'): ?>
        <article class="stat-card">
            <span>Pesanan Hari Ini</span>
            <strong><?= $pesananHariIni; ?></strong>
        </article>
        <article class="stat-card">
            <span>Pendapatan Hari Ini</span>
            <strong><?= rupiah($pendapatanHariIni['total'] ?? 0); ?></strong>
        </article>
        <article class="stat-card">
            <span>Total Menu</span>
            <strong><?= $totalMenu; ?></strong>
        </article>
        <article class="stat-card danger">
            <span>Pesanan Baru</span>
            <strong><?= $pesananBaruDapur; ?></strong>
        </article>
        <article class="stat-card">
            <span>Pendapatan Bulan Ini</span>
            <strong><?= rupiah($pendapatanBulanIni['total'] ?? 0); ?></strong>
        </article>
        <article class="stat-card">
            <span>Pengeluaran Bulan Ini</span>
            <strong><?= rupiah($pengeluaranBulanIni['total'] ?? 0); ?></strong>
        </article>
        <article class="stat-card <?= $untungRugiBulanIni >= 0 ? 'success' : 'danger'; ?>">
            <span>Untung / Rugi Bersih</span>
            <strong><?= $untungRugiBulanIni >= 0 ? '+' : ''; ?><?= rupiah($untungRugiBulanIni); ?></strong>
        </article>
    <?php elseif ($role === 'dapur'): ?>
        <article class="stat-card danger">
            <span>Pesanan Baru</span>
            <strong><?= $pesananBaruDapur; ?></strong>
        </article>
        <article class="stat-card">
            <span>Sedang Diproses</span>
            <strong><?= $pesananDiproses; ?></strong>
        </article>
        <article class="stat-card">
            <span>Stok Keluar</span>
            <strong><?= db_count($conn, 'stok_keluar'); ?></strong>
        </article>
        <article class="stat-card danger">
            <span>Stok Menipis</span>
            <strong><?= $stokMenipis; ?></strong>
        </article>
    <?php else: ?>
        <article class="stat-card">
            <span>Total Bahan Baku</span>
            <strong><?= $totalBahan; ?></strong>
        </article>
        <article class="stat-card">
            <span>Total Menu</span>
            <strong><?= $totalMenu; ?></strong>
        </article>
        <article class="stat-card">
            <span>Total Supplier</span>
            <strong><?= $totalSupplier; ?></strong>
        </article>
        <article class="stat-card danger">
            <span>Stok Menipis</span>
            <strong><?= $stokMenipis; ?></strong>
        </article>
        <article class="stat-card">
            <span>Pendapatan Bulan Ini</span>
            <strong><?= rupiah($pendapatanBulanIni['total'] ?? 0); ?></strong>
        </article>
        <article class="stat-card">
            <span>Pengeluaran Bulan Ini</span>
            <strong><?= rupiah($pengeluaranBulanIni['total'] ?? 0); ?></strong>
        </article>
        <article class="stat-card <?= $untungRugiBulanIni >= 0 ? 'success' : 'danger'; ?>">
            <span>Untung / Rugi Bersih</span>
            <strong><?= $untungRugiBulanIni >= 0 ? '+' : ''; ?><?= rupiah($untungRugiBulanIni); ?></strong>
        </article>
    <?php endif; ?>
</section>

<?php if (role_can_access(['gudang', 'dapur'])): ?>
<section class="panel">
    <div class="panel-header">
        <div>
            <h2>Akses Operasional</h2>
            <p>Pilih area kerja sesuai role: kasir, dapur, atau gudang.</p>
        </div>
    </div>
    <div class="quick-grid">
        <?php if (role_can_access(['kasir'])): ?>
            <a class="quick-card" href="kasir/index.php">
                <strong>Kasir & Pemesanan</strong>
                <span><?= $pesananHariIni; ?> pesanan hari ini dari <?= $totalPesanan; ?> total pesanan.</span>
            </a>
        <?php endif; ?>
        <?php if (role_can_access(['dapur'])): ?>
            <a class="quick-card" href="dapur/index.php">
                <strong>Dapur</strong>
                <span><?= $pesananBaruDapur; ?> pesanan baru menunggu dibuat.</span>
            </a>
            <a class="quick-card" href="produksi/index.php">
                <strong>Produksi Dapur</strong>
                <span>Ubah bahan mentah menjadi stok produk jadi.</span>
            </a>
        <?php endif; ?>
        <?php if (role_can_access(['gudang'])): ?>
            <a class="quick-card" href="masuk/index.php">
                <strong>Gudang</strong>
                <span>Kelola bahan baku, supplier, dan stok masuk.</span>
            </a>
        <?php endif; ?>
    </div>
</section>

<section class="panel">
    <div class="panel-header">
        <div>
            <h2>Stok Terbaru</h2>
            <p>Daftar bahan yang baru ditambahkan atau diperbarui.</p>
        </div>
        <a class="btn btn-primary" href="bahan/form.php">Tambah Bahan</a>
    </div>
    <div class="table-wrap">
        <table class="table-fit">
            <thead>
                <tr>
                    <th>Nama Bahan</th>
                    <th>Stok</th>
                    <th>Harga</th>
                    <th>Dibuat</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($stokTerbaru): ?>
                    <?php foreach ($stokTerbaru as $row): ?>
                        <tr>
                            <td><?= e($row['nama_bahan']); ?></td>
                            <td><?= e((string) $row['stok']); ?> <?= e($row['satuan']); ?></td>
                            <td><?= rupiah($row['harga']); ?></td>
                            <td><?= e(date('d M Y', strtotime($row['created_at']))); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="4" class="empty">Belum ada data bahan baku.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>
<?php endif; ?>
<?php include __DIR__ . '/../includes/footer.php'; ?>
