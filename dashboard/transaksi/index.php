<?php
require_once __DIR__ . '/../../config/koneksi.php';
require_once __DIR__ . '/../../includes/functions.php';
require_role(['kasir']);

$pageTitle = 'Riwayat Transaksi';
$basePath = '../../';

$tanggal = $_GET['tanggal'] ?? '';
$where = '';
$params = [];
$types = '';

if ($tanggal !== '') {
    $where = 'WHERE DATE(p.created_at) = ?';
    $params[] = $tanggal;
    $types .= 's';
}

$sql = "SELECT p.*,
               GROUP_CONCAT(CONCAT(m.nama_menu, ' x', pd.qty) ORDER BY pd.id SEPARATOR ', ') AS item_pesanan
        FROM pesanan p
        LEFT JOIN pesanan_detail pd ON pd.pesanan_id = p.id
        LEFT JOIN menu_nasi_bakar m ON m.id = pd.menu_id
        $where
        GROUP BY p.id
        ORDER BY p.created_at DESC";

$stmt = mysqli_prepare($conn, $sql);
bind_params($stmt, $types, $params);
$rows = fetch_all_stmt($stmt);

$totalTransaksi = count($rows);
$totalPendapatan = 0;
$transaksiSelesai = 0;
$transaksiBatal = 0;

foreach ($rows as $row) {
    $totalPendapatan += (float) $row['total'];
    if ($row['status'] === 'selesai') {
        $transaksiSelesai++;
    }
    if ($row['status'] === 'batal') {
        $transaksiBatal++;
    }
}

include __DIR__ . '/../../includes/header.php';
?>
<section class="stats-grid">
    <article class="stat-card">
        <span>Total Transaksi</span>
        <strong><?= $totalTransaksi; ?></strong>
    </article>
    <article class="stat-card">
        <span>Total Pendapatan</span>
        <strong><?= rupiah($totalPendapatan); ?></strong>
    </article>
    <article class="stat-card">
        <span>Transaksi Selesai</span>
        <strong><?= $transaksiSelesai; ?></strong>
    </article>
    <article class="stat-card danger">
        <span>Transaksi Batal</span>
        <strong><?= $transaksiBatal; ?></strong>
    </article>
</section>

<section class="panel">
    <div class="panel-header">
        <div>
            <h2>Riwayat Transaksi</h2>
            <p>Daftar transaksi kasir dan status pesanan dapur.</p>
        </div>
        <a class="btn btn-primary" href="../kasir/index.php">Tambah Transaksi</a>
    </div>
    <form class="toolbar" method="get">
        <input type="date" name="tanggal" value="<?= e($tanggal); ?>">
        <button class="btn btn-secondary" type="submit">Filter</button>
        <a class="btn btn-secondary" href="index.php">Reset</a>
    </form>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Kode</th>
                    <th>Pelanggan</th>
                    <th>Item</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rows as $row): ?>
                    <tr>
                        <td><?= e(date('d M Y H:i', strtotime($row['created_at']))); ?></td>
                        <td><?= e($row['kode_pesanan']); ?></td>
                        <td><?= e($row['nama_pelanggan']); ?></td>
                        <td><?= e($row['item_pesanan'] ?: '-'); ?></td>
                        <td><?= rupiah($row['total']); ?></td>
                        <td><span class="badge <?= $row['status'] === 'batal' ? 'danger' : ''; ?>"><?= e(status_pesanan_label($row['status'])); ?></span></td>
                        <td class="actions">
                            <a class="btn-small" href="../kasir/form.php?id=<?= (int) $row['id']; ?>">Edit</a>
                            <a class="btn-small danger" href="../kasir/hapus.php?id=<?= (int) $row['id']; ?>" data-confirm="Hapus transaksi ini?">Hapus</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (!$rows): ?>
                    <tr><td colspan="7" class="empty">Belum ada transaksi.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
