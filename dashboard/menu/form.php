<?php
require_once __DIR__ . '/../../config/koneksi.php';
require_once __DIR__ . '/../../includes/functions.php';
require_role(['admin']);

$pageTitle = 'Form Menu';
$basePath = '../../';
$id = (int) ($_GET['id'] ?? 0);
$data = ['nama_menu' => '', 'harga' => '', 'deskripsi' => '', 'gambar' => ''];

if ($id > 0) {
    $stmt = mysqli_prepare($conn, 'SELECT * FROM menu_nasi_bakar WHERE id = ?');
    mysqli_stmt_bind_param($stmt, 'i', $id);
    $data = fetch_one_stmt($stmt) ?: $data;
}

include __DIR__ . '/../../includes/header.php';
?>
<section class="panel form-panel">
    <div class="panel-header">
        <div>
            <h2><?= $id ? 'Edit Menu' : 'Tambah Menu'; ?></h2>
            <p>Isi detail menu nasi bakar.</p>
        </div>
    </div>
    <form class="data-form" action="simpan.php" method="post" data-validate>
        <input type="hidden" name="id" value="<?= $id; ?>">
        <div class="form-grid">
            <div class="form-group">
                <label for="nama_menu">Nama Menu</label>
                <input id="nama_menu" name="nama_menu" value="<?= e($data['nama_menu']); ?>" required>
                <small class="error-message"></small>
            </div>
            <div class="form-group">
                <label for="harga">Harga</label>
                <input id="harga" type="number" min="0" name="harga" value="<?= e((string) $data['harga']); ?>" required>
                <small class="error-message"></small>
            </div>
            <div class="form-group full">
                <label for="deskripsi">Deskripsi</label>
                <textarea id="deskripsi" name="deskripsi" rows="4" required><?= e($data['deskripsi']); ?></textarea>
                <small class="error-message"></small>
            </div>
            <div class="form-group full">
                <label for="gambar">Nama File Gambar</label>
                <input id="gambar" name="gambar" value="<?= e($data['gambar']); ?>" placeholder="nasi-bakar-ayam.jpg">
            </div>
        </div>
        <div class="form-actions">
            <a class="btn btn-secondary" href="index.php">Batal</a>
            <button class="btn btn-primary" type="submit">Simpan</button>
        </div>
    </form>
</section>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
