<?php
$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

if ($username === '' || $password === '') {
    header("Location: index.html?msg=fail");
    exit;
}

$path = __DIR__ . "/users.json";

// kalau file belum ada → buat kosong
if (!file_exists($path)) {
    file_put_contents($path, "[]");
}

// baca data user lama
$users = json_decode(file_get_contents($path), true);

// cek username sudah dipakai belum
foreach ($users as $u) {
    if (strtolower($u['username']) === strtolower($username)) {
        header("Location: index.html?msg=fail");
        exit;
    }
}

// tambahin user baru
$users[] = [
    "username" => $username,
    "password" => password_hash($password, PASSWORD_DEFAULT)
];

// simpan kembali ke file JSON
file_put_contents($path, json_encode($users, JSON_PRETTY_PRINT));

// redirect dengan pesan sukses
header("Location: index.html?msg=register");
exit;
