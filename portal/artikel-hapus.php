<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
requireLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !verifyCsrf($_POST['csrf_token'] ?? null)) {
    setFlash('error', 'Permintaan hapus tidak valid.');
    header('Location: artikel.php');
    exit;
}

$id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
$pdo = getDB();

$stmt = $pdo->prepare("SELECT gambar FROM artikel WHERE id = ?");
$stmt->execute([$id]);
$row = $stmt->fetch();

if ($row) {
    if (!empty($row['gambar'])) {
        $filePath = __DIR__ . '/../uploads/artikel/' . $row['gambar'];
        if (file_exists($filePath)) {
            @unlink($filePath);
        }
    }
    $del = $pdo->prepare("DELETE FROM artikel WHERE id = ?");
    $del->execute([$id]);
    setFlash('success', 'Artikel berhasil dihapus.');
} else {
    setFlash('error', 'Artikel tidak ditemukan.');
}

header('Location: artikel.php');
exit;
