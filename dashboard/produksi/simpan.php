<?php
require_once __DIR__ . '/../../config/koneksi.php';
require_once __DIR__ . '/../../includes/functions.php';
require_role(['dapur']);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('index.php');
}

$produkId = (int) ($_POST['produk_id'] ?? 0);
$jumlahHasil = (float) ($_POST['jumlah_hasil'] ?? 0);
$tanggal = $_POST['tanggal'] ?? date('Y-m-d');
$keterangan = trim($_POST['keterangan'] ?? '');
$inputBahan = $_POST['bahan_qty'] ?? [];
$bahanDipakai = [];

foreach ($inputBahan as $bahanId => $jumlah) {
    $bahanId = (int) $bahanId;
    $jumlah = (float) $jumlah;
    if ($bahanId > 0 && $jumlah > 0) {
        $bahanDipakai[] = ['bahan_id' => $bahanId, 'jumlah' => $jumlah];
    }
}

if ($produkId <= 0 || $jumlahHasil <= 0 || !$bahanDipakai) {
    set_flash('danger', 'Pilih produk jadi, isi hasil produksi, dan masukkan minimal satu bahan mentah.');
    redirect('form.php');
}

mysqli_begin_transaction($conn);
try {
    $produkStmt = mysqli_prepare($conn, "SELECT id, nama_bahan, stok FROM bahan_baku WHERE id = ? AND jenis = 'jadi' FOR UPDATE");
    mysqli_stmt_bind_param($produkStmt, 'i', $produkId);
    $produk = fetch_one_stmt($produkStmt);
    if (!$produk) {
        throw new RuntimeException('Produk jadi tidak ditemukan.');
    }

    $bahanStmt = mysqli_prepare($conn, "SELECT id, nama_bahan, stok FROM bahan_baku WHERE id = ? AND jenis = 'mentah' FOR UPDATE");
    $updateBahan = mysqli_prepare($conn, 'UPDATE bahan_baku SET stok = stok - ? WHERE id = ?');
    foreach ($bahanDipakai as $item) {
        mysqli_stmt_bind_param($bahanStmt, 'i', $item['bahan_id']);
        $bahan = fetch_one_stmt($bahanStmt);
        if (!$bahan) {
            throw new RuntimeException('Bahan mentah tidak ditemukan.');
        }
        if ((float) $bahan['stok'] < $item['jumlah']) {
            throw new RuntimeException('Stok bahan ' . $bahan['nama_bahan'] . ' tidak mencukupi.');
        }
        mysqli_stmt_bind_param($updateBahan, 'di', $item['jumlah'], $item['bahan_id']);
        mysqli_stmt_execute($updateBahan);
    }

    $kodeProduksi = 'PRD-' . date('Ymd-His');
    $insertProduksi = mysqli_prepare($conn, 'INSERT INTO produksi (kode_produksi, produk_id, jumlah_hasil, tanggal, keterangan, user_id) VALUES (?, ?, ?, ?, ?, ?)');
    $userId = (int) $_SESSION['user_id'];
    mysqli_stmt_bind_param($insertProduksi, 'sidssi', $kodeProduksi, $produkId, $jumlahHasil, $tanggal, $keterangan, $userId);
    mysqli_stmt_execute($insertProduksi);
    $produksiId = mysqli_insert_id($conn);

    $insertDetail = mysqli_prepare($conn, 'INSERT INTO produksi_detail (produksi_id, bahan_id, jumlah_pakai) VALUES (?, ?, ?)');
    foreach ($bahanDipakai as $item) {
        mysqli_stmt_bind_param($insertDetail, 'iid', $produksiId, $item['bahan_id'], $item['jumlah']);
        mysqli_stmt_execute($insertDetail);
    }

    $updateProduk = mysqli_prepare($conn, 'UPDATE bahan_baku SET stok = stok + ? WHERE id = ?');
    mysqli_stmt_bind_param($updateProduk, 'di', $jumlahHasil, $produkId);
    mysqli_stmt_execute($updateProduk);

    mysqli_commit($conn);
    set_flash('success', 'Produksi berhasil disimpan. Stok bahan mentah berkurang dan produk jadi bertambah.');
} catch (Throwable $error) {
    mysqli_rollback($conn);
    set_flash('danger', $error->getMessage() ?: 'Gagal menyimpan produksi.');
}

redirect('index.php');
