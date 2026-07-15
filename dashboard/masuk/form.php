<?php
require_once __DIR__ . '/../../config/koneksi.php';
require_once __DIR__ . '/../../includes/functions.php';
require_role(['gudang']);

$pageTitle = 'Tambah Stok Masuk';
$basePath = '../../';
$bahan = mysqli_fetch_all(mysqli_query($conn, 'SELECT id, nama_bahan, satuan FROM bahan_baku ORDER BY nama_bahan'), MYSQLI_ASSOC);
$suppliers = mysqli_fetch_all(mysqli_query($conn, 'SELECT id, nama_supplier FROM supplier ORDER BY nama_supplier'), MYSQLI_ASSOC);

include __DIR__ . '/../../includes/header.php';
?>
<section class="panel form-panel">
    <div class="panel-header">
        <div>
            <h2>Tambah Stok Masuk</h2>
            <p>Pilih bahan dan supplier lalu isi jumlah masuk.</p>
        </div>
    </div>
    <form class="data-form" action="simpan.php" method="post" data-validate>
        <div class="form-grid">
            <div class="form-group">
                <label for="bahan_id">Bahan</label>
                <select id="bahan_id" name="bahan_id" required>
                    <option value="">Pilih bahan</option>
                    <?php foreach ($bahan as $item): ?>
                        <option value="<?= (int) $item['id']; ?>"><?= e($item['nama_bahan']); ?> (<?= e($item['satuan']); ?>)</option>
                    <?php endforeach; ?>
                </select>
                <small class="error-message"></small>
            </div>
            <div class="form-group">
                <label for="supplier_id">Supplier</label>
                <select id="supplier_id" name="supplier_id" required>
                    <option value="">Pilih supplier</option>
                    <?php foreach ($suppliers as $supplier): ?>
                        <option value="<?= (int) $supplier['id']; ?>"><?= e($supplier['nama_supplier']); ?></option>
                    <?php endforeach; ?>
                </select>
                <small class="error-message"></small>
            </div>
            <div class="form-group">
                <label for="jumlah">Jumlah</label>
                <input id="jumlah" type="number" min="0.01" step="0.01" name="jumlah" required>
                <small class="error-message"></small>
            </div>
            <div class="form-group">
                <label for="tanggal">Tanggal</label>
                <input id="tanggal" type="date" name="tanggal" value="<?= date('Y-m-d'); ?>" required>
                <small class="error-message"></small>
            </div>
            <div class="form-group">
                <label for="harga_beli">Harga Beli per Unit (Rp)</label>
                <input id="harga_beli" type="number" min="0" name="harga_beli" placeholder="Contoh: 15000" required>
                <small class="error-message"></small>
            </div>
            <div class="form-group full">
                <label for="keterangan">Keterangan</label>
                <textarea id="keterangan" name="keterangan" rows="4" required placeholder="Contoh: restok dari supplier mingguan"></textarea>
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
