<?php
require_once __DIR__ . '/../../config/koneksi.php';
require_once __DIR__ . '/../../includes/functions.php';
require_role(['gudang', 'dapur', 'kasir']);

$pageTitle = 'Laporan';
$basePath = '../../';
$role = current_role();

$stokMenipis = mysqli_fetch_all(mysqli_query($conn, 'SELECT nama_bahan, stok, stok_minimum, satuan FROM bahan_baku WHERE stok <= stok_minimum ORDER BY stok ASC'), MYSQLI_ASSOC);
$masuk = mysqli_fetch_all(mysqli_query($conn, 'SELECT sm.tanggal, b.nama_bahan, sm.jumlah, b.satuan FROM stok_masuk sm JOIN bahan_baku b ON b.id = sm.bahan_id ORDER BY sm.tanggal DESC LIMIT 10'), MYSQLI_ASSOC);
$keluar = mysqli_fetch_all(mysqli_query($conn, 'SELECT sk.tanggal, b.nama_bahan, sk.jumlah, b.satuan, sk.keterangan FROM stok_keluar sk JOIN bahan_baku b ON b.id = sk.bahan_id ORDER BY sk.tanggal DESC LIMIT 10'), MYSQLI_ASSOC);
$transaksi = mysqli_fetch_all(mysqli_query($conn, "SELECT kode_pesanan, nama_pelanggan, total, metode_bayar, status, created_at FROM pesanan ORDER BY created_at DESC LIMIT 15"), MYSQLI_ASSOC);
$pendapatan = mysqli_fetch_assoc(mysqli_query($conn, 'SELECT COALESCE(SUM(total), 0) AS total FROM pesanan'));

include __DIR__ . '/../../includes/header.php';
?>
<?php if ($role === 'kasir'): ?>
<section class="stats-grid">
    <article class="stat-card">
        <span>Total Transaksi</span>
        <strong><?= db_count($conn, 'pesanan'); ?></strong>
    </article>
    <article class="stat-card">
        <span>Total Pendapatan</span>
        <strong><?= rupiah($pendapatan['total'] ?? 0); ?></strong>
    </article>
    <article class="stat-card">
        <span>Transaksi Selesai</span>
        <strong><?= db_count($conn, 'pesanan', "status = 'selesai'"); ?></strong>
    </article>
    <article class="stat-card danger">
        <span>Transaksi Batal</span>
        <strong><?= db_count($conn, 'pesanan', "status = 'batal'"); ?></strong>
    </article>
</section>

<section class="panel">
    <div class="panel-header">
        <div>
            <h2>Laporan Transaksi</h2>
            <p>Ringkasan transaksi kasir dan status pesanan dapur.</p>
        </div>
    </div>
    <div class="table-wrap">
        <table>
            <thead><tr><th>Tanggal</th><th>Kode</th><th>Pelanggan</th><th>Bayar</th><th>Total</th><th>Status</th></tr></thead>
            <tbody>
                <?php foreach ($transaksi as $row): ?>
                    <tr>
                        <td><?= e(date('d M Y H:i', strtotime($row['created_at']))); ?></td>
                        <td><?= e($row['kode_pesanan']); ?></td>
                        <td><?= e($row['nama_pelanggan']); ?></td>
                        <td><?= e(strtoupper($row['metode_bayar'])); ?></td>
                        <td><?= rupiah($row['total']); ?></td>
                        <td><span class="badge <?= $row['status'] === 'batal' ? 'danger' : ''; ?>"><?= e(status_pesanan_label($row['status'])); ?></span></td>
                    </tr>
                <?php endforeach; ?>
                <?php if (!$transaksi): ?><tr><td colspan="6" class="empty">Belum ada transaksi.</td></tr><?php endif; ?>
            </tbody>
        </table>
    </div>
</section>
<?php else: ?>
<section class="stats-grid">
    <article class="stat-card">
        <span>Total Stok Masuk</span>
        <strong><?= db_count($conn, 'stok_masuk'); ?></strong>
    </article>
    <article class="stat-card">
        <span>Total Stok Keluar</span>
        <strong><?= db_count($conn, 'stok_keluar'); ?></strong>
    </article>
    <article class="stat-card danger">
        <span>Bahan Menipis</span>
        <strong><?= count($stokMenipis); ?></strong>
    </article>
    <article class="stat-card">
        <span>Nilai Stok</span>
        <?php
        $nilai = mysqli_fetch_assoc(mysqli_query($conn, 'SELECT SUM(stok * harga) AS total FROM bahan_baku'));
        ?>
        <strong><?= rupiah($nilai['total'] ?? 0); ?></strong>
    </article>
</section>

<section class="panel">
    <div class="panel-header"><h2>Bahan Menipis</h2></div>
    <div class="table-wrap">
        <table>
            <thead><tr><th>Bahan</th><th>Stok</th></tr></thead>
            <tbody>
                <?php foreach ($stokMenipis as $row): ?>
                    <tr><td><?= e($row['nama_bahan']); ?></td><td><?= e((string) $row['stok']); ?> <?= e($row['satuan']); ?> (min <?= e((string) $row['stok_minimum']); ?>)</td></tr>
                <?php endforeach; ?>
                <?php if (!$stokMenipis): ?><tr><td colspan="2" class="empty">Tidak ada bahan menipis.</td></tr><?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<section class="split-panels">
    <div class="panel">
        <div class="panel-header"><h2>Stok Masuk Terbaru</h2></div>
        <div class="table-wrap">
            <table>
                <thead><tr><th>Tanggal</th><th>Bahan</th><th>Jumlah</th></tr></thead>
                <tbody>
                    <?php foreach ($masuk as $row): ?>
                        <tr><td><?= e(date('d M Y', strtotime($row['tanggal']))); ?></td><td><?= e($row['nama_bahan']); ?></td><td><?= e((string) $row['jumlah']); ?> <?= e($row['satuan']); ?></td></tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="panel">
        <div class="panel-header"><h2>Stok Keluar Terbaru</h2></div>
        <div class="table-wrap">
            <table>
                <thead><tr><th>Tanggal</th><th>Bahan</th><th>Jumlah</th></tr></thead>
                <tbody>
                    <?php foreach ($keluar as $row): ?>
                        <tr><td><?= e(date('d M Y', strtotime($row['tanggal']))); ?></td><td><?= e($row['nama_bahan']); ?></td><td><?= e((string) $row['jumlah']); ?> <?= e($row['satuan']); ?></td></tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>
<?php endif; ?>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
