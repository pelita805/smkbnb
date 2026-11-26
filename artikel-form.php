<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
requireLogin();

$pdo = getDB();
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$isEdit = $id > 0;

$artikel = [
    'judul' => '',
    'kategori' => 'Berita Sekolah',
    'ringkasan' => '',
    'konten' => '',
    'penulis' => $_SESSION['user_nama'] ?? 'Admin SMK BNB',
    'status' => 'published',
    'gambar' => null,
];

if ($isEdit) {
    $stmt = $pdo->prepare("SELECT * FROM artikel WHERE id = ?");
    $stmt->execute([$id]);
    $existing = $stmt->fetch();
    if (!$existing) {
        setFlash('error', 'Artikel tidak ditemukan.');
        header('Location: kelola.php');
        exit;
    }
    $artikel = $existing;
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCsrf($_POST['csrf_token'] ?? null)) {
        $errors[] = 'Token keamanan tidak valid. Silakan coba lagi.';
    } else {
        $judul = trim($_POST['judul'] ?? '');
        $kategori = trim($_POST['kategori'] ?? 'Berita Sekolah');
        $ringkasan = trim($_POST['ringkasan'] ?? '');
        $konten = trim($_POST['konten'] ?? '');
        $penulis = trim($_POST['penulis'] ?? 'Admin SMK BNB');
        $status = ($_POST['status'] ?? 'published') === 'draft' ? 'draft' : 'published';

        if ($judul === '') $errors[] = 'Judul artikel wajib diisi.';
        if ($ringkasan === '') $errors[] = 'Ringkasan artikel wajib diisi.';
        if ($konten === '') $errors[] = 'Konten artikel wajib diisi.';

        $gambarNama = $artikel['gambar'];
        if (empty($errors) && !empty($_FILES['gambar']['name'])) {
            try {
                $uploaded = uploadGambar($_FILES['gambar'], __DIR__ . '/uploads/artikel');
                if ($uploaded) {
                    if ($isEdit && !empty($artikel['gambar'])) {
                        $oldPath = __DIR__ . '/uploads/artikel/' . $artikel['gambar'];
                        if (file_exists($oldPath)) {
                            @unlink($oldPath);
                        }
                    }
                    $gambarNama = $uploaded;
                }
            } catch (RuntimeException $ex) {
                $errors[] = $ex->getMessage();
            }
        }

        if (empty($errors)) {
            $baseSlug = makeSlug($judul);
            $slug = $baseSlug;
            $counter = 1;
            while (true) {
                $check = $pdo->prepare("SELECT id FROM artikel WHERE slug = ? AND id != ?");
                $check->execute([$slug, $id]);
                if (!$check->fetch()) {
                    break;
                }
                $slug = $baseSlug . '-' . $counter++;
            }

            if ($isEdit) {
                $stmt = $pdo->prepare("UPDATE artikel SET judul = ?, slug = ?, kategori = ?, ringkasan = ?, konten = ?, gambar = ?, penulis = ?, status = ? WHERE id = ?");
                $stmt->execute([$judul, $slug, $kategori, $ringkasan, $konten, $gambarNama, $penulis, $status, $id]);
                setFlash('success', 'Artikel berhasil diperbarui.');
            } else {
                $stmt = $pdo->prepare("INSERT INTO artikel (judul, slug, kategori, ringkasan, konten, gambar, penulis, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$judul, $slug, $kategori, $ringkasan, $konten, $gambarNama, $penulis, $status]);
                setFlash('success', 'Artikel baru berhasil ditambahkan.');
            }

            header('Location: kelola.php');
            exit;
        }

        $artikel = array_merge($artikel, [
            'judul' => $judul,
            'kategori' => $kategori,
            'ringkasan' => $ringkasan,
            'konten' => $konten,
            'penulis' => $penulis,
            'status' => $status,
        ]);
    }
}

$pageTitle = $isEdit ? 'Edit Artikel' : 'Tambah Artikel';
require_once __DIR__ . '/includes/header.php';
?>

<section class="page-header">
    <div class="container">
        <span class="section-tag">Portal Artikel</span>
        <h1><?= e($isEdit ? 'Edit Artikel' : 'Tambah Artikel Baru') ?></h1>
        <p>Tulis atau perbarui artikel sekolah untuk dipublikasikan di website <?= e(SITE_NAME) ?>.</p>
    </div>
</section>

<section class="section">
    <div class="container" style="max-width: 900px;">
        <div class="portal-panel">
            <div class="portal-panel-header">
                <h2>Formulir Artikel</h2>
                <a href="kelola.php" class="btn btn-outline btn-sm">&larr; Kembali ke Kelola Konten</a>
            </div>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-error">
                    <ul style="margin:0;padding-left:18px;">
                        <?php foreach ($errors as $err): ?>
                            <li><?= e($err) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="POST" action="artikel-form.php<?= $isEdit ? '?id=' . $id : '' ?>" enctype="multipart/form-data" class="portal-form">
                <input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">

                <div class="form-grid-2">
                    <div class="form-group">
                        <label for="judul">Judul Artikel *</label>
                        <input type="text" id="judul" name="judul" value="<?= e($artikel['judul']) ?>" required placeholder="Contoh: Siswa TKJ Raih Juara LKS">
                    </div>

                    <div class="form-group">
                        <label for="kategori">Kategori</label>
                        <select id="kategori" name="kategori">
                            <?php foreach (['Berita Sekolah', 'Pengumuman', 'Prestasi', 'Kegiatan', 'Akademik', 'Karya Siswa'] as $kat): ?>
                                <option value="<?= e($kat) ?>" <?= $artikel['kategori'] === $kat ? 'selected' : '' ?>><?= e($kat) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-grid-2">
                    <div class="form-group">
                        <label for="penulis">Nama Penulis</label>
                        <input type="text" id="penulis" name="penulis" value="<?= e($artikel['penulis']) ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="status">Status Publikasi</label>
                        <select id="status" name="status">
                            <option value="published" <?= $artikel['status'] === 'published' ? 'selected' : '' ?>>Tayangkan (Published)</option>
                            <option value="draft" <?= $artikel['status'] === 'draft' ? 'selected' : '' ?>>Simpan sebagai Draft</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="ringkasan">Ringkasan Singkat *</label>
                    <textarea id="ringkasan" name="ringkasan" rows="2" required placeholder="Ringkasan singkat yang tampil di daftar artikel..."><?= e($artikel['ringkasan']) ?></textarea>
                </div>

                <div class="form-group">
                    <label for="konten">Isi Artikel Lengkap *</label>
                    <textarea id="konten" name="konten" rows="10" required placeholder="Tulis isi artikel lengkap di sini..."><?= e($artikel['konten']) ?></textarea>
                </div>

                <div class="form-group">
                    <label for="gambar">Gambar Sampul (Opsional, JPG/PNG/WEBP maks. 2MB)</label>
                    <input type="file" id="gambar" name="gambar" accept=".jpg,.jpeg,.png,.webp">
                    <?php if (!empty($artikel['gambar'])): ?>
                        <div style="margin-top:8px;">
                            <small>Gambar saat ini: <strong><?= e($artikel['gambar']) ?></strong></small>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <?= $isEdit ? 'Simpan Perubahan' : 'Publikasikan Artikel' ?>
                    </button>
                    <a href="kelola.php" class="btn btn-outline btn-lg">Batal</a>
                </div>
            </form>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
