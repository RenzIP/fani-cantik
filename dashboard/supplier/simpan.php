<?php
require_once __DIR__ . '/../../config/koneksi.php';
require_once __DIR__ . '/../../includes/functions.php';
require_role(['gudang']);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('index.php');
}

$id = (int) ($_POST['id'] ?? 0);
$nama = trim($_POST['nama_supplier'] ?? '');
$telp = trim($_POST['no_telp'] ?? '');
$alamat = trim($_POST['alamat'] ?? '');

if ($nama === '' || $telp === '' || $alamat === '') {
    set_flash('danger', 'Mohon isi data supplier dengan benar.');
    redirect($id ? 'form.php?id=' . $id : 'form.php');
}

if ($id > 0) {
    $stmt = mysqli_prepare($conn, 'UPDATE supplier SET nama_supplier = ?, no_telp = ?, alamat = ? WHERE id = ?');
    mysqli_stmt_bind_param($stmt, 'sssi', $nama, $telp, $alamat, $id);
    mysqli_stmt_execute($stmt);
    set_flash('success', 'Supplier berhasil diperbarui.');
} else {
    $stmt = mysqli_prepare($conn, 'INSERT INTO supplier (nama_supplier, no_telp, alamat) VALUES (?, ?, ?)');
    mysqli_stmt_bind_param($stmt, 'sss', $nama, $telp, $alamat);
    mysqli_stmt_execute($stmt);
    set_flash('success', 'Supplier berhasil ditambahkan.');
}

redirect('index.php');
