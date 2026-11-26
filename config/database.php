<?php
/**
 * Konfigurasi Database & Konstanta Aplikasi
 * SMK Bangun Nusa Bangsa (SMK BNB)
 */

define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'smk_bnb');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

define('SITE_NAME', 'SMK Bangun Nusa Bangsa');
define('SITE_SHORT', 'SMK BNB');
define('SITE_TAGLINE', 'Unggul dalam Prestasi, Terampil dalam Kompetensi, Berakhlak Mulia');
define('SITE_ADDRESS', 'Jl. KSR No. 1 RT 08/07, Kel. Karadenan, Cibinong, Kab. Bogor, Jawa Barat');
define('SITE_PHONE', '(021) 8790-1234');
define('SITE_EMAIL', 'info@smkbnb.sch.id');
define('SITE_NPSN', '69769379');
define('SITE_ACCREDITATION', 'A (Unggul)');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Mengembalikan koneksi PDO tunggal (singleton).
 */
function getDB(): PDO
{
    static $pdo = null;
    if ($pdo === null) {
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        try {
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            die(
                '<div style="font-family:sans-serif;max-width:600px;margin:40px auto;padding:24px;border:1px solid #fecaca;background:#fef2f2;border-radius:12px;color:#991b1b;">'
                . '<h2 style="margin-top:0;">Koneksi Database Gagal</h2>'
                . '<p>Pastikan MySQL di XAMPP sudah berjalan dan database <strong>' . htmlspecialchars(DB_NAME) . '</strong> sudah diimpor dari file <code>database.sql</code>.</p>'
                . '<p style="font-size:13px;color:#7f1d1d;">Detail: ' . htmlspecialchars($e->getMessage()) . '</p>'
                . '</div>'
            );
        }
    }
    return $pdo;
}
