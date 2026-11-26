-- Database: smk_bnb
-- SMK Bangun Nusa Bangsa - Website & Portal

CREATE DATABASE IF NOT EXISTS `smk_bnb` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `smk_bnb`;

-- 1. Tabel Users (Admin Portal)
CREATE TABLE IF NOT EXISTS `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `nama` VARCHAR(100) NOT NULL,
    `username` VARCHAR(50) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `role` ENUM('admin', 'editor') DEFAULT 'admin',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Default admin: username = admin, password = admin123
INSERT INTO `users` (`nama`, `username`, `password`, `role`) VALUES
('Administrator SMK BNB', 'admin', '$2y$10$T/EjKgOu7nYqFkBK3jsxauxFABGh4qGTllyZ3j1rDQkViv0MeOa8C', 'admin')
ON DUPLICATE KEY UPDATE `password` = VALUES(`password`);

-- 2. Tabel Jurusan
CREATE TABLE IF NOT EXISTS `jurusan` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `kode` VARCHAR(20) NOT NULL UNIQUE,
    `nama` VARCHAR(120) NOT NULL,
    `singkatan` VARCHAR(20) NOT NULL,
    `deskripsi_singkat` TEXT NOT NULL,
    `deskripsi_lengkap` TEXT NOT NULL,
    `kompetensi` TEXT NOT NULL,
    `prospek_karir` TEXT NOT NULL,
    `fasilitas` TEXT NOT NULL,
    `ikon` VARCHAR(50) DEFAULT 'cpu',
    `warna` VARCHAR(20) DEFAULT '#2563eb',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO `jurusan` (`kode`, `nama`, `singkatan`, `deskripsi_singkat`, `deskripsi_lengkap`, `kompetensi`, `prospek_karir`, `fasilitas`, `ikon`, `warna`) VALUES
('tkj', 'Teknik Jaringan Komputer', 'TKJ',
 'Mempelajari instalasi jaringan komputer, administrasi server, keamanan siber, dan infrastruktur IT modern.',
 'Program Keahlian Teknik Jaringan Komputer (TKJ) SMK Bangun Nusa Bangsa membekali siswa dengan keterampilan merancang, membangun, mengelola, dan mengamankan jaringan komputer skala lokal maupun luas (LAN/WAN), cloud computing, serta pemeliharaan perangkat keras.',
 'Instalasi & Konfigurasi Mikrotik/Cisco|Administrasi Server Linux & Windows|Keamanan Jaringan & Cyber Security|Fiber Optic & Wireless Networking|Perakitan & Troubleshooting PC',
 'Network Engineer|System Administrator|IT Support Specialist|Cyber Security Analyst|Teknisi Fiber Optic',
 'Lab Komputer Ber-AC|Lab Mikrotik & Cisco|Perangkat Fiber Optic Splicer|Server Lokal Praktikum',
 'network', '#2563eb'),

('tkr', 'Teknik Kendaraan Ringan', 'TKR',
 'Menguasai perawatan, perbaikan, dan diagnosa sistem mesin, sasis, serta kelistrikan kendaraan roda empat modern.',
 'Program Keahlian Teknik Kendaraan Ringan (TKR) mempersiapkan siswa menjadi teknisi otomotif profesional yang mahir dalam teknologi mesin EFI/injeksi, sistem kelistrikan bodi, transmisi, sasis, hingga diagnosa kendaraan menggunakan scanner otomotif modern.',
 'Pemeliharaan Mesin Kendaraan Ringan (EFI)|Sistem Kelistrikan & AC Mobil|Perawatan Sasis & Pemindah Tenaga|Diagnosa Kerusakan dengan OBD-II Scanner|Keselamatan & Kesehatan Kerja Bengkel',
 'Mekanik Otomotif Profesional|Service Advisor Dealer Resmi|Teknisi Kelistrikan Mobil|Quality Control Industri Otomotif|Wirausaha Bengkel Mandiri',
 'Bengkel Praktik Standar Industri|Unit Mobil Praktik & Engine Stand|Alat Scanner Diagnostik Modern|Car Lift & Peralatan Spooring',
 'car', '#dc2626'),

('akl', 'Akuntansi', 'AKL',
 'Membentuk tenaga profesional di bidang pembukuan, laporan keuangan, perpajakan, dan aplikasi akuntansi digital.',
 'Program Keahlian Akuntansi SMK Bangun Nusa Bangsa mendidik siswa agar teliti dan kompeten dalam menyusun siklus akuntansi perusahaan jasa, dagang, dan manufaktur, pengelolaan pajak, perbankan, serta pengoperasian perangkat lunak akuntansi terkini.',
 'Akuntansi Keuangan Dasar & Menengah|Komputer Akuntansi (MYOB / Accurate)|Administrasi Pajak (e-SPT & e-Faktur)|Pengelolaan Kas & Perbankan|Spreadsheet & Pengolahan Data Keuangan',
 'Staf Akuntansi & Keuangan|Kasir & Teller Perbankan|Staf Administrasi Perpajakan|Junior Auditor|Entrepreneur & Pengelola Keuangan UMKM',
 'Lab Komputer Akuntansi|Simulasi Bank Mini Sekolah|Software Akuntansi Terlisensi|Ruang Praktik Administrasi Modern',
 'calculator', '#059669')
ON DUPLICATE KEY UPDATE `nama` = VALUES(`nama`);

-- 3. Tabel Artikel
CREATE TABLE IF NOT EXISTS `artikel` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `judul` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(255) NOT NULL UNIQUE,
    `kategori` VARCHAR(60) NOT NULL DEFAULT 'Berita Sekolah',
    `ringkasan` TEXT NOT NULL,
    `konten` LONGTEXT NOT NULL,
    `gambar` VARCHAR(255) DEFAULT NULL,
    `penulis` VARCHAR(100) NOT NULL DEFAULT 'Admin SMK BNB',
    `status` ENUM('published', 'draft') DEFAULT 'published',
    `views` INT DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO `artikel` (`judul`, `slug`, `kategori`, `ringkasan`, `konten`, `penulis`, `status`, `views`) VALUES
('Penerimaan Peserta Didik Baru (PPDB) SMK Bangun Nusa Bangsa Tahun Ajaran 2026/2027',
 'ppdb-smk-bangun-nusa-bangsa-2026-2027',
 'Pengumuman',
 'SMK Bangun Nusa Bangsa resmi membuka pendaftaran siswa baru untuk jurusan Teknik Jaringan Komputer, Teknik Kendaraan Ringan, dan Akuntansi.',
 'SMK Bangun Nusa Bangsa (SMK BNB) kembali membuka Penerimaan Peserta Didik Baru (PPDB) untuk Tahun Ajaran 2026/2027. Sebagai sekolah kejuruan terakreditasi A di Cibinong, Kabupaten Bogor, kami berkomitmen mencetak lulusan yang siap kerja, cerdas, dan berkarakter.\n\nTiga program keahlian unggulan yang dapat dipilih oleh calon peserta didik adalah:\n1. Teknik Jaringan Komputer (TKJ)\n2. Teknik Kendaraan Ringan (TKR)\n3. Akuntansi (AKL)\n\nCalon siswa dapat melakukan pendaftaran secara langsung di kampus SMK Bangun Nusa Bangsa maupun melalui portal informasi resmi sekolah. Tersedia program beasiswa prestasi akademik dan non-akademik bagi pendaftar gelombang pertama.',
 'Panitia PPDB', 'published', 142),

('Siswa TKJ SMK BNB Raih Prestasi di Lomba Kompetensi Siswa (LKS) Tingkat Kabupaten',
 'siswa-tkj-smk-bnb-raih-prestasi-lks',
 'Prestasi',
 'Tim Teknik Jaringan Komputer SMK Bangun Nusa Bangsa berhasil menunjukkan kemampuan terbaiknya dalam bidang IT Network System Administration.',
 'Prestasi membanggakan kembali ditorehkan oleh peserta didik jurusan Teknik Jaringan Komputer (TKJ) SMK Bangun Nusa Bangsa dalam ajang Lomba Kompetensi Siswa (LKS) bidang IT Network System Administration.\n\nDalam kompetisi tersebut, siswa diuji kemampuannya dalam merancang topologi jaringan, mengonfigurasi server Linux, manajemen routing Mikrotik, serta mengamankan layanan jaringan dalam waktu yang terbatas.\n\nKepala Sekolah SMK Bangun Nusa Bangsa menyampaikan apresiasi setinggi-tingginya kepada siswa dan guru pembimbing yang telah berlatih keras. Keberhasilan ini membuktikan kualitas pembelajaran praktik di laboratorium TKJ SMK BNB.',
 'Humas SMK BNB', 'published', 98),

('Kunjungan Industri Jurusan Teknik Kendaraan Ringan ke Pabrik Perakitan Otomotif',
 'kunjungan-industri-jurusan-tkr-smk-bnb',
 'Kegiatan',
 'Siswa kelas XI Teknik Kendaraan Ringan mengikuti kunjungan industri guna mengenal langsung standar kerja dan teknologi manufaktur kendaraan modern.',
 'Dalam rangka menjembatani teori di kelas dengan dunia kerja nyata, jurusan Teknik Kendaraan Ringan (TKR) SMK Bangun Nusa Bangsa melaksanakan kegiatan Kunjungan Industri.\n\nPara siswa diajak melihat secara langsung proses perakitan kendaraan, sistem kendali mutu (quality control), serta penerapan budaya keselamatan kerja (K3) di lingkungan industri otomotif skala nasional.\n\nKegiatan ini diharapkan mampu memotivasi siswa untuk terus mengasah kompetensi teknis dan kedisiplinan sebelum terjun ke program Praktik Kerja Lapangan (PKL).',
 'Tim Kurikulum', 'published', 76),

('Pelatihan Software Akuntansi Digital untuk Siswa Jurusan Akuntansi SMK BNB',
 'pelatihan-software-akuntansi-digital-smk-bnb',
 'Akademik',
 'Jurusan Akuntansi menggelar workshop intensif penyusunan laporan keuangan berbasis software akuntansi modern bagi siswa tingkat akhir.',
 'Menghadapi era digitalisasi keuangan, jurusan Akuntansi SMK Bangun Nusa Bangsa menyelenggarakan workshop intensif penggunaan software akuntansi modern untuk penyusunan laporan keuangan perusahaan.\n\nMateri pelatihan mencakup pencatatan transaksi harian, pengelolaan buku besar, rekonsiliasi bank, hingga pelaporan pajak secara terintegrasi. Dengan sertifikasi kompetensi ini, lulusan Akuntansi SMK BNB siap bersaing di dunia perbankan dan perusahaan swasta.',
 'Kaprodi Akuntansi', 'published', 64)
ON DUPLICATE KEY UPDATE `judul` = VALUES(`judul`);

-- 4. Tabel Informasi Sekolah (Pengumuman, Agenda, Info Penting)
CREATE TABLE IF NOT EXISTS `informasi` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `judul` VARCHAR(200) NOT NULL,
    `kategori` ENUM('Pengumuman', 'Agenda', 'Beasiswa', 'Layanan') DEFAULT 'Pengumuman',
    `isi` TEXT NOT NULL,
    `tanggal` DATE NOT NULL,
    `lokasi` VARCHAR(150) DEFAULT 'SMK Bangun Nusa Bangsa',
    `is_active` TINYINT(1) DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO `informasi` (`judul`, `kategori`, `isi`, `tanggal`, `lokasi`, `is_active`) VALUES
('Jadwal Pendaftaran PPDB Gelombang 1', 'Pengumuman', 'Pendaftaran dibuka setiap hari Senin - Sabtu pukul 08.00 - 15.00 WIB di Sekretariat PPDB SMK Bangun Nusa Bangsa.', '2026-12-01', 'Kampus SMK BNB, Cibinong', 1),
('Uji Kompetensi Keahlian (UKK) Kelas XII', 'Agenda', 'Pelaksanaan Uji Kompetensi Keahlian untuk jurusan TKJ, TKR, dan Akuntansi bersama asesor industri.', '2026-12-15', 'Lab & Bengkel SMK BNB', 1),
('Program Beasiswa Prestasi & KIP Sekolah', 'Beasiswa', 'Tersedia potongan biaya pendidikan hingga 50% bagi siswa berprestasi akademik maupun non-akademik.', '2026-12-10', 'Ruang Tata Usaha', 1),
('Bursa Kerja Khusus (BKK) & Penyaluran Kerja', 'Layanan', 'Layanan informasi lowongan kerja dan penyaluran alumni ke perusahaan mitra industri nasional.', '2026-11-28', 'Ruang BKK SMK BNB', 1)
ON DUPLICATE KEY UPDATE `judul` = VALUES(`judul`);
