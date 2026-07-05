<?php
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('login.php');
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$remember = isset($_POST['remember']);

if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $password === '') {
    set_flash('danger', 'Email atau password belum valid.');
    redirect('login.php');
}

$stmt = mysqli_prepare($conn, 'SELECT id, nama, email, password, role FROM users WHERE email = ? LIMIT 1');
mysqli_stmt_bind_param($stmt, 's', $email);
$user = fetch_one_stmt($stmt);

if (!$user || !password_verify($password, $user['password'])) {
    set_flash('danger', 'Email atau password salah.');
    redirect('login.php');
}

session_regenerate_id(true);
$_SESSION['user_id'] = (int) $user['id'];
$_SESSION['nama'] = $user['nama'];
$_SESSION['email'] = $user['email'];
$_SESSION['role'] = $user['role'];

if ($remember) {
    setcookie('remember_email', $email, time() + (60 * 60 * 24 * 30), '/');
} else {
    setcookie('remember_email', '', time() - 3600, '/');
}

redirect(dashboard_home_for_role($user['role']));
