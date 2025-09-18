<?php
// register.php
// Pastikan dijalankan di server PHP (http), bukan file://

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

if ($username === '' || $password === '') {
    header('Location: index.html?msg=Isi%20username%20dan%20password');
    exit;
}

$path = __DIR__ . '/users.json';
if (!file_exists($path)) {
    // buat file initial
    file_put_contents($path, "[]");
}

// baca dan lock file agar thread-safe
$fp = fopen($path, 'c+');
if (!$fp) {
    header('Location: index.html?msg=Gagal%20akses%20users.json');
    exit;
}
flock($fp, LOCK_EX);
$raw = stream_get_contents($fp);
$users = json_decode($raw ?: '[]', true);
if (!is_array($users)) $users = [];

// cek username unik
foreach ($users as $u) {
    if (strtolower($u['username']) === strtolower($username)) {
        flock($fp, LOCK_UN);
        fclose($fp);
        header('Location: index.html?msg=Username%20sudah%20ada');
        exit;
    }
}

// tambah user
$users[] = [
    'username' => $username,
    'password' => password_hash($password, PASSWORD_DEFAULT),
    'created_at' => date('c')
];

// tulis ulang
ftruncate($fp, 0);
rewind($fp);
fwrite($fp, json_encode($users, JSON_PRETTY_PRINT));
fflush($fp);
flock($fp, LOCK_UN);
fclose($fp);

header('Location: index.html?msg=register');
exit;
