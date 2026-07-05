<?php
require_once __DIR__ . '/../../config/koneksi.php';
require_once __DIR__ . '/../../includes/functions.php';
require_role(['kasir']);

$id = (int) ($_GET['id'] ?? 0);

if ($id <= 0) {
    set_flash('danger', 'Pesanan tidak valid.');
    redirect('index.php');
}

$stmt = mysqli_prepare($conn, 'DELETE FROM pesanan WHERE id = ?');
mysqli_stmt_bind_param($stmt, 'i', $id);

if (mysqli_stmt_execute($stmt)) {
    set_flash('success', 'Pesanan berhasil dihapus.');
} else {
    set_flash('danger', 'Gagal menghapus pesanan.');
}

redirect('index.php');
