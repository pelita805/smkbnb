<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
requireLogin();

$pdo = getDB();

// Handle Tambah / Hapus Informasi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCsrf($_POST['csrf_token'] ?? null)) {
        setFlash('error', 'Token keamanan tidak valid.');
        header('Location: informasi.php');
        exit;
    }

    $action = $_POST['action'] ?? '';
    if ($action === 'tambah') {
        $judul = trim($_POST['judul'] ?? '');
        $kategori = $_POST['kategori'] ?? 'Pengumuman';
        $isi = trim($_POST['isi'] ?? '');
        $tanggal = $_POST['tanggal'] ?? date('Y-m-d');
        $lokasi = trim($_POST['lokasi'] ?? SITE_NAME);

        if ($judul !== '' && $isi !== '') {
            $stmt = $pdo->prepare("INSERT INTO informasi (judul, kategori, isi, tanggal, lokasi, is_active) VALUES (?, ?, ?, ?, ?, 1)");
            $stmt->execute([$judul, $kategori, $isi, $tanggal, $lokasi]);
            setFlash('success', 'Informasi sekolah berhasil ditambahkan.');
        } else {
            setFlash('error', 'Judul dan isi informasi wajib diisi.');
        }
    } elseif ($action === 'hapus') {
        $id = (int) ($_POST['id'] ?? 0);
        $pdo->prepare("DELETE FROM informasi WHERE id = ?")->execute([$id]);
        setFlash('success', 'Informasi berhasil dihapus.');
    }

    header('Location: informasi.php');
    exit;
}

$infoList = $pdo->query("SELECT * FROM informasi ORDER BY tanggal DESC, id DESC")->fetchAll();

$portalTitle = 'Kelola Informasi';
$pageHeading = 'Manajemen Informasi Sekolah';
require_once __DIR__ . '/includes/portal-header.php';
?>

<div class="portal-panel" style="margin-bottom:24px;">
    <div class="portal-panel-header">
        <h2>Tambah Informasi / Pengumuman</h2>
    </div>
    <form method="POST" action="informasi.php" class="portal-form">
        <input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">
        <input type="hidden" name="action" value="tambah">

        <div class="form-grid-2">
            <div class="form-group">
                <label for="judul">Judul Informasi *</label>
                <input type="text" id="judul" name="judul" required placeholder="Contoh: Jadwal Pembagian Rapor">
            </div>
            <div class="form-group">
                <label for="kategori">Kategori</label>
                <select id="kategori" name="kategori">
                    <option value="Pengumuman">Pengumuman</option>
                    <option value="Agenda">Agenda</option>
                    <option value="Beasiswa">Beasiswa</option>
                    <option value="Layanan">Layanan</option>
                </select>
            </div>
        </div>

        <div class="form-grid-2">
            <div class="form-group">
                <label for="tanggal">Tanggal</label>
                <input type="date" id="tanggal" name="tanggal" value="<?= date('Y-m-d') ?>" required>
            </div>
            <div class="form-group">
                <label for="lokasi">Lokasi</label>
                <input type="text" id="lokasi" name="lokasi" value="Kampus SMK BNB, Cibinong">
            </div>
        </div>

        <div class="form-group">
            <label for="isi">Isi Informasi *</label>
            <textarea id="isi" name="isi" rows="3" required placeholder="Detail pengumuman atau agenda..."></textarea>
        </div>

        <button type="submit" class="btn btn-primary">+ Simpan Informasi</button>
    </form>
</div>

<div class="portal-panel">
    <div class="portal-panel-header">
        <h2>Daftar Informasi Aktif</h2>
    </div>
    <div class="table-responsive">
        <table class="portal-table">
            <thead>
                <tr>
                    <th>Judul</th>
                    <th>Kategori</th>
                    <th>Tanggal</th>
                    <th>Lokasi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($infoList)): ?>
                    <tr><td colspan="5" style="text-align:center;">Belum ada informasi.</td></tr>
                <?php else: ?>
                    <?php foreach ($infoList as $row): ?>
                        <tr>
                            <td>
                                <strong><?= e($row['judul']) ?></strong><br>
                                <small class="text-muted"><?= e(excerpt($row['isi'], 80)) ?></small>
                            </td>
                            <td><span class="badge-soft"><?= e($row['kategori']) ?></span></td>
                            <td><?= formatTanggal($row['tanggal']) ?></td>
                            <td><?= e($row['lokasi']) ?></td>
                            <td>
                                <form method="POST" action="informasi.php" onsubmit="return confirm('Hapus informasi ini?');">
                                    <input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">
                                    <input type="hidden" name="action" value="hapus">
                                    <input type="hidden" name="id" value="<?= (int) $row['id'] ?>">
                                    <button type="submit" class="btn btn-danger btn-xs">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/includes/portal-footer.php'; ?>
