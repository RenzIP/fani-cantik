<?php
require_once __DIR__ . '/../../config/koneksi.php';
require_once __DIR__ . '/../../includes/functions.php';
require_role(['gudang']);

$pageTitle = 'Supplier';
$basePath = '../../';
$rows = mysqli_fetch_all(mysqli_query($conn, 'SELECT * FROM supplier ORDER BY id DESC'), MYSQLI_ASSOC);

include __DIR__ . '/../../includes/header.php';
?>
<section class="panel">
    <div class="panel-header">
        <div>
            <h2>Data Supplier</h2>
            <p>Kelola kontak pemasok bahan baku.</p>
        </div>
        <a class="btn btn-primary" href="form.php">Tambah Supplier</a>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Nama Supplier</th>
                    <th>No. Telp</th>
                    <th>Alamat</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rows as $row): ?>
                    <tr>
                        <td><?= e($row['nama_supplier']); ?></td>
                        <td><?= e($row['no_telp']); ?></td>
                        <td><?= e($row['alamat']); ?></td>
                        <td class="actions">
                            <a class="btn-small" href="form.php?id=<?= (int) $row['id']; ?>">Edit</a>
                            <a class="btn-small danger" href="hapus.php?id=<?= (int) $row['id']; ?>" data-confirm="Hapus supplier ini?">Hapus</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (!$rows): ?>
                    <tr><td colspan="4" class="empty">Belum ada supplier.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
