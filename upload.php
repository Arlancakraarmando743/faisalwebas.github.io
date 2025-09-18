<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: index.html?msg=Silakan%20login'); exit;
}
$targetDir = __DIR__ . '/uploads/';
if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

if (empty($_FILES['foto']['name'])) {
    header('Location: dashboard.php?msg=Pilihan%20file%20kosong'); exit;
}

$fname = basename($_FILES['foto']['name']);
$ext = strtolower(pathinfo($fname, PATHINFO_EXTENSION));
$allowed = ['jpg','jpeg','png','gif','webp'];
if (!in_array($ext, $allowed)) {
    header('Location: dashboard.php?msg=Format%20tidak%20diperbolehkan'); exit;
}

$target = $targetDir . time() . '_' . preg_replace('/[^A-Za-z0-9._-]/', '_', $fname);
if (move_uploaded_file($_FILES['foto']['tmp_name'], $target)) {
    header('Location: dashboard.php?msg=upload_ok'); exit;
} else {
    header('Location: dashboard.php?msg=Upload%20gagal'); exit;
}
