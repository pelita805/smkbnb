<?php
$currentPage = basename($_SERVER['PHP_SELF']);
$pageTitle = isset($pageTitle) ? $pageTitle . ' — ' . SITE_NAME : SITE_NAME . ' | Sekolah Pusat Keunggulan';
$flash = getFlash();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Website & Portal Resmi <?= e(SITE_NAME) ?> (SMK BNB) Cibinong, Bogor. Program Keahlian Teknik Jaringan Komputer, Teknik Kendaraan Ringan, dan Akuntansi.">
    <title><?= e($pageTitle) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <!-- Top Info Bar -->
    <div class="topbar">
        <div class="container topbar-inner">
            <div class="topbar-left">
                <span>NPSN: <?= e(SITE_NPSN) ?></span>
                <span class="dot">•</span>
                <span>Akreditasi: <strong><?= e(SITE_ACCREDITATION) ?></strong></span>
                <span class="dot hide-sm">•</span>
                <span class="hide-sm"><?= e(SITE_ADDRESS) ?></span>
            </div>
            <div class="topbar-right">
                <?php if (isLoggedIn()): ?>
                    <span class="topbar-user-badge">
                        Mode Portal: <strong><?= e($_SESSION['user_nama'] ?? 'Admin') ?></strong>
                    </span>
                <?php else: ?>
                    <span>Portal Terpadu <?= e(SITE_SHORT) ?></span>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Main Navbar (Website & Portal Terpadu) -->
    <header class="navbar" id="navbar">
        <div class="container navbar-inner">
            <a href="index.php" class="brand">
                <div class="brand-logo">
                    <span>BNB</span>
                </div>
                <div class="brand-text">
                    <strong><?= e(SITE_NAME) ?></strong>
                    <small>Karadenan • Cibinong, Bogor</small>
                </div>
            </a>

            <button class="nav-toggle" id="navToggle" aria-label="Buka Menu">
                <span></span>
                <span></span>
                <span></span>
            </button>

            <nav class="nav-menu" id="navMenu">
                <a href="index.php" class="nav-link <?= $currentPage === 'index.php' ? 'active' : '' ?>">Beranda</a>
                <a href="jurusan.php" class="nav-link <?= $currentPage === 'jurusan.php' ? 'active' : '' ?>">Jurusan</a>
                <a href="informasi.php" class="nav-link <?= $currentPage === 'informasi.php' ? 'active' : '' ?>">Informasi</a>
                <a href="artikel.php" class="nav-link <?= in_array($currentPage, ['artikel.php', 'artikel-detail.php', 'artikel-form.php'], true) ? 'active' : '' ?>">Artikel</a>

                <?php if (isLoggedIn()): ?>
                    <a href="kelola.php" class="nav-link <?= $currentPage === 'kelola.php' ? 'active' : '' ?>">Kelola Konten</a>
                    <a href="logout.php" class="btn btn-outline btn-sm nav-cta">Keluar</a>
                <?php else: ?>
                    <a href="login.php" class="btn btn-primary btn-sm nav-cta <?= $currentPage === 'login.php' ? 'active' : '' ?>">
                        Masuk Portal
                    </a>
                <?php endif; ?>
            </nav>
        </div>
    </header>

    <?php if ($flash): ?>
        <div class="container" style="margin-top: 18px;">
            <div class="alert alert-<?= e($flash['type']) ?>" style="margin-bottom: 0;">
                <?= e($flash['message']) ?>
            </div>
        </div>
    <?php endif; ?>
