<?php
require_once __DIR__ . '/../../config/koneksi.php';
require_once __DIR__ . '/../../includes/functions.php';
require_role(['kasir']);

$pageTitle = 'Edit Pesanan';
$basePath = '../../';
$id = (int) ($_GET['id'] ?? 0);

if ($id <= 0) {
    set_flash('danger', 'Pesanan tidak valid.');
    redirect('index.php');
}

$stmt = mysqli_prepare($conn, 'SELECT * FROM pesanan WHERE id = ?');
mysqli_stmt_bind_param($stmt, 'i', $id);
$pesanan = fetch_one_stmt($stmt);

if (!$pesanan) {
    set_flash('danger', 'Pesanan tidak ditemukan.');
    redirect('index.php');
}

$menus = mysqli_fetch_all(mysqli_query($conn, 'SELECT id, nama_menu, harga FROM menu_nasi_bakar ORDER BY nama_menu ASC'), MYSQLI_ASSOC);
$detailRows = mysqli_fetch_all(mysqli_query($conn, 'SELECT menu_id, qty FROM pesanan_detail WHERE pesanan_id = ' . $id), MYSQLI_ASSOC);
$qtyMap = [];

foreach ($detailRows as $detail) {
    $qtyMap[(int) $detail['menu_id']] = (int) $detail['qty'];
}

include __DIR__ . '/../../includes/header.php';
?>
<section class="panel form-panel">
    <div class="panel-header">
        <div>
            <h2>Edit Pesanan <?= e($pesanan['kode_pesanan']); ?></h2>
            <p>Ubah data pelanggan, item pesanan, dan status pembayaran.</p>
        </div>
        <a class="btn btn-secondary" href="index.php">Kembali</a>
    </div>
    <form class="data-form" action="simpan.php" method="post" data-validate>
        <input type="hidden" name="id" value="<?= $id; ?>">
        <div class="form-grid">
            <div class="form-group">
                <label for="nama_pelanggan">Nama Pelanggan</label>
                <input id="nama_pelanggan" name="nama_pelanggan" value="<?= e($pesanan['nama_pelanggan']); ?>" required>
                <small class="error-message"></small>
            </div>
            <div class="form-group">
                <label for="metode_bayar">Metode Bayar</label>
                <select id="metode_bayar" name="metode_bayar" required>
                    <?php foreach (['tunai' => 'Tunai', 'qris' => 'QRIS', 'transfer' => 'Transfer'] as $value => $label): ?>
                        <option value="<?= e($value); ?>" <?= $pesanan['metode_bayar'] === $value ? 'selected' : ''; ?>><?= e($label); ?></option>
                    <?php endforeach; ?>
                </select>
                <small class="error-message"></small>
            </div>
            <div class="form-group">
                <label for="status">Status</label>
                <select id="status" name="status" required>
                    <?php foreach (['menunggu' => 'Menunggu', 'diproses' => 'Diproses', 'selesai' => 'Selesai', 'batal' => 'Batal'] as $value => $label): ?>
                        <option value="<?= e($value); ?>" <?= ($pesanan['status'] === $value || ($value === 'menunggu' && $pesanan['status'] === 'baru')) ? 'selected' : ''; ?>><?= e($label); ?></option>
                    <?php endforeach; ?>
                </select>
                <small class="error-message"></small>
            </div>
            <div class="form-group">
                <label for="status_pembayaran">Status Pembayaran</label>
                <select id="status_pembayaran" name="status_pembayaran" required>
                    <option value="belum_bayar" <?= $pesanan['status_pembayaran'] === 'belum_bayar' ? 'selected' : ''; ?>>Belum Bayar</option>
                    <option value="lunas" <?= $pesanan['status_pembayaran'] === 'lunas' ? 'selected' : ''; ?>>Lunas</option>
                </select>
                <small class="error-message"></small>
            </div>
        </div>

        <div class="menu-order-list">
            <?php foreach ($menus as $menu): ?>
                <label class="menu-order-row">
                    <span>
                        <strong><?= e($menu['nama_menu']); ?></strong>
                        <small><?= rupiah($menu['harga']); ?></small>
                    </span>
                    <input type="number" name="qty[<?= (int) $menu['id']; ?>]" min="0" value="<?= $qtyMap[(int) $menu['id']] ?? 0; ?>" aria-label="Jumlah <?= e($menu['nama_menu']); ?>">
                </label>
            <?php endforeach; ?>
        </div>

        <div class="form-group full">
            <label for="catatan">Catatan</label>
            <textarea id="catatan" name="catatan" rows="3"><?= e($pesanan['catatan'] ?? ''); ?></textarea>
        </div>
        <div class="form-actions">
            <a class="btn btn-secondary" href="index.php">Batal</a>
            <button class="btn btn-primary" type="submit">Update Pesanan</button>
        </div>
    </form>
</section>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
