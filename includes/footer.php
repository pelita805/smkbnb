    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-brand">
                    <div class="brand">
                        <div class="brand-logo"><span>BNB</span></div>
                        <div class="brand-text">
                            <strong style="color:#fff;"><?= e(SITE_NAME) ?></strong>
                            <small style="color:#94a3b8;">Yayasan Bangun Nusa Bangsa</small>
                        </div>
                    </div>
                    <p class="footer-desc">
                        Mencetak generasi unggul, kompeten di bidang teknologi, otomotif, dan keuangan lembaga, serta siap bersaing di dunia industri modern.
                    </p>
                    <div class="footer-badges">
                        <span class="badge-pill">NPSN <?= e(SITE_NPSN) ?></span>
                        <span class="badge-pill">Akreditasi <?= e(SITE_ACCREDITATION) ?></span>
                    </div>
                </div>

                <div>
                    <h4 class="footer-title">Navigasi</h4>
                    <ul class="footer-links">
                        <li><a href="index.php">Beranda</a></li>
                        <li><a href="jurusan.php">Program Keahlian</a></li>
                        <li><a href="informasi.php">Informasi Sekolah</a></li>
                        <li><a href="artikel.php">Artikel &amp; Berita</a></li>
                        <?php if (isLoggedIn()): ?>
                            <li><a href="kelola.php">Kelola Konten</a></li>
                        <?php else: ?>
                            <li><a href="login.php">Masuk Portal</a></li>
                        <?php endif; ?>
                    </ul>
                </div>

                <div>
                    <h4 class="footer-title">Program Keahlian</h4>
                    <ul class="footer-links">
                        <li><a href="jurusan.php?kode=tkj">Teknik Jaringan Komputer (TKJ)</a></li>
                        <li><a href="jurusan.php?kode=tkr">Teknik Kendaraan Ringan (TKR)</a></li>
                        <li><a href="jurusan.php?kode=akl">Akuntansi (AKL)</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="footer-title">Kontak Kami</h4>
                    <ul class="footer-contact">
                        <li>
                            <strong>Alamat:</strong><br>
                            <?= e(SITE_ADDRESS) ?>
                        </li>
                        <li>
                            <strong>Telepon:</strong> <?= e(SITE_PHONE) ?>
                        </li>
                        <li>
                            <strong>Email:</strong> <?= e(SITE_EMAIL) ?>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="footer-bottom">
                <p>&copy; <?= date('Y') ?> <?= e(SITE_NAME) ?>. Seluruh hak cipta dilindungi.</p>
                <p class="footer-ref">Website &amp; Portal Informasi Resmi <?= e(SITE_NAME) ?></p>
            </div>
        </div>
    </footer>

    <script src="assets/js/main.js"></script>
</body>
</html>
