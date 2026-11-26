<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

session_unset();
session_destroy();
session_start();
setFlash('success', 'Anda telah keluar dari Portal Admin.');
header('Location: login.php');
exit;
