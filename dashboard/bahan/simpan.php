<?php
require_once __DIR__ . '/../../config/koneksi.php';
require_once __DIR__ . '/../../includes/functions.php';
require_role(['gudang']);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('index.php');
}

$id = (int) ($_POST['id'] ?? 0);
$nama = trim($_POST['nama_bahan'] ?? '');
$stok = (float) ($_POST['stok'] ?? 0);
$stokMinimum = (float) ($_POST['stok_minimum'] ?? 0);
$satuan = trim($_POST['satuan'] ?? '');
$harga = (float) ($_POST['harga'] ?? 0);

if ($nama === '' || $satuan === '' || $stok < 0 || $stokMinimum < 0 || $harga < 0) {
    set_flash('danger', 'Mohon isi data bahan dengan benar.');
    redirect($id ? 'form.php?id=' . $id : 'form.php');
}

if ($id > 0) {
    $stmt = mysqli_prepare($conn, 'UPDATE bahan_baku SET nama_bahan = ?, stok = ?, stok_minimum = ?, satuan = ?, harga = ? WHERE id = ?');
    mysqli_stmt_bind_param($stmt, 'sddsdi', $nama, $stok, $stokMinimum, $satuan, $harga, $id);
    mysqli_stmt_execute($stmt);
    set_flash('success', 'Data bahan berhasil diperbarui.');
} else {
    $stmt = mysqli_prepare($conn, 'INSERT INTO bahan_baku (nama_bahan, stok, stok_minimum, satuan, harga) VALUES (?, ?, ?, ?, ?)');
    mysqli_stmt_bind_param($stmt, 'sddsd', $nama, $stok, $stokMinimum, $satuan, $harga);
    mysqli_stmt_execute($stmt);
    set_flash('success', 'Data bahan berhasil ditambahkan.');
}

redirect('index.php');
