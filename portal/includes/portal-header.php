<?php
$portalPage = basename($_SERVER['PHP_SELF']);
$portalTitle = isset($portalTitle) ? $portalTitle . ' — Portal ' . SITE_SHORT : 'Portal ' . SITE_NAME;
$flash = getFlash();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($portalTitle) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="portal-body">
    <div class="portal-layout">
        <!-- Sidebar -->
        <aside class="portal-sidebar" id="portalSidebar">
            <div class="portal-brand">
                <div class="brand-logo"><span>BNB</span></div>
                <div>
                    <strong>Portal <?= e(SITE_SHORT) ?></strong>
                    <small>Panel Manajemen</small>
                </div>
            </div>

            <nav class="portal-nav">
                <a href="index.php" class="portal-nav-link <?= $portalPage === 'index.php' ? 'active' : '' ?>">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                    Dashboard
                </a>
                <a href="artikel.php" class="portal-nav-link <?= ($portalPage === 'artikel.php' || $portalPage === 'artikel-form.php') ? 'active' : '' ?>">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                    Kelola Artikel
                </a>
                <a href="informasi.php" class="portal-nav-link <?= $portalPage === 'informasi.php' ? 'active' : '' ?>">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                    Kelola Informasi
                </a>
                <div class="portal-nav-divider"></div>
                <a href="../index.php" class="portal-nav-link" target="_blank">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                    Lihat Website
                </a>
                <a href="logout.php" class="portal-nav-link text-danger">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                    Keluar
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="portal-main">
            <header class="portal-topbar">
                <div class="portal-topbar-left">
                    <button class="portal-menu-btn" id="portalMenuBtn" aria-label="Toggle Sidebar">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
                    </button>
                    <h1 class="portal-page-title"><?= e($pageHeading ?? 'Dashboard') ?></h1>
                </div>
                <div class="portal-user">
                    <div class="portal-avatar"><?= strtoupper(substr($_SESSION['user_nama'] ?? 'A', 0, 1)) ?></div>
                    <div>
                        <strong><?= e($_SESSION['user_nama'] ?? 'Admin') ?></strong>
                        <small><?= e($_SESSION['user_role'] ?? 'admin') ?></small>
                    </div>
                </div>
            </header>

            <main class="portal-content">
                <?php if ($flash): ?>
                    <div class="alert alert-<?= e($flash['type']) ?>">
                        <?= e($flash['message']) ?>
                    </div>
                <?php endif; ?>
