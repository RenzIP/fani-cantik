<?php
require_once __DIR__ . '/../../config/koneksi.php';
require_once __DIR__ . '/../../includes/functions.php';
require_role(['gudang']);

$pageTitle = 'Form Supplier';
$basePath = '../../';
$id = (int) ($_GET['id'] ?? 0);
$data = ['nama_supplier' => '', 'no_telp' => '', 'alamat' => ''];

if ($id > 0) {
    $stmt = mysqli_prepare($conn, 'SELECT * FROM supplier WHERE id = ?');
    mysqli_stmt_bind_param($stmt, 'i', $id);
    $data = fetch_one_stmt($stmt) ?: $data;
}

include __DIR__ . '/../../includes/header.php';
?>
<section class="panel form-panel">
    <div class="panel-header">
        <div>
            <h2><?= $id ? 'Edit Supplier' : 'Tambah Supplier'; ?></h2>
            <p>Isi identitas supplier bahan baku.</p>
        </div>
    </div>
    <form class="data-form" action="simpan.php" method="post" data-validate>
        <input type="hidden" name="id" value="<?= $id; ?>">
        <div class="form-grid">
            <div class="form-group">
                <label for="nama_supplier">Nama Supplier</label>
                <input id="nama_supplier" name="nama_supplier" value="<?= e($data['nama_supplier']); ?>" required>
                <small class="error-message"></small>
            </div>
            <div class="form-group">
                <label for="no_telp">No. Telp</label>
                <input id="no_telp" name="no_telp" value="<?= e($data['no_telp']); ?>" required>
                <small class="error-message"></small>
            </div>
            <div class="form-group full">
                <label for="alamat">Alamat</label>
                <textarea id="alamat" name="alamat" rows="4" required><?= e($data['alamat']); ?></textarea>
                <small class="error-message"></small>
            </div>
        </div>
        <div class="form-actions">
            <a class="btn btn-secondary" href="index.php">Batal</a>
            <button class="btn btn-primary" type="submit">Simpan</button>
        </div>
    </form>
</section>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
