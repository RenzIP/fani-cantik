<?php
require_once __DIR__ . '/../../config/koneksi.php';
require_once __DIR__ . '/../../includes/functions.php';
require_role(['dapur']);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('index.php');
}

$bahanId = (int) ($_POST['bahan_id'] ?? 0);
$jumlah = (float) ($_POST['jumlah'] ?? 0);
$tanggal = $_POST['tanggal'] ?? date('Y-m-d');
$keterangan = trim($_POST['keterangan'] ?? '');

if ($bahanId <= 0 || $jumlah <= 0 || $tanggal === '' || $keterangan === '') {
    set_flash('danger', 'Mohon isi permintaan restok dengan benar.');
    redirect('form.php');
}

$check = mysqli_prepare($conn, 'SELECT id FROM bahan_baku WHERE id = ?');
mysqli_stmt_bind_param($check, 'i', $bahanId);
$bahan = fetch_one_stmt($check);

if (!$bahan) {
    set_flash('danger', 'Bahan tidak ditemukan.');
    redirect('form.php');
}

$status = 'Menunggu';
$stmt = mysqli_prepare($conn, 'INSERT INTO permintaan_restok (bahan_id, jumlah, tanggal, status, keterangan) VALUES (?, ?, ?, ?, ?)');
mysqli_stmt_bind_param($stmt, 'idsss', $bahanId, $jumlah, $tanggal, $status, $keterangan);

if (mysqli_stmt_execute($stmt)) {
    set_flash('success', 'Permintaan restok berhasil dikirim ke gudang.');
} else {
    set_flash('danger', 'Gagal menyimpan permintaan restok.');
}

redirect('index.php');
