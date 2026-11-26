<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
requireLogin();

$pdo = getDB();
$q = trim($_GET['q'] ?? '');

if ($q !== '') {
    $stmt = $pdo->prepare("SELECT * FROM artikel WHERE judul LIKE ? OR kategori LIKE ? ORDER BY created_at DESC");
    $like = '%' . $q . '%';
    $stmt->execute([$like, $like]);
    $artikelList = $stmt->fetchAll();
} else {
    $artikelList = $pdo->query("SELECT * FROM artikel ORDER BY created_at DESC")->fetchAll();
}

$portalTitle = 'Kelola Artikel';
$pageHeading = 'Manajemen Artikel';
require_once __DIR__ . '/includes/portal-header.php';
?>

<div class="portal-panel">
    <div class="portal-panel-header">
        <form method="GET" action="artikel.php" class="search-form">
            <input type="text" name="q" value="<?= e($q) ?>" placeholder="Cari judul atau kategori...">
            <button type="submit" class="btn btn-outline btn-sm">Cari</button>
        </form>
        <a href="artikel-form.php" class="btn btn-primary">+ Tambah Artikel Baru</a>
    </div>

    <div class="table-responsive">
        <table class="portal-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Judul</th>
                    <th>Kategori</th>
                    <th>Penulis</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($artikelList)): ?>
                    <tr><td colspan="7" style="text-align:center;">Belum ada data artikel.</td></tr>
                <?php else: ?>
                    <?php foreach ($artikelList as $i => $row): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td>
                                <strong><?= e($row['judul']) ?></strong><br>
                                <small class="text-muted"><?= e(excerpt($row['ringkasan'], 70)) ?></small>
                            </td>
                            <td><span class="badge-soft"><?= e($row['kategori']) ?></span></td>
                            <td><?= e($row['penulis']) ?></td>
                            <td>
                                <span class="status-pill <?= $row['status'] === 'published' ? 'published' : 'draft' ?>">
                                    <?= $row['status'] === 'published' ? 'Tayang' : 'Draft' ?>
                                </span>
                            </td>
                            <td><?= formatTanggal($row['created_at']) ?></td>
                            <td>
                                <div class="action-group">
                                    <?php if ($row['status'] === 'published'): ?>
                                        <a href="../artikel-detail.php?slug=<?= urlencode($row['slug']) ?>" target="_blank" class="btn btn-outline btn-xs">Lihat</a>
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

<?php require_once __DIR__ . '/includes/portal-footer.php'; ?>
