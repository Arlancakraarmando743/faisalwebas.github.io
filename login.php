<?php
session_start();

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

if ($username === '' || $password === '') {
    header('Location: index.html?msg=Isi%20username%20dan%20password');
    exit;
}

$path = __DIR__ . '/users.json';
if (!file_exists($path)) {
    header('Location: index.html?msg=Belum%20ada%20user');
    exit;
}

$raw = file_get_contents($path);
$users = json_decode($raw ?: '[]', true);
$found = null;
foreach ($users as $u) {
    if (strtolower($u['username']) === strtolower($username)) {
        $found = $u;
        break;
    }
}

if (!$found || !password_verify($password, $found['password'])) {
    header('Location: index.html?msg=Login%20gagal');
    exit;
}

// set session dan redirect ke dashboard
$_SESSION['username'] = $found['username'];
header('Location: dashboard.php?msg=login');
exit;
