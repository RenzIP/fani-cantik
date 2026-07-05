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

if ($bahanId <= 0 || $jumlah <= 0 || $keterangan === '') {
    set_flash('danger', 'Mohon isi transaksi stok keluar dengan benar.');
    redirect('form.php');
}

mysqli_begin_transaction($conn);
try {
    $check = mysqli_prepare($conn, 'SELECT nama_bahan, stok, stok_minimum FROM bahan_baku WHERE id = ? FOR UPDATE');
    mysqli_stmt_bind_param($check, 'i', $bahanId);
    $bahan = fetch_one_stmt($check);

    if (!$bahan || (float) $bahan['stok'] < $jumlah) {
        mysqli_rollback($conn);
        set_flash('danger', 'Stok tidak mencukupi untuk transaksi keluar.');
        redirect('form.php');
    }

    $stmt = mysqli_prepare($conn, 'INSERT INTO stok_keluar (bahan_id, jumlah, keterangan, tanggal) VALUES (?, ?, ?, ?)');
    mysqli_stmt_bind_param($stmt, 'idss', $bahanId, $jumlah, $keterangan, $tanggal);
    mysqli_stmt_execute($stmt);

    $update = mysqli_prepare($conn, 'UPDATE bahan_baku SET stok = stok - ? WHERE id = ?');
    mysqli_stmt_bind_param($update, 'di', $jumlah, $bahanId);
    mysqli_stmt_execute($update);

    $stokAkhir = (float) $bahan['stok'] - $jumlah;
    mysqli_commit($conn);
    if ($stokAkhir <= (float) $bahan['stok_minimum']) {
        set_flash('success', 'Stok keluar berhasil disimpan. Stok ' . $bahan['nama_bahan'] . ' sudah mencapai batas minimum, buat permintaan restok bila diperlukan.');
    } else {
        set_flash('success', 'Stok keluar berhasil disimpan.');
    }
} catch (Throwable $error) {
    mysqli_rollback($conn);
    set_flash('danger', 'Gagal menyimpan stok keluar.');
}

redirect('index.php');
