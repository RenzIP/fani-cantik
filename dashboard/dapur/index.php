<?php
require_once __DIR__ . '/../../config/koneksi.php';
require_once __DIR__ . '/../../includes/functions.php';
require_role(['dapur']);

$pageTitle = 'Pesanan Dapur';
$basePath = '../../';
$autoRefreshSeconds = 20;

$pesananBaru = db_count($conn, 'pesanan', "status IN ('baru', 'menunggu')");
$pesananDiproses = db_count($conn, 'pesanan', "status = 'diproses'");
$pesananSelesaiHariIni = db_count($conn, 'pesanan', "status = 'selesai' AND DATE(created_at) = CURDATE()");
$totalHariIni = db_count($conn, 'pesanan', 'DATE(created_at) = CURDATE()');

$sql = "SELECT p.*,
               GROUP_CONCAT(CONCAT(m.nama_menu, ' x', pd.qty) ORDER BY pd.id SEPARATOR ', ') AS item_pesanan
        FROM pesanan p
        LEFT JOIN pesanan_detail pd ON pd.pesanan_id = p.id
        LEFT JOIN menu_nasi_bakar m ON m.id = pd.menu_id
        WHERE p.status IN ('baru', 'menunggu', 'diproses')
        GROUP BY p.id
        ORDER BY FIELD(p.status, 'baru', 'menunggu', 'diproses'), p.created_at ASC";
$antrian = mysqli_fetch_all(mysqli_query($conn, $sql), MYSQLI_ASSOC);

$selesai = mysqli_fetch_all(mysqli_query($conn, "SELECT p.*,
        GROUP_CONCAT(CONCAT(m.nama_menu, ' x', pd.qty) ORDER BY pd.id SEPARATOR ', ') AS item_pesanan
        FROM pesanan p
        LEFT JOIN pesanan_detail pd ON pd.pesanan_id = p.id
        LEFT JOIN menu_nasi_bakar m ON m.id = pd.menu_id
        WHERE p.status = 'selesai' AND DATE(p.created_at) = CURDATE()
        GROUP BY p.id
        ORDER BY p.created_at DESC
        LIMIT 8"), MYSQLI_ASSOC);

include __DIR__ . '/../../includes/header.php';
?>
<?php if ($pesananBaru > 0): ?>
    <div class="alert alert-warning">Ada <?= $pesananBaru; ?> pesanan baru dari kasir yang perlu diproses dapur.</div>
<?php endif; ?>

<section class="stats-grid">
    <article class="stat-card danger">
        <span>Pesanan Baru</span>
        <strong><?= $pesananBaru; ?></strong>
    </article>
    <article class="stat-card">
        <span>Sedang Diproses</span>
        <strong><?= $pesananDiproses; ?></strong>
    </article>
    <article class="stat-card">
        <span>Selesai Hari Ini</span>
        <strong><?= $pesananSelesaiHariIni; ?></strong>
    </article>
    <article class="stat-card">
        <span>Total Hari Ini</span>
        <strong><?= $totalHariIni; ?></strong>
    </article>
</section>

<section class="panel">
    <div class="panel-header">
        <div>
            <h2>Antrian Pesanan</h2>
            <p>Pesanan dari kasir yang harus dibuat oleh dapur.</p>
        </div>
        <a class="btn btn-secondary" href="index.php">Refresh</a>
    </div>
    <div class="kitchen-board">
        <?php foreach ($antrian as $row): ?>
            <article class="kitchen-ticket <?= in_array($row['status'], ['baru', 'menunggu'], true) ? 'is-new' : ''; ?>">
                <div class="ticket-head">
                    <div>
                        <strong><?= e($row['kode_pesanan']); ?></strong>
                        <span><?= e(date('d M Y H:i', strtotime($row['created_at']))); ?></span>
                    </div>
                    <span class="badge <?= in_array($row['status'], ['baru', 'menunggu'], true) ? 'danger' : ''; ?>"><?= e(status_pesanan_label($row['status'])); ?></span>
                </div>
                <h3><?= e($row['nama_pelanggan']); ?></h3>
                <p class="ticket-items"><?= e($row['item_pesanan'] ?: 'Item belum tersedia'); ?></p>
                <?php if (!empty($row['catatan'])): ?>
                    <p class="ticket-note"><?= e($row['catatan']); ?></p>
                <?php endif; ?>
                <div class="actions">
                    <?php if (in_array($row['status'], ['baru', 'menunggu'], true)): ?>
                        <a class="btn-small" href="status.php?id=<?= (int) $row['id']; ?>&status=diproses">Proses</a>
                    <?php endif; ?>
                    <a class="btn-small" href="status.php?id=<?= (int) $row['id']; ?>&status=selesai">Selesai</a>
                    <a class="btn-small danger" href="status.php?id=<?= (int) $row['id']; ?>&status=batal" data-confirm="Batalkan pesanan ini?">Batal</a>
                </div>
            </article>
        <?php endforeach; ?>
        <?php if (!$antrian): ?>
            <p class="empty">Tidak ada antrian pesanan untuk dapur.</p>
        <?php endif; ?>
    </div>
</section>

<section class="panel">
    <div class="panel-header">
        <div>
            <h2>Pesanan Selesai Hari Ini</h2>
            <p>Daftar pesanan yang sudah ditandai selesai oleh dapur.</p>
        </div>
    </div>
    <div class="table-wrap">
        <table class="table-fit">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Pelanggan</th>
                    <th>Item</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($selesai as $row): ?>
                    <tr>
                        <td><?= e($row['kode_pesanan']); ?><br><small><?= e(date('H:i', strtotime($row['created_at']))); ?></small></td>
                        <td><?= e($row['nama_pelanggan']); ?></td>
                        <td><?= e($row['item_pesanan'] ?: '-'); ?></td>
                        <td><?= rupiah($row['total']); ?></td>
                    </tr>
                <?php endforeach; ?>
                <?php if (!$selesai): ?>
                    <tr><td colspan="4" class="empty">Belum ada pesanan selesai hari ini.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
