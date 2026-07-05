<?php
require_once __DIR__ . '/../../config/koneksi.php';
require_once __DIR__ . '/../../includes/functions.php';
require_role(['dapur']);

$id = (int) ($_GET['id'] ?? 0);
$status = $_GET['status'] ?? '';
$allowedStatus = ['diproses', 'selesai', 'batal'];

if ($id <= 0 || !in_array($status, $allowedStatus, true)) {
    set_flash('danger', 'Status pesanan tidak valid.');
    redirect('index.php');
}

$stmt = mysqli_prepare($conn, 'UPDATE pesanan SET status = ? WHERE id = ?');
mysqli_stmt_bind_param($stmt, 'si', $status, $id);

if (mysqli_stmt_execute($stmt)) {
    set_flash('success', 'Status pesanan berhasil diperbarui.');
} else {
    set_flash('danger', 'Gagal memperbarui status pesanan.');
}

redirect('index.php');
