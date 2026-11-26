<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$pdo = getDB();

// Ambil data jurusan
$jurusanList = $pdo->query("SELECT * FROM jurusan ORDER BY id ASC")->fetchAll();

// Ambil 4 informasi aktif terbaru
$infoList = $pdo->query("SELECT * FROM informasi WHERE is_active = 1 ORDER BY tanggal DESC, id DESC LIMIT 4")->fetchAll();

// Ambil 3 artikel terbaru yang dipublikasikan
$artikelList = $pdo->query("SELECT * FROM artikel WHERE status = 'published' ORDER BY created_at DESC LIMIT 3")->fetchAll();

$pageTitle = 'Beranda';
require_once __DIR__ . '/includes/header.php';
?>

<!-- Hero Section dengan Foto Kampus -->
<section class="hero">
    <div class="hero-bg-shapes">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
    </div>
    <div class="container hero-inner">
        <div class="hero-content">
            <div class="hero-badge">
                <span class="pulse-dot"></span>
                Sekolah Kejuruan Terakreditasi <?= e(SITE_ACCREDITATION) ?> • Cibinong
            </div>
            <h1>Wujudkan Masa Depan Unggul Bersama <span><?= e(SITE_NAME) ?></span></h1>
            <p class="hero-subtitle">
                <?= e(SITE_TAGLINE) ?>. Memadukan kurikulum industri modern dengan praktik nyata di bidang Teknologi Jaringan, Otomotif, dan Akuntansi.
            </p>
            <div class="hero-actions">
                <a href="jurusan.php" class="btn btn-primary btn-lg">
                    Jelajahi Jurusan
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </a>
                <a href="informasi.php" class="btn btn-outline-light btn-lg">Info Sekolah &amp; PPDB</a>
            </div>
            <div class="hero-stats">
                <div class="stat-item">
                    <strong>3</strong>
                    <span>Program Keahlian</span>
                </div>
                <div class="stat-divider"></div>
                <div class="stat-item">
                    <strong>A</strong>
                    <span>Akreditasi Sekolah</span>
                </div>
                <div class="stat-divider"></div>
                <div class="stat-item">
                    <strong>50+</strong>
                    <span>Mitra Industri</span>
                </div>
            </div>
        </div>

        <div class="hero-visual">
            <div class="hero-photo-card">
                <div class="hero-photo-wrap">
                    <img src="assets/img/gedung-smkbnb.jpg" alt="Gedung Kampus <?= e(SITE_NAME) ?>">
                    <span class="hero-photo-badge">Yayasan Bangun Nusa Bangsa</span>
                </div>
                <div class="hero-photo-caption">
                    <div>
                        <strong>Kampus <?= e(SITE_NAME) ?></strong>
                        <small>Karadenan, Cibinong — Kab. Bogor</small>
                    </div>
                    <a href="<?= isLoggedIn() ? 'kelola.php' : 'artikel.php' ?>" class="btn btn-primary btn-xs">
                        <?= isLoggedIn() ? 'Kelola Portal' : 'Baca Artikel' ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Program Keahlian / Jurusan -->
