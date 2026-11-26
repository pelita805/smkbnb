<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$pdo = getDB();
$slug = trim($_GET['slug'] ?? '');

$stmt = $pdo->prepare("SELECT * FROM artikel WHERE slug = ? AND status = 'published'");
$stmt->execute([$slug]);
$artikel = $stmt->fetch();

if (!$artikel) {
    http_response_code(404);
    $pageTitle = 'Artikel Tidak Ditemukan';
    require_once __DIR__ . '/includes/header.php';
    echo '<section class="section"><div class="container"><div class="empty-state"><h2>Artikel Tidak Ditemukan</h2><p>Maaf, artikel yang Anda cari tidak tersedia atau telah dihapus.</p><a href="artikel.php" class="btn btn-primary" style="margin-top:16px;">Kembali ke Daftar Artikel</a></div></div></section>';
    require_once __DIR__ . '/includes/footer.php';
    exit;
}

// Tambah jumlah views
$pdo->prepare("UPDATE artikel SET views = views + 1 WHERE id = ?")->execute([$artikel['id']]);

// Artikel terkait
$stmtRelated = $pdo->prepare("SELECT * FROM artikel WHERE status = 'published' AND id != ? ORDER BY created_at DESC LIMIT 3");
$stmtRelated->execute([$artikel['id']]);
$related = $stmtRelated->fetchAll();

$pageTitle = $artikel['judul'];
require_once __DIR__ . '/includes/header.php';
?>

<section class="section">
    <div class="container article-detail-container">
        <div class="breadcrumb">
            <a href="index.php">Beranda</a> /
            <a href="artikel.php">Artikel</a> /
            <span><?= e($artikel['kategori']) ?></span>
        </div>

        <article class="article-detail-card">
            <span class="info-category badge-pengumuman"><?= e($artikel['kategori']) ?></span>
            <h1><?= e($artikel['judul']) ?></h1>
            <div class="article-detail-meta">
                <span>Oleh <strong><?= e($artikel['penulis']) ?></strong></span>
                <span>•</span>
                <span><?= formatTanggal($artikel['created_at']) ?></span>
                <span>•</span>
                <span><?= (int) $artikel['views'] + 1 ?> kali dibaca</span>
            </div>

            <?php if (!empty($artikel['gambar']) && file_exists(__DIR__ . '/uploads/artikel/' . $artikel['gambar'])): ?>
                <div class="article-detail-image">
                    <img src="uploads/artikel/<?= e($artikel['gambar']) ?>" alt="<?= e($artikel['judul']) ?>">
                </div>
            <?php endif; ?>

            <div class="article-lead">
                <?= e($artikel['ringkasan']) ?>
            </div>

            <div class="article-content">
                <?= nl2br(e($artikel['konten'])) ?>
            </div>
        </article>

        <?php if (!empty($related)): ?>
            <div class="related-section">
                <h3>Artikel Lainnya</h3>
                <div class="grid-3">
                    <?php foreach ($related as $rel): ?>
                        <div class="card">
                            <span class="article-meta"><?= formatTanggal($rel['created_at']) ?></span>
                            <h4 style="margin:8px 0;">
                                <a href="artikel-detail.php?slug=<?= urlencode($rel['slug']) ?>"><?= e($rel['judul']) ?></a>
                            </h4>
                            <a href="artikel-detail.php?slug=<?= urlencode($rel['slug']) ?>" class="card-link">Baca &rarr;</a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
