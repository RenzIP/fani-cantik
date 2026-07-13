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
    <form class="data-form" action="simpan.php" method="post" enctype="multipart/form-data" data-validate>
        <input type="hidden" name="id" value="<?= $id; ?>">
        <input type="hidden" name="existing_gambar" value="<?= e($data['gambar']); ?>">
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
                <label for="gambar">Gambar Menu</label>
                <?php if (!empty($data['gambar']) && file_exists(__DIR__ . '/../../assets/images/' . $data['gambar'])): ?>
                    <div style="margin-bottom: 12px; display: flex; align-items: center; gap: 12px;">
                        <img src="../../assets/images/<?= e($data['gambar']); ?>" alt="Current Image" style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px; border: 1px solid var(--border);">
                        <div>
                            <p style="font-size: 0.85rem; color: var(--muted); margin: 0;">File saat ini:</p>
                            <strong style="font-size: 0.9rem; color: var(--primary-dark); word-break: break-all;"><?= e($data['gambar']); ?></strong>
                        </div>
                    </div>
                <?php endif; ?>
                <input id="gambar" type="file" name="gambar" accept="image/*">
                <small style="color: var(--muted); font-size: 0.82rem; display: block; margin-top: 4px;">Format: JPG, JPEG, PNG, WEBP. Max: 2MB. Kosongkan jika tidak ingin mengubah gambar.</small>
            </div>
        </div>
        <div class="form-actions">
            <a class="btn btn-secondary" href="index.php">Batal</a>
            <button class="btn btn-primary" type="submit">Simpan</button>
        </div>
    </form>
</section>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
