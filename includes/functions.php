<?php
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function redirect(string $path): void
{
    header('Location: ' . $path);
    exit;
}

function require_login(): void
{
    if (empty($_SESSION['user_id'])) {
        redirect('../../auth/login.php');
    }
}

function require_login_dashboard(): void
{
    if (empty($_SESSION['user_id'])) {
        redirect('../auth/login.php');
    }
}

function current_role(): string
{
    $role = $_SESSION['role'] ?? '';
    return $role === 'restok' ? 'gudang' : $role;
}

function is_full_access(): bool
{
    return in_array(current_role(), ['admin', 'owner'], true);
}

function role_can_access(array $roles): bool
{
    return is_full_access() || in_array(current_role(), $roles, true);
}

function require_role(array $roles, string $loginPath = '../../auth/login.php', string $fallbackPath = '../index.php'): void
{
    if (empty($_SESSION['user_id'])) {
        redirect($loginPath);
    }

    if (!role_can_access($roles)) {
        set_flash('danger', 'Akses halaman ini tidak tersedia untuk role Anda.');
        redirect($fallbackPath);
    }
}

function dashboard_home_for_role(string $role): string
{
    $role = $role === 'restok' ? 'gudang' : $role;

    if ($role === 'kasir') {
        return '../dashboard/kasir/index.php';
    }

    if ($role === 'dapur') {
        return '../dashboard/dapur/index.php';
    }

    if ($role === 'gudang') {
        return '../dashboard/index.php';
    }

    return '../dashboard/index.php';
}

function set_flash(string $type, string $message): void
{
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function get_flash(): ?array
{
    if (empty($_SESSION['flash'])) {
        return null;
    }

    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);
    return $flash;
}

function rupiah($value): string
{
    return 'Rp ' . number_format((float) $value, 0, ',', '.');
}

function status_pesanan_label(string $status): string
{
    $labels = [
        'baru' => 'Menunggu',
        'menunggu' => 'Menunggu',
        'diproses' => 'Diproses',
        'selesai' => 'Selesai',
        'batal' => 'Batal',
    ];

    return $labels[$status] ?? ucfirst($status);
}

function db_count(mysqli $conn, string $table, string $where = ''): int
{
    $sql = 'SELECT COUNT(*) AS total FROM ' . $table . ($where ? ' WHERE ' . $where : '');
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    return (int) ($row['total'] ?? 0);
}

function fetch_all_stmt(mysqli_stmt $stmt): array
{
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

function fetch_one_stmt(mysqli_stmt $stmt): ?array
{
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    return $row ?: null;
}

function bind_params(mysqli_stmt $stmt, string $types, array $params): void
{
    if ($types !== '') {
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }
}

function paginate(int $page, int $limit, int $total): array
{
    $totalPages = max(1, (int) ceil($total / $limit));
    $page = max(1, min($page, $totalPages));
    return [
        'page' => $page,
        'limit' => $limit,
        'offset' => ($page - 1) * $limit,
        'total_pages' => $totalPages,
    ];
}

function adjust_finished_product_stock(mysqli $conn, array $items, int $direction): void
{
    if (!in_array($direction, [-1, 1], true)) {
        throw new InvalidArgumentException('Arah penyesuaian stok tidak valid.');
    }

    $menuStmt = mysqli_prepare($conn, "SELECT m.produk_jadi_id, b.nama_bahan, b.stok, b.jenis
        FROM menu_nasi_bakar m
        LEFT JOIN bahan_baku b ON b.id = m.produk_jadi_id
        WHERE m.id = ? FOR UPDATE");
    $updateStmt = mysqli_prepare($conn, 'UPDATE bahan_baku SET stok = stok + ? WHERE id = ?');

    if (!$menuStmt || !$updateStmt) {
        throw new RuntimeException('Struktur stok produk jadi belum tersedia. Jalankan migrasi database produksi terlebih dahulu.');
    }

    foreach ($items as $item) {
        $menuId = (int) ($item['menu_id'] ?? 0);
        $qty = (float) ($item['qty'] ?? 0);
        if ($menuId <= 0 || $qty <= 0) {
            continue;
        }

        mysqli_stmt_bind_param($menuStmt, 'i', $menuId);
        $product = fetch_one_stmt($menuStmt);

        // Menu tanpa produk jadi tetap dapat dijual untuk menjaga kompatibilitas data lama.
        if (!$product || empty($product['produk_jadi_id'])) {
            continue;
        }

        if ($product['jenis'] !== 'jadi') {
            throw new RuntimeException('Produk jadi untuk menu tidak valid.');
        }

        if ($direction < 0 && (float) $product['stok'] < $qty) {
            throw new RuntimeException('Stok produk jadi ' . $product['nama_bahan'] . ' tidak mencukupi.');
        }

        $delta = $qty * $direction;
        $productId = (int) $product['produk_jadi_id'];
        mysqli_stmt_bind_param($updateStmt, 'di', $delta, $productId);
        mysqli_stmt_execute($updateStmt);
    }
}

function get_order_stock_items(mysqli $conn, int $pesananId): array
{
    $stmt = mysqli_prepare($conn, 'SELECT menu_id, qty FROM pesanan_detail WHERE pesanan_id = ?');
    if (!$stmt) {
        throw new RuntimeException('Detail pesanan tidak dapat dibaca.');
    }

    mysqli_stmt_bind_param($stmt, 'i', $pesananId);
    return fetch_all_stmt($stmt);
}
