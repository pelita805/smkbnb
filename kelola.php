<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
requireLogin();

$pdo = getDB();

$totalArtikel = (int) $pdo->query("SELECT COUNT(*) FROM artikel")->fetchColumn();
$artikelPublished = (int) $pdo->query("SELECT COUNT(*) FROM artikel WHERE status = 'published'")->fetchColumn();
$totalInfo = (int) $pdo->query("SELECT COUNT(*) FROM informasi WHERE is_active = 1")->fetchColumn();
$totalViews = (int) $pdo->query("SELECT COALESCE(SUM(views), 0) FROM artikel")->fetchColumn();

$artikelList = $pdo->query("SELECT * FROM artikel ORDER BY created_at DESC")->fetchAll();

$pageTitle = 'Kelola Konten & Portal';
require_once __DIR__ . '/includes/header.php';
?>

<section class="page-header">
    <div class="container">
        <span class="section-tag">Portal Terpadu <?= e(SITE_SHORT) ?></span>
        <h1>Kelola Artikel &amp; Informasi Sekolah</h1>
        <p>Tambah, edit, atau hapus artikel serta kelola informasi sekolah langsung dari satu halaman terpadu.</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="stats-grid">
            <div class="stat-card">
                <span class="stat-label">Total Artikel</span>
                <strong class="stat-value"><?= $totalArtikel ?></strong>
                <small><?= $artikelPublished ?> dipublikasikan</small>
            </div>
            <div class="stat-card">
                <span class="stat-label">Informasi Aktif</span>
                <strong class="stat-value"><?= $totalInfo ?></strong>
                <small><a href="informasi.php#pengumuman">Kelola Informasi &rarr;</a></small>
            </div>
            <div class="stat-card">
                <span class="stat-label">Program Keahlian</span>
                <strong class="stat-value">3</strong>
                <small>TKJ • TKR • Akuntansi</small>
            </div>
            <div class="stat-card">
                <span class="stat-label">Total Pembaca Artikel</span>
                <strong class="stat-value"><?= number_format($totalViews) ?></strong>
                <small>Akumulasi tayangan</small>
            </div>
        </div>

        <div class="portal-panel">
            <div class="portal-panel-header">
                <div>
                    <h2>Daftar Semua Artikel</h2>
                    <p>Kelola artikel berita, prestasi, dan kegiatan sekolah.</p>
                </div>
                <div style="display:flex;gap:10px;flex-wrap:wrap;">
                    <a href="informasi.php#pengumuman" class="btn btn-outline">Kelola Informasi</a>
                    <a href="artikel-form.php" class="btn btn-primary">+ Tambah Artikel Baru</a>
                </div>
            </div>

            <div class="table-responsive">
                <table class="portal-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Judul Artikel</th>
                            <th>Kategori</th>
                            <th>Status</th>
                            <th>Dibaca</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($artikelList)): ?>
                            <tr><td colspan="7" style="text-align:center;">Belum ada artikel.</td></tr>
                        <?php else: ?>
                            <?php foreach ($artikelList as $i => $row): ?>
                                <tr>
                                    <td><?= $i + 1 ?></td>
                                    <td>
                                        <strong><?= e($row['judul']) ?></strong><br>
                                        <small class="text-muted"><?= e(excerpt($row['ringkasan'], 65)) ?></small>
                                    </td>
                                    <td><span class="badge-soft"><?= e($row['kategori']) ?></span></td>
                                    <td>
                                        <span class="status-pill <?= $row['status'] === 'published' ? 'published' : 'draft' ?>">
                                            <?= $row['status'] === 'published' ? 'Tayang' : 'Draft' ?>
                                        </span>
                                    </td>
                                    <td><?= (int) $row['views'] ?>x</td>
                                    <td><?= formatTanggal($row['created_at']) ?></td>
                                    <td>
                                        <div class="action-group">
                                            <?php if ($row['status'] === 'published'): ?>
                                                <a href="artikel-detail.php?slug=<?= urlencode($row['slug']) ?>" class="btn btn-outline btn-xs">Lihat</a>
                                            <?php endif; ?>
                                            <a href="artikel-form.php?id=<?= (int) $row['id'] ?>" class="btn btn-primary btn-xs">Edit</a>
                                            <form method="POST" action="artikel-hapus.php" onsubmit="return confirm('Yakin ingin menghapus artikel ini?');" style="display:inline;">
                                                <input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">
                                                <input type="hidden" name="id" value="<?= (int) $row['id'] ?>">
                                                <button type="submit" class="btn btn-danger btn-xs">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
