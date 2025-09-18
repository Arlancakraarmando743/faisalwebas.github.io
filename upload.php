<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.html");
    exit;
}

$targetDir = __DIR__ . "/uploads/";
if (!is_dir($targetDir)) {
    mkdir($targetDir, 0777, true);
}

if (!empty($_FILES['foto']['name'])) {
    $fileName = time() . "_" . basename($_FILES['foto']['name']);
    $targetFile = $targetDir . $fileName;

    if (move_uploaded_file($_FILES['foto']['tmp_name'], $targetFile)) {
        echo "Upload berhasil! <a href='dashboard.php'>Kembali</a>";
    } else {
        echo "Upload gagal. <a href='dashboard.php'>Coba lagi</a>";
    }
} else {
    echo "Tidak ada file yang dipilih. <a href='dashboard.php'>Coba lagi</a>";
}
