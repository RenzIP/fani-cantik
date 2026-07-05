<?php
require_once __DIR__ . '/../../config/koneksi.php';
require_once __DIR__ . '/../../includes/functions.php';
require_role(['dapur']);

$pageTitle = 'Tambah Permintaan Restok';
$basePath = '../../';
$bahan = mysqli_fetch_all(mysqli_query($conn, 'SELECT id, nama_bahan, stok, stok_minimum, satuan FROM bahan_baku ORDER BY nama_bahan ASC'), MYSQLI_ASSOC);

include __DIR__ . '/../../includes/header.php';
?>
<section class="panel form-panel">
    <div class="panel-header">
        <div>
            <h2>Tambah Permintaan Restok</h2>
            <p>Ajukan kebutuhan bahan ke gudang ketika stok mulai menipis.</p>
        </div>
    </div>
    <form class="data-form" action="simpan.php" method="post" data-validate>
        <div class="form-grid">
            <div class="form-group">
                <label for="bahan_id">Bahan</label>
                <select id="bahan_id" name="bahan_id" required>
                    <option value="">Pilih bahan</option>
                    <?php foreach ($bahan as $item): ?>
                        <option value="<?= (int) $item['id']; ?>">
                            <?= e($item['nama_bahan']); ?> - stok <?= e((string) $item['stok']); ?> <?= e($item['satuan']); ?>, min <?= e((string) $item['stok_minimum']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <small class="error-message"></small>
            </div>
            <div class="form-group">
                <label for="jumlah">Jumlah Diminta</label>
                <input id="jumlah" type="number" min="0.01" step="0.01" name="jumlah" required>
                <small class="error-message"></small>
            </div>
            <div class="form-group">
                <label for="tanggal">Tanggal</label>
                <input id="tanggal" type="date" name="tanggal" value="<?= date('Y-m-d'); ?>" required>
                <small class="error-message"></small>
            </div>
            <div class="form-group full">
                <label for="keterangan">Keterangan</label>
                <textarea id="keterangan" name="keterangan" rows="4" required placeholder="Contoh: stok ayam suwir hampir habis untuk produksi sore"></textarea>
                <small class="error-message"></small>
            </div>
        </div>
        <div class="form-actions">
            <a class="btn btn-secondary" href="index.php">Batal</a>
            <button class="btn btn-primary" type="submit">Kirim Permintaan</button>
        </div>
    </form>
</section>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
