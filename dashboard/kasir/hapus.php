<?php
require_once __DIR__ . '/../../config/koneksi.php';
require_once __DIR__ . '/../../includes/functions.php';
require_role(['kasir']);

$id = (int) ($_GET['id'] ?? 0);

if ($id <= 0) {
    set_flash('danger', 'Pesanan tidak valid.');
    redirect('index.php');
}

mysqli_begin_transaction($conn);
try {
    $check = mysqli_prepare($conn, 'SELECT status FROM pesanan WHERE id = ? FOR UPDATE');
    mysqli_stmt_bind_param($check, 'i', $id);
    $pesanan = fetch_one_stmt($check);

    if (!$pesanan) {
        throw new RuntimeException('Pesanan tidak ditemukan.');
    }

    if ($pesanan['status'] !== 'batal') {
        adjust_finished_product_stock($conn, get_order_stock_items($conn, $id), 1);
    }

    $stmt = mysqli_prepare($conn, 'DELETE FROM pesanan WHERE id = ?');
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);

    mysqli_commit($conn);
    set_flash('success', 'Pesanan berhasil dihapus dan stok produk jadi dikembalikan.');
} catch (Throwable $error) {
    mysqli_rollback($conn);
    set_flash('danger', $error->getMessage() ?: 'Gagal menghapus pesanan.');
}

redirect('index.php');
