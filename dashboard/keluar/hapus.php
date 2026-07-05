<?php
require_once __DIR__ . '/../../config/koneksi.php';
require_once __DIR__ . '/../../includes/functions.php';
require_role(['dapur']);

$id = (int) ($_GET['id'] ?? 0);

if ($id > 0) {
    mysqli_begin_transaction($conn);
    try {
        $stmt = mysqli_prepare($conn, 'SELECT bahan_id, jumlah FROM stok_keluar WHERE id = ?');
        mysqli_stmt_bind_param($stmt, 'i', $id);
        $row = fetch_one_stmt($stmt);

        if ($row) {
            $update = mysqli_prepare($conn, 'UPDATE bahan_baku SET stok = stok + ? WHERE id = ?');
            mysqli_stmt_bind_param($update, 'di', $row['jumlah'], $row['bahan_id']);
            mysqli_stmt_execute($update);

            $delete = mysqli_prepare($conn, 'DELETE FROM stok_keluar WHERE id = ?');
            mysqli_stmt_bind_param($delete, 'i', $id);
            mysqli_stmt_execute($delete);
        }

        mysqli_commit($conn);
        set_flash('success', 'Transaksi stok keluar berhasil dihapus.');
    } catch (Throwable $error) {
        mysqli_rollback($conn);
        set_flash('danger', 'Gagal menghapus transaksi stok keluar.');
    }
}

redirect('index.php');
