<?php
require_once __DIR__ . '/../../config/koneksi.php';
require_once __DIR__ . '/../../includes/functions.php';
require_role(['dapur', 'gudang']);

$pageTitle = 'Permintaan Restok';
$basePath = '../../';
$canCreate = role_can_access(['dapur']);
$canApprove = role_can_access(['gudang']);

$sql = "SELECT pr.*, b.nama_bahan, b.satuan, b.stok, b.stok_minimum
        FROM permintaan_restok pr
        JOIN bahan_baku b ON b.id = pr.bahan_id
        ORDER BY FIELD(pr.status, 'Menunggu', 'Disetujui', 'Ditolak'), pr.tanggal DESC, pr.id DESC";
$rows = mysqli_fetch_all(mysqli_query($conn, $sql), MYSQLI_ASSOC);

include __DIR__ . '/../../includes/header.php';
?>
<section class="stats-grid">
    <article class="stat-card danger">
        <span>Menunggu</span>
        <strong><?= db_count($conn, 'permintaan_restok', "status = 'Menunggu'"); ?></strong>
    </article>
    <article class="stat-card">
        <span>Disetujui</span>
        <strong><?= db_count($conn, 'permintaan_restok', "status = 'Disetujui'"); ?></strong>
    </article>
    <article class="stat-card">
        <span>Ditolak</span>
        <strong><?= db_count($conn, 'permintaan_restok', "status = 'Ditolak'"); ?></strong>
    </article>
    <article class="stat-card danger">
        <span>Bahan Menipis</span>
        <strong><?= db_count($conn, 'bahan_baku', 'stok <= stok_minimum'); ?></strong>
    </article>
</section>

<section class="panel">
    <div class="panel-header">
        <div>
            <h2>Daftar Permintaan Restok</h2>
            <p>Dapur mengajukan restok, gudang menyetujui atau menolak.</p>
        </div>
        <?php if ($canCreate): ?>
            <a class="btn btn-primary" href="form.php">Tambah Permintaan</a>
        <?php endif; ?>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Bahan</th>
                    <th>Stok</th>
                    <th>Jumlah Diminta</th>
                    <th>Status</th>
                    <th>Keterangan</th>
                    <?php if ($canApprove): ?>
                        <th>Aksi</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rows as $row): ?>
                    <tr>
                        <td><?= e(date('d M Y', strtotime($row['tanggal']))); ?></td>
                        <td><?= e($row['nama_bahan']); ?></td>
                        <td>
                            <span class="<?= (float) $row['stok'] <= (float) $row['stok_minimum'] ? 'badge danger' : 'badge'; ?>">
                                <?= e((string) $row['stok']); ?> <?= e($row['satuan']); ?>
                            </span>
                        </td>
                        <td><?= e((string) $row['jumlah']); ?> <?= e($row['satuan']); ?></td>
                        <td><span class="badge <?= $row['status'] === 'Ditolak' ? 'danger' : ''; ?>"><?= e($row['status']); ?></span></td>
                        <td><?= e($row['keterangan'] ?: '-'); ?></td>
                        <?php if ($canApprove): ?>
                            <td class="actions">
                                <?php if ($row['status'] === 'Menunggu'): ?>
                                    <a class="btn-small" href="status.php?id=<?= (int) $row['id']; ?>&status=Disetujui">Setujui</a>
                                    <a class="btn-small danger" href="status.php?id=<?= (int) $row['id']; ?>&status=Ditolak" data-confirm="Tolak permintaan restok ini?">Tolak</a>
                                <?php else: ?>
                                    <span class="empty">Selesai</span>
                                <?php endif; ?>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
                <?php if (!$rows): ?>
                    <tr><td colspan="<?= $canApprove ? 7 : 6; ?>" class="empty">Belum ada permintaan restok.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
