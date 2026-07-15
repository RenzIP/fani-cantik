<?php
require_once __DIR__ . '/../../config/koneksi.php';
require_once __DIR__ . '/../../includes/functions.php';
require_role(['kasir']);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('index.php');
}

$namaPelanggan = trim($_POST['nama_pelanggan'] ?? '');
$metodeBayar = $_POST['metode_bayar'] ?? 'tunai';
$status = $_POST['status'] ?? 'baru';
$statusPembayaran = $_POST['status_pembayaran'] ?? 'belum_bayar';
$catatan = trim($_POST['catatan'] ?? '');
$qtyInput = $_POST['qty'] ?? [];
$allowedPayment = ['tunai', 'qris', 'transfer'];
$allowedStatus = ['baru', 'menunggu', 'diproses', 'selesai', 'batal'];
$allowedStatusPembayaran = ['belum_bayar', 'lunas'];
$items = [];
$total = 0.0;
$id = (int) ($_POST['id'] ?? 0);

if ($namaPelanggan === '' || !in_array($metodeBayar, $allowedPayment, true) || !in_array($status, $allowedStatus, true) || !in_array($statusPembayaran, $allowedStatusPembayaran, true)) {
    set_flash('danger', 'Mohon isi data pelanggan, metode bayar, dan status pembayaran dengan benar.');
    redirect($id > 0 ? 'form.php?id=' . $id : 'index.php');
}

foreach ($qtyInput as $menuId => $qty) {
    $menuId = (int) $menuId;
    $qty = (int) $qty;

    if ($menuId <= 0 || $qty <= 0) {
        continue;
    }

    $stmt = mysqli_prepare($conn, 'SELECT id, harga FROM menu_nasi_bakar WHERE id = ?');
    mysqli_stmt_bind_param($stmt, 'i', $menuId);
    $menu = fetch_one_stmt($stmt);

    if (!$menu) {
        continue;
    }

    $harga = (float) $menu['harga'];
    $subtotal = $harga * $qty;
    $total += $subtotal;
    $items[] = [
        'menu_id' => $menuId,
        'qty' => $qty,
        'harga' => $harga,
        'subtotal' => $subtotal,
    ];
}

if (!$items) {
    set_flash('danger', 'Pilih minimal satu menu untuk pesanan.');
    redirect($id > 0 ? 'form.php?id=' . $id : 'index.php');
}

mysqli_begin_transaction($conn);
try {
    $userId = (int) $_SESSION['user_id'];

    if ($id > 0) {
        $check = mysqli_prepare($conn, 'SELECT id FROM pesanan WHERE id = ?');
        mysqli_stmt_bind_param($check, 'i', $id);
        $existing = fetch_one_stmt($check);

        if (!$existing) {
            mysqli_rollback($conn);
            set_flash('danger', 'Pesanan tidak ditemukan.');
            redirect('index.php');
        }

        $stmt = mysqli_prepare($conn, 'UPDATE pesanan SET nama_pelanggan = ?, total = ?, metode_bayar = ?, status = ?, status_pembayaran = ?, catatan = ?, user_id = ? WHERE id = ?');
        mysqli_stmt_bind_param($stmt, 'sdssssii', $namaPelanggan, $total, $metodeBayar, $status, $statusPembayaran, $catatan, $userId, $id);
        mysqli_stmt_execute($stmt);

        $delete = mysqli_prepare($conn, 'DELETE FROM pesanan_detail WHERE pesanan_id = ?');
        mysqli_stmt_bind_param($delete, 'i', $id);
        mysqli_stmt_execute($delete);
        $pesananId = $id;
    } else {
        $kodePesanan = 'NB-' . date('Ymd-His');
        $status = $status === 'baru' ? 'menunggu' : $status;
        $stmt = mysqli_prepare($conn, 'INSERT INTO pesanan (kode_pesanan, nama_pelanggan, total, metode_bayar, status, status_pembayaran, catatan, user_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
        mysqli_stmt_bind_param($stmt, 'ssdssssi', $kodePesanan, $namaPelanggan, $total, $metodeBayar, $status, $statusPembayaran, $catatan, $userId);
        mysqli_stmt_execute($stmt);
        $pesananId = mysqli_insert_id($conn);
    }

    $detail = mysqli_prepare($conn, 'INSERT INTO pesanan_detail (pesanan_id, menu_id, qty, harga, subtotal) VALUES (?, ?, ?, ?, ?)');
    foreach ($items as $item) {
        mysqli_stmt_bind_param($detail, 'iiidd', $pesananId, $item['menu_id'], $item['qty'], $item['harga'], $item['subtotal']);
        mysqli_stmt_execute($detail);
    }

    mysqli_commit($conn);
    set_flash('success', $id > 0 ? 'Pesanan berhasil diperbarui.' : 'Pesanan berhasil disimpan.');
} catch (Throwable $error) {
    mysqli_rollback($conn);
    set_flash('danger', 'Gagal menyimpan pesanan.');
}

redirect('index.php');
