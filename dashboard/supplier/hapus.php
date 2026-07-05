<?php
require_once __DIR__ . '/../../config/koneksi.php';
require_once __DIR__ . '/../../includes/functions.php';
require_role(['gudang']);

$id = (int) ($_GET['id'] ?? 0);
if ($id > 0) {
    $stmt = mysqli_prepare($conn, 'DELETE FROM supplier WHERE id = ?');
    mysqli_stmt_bind_param($stmt, 'i', $id);
    if (mysqli_stmt_execute($stmt)) {
        set_flash('success', 'Supplier berhasil dihapus.');
    } else {
        set_flash('danger', 'Supplier tidak bisa dihapus karena masih dipakai transaksi.');
    }
}
redirect('index.php');
