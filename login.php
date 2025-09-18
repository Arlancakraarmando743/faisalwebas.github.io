<?php
session_start();

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

if ($username === '' || $password === '') {
    header("Location: index.html?msg=fail");
    exit;
}

$path = __DIR__ . "/users.json";
if (!file_exists($path)) {
    header("Location: index.html?msg=fail");
    exit;
}

$users = json_decode(file_get_contents($path), true);

$found = null;
foreach ($users as $u) {
    if (strtolower($u['username']) === strtolower($username)) {
        $found = $u;
        break;
    }
}

if (!$found || !password_verify($password, $found['password'])) {
    header("Location: index.html?msg=fail");
    exit;
}

$_SESSION['username'] = $username;

// redirect dengan pesan sukses login
header("Location: dashboard.php?msg=login");
exit;
