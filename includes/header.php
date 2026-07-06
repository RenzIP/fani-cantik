<?php
$pageTitle = $pageTitle ?? 'Dashboard';
$basePath = $basePath ?? '../';
$flash = get_flash();
$adminName = $_SESSION['nama'] ?? 'Admin';
$initial = strtoupper(substr($adminName, 0, 1));
$roleLabel = current_role() ?: 'admin';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle); ?> | Inventaris Nasi Bakar</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <?php if (!empty($autoRefreshSeconds)): ?>
        <meta http-equiv="refresh" content="<?= (int) $autoRefreshSeconds; ?>">
    <?php endif; ?>
    <link rel="stylesheet" href="<?= e($basePath); ?>assets/css/dashboard.css?v=<?= filemtime(__DIR__ . '/../assets/css/dashboard.css'); ?>">
</head>
<body>
<div class="app-shell">
    <?php include __DIR__ . '/sidebar.php'; ?>
    <div class="sidebar-backdrop" data-sidebar-close></div>
    <main class="main-content">
        <header class="topbar">
            <button class="hamburger" type="button" data-sidebar-toggle aria-label="Buka menu">
                <span></span><span></span><span></span>
            </button>
            <div>
                <h1><?= e($pageTitle); ?></h1>
                <p>Kelola operasional stok nasi bakar dengan cepat.</p>
            </div>
            <div class="admin-chip">
                <span class="avatar"><?= e($initial); ?></span>
                <div>
                    <strong><?= e($adminName); ?></strong>
                    <small><?= e($roleLabel); ?></small>
                </div>
            </div>
        </header>
        <?php if ($flash): ?>
            <div class="alert alert-<?= e($flash['type']); ?>"><?= e($flash['message']); ?></div>
        <?php endif; ?>
