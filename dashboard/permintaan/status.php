<?php
require_once __DIR__ . '/../../config/koneksi.php';
require_once __DIR__ . '/../../includes/functions.php';
require_role(['gudang']);

$id = (int) ($_GET['id'] ?? 0);
$status = $_GET['status'] ?? '';
$allowedStatus = ['Disetujui', 'Ditolak'];

if ($id <= 0 || !in_array($status, $allowedStatus, true)) {
    set_flash('danger', 'Status permintaan tidak valid.');
    redirect('index.php');
}

$stmt = mysqli_prepare($conn, "UPDATE permintaan_restok SET status = ? WHERE id = ? AND status = 'Menunggu'");
mysqli_stmt_bind_param($stmt, 'si', $status, $id);
mysqli_stmt_execute($stmt);

if (mysqli_stmt_affected_rows($stmt) > 0) {
    set_flash('success', 'Permintaan restok berhasil diperbarui.');
} else {
    set_flash('danger', 'Permintaan tidak ditemukan atau sudah diproses.');
}

redirect('index.php');
