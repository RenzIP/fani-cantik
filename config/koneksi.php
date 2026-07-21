<?php
declare(strict_types=1);

// $host = 'localhost';
// $user = 'root';
// $password = '';
// $database = 'inventaris_nasi_bakar';

$host = 'sao.domcloud.co';
$user = 'nasi-bakar-rpl';
$password = 'PxbT_1(5c769uUTgA_';
$database = 'nasi_bakar_rpl_db';

$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    die('Koneksi database gagal: ' . mysqli_connect_error());
}

date_default_timezone_set('Asia/Jakarta');
mysqli_set_charset($conn, 'utf8mb4');
mysqli_query($conn, "SET time_zone = '+07:00'");
