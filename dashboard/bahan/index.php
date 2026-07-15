<?php
require_once __DIR__ . '/../../config/koneksi.php';
require_once __DIR__ . '/../../includes/functions.php';
require_role(['gudang', 'dapur']);

$pageTitle = 'Bahan Baku';
$basePath = '../../';
$search = trim($_GET['search'] ?? '');
$page = (int) ($_GET['page'] ?? 1);
$tab = $_GET['tab'] ?? 'mentah';
if (!in_array($tab, ['mentah', 'jadi'], true)) {
    $tab = 'mentah';
}
$limit = 8;
$like = '%' . $search . '%';

if ($search !== '') {
    $countStmt = mysqli_prepare($conn, 'SELECT COUNT(*) AS total FROM bahan_baku WHERE jenis = ? AND (nama_bahan LIKE ? OR satuan LIKE ?)');
    mysqli_stmt_bind_param($countStmt, 'sss', $tab, $like, $like);
    $countRow = fetch_one_stmt($countStmt);
    $total = (int) ($countRow['total'] ?? 0);
} else {
    $total = db_count($conn, 'bahan_baku', "jenis = '" . mysqli_real_escape_string($conn, $tab) . "'");
}

$pagination = paginate($page, $limit, $total);

if ($search !== '') {
    $stmt = mysqli_prepare($conn, 'SELECT * FROM bahan_baku WHERE jenis = ? AND (nama_bahan LIKE ? OR satuan LIKE ?) ORDER BY id DESC LIMIT ? OFFSET ?');
    mysqli_stmt_bind_param($stmt, 'sssii', $tab, $like, $like, $pagination['limit'], $pagination['offset']);
} else {
    $stmt = mysqli_prepare($conn, 'SELECT * FROM bahan_baku WHERE jenis = ? ORDER BY id DESC LIMIT ? OFFSET ?');
    mysqli_stmt_bind_param($stmt, 'sii', $tab, $pagination['limit'], $pagination['offset']);
}
$rows = fetch_all_stmt($stmt);

include __DIR__ . '/../../includes/header.php';
$canManageBahan = role_can_access(['gudang']);
?>
<section class="panel">
    <div class="panel-header">
        <div>
            <h2>Data Bahan Baku</h2>
            <p>Kelola stok bahan utama untuk produksi nasi bakar.</p>
        </div>
        <?php if ($canManageBahan): ?>
            <a class="btn btn-primary" href="form.php">Tambah Bahan</a>
        <?php endif; ?>
    </div>

    <div class="tabs-toolbar">
        <a href="?tab=mentah&search=<?= urlencode($search); ?>" class="tab-btn <?= $tab === 'mentah' ? 'active' : ''; ?>">Bahan Baku (Mentah)</a>
        <a href="?tab=jadi&search=<?= urlencode($search); ?>" class="tab-btn <?= $tab === 'jadi' ? 'active' : ''; ?>">Produk Jadi</a>
    </div>

    <form class="toolbar" method="get">
        <input type="hidden" name="tab" value="<?= e($tab); ?>">
        <input type="search" name="search" value="<?= e($search); ?>" placeholder="Cari bahan atau satuan...">
        <button class="btn btn-secondary" type="submit">Cari</button>
    </form>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Nama Bahan</th>
                    <th>Stok</th>
                    <th>Stok Minimum</th>
                    <th>Satuan</th>
                    <th>Harga Terlama (Beli)</th>
                    <th>Harga Terbaru (Beli)</th>
                    <th>Harga Standar</th>
                    <?php if ($canManageBahan): ?>
                        <th>Aksi</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rows as $row):
                    // Get oldest purchase price
                    $oldestQ = mysqli_query($conn, "SELECT harga_beli FROM stok_masuk WHERE bahan_id = " . (int)$row['id'] . " ORDER BY tanggal ASC, id ASC LIMIT 1");
                    $oldestRow = $oldestQ ? mysqli_fetch_assoc($oldestQ) : null;
                    $oldestPrice = $oldestRow ? (float)$oldestRow['harga_beli'] : (float)$row['harga'];

                    // Get latest purchase price
                    $latestQ = mysqli_query($conn, "SELECT harga_beli FROM stok_masuk WHERE bahan_id = " . (int)$row['id'] . " ORDER BY tanggal DESC, id DESC LIMIT 1");
                    $latestRow = $latestQ ? mysqli_fetch_assoc($latestQ) : null;
                    $latestPrice = $latestRow ? (float)$latestRow['harga_beli'] : (float)$row['harga'];
                ?>
                    <tr>
                        <td><?= e($row['nama_bahan']); ?></td>
                        <td><span class="<?= (float) $row['stok'] <= (float) ($row['stok_minimum'] ?? 10) ? 'badge danger' : 'badge'; ?>"><?= e((string) $row['stok']); ?></span></td>
                        <td><?= e((string) ($row['stok_minimum'] ?? 10)); ?></td>
                        <td><?= e($row['satuan']); ?></td>
                        <td><?= rupiah($oldestPrice); ?></td>
                        <td><?= rupiah($latestPrice); ?></td>
                        <td><?= rupiah($row['harga']); ?></td>
                        <?php if ($canManageBahan): ?>
                            <td class="actions">
                                <a class="btn-small" href="form.php?id=<?= (int) $row['id']; ?>">Edit</a>
                                <a class="btn-small danger" href="hapus.php?id=<?= (int) $row['id']; ?>" data-confirm="Hapus bahan ini?">Hapus</a>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
                <?php if (!$rows): ?>
                    <tr><td colspan="<?= $canManageBahan ? 8 : 7; ?>" class="empty">Data bahan belum ditemukan.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="pagination">
        <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
            <a class="<?= $i === $pagination['page'] ? 'active' : ''; ?>" href="?tab=<?= urlencode($tab); ?>&search=<?= urlencode($search); ?>&page=<?= $i; ?>"><?= $i; ?></a>
        <?php endfor; ?>
    </div>
</section>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