<section class="section" id="jurusan">
    <div class="container">
        <div class="section-header">
            <span class="section-tag">Program Keahlian</span>
            <h2>Jurusan Unggulan Siap Kerja</h2>
            <p>Pilih kompetensi keahlian sesuai minat dan bakat untuk masa depan karier profesional maupun wirausaha.</p>
        </div>

        <div class="grid-3">
            <?php foreach ($jurusanList as $j): ?>
                <article class="card jurusan-card">
                    <div class="jurusan-icon" style="--accent: <?= e($j['warna']) ?>;">
                        <?= getJurusanIcon($j['ikon']) ?>
                    </div>
                    <div class="jurusan-badge" style="color: <?= e($j['warna']) ?>; background: <?= e($j['warna']) ?>15;">
                        <?= e($j['singkatan']) ?>
                    </div>
                    <h3><?= e($j['nama']) ?></h3>
                    <p><?= e($j['deskripsi_singkat']) ?></p>
                    <a href="jurusan.php?kode=<?= urlencode($j['kode']) ?>" class="card-link">
                        Lihat Detail Kompetensi
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </a>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Informasi & Pengumuman Sekolah -->
<section class="section section-alt">
    <div class="container">
        <div class="section-header-row">
            <div>
                <span class="section-tag">Papan Informasi</span>
                <h2>Informasi &amp; Agenda Sekolah</h2>
            </div>
            <a href="informasi.php" class="btn btn-outline">Semua Informasi &rarr;</a>
        </div>

        <div class="grid-2">
            <?php if (empty($infoList)): ?>
                <div class="empty-state">Belum ada informasi terbaru saat ini.</div>
            <?php else: ?>
                <?php foreach ($infoList as $info): ?>
                    <div class="card info-card">
                        <div class="info-meta">
                            <span class="info-category badge-<?= strtolower(e($info['kategori'])) ?>"><?= e($info['kategori']) ?></span>
                            <span class="info-date"><?= formatTanggal($info['tanggal']) ?></span>
                        </div>
                        <h3><?= e($info['judul']) ?></h3>
                        <p><?= e($info['isi']) ?></p>
                        <div class="info-location">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                            <?= e($info['lokasi']) ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Artikel & Berita Terbaru -->
<section class="section">
    <div class="container">
        <div class="section-header-row">
            <div>
                <span class="section-tag">Berita &amp; Karya</span>
                <h2>Artikel Terbaru Sekolah</h2>
            </div>
            <div style="display:flex;gap:10px;flex-wrap:wrap;">
                <?php if (isLoggedIn()): ?>
                    <a href="artikel-form.php" class="btn btn-primary">+ Tambah Artikel</a>
                <?php endif; ?>
                <a href="artikel.php" class="btn btn-outline">Lihat Semua Artikel &rarr;</a>
            </div>
        </div>

        <div class="grid-3">
            <?php if (empty($artikelList)): ?>
                <div class="empty-state">Belum ada artikel yang dipublikasikan.</div>
            <?php else: ?>
                <?php foreach ($artikelList as $art): ?>
                    <article class="card article-card">
                        <div class="article-thumb">
                            <?php if (!empty($art['gambar']) && file_exists(__DIR__ . '/uploads/artikel/' . $art['gambar'])): ?>
                                <img src="uploads/artikel/<?= e($art['gambar']) ?>" alt="<?= e($art['judul']) ?>">
                            <?php else: ?>
                                <div class="article-thumb-placeholder">
                                    <span><?= e($art['kategori']) ?></span>
                                </div>
                            <?php endif; ?>
                            <span class="article-cat"><?= e($art['kategori']) ?></span>
                        </div>
                        <div class="article-body">
                            <div class="article-meta">
                                <span><?= formatTanggal($art['created_at']) ?></span>
                                <span>•</span>
                                <span><?= e($art['penulis']) ?></span>
                            </div>
                            <h3>
                                <a href="artikel-detail.php?slug=<?= urlencode($art['slug']) ?>">
                                    <?= e($art['judul']) ?>
                                </a>
                            </h3>
                            <p><?= e(excerpt($art['ringkasan'], 110)) ?></p>
                            <a href="artikel-detail.php?slug=<?= urlencode($art['slug']) ?>" class="card-link">
                                Baca Selengkapnya &rarr;
                            </a>
                        </div>
                    </article>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- CTA Terpadu -->
<section class="cta-section">
    <div class="container">
        <div class="cta-box">
            <div class="cta-text">
                <h2>Portal Artikel &amp; Informasi Terintegrasi dalam Satu Website</h2>
                <p>Guru dan admin sekolah dapat langsung menambah, mengedit, dan menghapus artikel serta informasi sekolah dari menu yang sama tanpa berpindah situs.</p>
            </div>
            <div class="cta-actions">
                <?php if (isLoggedIn()): ?>
                    <a href="kelola.php" class="btn btn-light btn-lg">Kelola Artikel &amp; Info</a>
                <?php else: ?>
                    <a href="login.php" class="btn btn-light btn-lg">Masuk Portal</a>
                <?php endif; ?>
                <a href="informasi.php#kontak" class="btn btn-outline-light btn-lg">Hubungi Sekolah</a>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
