<?php
require_once __DIR__ . '/../../config/koneksi.php';
require_once __DIR__ . '/../../includes/functions.php';
require_role(['gudang']);

$pageTitle = 'Form Bahan Baku';
$basePath = '../../';
$id = (int) ($_GET['id'] ?? 0);
$data = ['nama_bahan' => '', 'stok' => '', 'stok_minimum' => '10', 'satuan' => '', 'harga' => ''];

if ($id > 0) {
    $stmt = mysqli_prepare($conn, 'SELECT * FROM bahan_baku WHERE id = ?');
    mysqli_stmt_bind_param($stmt, 'i', $id);
    $row = fetch_one_stmt($stmt);
    if (!$row) {
        set_flash('danger', 'Data bahan tidak ditemukan.');
        redirect('index.php');
    }
    $data = $row;
}

include __DIR__ . '/../../includes/header.php';
?>
<section class="panel form-panel">
    <div class="panel-header">
        <div>
            <h2><?= $id ? 'Edit Bahan' : 'Tambah Bahan'; ?></h2>
            <p>Lengkapi data bahan baku dengan benar.</p>
        </div>
    </div>
    <form class="data-form" action="simpan.php" method="post" data-validate>
        <input type="hidden" name="id" value="<?= $id; ?>">
        <div class="form-grid">
            <div class="form-group">
                <label for="nama_bahan">Nama Bahan</label>
                <input id="nama_bahan" name="nama_bahan" value="<?= e($data['nama_bahan']); ?>" required>
                <small class="error-message"></small>
            </div>
            <div class="form-group">
                <label for="stok">Stok</label>
                <input id="stok" type="number" min="0" step="0.01" name="stok" value="<?= e((string) $data['stok']); ?>" required>
                <small class="error-message"></small>
            </div>
            <div class="form-group">
                <label for="stok_minimum">Stok Minimum</label>
                <input id="stok_minimum" type="number" min="0" step="0.01" name="stok_minimum" value="<?= e((string) ($data['stok_minimum'] ?? 10)); ?>" required>
                <small class="error-message"></small>
            </div>
            <div class="form-group">
                <label for="satuan">Satuan</label>
                <input id="satuan" name="satuan" value="<?= e($data['satuan']); ?>" placeholder="kg, pcs, ikat" required>
                <small class="error-message"></small>
            </div>
            <div class="form-group">
                <label for="harga">Harga</label>
                <input id="harga" type="number" min="0" name="harga" value="<?= e((string) $data['harga']); ?>" required>
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
