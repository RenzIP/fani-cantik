<?php
require_once __DIR__ . '/../../config/koneksi.php';
require_once __DIR__ . '/../../includes/functions.php';
require_role(['dapur']);

$pageTitle = 'Produksi Dapur';
$basePath = '../../';

$totalHariIni = db_count($conn, 'produksi', 'tanggal = CURDATE()');
$hasilHariIni = mysqli_fetch_assoc(mysqli_query($conn, 'SELECT COALESCE(SUM(jumlah_hasil), 0) AS total FROM produksi WHERE tanggal = CURDATE()'));
$stokMenipis = db_count($conn, 'bahan_baku', "jenis = 'mentah' AND stok <= stok_minimum");

$sql = "SELECT p.*, produk.nama_bahan AS produk_jadi, produk.satuan,
               GROUP_CONCAT(CONCAT(b.nama_bahan, ' ', pd.jumlah_pakai, ' ', b.satuan) ORDER BY pd.id SEPARATOR ', ') AS bahan_dipakai
        FROM produksi p
        JOIN bahan_baku produk ON produk.id = p.produk_id
        LEFT JOIN produksi_detail pd ON pd.produksi_id = p.id
        LEFT JOIN bahan_baku b ON b.id = pd.bahan_id
        GROUP BY p.id
        ORDER BY p.tanggal DESC, p.id DESC";
$rows = mysqli_fetch_all(mysqli_query($conn, $sql), MYSQLI_ASSOC);

include __DIR__ . '/../../includes/header.php';
?>
<section class="stats-grid">
    <article class="stat-card">
        <span>Batch Produksi Hari Ini</span>
        <strong><?= $totalHariIni; ?></strong>
    </article>
    <article class="stat-card">
        <span>Produk Jadi Hari Ini</span>
        <strong><?= e((string) ($hasilHariIni['total'] ?? 0)); ?></strong>
    </article>
    <article class="stat-card danger">
        <span>Bahan Mentah Menipis</span>
        <strong><?= $stokMenipis; ?></strong>
    </article>
</section>

<section class="panel">
    <div class="panel-header">
        <div>
            <h2>Riwayat Produksi</h2>
            <p>Catat pemakaian bahan mentah dan hasilkan stok produk jadi.</p>
        </div>
        <a class="btn btn-primary" href="form.php">Catat Produksi</a>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Kode Produksi</th>
                    <th>Produk Jadi</th>
                    <th>Hasil</th>
                    <th>Bahan Dipakai</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rows as $row): ?>
                    <tr>
                        <td><?= e(date('d M Y', strtotime($row['tanggal']))); ?></td>
                        <td><?= e($row['kode_produksi']); ?></td>
                        <td><?= e($row['produk_jadi']); ?></td>
                        <td><?= e((string) $row['jumlah_hasil']); ?> <?= e($row['satuan']); ?></td>
                        <td><?= e($row['bahan_dipakai'] ?: '-'); ?></td>
                        <td><?= e($row['keterangan'] ?: '-'); ?></td>
                    </tr>
                <?php endforeach; ?>
                <?php if (!$rows): ?>
                    <tr><td colspan="6" class="empty">Belum ada riwayat produksi.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
