<?php
require_once __DIR__ . '/../../config/koneksi.php';
require_once __DIR__ . '/../../includes/functions.php';
require_role(['gudang']);

$id = (int) ($_GET['id'] ?? 0);

if ($id > 0) {
    mysqli_begin_transaction($conn);
    try {
        $stmt = mysqli_prepare($conn, 'SELECT sm.bahan_id, sm.jumlah, b.stok FROM stok_masuk sm JOIN bahan_baku b ON b.id = sm.bahan_id WHERE sm.id = ? FOR UPDATE');
        mysqli_stmt_bind_param($stmt, 'i', $id);
        $row = fetch_one_stmt($stmt);

        if ($row) {
            if ((float) $row['stok'] < (float) $row['jumlah']) {
                mysqli_rollback($conn);
                set_flash('danger', 'Stok tidak mencukupi.');
                redirect('index.php');
            }

            $update = mysqli_prepare($conn, 'UPDATE bahan_baku SET stok = stok - ? WHERE id = ?');
            mysqli_stmt_bind_param($update, 'di', $row['jumlah'], $row['bahan_id']);
            mysqli_stmt_execute($update);

            $delete = mysqli_prepare($conn, 'DELETE FROM stok_masuk WHERE id = ?');
            mysqli_stmt_bind_param($delete, 'i', $id);
            mysqli_stmt_execute($delete);
        }

        mysqli_commit($conn);
        set_flash('success', 'Transaksi stok masuk berhasil dihapus.');
    } catch (Throwable $error) {
        mysqli_rollback($conn);
        set_flash('danger', 'Gagal menghapus transaksi stok masuk.');
    }
}

redirect('index.php');
