<?php
session_start();

function setFlash($msg, $type = "success") {
    $_SESSION['flash'] = [
        "msg" => $msg,
        "type" => $type
    ];
}

function showFlash() {
    if (!empty($_SESSION['flash'])) {
        $f = $_SESSION['flash'];
        echo "<div class='flash {$f['type']}'>" . htmlspecialchars($f['msg']) . "</div>";
        unset($_SESSION['flash']);
    }
}
