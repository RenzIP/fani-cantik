<?php
require_once __DIR__ . '/../../config/koneksi.php';
require_once __DIR__ . '/../../includes/functions.php';
require_role(['admin']);

$id = (int) ($_GET['id'] ?? 0);
if ($id > 0) {
    $stmt = mysqli_prepare($conn, 'DELETE FROM menu_nasi_bakar WHERE id = ?');
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    set_flash('success', 'Menu berhasil dihapus.');
}
redirect('index.php');
