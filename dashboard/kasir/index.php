<?php
require_once __DIR__ . '/../../config/koneksi.php';
require_once __DIR__ . '/../../includes/functions.php';
require_role(['kasir']);

$pageTitle = 'Kasir & Pemesanan';
$basePath = '../../';

$menus = mysqli_fetch_all(mysqli_query($conn, "SELECT m.id, m.nama_menu, m.harga, m.produk_jadi_id, b.stok AS stok_produk_jadi
    FROM menu_nasi_bakar m
    LEFT JOIN bahan_baku b ON b.id = m.produk_jadi_id
    ORDER BY m.nama_menu ASC"), MYSQLI_ASSOC);
$pesanan = mysqli_fetch_all(mysqli_query($conn, "SELECT p.*, COUNT(pd.id) AS total_item FROM pesanan p LEFT JOIN pesanan_detail pd ON pd.pesanan_id = p.id GROUP BY p.id ORDER BY p.created_at DESC LIMIT 10"), MYSQLI_ASSOC);
$totalHariIni = db_count($conn, 'pesanan', "DATE(created_at) = CURDATE()");
$pendapatanHariIni = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COALESCE(SUM(total), 0) AS total FROM pesanan WHERE DATE(created_at) = CURDATE()"));
$menunggu = db_count($conn, 'pesanan', "status IN ('baru', 'menunggu')");

include __DIR__ . '/../../includes/header.php';
?>
<section class="stats-grid">
    <article class="stat-card">
        <span>Pesanan Hari Ini</span>
        <strong><?= $totalHariIni; ?></strong>
    </article>
    <article class="stat-card">
        <span>Pendapatan Hari Ini</span>
        <strong><?= rupiah($pendapatanHariIni['total'] ?? 0); ?></strong>
    </article>
    <article class="stat-card danger">
        <span>Pesanan Baru</span>
        <strong><?= $menunggu; ?></strong>
    </article>
    <article class="stat-card">
        <span>Total Menu Aktif</span>
        <strong><?= count($menus); ?></strong>
    </article>
</section>

<section class="split-panels cashier-layout">
    <div class="panel">
        <div class="panel-header">
            <div>
                <h2>Input Pesanan</h2>
                <p>Pilih menu dan jumlah pesanan pelanggan.</p>
            </div>
        </div>
        <form class="data-form" action="simpan.php" method="post" data-validate>
            <div class="form-grid">
                <div class="form-group">
                    <label for="nama_pelanggan">Nama Pelanggan</label>
                    <input id="nama_pelanggan" name="nama_pelanggan" placeholder="Contoh: Andi" required>
                    <small class="error-message"></small>
                </div>
                <div class="form-group">
                    <label for="metode_bayar">Metode Bayar</label>
                    <select id="metode_bayar" name="metode_bayar" required>
                        <option value="tunai">Tunai</option>
                        <option value="qris">QRIS</option>
                        <option value="transfer">Transfer</option>
                    </select>
                    <small class="error-message"></small>
                </div>
                <div class="form-group">
                    <label for="status_pembayaran">Status Pembayaran</label>
                    <select id="status_pembayaran" name="status_pembayaran" required>
                        <option value="belum_bayar">Belum Bayar</option>
                        <option value="lunas">Lunas</option>
                    </select>
                    <small class="error-message"></small>
                </div>
            </div>

            <div class="menu-order-list">
                <?php foreach ($menus as $menu): ?>
                    <?php
                    $usesFinishedStock = !empty($menu['produk_jadi_id']);
                    $availableStock = max(0, (int) ($menu['stok_produk_jadi'] ?? 0));
                    $stockLabel = $usesFinishedStock ? 'Stok siap jual: ' . $availableStock : 'Stok belum dipetakan';
                    ?>
                    <label class="menu-order-row">
                        <span>
                            <strong><?= e($menu['nama_menu']); ?></strong>
                            <small><?= rupiah($menu['harga']); ?> | <?= e($stockLabel); ?></small>
                        </span>
                        <input type="number" name="qty[<?= (int) $menu['id']; ?>]" min="0" value="0" <?= $usesFinishedStock ? 'max="' . $availableStock . '"' : ''; ?> <?= $usesFinishedStock && $availableStock === 0 ? 'disabled' : ''; ?> aria-label="Jumlah <?= e($menu['nama_menu']); ?>">
                    </label>
                <?php endforeach; ?>
                <?php if (!$menus): ?>
                    <p class="empty">Belum ada menu. Tambahkan menu terlebih dahulu.</p>
                <?php endif; ?>
            </div>

            <div class="form-group full">
                <label for="catatan">Catatan</label>
                <textarea id="catatan" name="catatan" rows="3" placeholder="Contoh: level pedas, dibungkus, tanpa kemangi"></textarea>
            </div>
            <div class="form-actions">
                <button class="btn btn-primary" type="submit" <?= !$menus ? 'disabled' : ''; ?>>Simpan Pesanan</button>
            </div>
        </form>
    </div>

    <div class="panel">
        <div class="panel-header">
            <div>
                <h2>Pesanan Terbaru</h2>
                <p>Riwayat pesanan yang masuk dari kasir.</p>
            </div>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Pelanggan</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pesanan as $row): ?>
                        <tr>
                            <td><?= e($row['kode_pesanan']); ?><br><small><?= e(date('d M H:i', strtotime($row['created_at']))); ?></small></td>
                            <td><?= e($row['nama_pelanggan']); ?><br><small><?= (int) $row['total_item']; ?> item</small></td>
                            <td><?= rupiah($row['total']); ?></td>
                            <td><span class="badge"><?= e(status_pesanan_label($row['status'])); ?></span></td>
                            <td class="actions">
                                <a class="btn-small" href="form.php?id=<?= (int) $row['id']; ?>">Edit</a>
                                <a class="btn-small danger" href="hapus.php?id=<?= (int) $row['id']; ?>" data-confirm="Hapus pesanan ini?">Hapus</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (!$pesanan): ?>
                        <tr><td colspan="5" class="empty">Belum ada pesanan.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
