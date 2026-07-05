<?php
require_once __DIR__ . '/../../config/koneksi.php';
require_once __DIR__ . '/../../includes/functions.php';
require_role(['kasir']);

$pageTitle = 'Menu Nasi Bakar';
$basePath = '../../';
$rows = mysqli_fetch_all(mysqli_query($conn, 'SELECT * FROM menu_nasi_bakar ORDER BY id DESC'), MYSQLI_ASSOC);

include __DIR__ . '/../../includes/header.php';
?>
<section class="panel">
    <div class="panel-header">
        <div>
            <h2>Data Menu Nasi Bakar</h2>
            <p>Kelola menu yang tampil pada halaman produk.</p>
        </div>
        <?php if (role_can_access(['dapur'])): ?>
            <a class="btn btn-primary" href="form.php">Tambah Menu</a>
        <?php endif; ?>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Nama Menu</th>
                    <th>Harga</th>
                    <th>Deskripsi</th>
                    <th>Gambar</th>
                    <?php if (role_can_access(['dapur'])): ?>
                        <th>Aksi</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rows as $row): ?>
                    <tr>
                        <td><?= e($row['nama_menu']); ?></td>
                        <td><?= rupiah($row['harga']); ?></td>
                        <td><?= e($row['deskripsi']); ?></td>
                        <td><?= e($row['gambar'] ?: '-'); ?></td>
                        <?php if (role_can_access(['dapur'])): ?>
                            <td class="actions">
                                <a class="btn-small" href="form.php?id=<?= (int) $row['id']; ?>">Edit</a>
                                <a class="btn-small danger" href="hapus.php?id=<?= (int) $row['id']; ?>" data-confirm="Hapus menu ini?">Hapus</a>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
                <?php if (!$rows): ?>
                    <tr><td colspan="<?= role_can_access(['dapur']) ? 5 : 4; ?>" class="empty">Belum ada menu.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
