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
$produkJadiId = (int) ($_POST['produk_jadi_id'] ?? 0);
$existingGambar = trim($_POST['existing_gambar'] ?? '');
$gambar = $existingGambar;

if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['gambar']['tmp_name'];
    $fileName = $_FILES['gambar']['name'];
    $fileSize = $_FILES['gambar']['size'];
    $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
    if (in_array($fileExtension, $allowedExtensions, true)) {
        if ($fileSize <= 2 * 1024 * 1024) { // Max 2MB
            $uploadFileDir = __DIR__ . '/../../assets/images/';
            if (!is_dir($uploadFileDir)) {
                mkdir($uploadFileDir, 0755, true);
            }
            
            // Clean up name
            $newFileName = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $fileName);
            $dest_path = $uploadFileDir . $newFileName;

            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                // Delete old image if it is updated
                if ($existingGambar !== '' && $existingGambar !== $newFileName) {
                    $oldFilePath = $uploadFileDir . $existingGambar;
                    if (file_exists($oldFilePath) && $existingGambar !== 'nasibakar.png') {
                        unlink($oldFilePath);
                    }
                }
                $gambar = $newFileName;
            } else {
                set_flash('danger', 'Gagal memindahkan file ke direktori tujuan.');
                redirect($id ? 'form.php?id=' . $id : 'form.php');
            }
        } else {
            set_flash('danger', 'Ukuran file gambar maksimal 2MB.');
            redirect($id ? 'form.php?id=' . $id : 'form.php');
        }
    } else {
        set_flash('danger', 'Format gambar harus berupa JPG, JPEG, PNG, atau WEBP.');
        redirect($id ? 'form.php?id=' . $id : 'form.php');
    }
}

if ($nama === '' || $deskripsi === '' || $harga < 0) {
    set_flash('danger', 'Mohon isi data menu dengan benar.');
    redirect($id ? 'form.php?id=' . $id : 'form.php');
}

if ($produkJadiId > 0) {
    $checkProduk = mysqli_prepare($conn, "SELECT id FROM bahan_baku WHERE id = ? AND jenis = 'jadi'");
    mysqli_stmt_bind_param($checkProduk, 'i', $produkJadiId);
    if (!fetch_one_stmt($checkProduk)) {
        set_flash('danger', 'Pilih data produk jadi yang valid.');
        redirect($id ? 'form.php?id=' . $id : 'form.php');
    }
}

$produkJadiId = $produkJadiId > 0 ? $produkJadiId : null;

if ($id > 0) {
    $stmt = mysqli_prepare($conn, 'UPDATE menu_nasi_bakar SET nama_menu = ?, harga = ?, deskripsi = ?, gambar = ?, produk_jadi_id = ? WHERE id = ?');
    mysqli_stmt_bind_param($stmt, 'sdssii', $nama, $harga, $deskripsi, $gambar, $produkJadiId, $id);
    mysqli_stmt_execute($stmt);
    set_flash('success', 'Menu berhasil diperbarui.');
} else {
    $stmt = mysqli_prepare($conn, 'INSERT INTO menu_nasi_bakar (nama_menu, harga, deskripsi, gambar, produk_jadi_id) VALUES (?, ?, ?, ?, ?)');
    mysqli_stmt_bind_param($stmt, 'sdssi', $nama, $harga, $deskripsi, $gambar, $produkJadiId);
    mysqli_stmt_execute($stmt);
    set_flash('success', 'Menu berhasil ditambahkan.');
}

redirect('index.php');
