<?php
require_once __DIR__ . '/../../config/koneksi.php';
require_once __DIR__ . '/../../includes/functions.php';
require_role(['gudang']);

$id = (int) ($_GET['id'] ?? 0);

if ($id > 0) {
    $stmt = mysqli_prepare($conn, 'DELETE FROM bahan_baku WHERE id = ?');
    mysqli_stmt_bind_param($stmt, 'i', $id);
    if (mysqli_stmt_execute($stmt)) {
        set_flash('success', 'Data bahan berhasil dihapus.');
    } else {
        set_flash('danger', 'Bahan tidak bisa dihapus karena masih dipakai transaksi.');
    }
}

redirect('index.php');
