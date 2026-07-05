<?php
require_once __DIR__ . '/../../config/koneksi.php';
require_once __DIR__ . '/../../includes/functions.php';
require_role(['gudang', 'dapur']);

$pageTitle = 'Bahan Baku';
$basePath = '../../';
$search = trim($_GET['search'] ?? '');
$page = (int) ($_GET['page'] ?? 1);
$limit = 8;
$like = '%' . $search . '%';

if ($search !== '') {
    $countStmt = mysqli_prepare($conn, 'SELECT COUNT(*) AS total FROM bahan_baku WHERE nama_bahan LIKE ? OR satuan LIKE ?');
    mysqli_stmt_bind_param($countStmt, 'ss', $like, $like);
    $countRow = fetch_one_stmt($countStmt);
    $total = (int) ($countRow['total'] ?? 0);
} else {
    $total = db_count($conn, 'bahan_baku');
}

$pagination = paginate($page, $limit, $total);

if ($search !== '') {
    $stmt = mysqli_prepare($conn, 'SELECT * FROM bahan_baku WHERE nama_bahan LIKE ? OR satuan LIKE ? ORDER BY id DESC LIMIT ? OFFSET ?');
    mysqli_stmt_bind_param($stmt, 'ssii', $like, $like, $pagination['limit'], $pagination['offset']);
} else {
    $stmt = mysqli_prepare($conn, 'SELECT * FROM bahan_baku ORDER BY id DESC LIMIT ? OFFSET ?');
    mysqli_stmt_bind_param($stmt, 'ii', $pagination['limit'], $pagination['offset']);
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

    <form class="toolbar" method="get">
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
                    <th>Harga</th>
                    <?php if ($canManageBahan): ?>
                        <th>Aksi</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rows as $row): ?>
                    <tr>
                        <td><?= e($row['nama_bahan']); ?></td>
                        <td><span class="<?= (float) $row['stok'] <= (float) ($row['stok_minimum'] ?? 10) ? 'badge danger' : 'badge'; ?>"><?= e((string) $row['stok']); ?></span></td>
                        <td><?= e((string) ($row['stok_minimum'] ?? 10)); ?></td>
                        <td><?= e($row['satuan']); ?></td>
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
                    <tr><td colspan="<?= $canManageBahan ? 6 : 5; ?>" class="empty">Data bahan belum ditemukan.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="pagination">
        <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
            <a class="<?= $i === $pagination['page'] ? 'active' : ''; ?>" href="?search=<?= urlencode($search); ?>&page=<?= $i; ?>"><?= $i; ?></a>
        <?php endfor; ?>
    </div>
</section>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
