<?php
require_once __DIR__ . '/../../config/koneksi.php';
require_once __DIR__ . '/../../includes/functions.php';
require_role(['dapur']);

$id = (int) ($_GET['id'] ?? 0);
$status = $_GET['status'] ?? '';
$allowedStatus = ['diproses', 'selesai', 'batal'];

if ($id <= 0 || !in_array($status, $allowedStatus, true)) {
    set_flash('danger', 'Status pesanan tidak valid.');
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

    if ($pesanan['status'] !== 'batal' && $status === 'batal') {
        adjust_finished_product_stock($conn, get_order_stock_items($conn, $id), 1);
    }

    if ($pesanan['status'] === 'batal' && $status !== 'batal') {
        adjust_finished_product_stock($conn, get_order_stock_items($conn, $id), -1);
    }

    $stmt = mysqli_prepare($conn, 'UPDATE pesanan SET status = ? WHERE id = ?');
    mysqli_stmt_bind_param($stmt, 'si', $status, $id);
    mysqli_stmt_execute($stmt);

    mysqli_commit($conn);
    set_flash('success', 'Status pesanan berhasil diperbarui.');
} catch (Throwable $error) {
    mysqli_rollback($conn);
    set_flash('danger', $error->getMessage() ?: 'Gagal memperbarui status pesanan.');
}

redirect('index.php');
