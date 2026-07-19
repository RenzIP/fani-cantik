<?php
require_once __DIR__ . '/../../config/koneksi.php';
require_once __DIR__ . '/../../includes/functions.php';
require_role(['dapur']);

$pageTitle = 'Catat Produksi';
$basePath = '../../';
$produkJadi = mysqli_fetch_all(mysqli_query($conn, "SELECT id, nama_bahan, stok, satuan FROM bahan_baku WHERE jenis = 'jadi' ORDER BY nama_bahan ASC"), MYSQLI_ASSOC);
$bahanMentah = mysqli_fetch_all(mysqli_query($conn, "SELECT id, nama_bahan, stok, stok_minimum, satuan FROM bahan_baku WHERE jenis = 'mentah' ORDER BY nama_bahan ASC"), MYSQLI_ASSOC);

include __DIR__ . '/../../includes/header.php';
?>
<section class="panel form-panel">
    <div class="panel-header">
        <div>
            <h2>Catat Produksi Dapur</h2>
            <p>Bahan mentah akan berkurang dan produk jadi akan bertambah setelah produksi disimpan.</p>
        </div>
        <a class="btn btn-secondary" href="index.php">Kembali</a>
    </div>
    <?php if (!$produkJadi): ?>
        <div class="alert alert-warning">Belum ada produk jadi. Gudang perlu menambahkan data bahan dengan jenis Produk Jadi terlebih dahulu.</div>
    <?php endif; ?>
    <form class="data-form" action="simpan.php" method="post" data-validate>
        <div class="form-grid">
            <div class="form-group">
                <label for="produk_id">Produk Jadi</label>
                <select id="produk_id" name="produk_id" required <?= !$produkJadi ? 'disabled' : ''; ?>>
                    <option value="">Pilih produk jadi</option>
                    <?php foreach ($produkJadi as $produk): ?>
                        <option value="<?= (int) $produk['id']; ?>"><?= e($produk['nama_bahan']); ?> (stok: <?= e((string) $produk['stok']); ?> <?= e($produk['satuan']); ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="jumlah_hasil">Jumlah Hasil</label>
                <input id="jumlah_hasil" type="number" name="jumlah_hasil" min="0.01" step="0.01" required <?= !$produkJadi ? 'disabled' : ''; ?>>
            </div>
            <div class="form-group">
                <label for="tanggal">Tanggal Produksi</label>
                <input id="tanggal" type="date" name="tanggal" value="<?= date('Y-m-d'); ?>" required <?= !$produkJadi ? 'disabled' : ''; ?>>
            </div>
            <div class="form-group full">
                <label for="keterangan">Keterangan</label>
                <textarea id="keterangan" name="keterangan" rows="3" placeholder="Contoh: Produksi pagi untuk stok penjualan hari ini" <?= !$produkJadi ? 'disabled' : ''; ?>></textarea>
            </div>
        </div>

        <div class="panel-header" style="margin-top: 20px;">
            <div>
                <h2>Bahan Mentah yang Dipakai</h2>
                <p>Isi jumlah hanya pada bahan yang digunakan untuk batch produksi ini.</p>
            </div>
        </div>
        <div class="menu-order-list">
            <?php foreach ($bahanMentah as $bahan): ?>
                <label class="menu-order-row">
                    <span>
                        <strong><?= e($bahan['nama_bahan']); ?></strong>
                        <small>Stok: <?= e((string) $bahan['stok']); ?> <?= e($bahan['satuan']); ?><?= (float) $bahan['stok'] <= (float) $bahan['stok_minimum'] ? ' - menipis' : ''; ?></small>
                    </span>
                    <input type="number" name="bahan_qty[<?= (int) $bahan['id']; ?>]" min="0" step="0.01" value="0" aria-label="Jumlah <?= e($bahan['nama_bahan']); ?>" <?= !$produkJadi ? 'disabled' : ''; ?>>
                </label>
            <?php endforeach; ?>
            <?php if (!$bahanMentah): ?>
                <p class="empty">Belum ada bahan mentah untuk digunakan dalam produksi.</p>
            <?php endif; ?>
        </div>
        <div class="form-actions">
            <a class="btn btn-secondary" href="index.php">Batal</a>
            <button class="btn btn-primary" type="submit" <?= (!$produkJadi || !$bahanMentah) ? 'disabled' : ''; ?>>Simpan Produksi</button>
        </div>
    </form>
</section>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
