<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$pdo = getDB();

// Handle Tambah / Hapus Informasi langsung jika sudah login portal
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isLoggedIn()) {
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

    header('Location: informasi.php#pengumuman');
    exit;
}

$kategoriFilter = $_GET['kategori'] ?? '';

if ($kategoriFilter !== '') {
    $stmt = $pdo->prepare("SELECT * FROM informasi WHERE is_active = 1 AND kategori = ? ORDER BY tanggal DESC, id DESC");
    $stmt->execute([$kategoriFilter]);
    $infoList = $stmt->fetchAll();
} else {
    $infoList = $pdo->query("SELECT * FROM informasi WHERE is_active = 1 ORDER BY tanggal DESC, id DESC")->fetchAll();
}

$pageTitle = 'Informasi Sekolah';
require_once __DIR__ . '/includes/header.php';
?>

<section class="page-header">
    <div class="container">
        <span class="section-tag">Pusat Informasi</span>
        <h1>Informasi &amp; Profil <?= e(SITE_NAME) ?></h1>
        <p>Profil sekolah, visi misi, pengumuman resmi, agenda akademik, serta layanan kontak sekolah.</p>
    </div>
</section>

<!-- Profil Singkat & Foto Gedung -->
<section class="section">
    <div class="container">
        <div class="card school-showcase-card" style="margin-bottom: 24px;">
            <div class="school-showcase-grid">
                <div class="school-showcase-photo">
                    <img src="assets/img/gedung-smkbnb.jpg" alt="Gedung <?= e(SITE_NAME) ?>">
                </div>
                <div>
                    <span class="section-tag">Identitas Sekolah</span>
                    <h2>Profil Singkat <?= e(SITE_SHORT) ?></h2>
                    <p>
                        <strong><?= e(SITE_NAME) ?></strong> adalah sekolah menengah kejuruan swasta di bawah naungan Yayasan Bangun Nusa Bangsa yang berlokasi di Karadenan, Cibinong, Kabupaten Bogor, Jawa Barat.
                    </p>
                    <table class="info-table">
                        <tr><th>Nama Sekolah</th><td><?= e(SITE_NAME) ?></td></tr>
                        <tr><th>NPSN</th><td><?= e(SITE_NPSN) ?></td></tr>
                        <tr><th>Akreditasi</th><td><strong><?= e(SITE_ACCREDITATION) ?></strong></td></tr>
                        <tr><th>Program Keahlian</th><td>TKJ, TKR, Akuntansi</td></tr>
                        <tr><th>Alamat</th><td><?= e(SITE_ADDRESS) ?></td></tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="card">
            <span class="section-tag">Arah Pendidikan</span>
            <h2>Visi &amp; Misi</h2>
            <div class="grid-2" style="margin-top: 12px;">
                <div class="visi-box">
                    <strong>Visi:</strong>
                    <p>"Terwujudnya lulusan SMK Bangun Nusa Bangsa yang berkarakter mulia, kompeten secara profesional, adaptif terhadap teknologi, dan berdaya saing global."</p>
                </div>
                <div>
                    <strong style="display:block;margin-bottom:8px;">Misi:</strong>
                    <ul class="check-list">
                        <li>Menyelenggarakan pembelajaran berbasis praktik dan standar dunia industri.</li>
                        <li>Mengembangkan keterampilan siswa di bidang TKJ, TKR, dan Akuntansi.</li>
                        <li>Membentuk karakter disiplin, jujur, dan beretos kerja tinggi.</li>
                        <li>Memperluas kemitraan industri untuk penyaluran PKL dan lulusan (BKK).</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Pengumuman & Agenda -->
<section class="section section-alt" id="pengumuman">
    <div class="container">
        <div class="section-header-row">
            <div>
                <span class="section-tag">Update Terkini</span>
                <h2>Pengumuman, Agenda &amp; Layanan</h2>
            </div>
            <?php if (!isLoggedIn()): ?>
                <a href="login.php" class="btn btn-outline btn-sm">Masuk Portal untuk Kelola</a>
            <?php endif; ?>
        </div>

        <?php if (isLoggedIn()): ?>
            <div class="card admin-inline-panel" style="margin-bottom: 24px;">
                <h3 style="margin-bottom: 14px;">+ Tambah Informasi Baru</h3>
                <form method="POST" action="informasi.php">
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
                            <input type="text" id="lokasi" name="lokasi" value="Kampus SMK BNB, Karadenan, Cibinong">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="isi">Isi Informasi *</label>
                        <textarea id="isi" name="isi" rows="2" required placeholder="Detail pengumuman atau agenda..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm">Simpan Informasi</button>
                </form>
            </div>
        <?php endif; ?>

        <div class="filter-pills" style="margin-bottom: 24px;">
            <a href="informasi.php#pengumuman" class="pill <?= $kategoriFilter === '' ? 'active' : '' ?>">Semua</a>
            <?php foreach (['Pengumuman', 'Agenda', 'Beasiswa', 'Layanan'] as $kat): ?>
                <a href="informasi.php?kategori=<?= urlencode($kat) ?>#pengumuman"
                   class="pill <?= $kategoriFilter === $kat ? 'active' : '' ?>">
                    <?= e($kat) ?>
                </a>
            <?php endforeach; ?>
        </div>

        <div class="grid-2">
            <?php if (empty($infoList)): ?>
                <div class="empty-state">Tidak ada informasi untuk kategori ini.</div>
            <?php else: ?>
                <?php foreach ($infoList as $info): ?>
                    <div class="card info-card">
                        <div class="info-meta">
                            <span class="info-category badge-<?= strtolower(e($info['kategori'])) ?>"><?= e($info['kategori']) ?></span>
                            <span class="info-date"><?= formatTanggal($info['tanggal']) ?></span>
                        </div>
                        <h3><?= e($info['judul']) ?></h3>
                        <p><?= nl2br(e($info['isi'])) ?></p>
                        <div class="info-card-footer">
                            <div class="info-location">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                <?= e($info['lokasi']) ?>
                            </div>
                            <?php if (isLoggedIn()): ?>
                                <form method="POST" action="informasi.php" onsubmit="return confirm('Hapus informasi ini?');">
                                    <input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">
                                    <input type="hidden" name="action" value="hapus">
                                    <input type="hidden" name="id" value="<?= (int) $info['id'] ?>">
                                    <button type="submit" class="btn btn-danger btn-xs">Hapus</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Kontak Sekolah -->
<section class="section" id="kontak">
    <div class="container">
        <div class="card contact-card">
            <div>
                <span class="section-tag">Hubungi Kami</span>
                <h2>Sekretariat &amp; Informasi PPDB</h2>
                <p>Kunjungi kampus kami atau hubungi kontak resmi di bawah ini pada hari kerja (Senin - Sabtu, 07.30 - 15.00 WIB).</p>
            </div>
            <div class="contact-grid">
                <div class="contact-item">
                    <strong>Alamat Kampus</strong>
                    <span><?= e(SITE_ADDRESS) ?></span>
                </div>
                <div class="contact-item">
                    <strong>Telepon</strong>
                    <span><?= e(SITE_PHONE) ?></span>
                </div>
                <div class="contact-item">
                    <strong>Email Resmi</strong>
                    <span><?= e(SITE_EMAIL) ?></span>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
