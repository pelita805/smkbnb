<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$pdo = getDB();
$jurusanList = $pdo->query("SELECT * FROM jurusan ORDER BY id ASC")->fetchAll();

$selectedKode = $_GET['kode'] ?? '';
$selectedJurusan = null;
if ($selectedKode) {
    $stmt = $pdo->prepare("SELECT * FROM jurusan WHERE kode = ?");
    $stmt->execute([$selectedKode]);
    $selectedJurusan = $stmt->fetch();
}

$pageTitle = $selectedJurusan ? $selectedJurusan['nama'] : 'Program Keahlian (Jurusan)';
require_once __DIR__ . '/includes/header.php';
?>

<section class="page-header">
    <div class="container">
        <span class="section-tag">Kompetensi Keahlian</span>
        <h1>Program Keahlian <?= e(SITE_SHORT) ?></h1>
        <p>Tiga jurusan unggulan berbasis standar industri untuk mempersiapkan lulusan kompeten dan siap kerja.</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <!-- Tab Filter Jurusan -->
        <div class="filter-pills" style="margin-bottom: 32px;">
            <a href="jurusan.php" class="pill <?= !$selectedJurusan ? 'active' : '' ?>">Semua Jurusan</a>
            <?php foreach ($jurusanList as $j): ?>
                <a href="jurusan.php?kode=<?= urlencode($j['kode']) ?>"
                   class="pill <?= ($selectedJurusan && $selectedJurusan['kode'] === $j['kode']) ? 'active' : '' ?>">
                    <?= e($j['singkatan']) ?> — <?= e($j['nama']) ?>
                </a>
            <?php endforeach; ?>
        </div>

        <?php
        $displayList = $selectedJurusan ? [$selectedJurusan] : $jurusanList;
        foreach ($displayList as $j):
            $kompetensi = array_filter(explode('|', $j['kompetensi']));
            $karir = array_filter(explode('|', $j['prospek_karir']));
            $fasilitas = array_filter(explode('|', $j['fasilitas']));
        ?>
            <div class="card jurusan-detail-card" style="margin-bottom: 28px; border-top: 4px solid <?= e($j['warna']) ?>;">
                <div class="jurusan-detail-header">
                    <div class="jurusan-icon lg" style="--accent: <?= e($j['warna']) ?>;">
                        <?= getJurusanIcon($j['ikon']) ?>
                    </div>
                    <div>
                        <span class="jurusan-badge" style="color: <?= e($j['warna']) ?>; background: <?= e($j['warna']) ?>15;">
                            Program Keahlian <?= e($j['singkatan']) ?>
                        </span>
                        <h2 style="margin-top: 8px;"><?= e($j['nama']) ?></h2>
                        <p class="lead-text"><?= e($j['deskripsi_lengkap']) ?></p>
                    </div>
                </div>

                <div class="jurusan-detail-grid">
                    <div class="detail-box">
                        <h4>Kompetensi yang Dipelajari</h4>
                        <ul class="check-list">
                            <?php foreach ($kompetensi as $item): ?>
                                <li><?= e(trim($item)) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>

                    <div class="detail-box">
                        <h4>Prospek Karir Lulusan</h4>
                        <ul class="check-list">
                            <?php foreach ($karir as $item): ?>
                                <li><?= e(trim($item)) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>

                    <div class="detail-box">
                        <h4>Fasilitas Praktik</h4>
                        <ul class="check-list">
                            <?php foreach ($fasilitas as $item): ?>
                                <li><?= e(trim($item)) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
