<?php
require_once __DIR__ . '/../../config/koneksi.php';
require_once __DIR__ . '/../../includes/functions.php';
require_role(['dapur']);

$pageTitle = 'Stok Keluar';
$basePath = '../../';

// Automatically delete history older than 1 week
mysqli_query($conn, "DELETE FROM stok_keluar WHERE tanggal < DATE_SUB(CURDATE(), INTERVAL 7 DAY)");

$sql = 'SELECT sk.*, b.nama_bahan, b.satuan
        FROM stok_keluar sk
        JOIN bahan_baku b ON b.id = sk.bahan_id
        ORDER BY sk.tanggal DESC, sk.id DESC';
$rows = mysqli_fetch_all(mysqli_query($conn, $sql), MYSQLI_ASSOC);

include __DIR__ . '/../../includes/header.php';
?>
<section class="panel">
    <div class="panel-header">
        <div>
            <h2>Riwayat Stok Keluar</h2>
            <p>Pengurangan stok divalidasi agar tidak melebihi stok tersedia.</p>
        </div>
        <a class="btn btn-primary" href="form.php">Tambah Stok Keluar</a>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Bahan</th>
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
                        <td><?= e((string) $row['jumlah']); ?> <?= e($row['satuan']); ?></td>
                        <td><?= e($row['keterangan']); ?></td>
                        <td><a class="btn-small danger" href="hapus.php?id=<?= (int) $row['id']; ?>" data-confirm="Hapus transaksi keluar ini? Stok akan dikembalikan.">Hapus</a></td>
                    </tr>
                <?php endforeach; ?>
                <?php if (!$rows): ?>
                    <tr><td colspan="5" class="empty">Belum ada transaksi stok keluar.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
