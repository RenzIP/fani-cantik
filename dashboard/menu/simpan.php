<?php
require_once __DIR__ . '/../../config/koneksi.php';
require_once __DIR__ . '/../../includes/functions.php';
require_role(['admin']);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('index.php');
}

$id = (int) ($_POST['id'] ?? 0);
$nama = trim($_POST['nama_menu'] ?? '');
$harga = (float) ($_POST['harga'] ?? 0);
$deskripsi = trim($_POST['deskripsi'] ?? '');
$gambar = trim($_POST['gambar'] ?? '');

if ($nama === '' || $deskripsi === '' || $harga < 0) {
    set_flash('danger', 'Mohon isi data menu dengan benar.');
    redirect($id ? 'form.php?id=' . $id : 'form.php');
}

if ($id > 0) {
    $stmt = mysqli_prepare($conn, 'UPDATE menu_nasi_bakar SET nama_menu = ?, harga = ?, deskripsi = ?, gambar = ? WHERE id = ?');
    mysqli_stmt_bind_param($stmt, 'sdssi', $nama, $harga, $deskripsi, $gambar, $id);
    mysqli_stmt_execute($stmt);
    set_flash('success', 'Menu berhasil diperbarui.');
} else {
    $stmt = mysqli_prepare($conn, 'INSERT INTO menu_nasi_bakar (nama_menu, harga, deskripsi, gambar) VALUES (?, ?, ?, ?)');
    mysqli_stmt_bind_param($stmt, 'sdss', $nama, $harga, $deskripsi, $gambar);
    mysqli_stmt_execute($stmt);
    set_flash('success', 'Menu berhasil ditambahkan.');
}

redirect('index.php');
