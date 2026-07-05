<?php
require_once __DIR__ . '/../../config/koneksi.php';
require_once __DIR__ . '/../../includes/functions.php';
require_role(['gudang']);

$pageTitle = 'Stok Masuk';
$basePath = '../../';
$sql = 'SELECT sm.*, b.nama_bahan, b.satuan, s.nama_supplier
        FROM stok_masuk sm
        JOIN bahan_baku b ON b.id = sm.bahan_id
        JOIN supplier s ON s.id = sm.supplier_id
        ORDER BY sm.tanggal DESC, sm.id DESC';
$rows = mysqli_fetch_all(mysqli_query($conn, $sql), MYSQLI_ASSOC);

include __DIR__ . '/../../includes/header.php';
?>
<section class="panel">
    <div class="panel-header">
        <div>
            <h2>Riwayat Stok Masuk</h2>
            <p>Setiap penambahan otomatis menaikkan stok bahan.</p>
        </div>
        <a class="btn btn-primary" href="form.php">Tambah Stok Masuk</a>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Bahan</th>
                    <th>Supplier</th>
                    <th>Jumlah</th>
                    <th>Keterangan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rows as $row): ?>
                    <tr>
                        <td><?= e(date('d M Y', strtotime($row['tanggal']))); ?></td>
                        <td><?= e($row['nama_bahan']); ?></td>
                        <td><?= e($row['nama_supplier']); ?></td>
                        <td><?= e((string) $row['jumlah']); ?> <?= e($row['satuan']); ?></td>
                        <td><?= e($row['keterangan'] ?? '-'); ?></td>
                        <td><a class="btn-small danger" href="hapus.php?id=<?= (int) $row['id']; ?>" data-confirm="Hapus transaksi masuk ini? Stok akan dikurangi kembali.">Hapus</a></td>
                    </tr>
                <?php endforeach; ?>
                <?php if (!$rows): ?>
                    <tr><td colspan="6" class="empty">Belum ada transaksi stok masuk.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
