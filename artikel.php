<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$pdo = getDB();
$search = trim($_GET['q'] ?? '');
$kategori = trim($_GET['kategori'] ?? '');

// Jika sudah login portal, tampilkan juga artikel draft agar mudah dikelola
$where = isLoggedIn() ? ["1=1"] : ["status = 'published'"];
$params = [];

if ($search !== '') {
    $where[] = "(judul LIKE ? OR ringkasan LIKE ? OR konten LIKE ?)";
    $like = '%' . $search . '%';
    $params[] = $like;
    $params[] = $like;
    $params[] = $like;
}

if ($kategori !== '') {
    $where[] = "kategori = ?";
    $params[] = $kategori;
}

$sql = "SELECT * FROM artikel WHERE " . implode(' AND ', $where) . " ORDER BY created_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$artikelList = $stmt->fetchAll();

// Ambil daftar kategori unik
$kategoriSql = isLoggedIn()
    ? "SELECT DISTINCT kategori FROM artikel ORDER BY kategori ASC"
    : "SELECT DISTINCT kategori FROM artikel WHERE status = 'published' ORDER BY kategori ASC";
$kategoriList = $pdo->query($kategoriSql)->fetchAll(PDO::FETCH_COLUMN);

$pageTitle = 'Artikel & Berita';
require_once __DIR__ . '/includes/header.php';
?>

<section class="page-header">
    <div class="container">
        <span class="section-tag">Jurnal &amp; Berita Sekolah</span>
        <h1>Artikel <?= e(SITE_NAME) ?></h1>
        <p>Kumpulan berita kegiatan, prestasi siswa, pengumuman akademik, dan karya warga sekolah.</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="artikel-toolbar">
            <div class="filter-pills">
                <a href="artikel.php" class="pill <?= $kategori === '' ? 'active' : '' ?>">Semua</a>
                <?php foreach ($kategoriList as $kat): ?>
                    <a href="artikel.php?kategori=<?= urlencode($kat) ?>"
                       class="pill <?= $kategori === $kat ? 'active' : '' ?>">
                        <?= e($kat) ?>
                    </a>
                <?php endforeach; ?>
            </div>

            <div class="toolbar-right-actions">
                <form action="artikel.php" method="GET" class="search-form">
                    <?php if ($kategori !== ''): ?>
                        <input type="hidden" name="kategori" value="<?= e($kategori) ?>">
                    <?php endif; ?>
                    <input type="text" name="q" value="<?= e($search) ?>" placeholder="Cari artikel...">
                    <button type="submit" class="btn btn-outline btn-sm">Cari</button>
                </form>
                <?php if (isLoggedIn()): ?>
                    <a href="artikel-form.php" class="btn btn-primary btn-sm">+ Tambah Artikel</a>
                <?php endif; ?>
            </div>
        </div>

        <?php if (empty($artikelList)): ?>
            <div class="empty-state">
                <p>Artikel tidak ditemukan. Coba kata kunci lain atau reset filter.</p>
                <a href="artikel.php" class="btn btn-outline" style="margin-top:12px;">Tampilkan Semua Artikel</a>
            </div>
        <?php else: ?>
            <div class="grid-3">
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
                            <?php if (isLoggedIn() && $art['status'] === 'draft'): ?>
                                <span class="article-draft-badge">Draft</span>
                            <?php endif; ?>
                        </div>
                        <div class="article-body">
                            <div class="article-meta">
                                <span><?= formatTanggal($art['created_at']) ?></span>
                                <span>•</span>
                                <span><?= (int) $art['views'] ?>x dibaca</span>
                            </div>
                            <h3>
                                <a href="artikel-detail.php?slug=<?= urlencode($art['slug']) ?>">
                                    <?= e($art['judul']) ?>
                                </a>
                            </h3>
                            <p><?= e(excerpt($art['ringkasan'], 120)) ?></p>

                            <div class="article-card-actions">
                                <a href="artikel-detail.php?slug=<?= urlencode($art['slug']) ?>" class="card-link">
                                    Baca Selengkapnya &rarr;
                                </a>
                                <?php if (isLoggedIn()): ?>
                                    <div class="action-group">
                                        <a href="artikel-form.php?id=<?= (int) $art['id'] ?>" class="btn btn-primary btn-xs">Edit</a>
                                        <form method="POST" action="artikel-hapus.php" onsubmit="return confirm('Yakin ingin menghapus artikel ini?');" style="display:inline;">
                                            <input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">
                                            <input type="hidden" name="id" value="<?= (int) $art['id'] ?>">
                                            <button type="submit" class="btn btn-danger btn-xs">Hapus</button>
                                        </form>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
