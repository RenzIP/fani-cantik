<?php
require_once __DIR__ . '/../../config/koneksi.php';
require_once __DIR__ . '/../../includes/functions.php';
require_role(['gudang']);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('index.php');
}

$bahanId = (int) ($_POST['bahan_id'] ?? 0);
$supplierId = (int) ($_POST['supplier_id'] ?? 0);
$jumlah = (float) ($_POST['jumlah'] ?? 0);
$tanggal = $_POST['tanggal'] ?? date('Y-m-d');
$keterangan = trim($_POST['keterangan'] ?? '');

if ($bahanId <= 0 || $supplierId <= 0 || $jumlah <= 0 || $keterangan === '') {
    set_flash('danger', 'Mohon isi transaksi stok masuk dengan benar.');
    redirect('form.php');
}

mysqli_begin_transaction($conn);
try {
    $stmt = mysqli_prepare($conn, 'INSERT INTO stok_masuk (bahan_id, supplier_id, jumlah, tanggal, keterangan) VALUES (?, ?, ?, ?, ?)');
    mysqli_stmt_bind_param($stmt, 'iidss', $bahanId, $supplierId, $jumlah, $tanggal, $keterangan);
    mysqli_stmt_execute($stmt);

    $update = mysqli_prepare($conn, 'UPDATE bahan_baku SET stok = stok + ? WHERE id = ?');
    mysqli_stmt_bind_param($update, 'di', $jumlah, $bahanId);
    mysqli_stmt_execute($update);

    mysqli_commit($conn);
    set_flash('success', 'Stok masuk berhasil disimpan.');
} catch (Throwable $error) {
    mysqli_rollback($conn);
    set_flash('danger', 'Gagal menyimpan stok masuk.');
}

redirect('index.php');
