<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.html?msg=fail");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Dashboard</title>
  <style>
    .flash {
      position: fixed;
      top: 20px; right: 20px;
      padding: 10px 15px;
      border-radius: 5px;
      color: #fff;
      background: #4CAF50;
      opacity: 0.95;
      transition: opacity 0.5s;
    }
  </style>
</head>
<body>
  <div id="flash" class="flash" style="display:none;"></div>

  <h1>Selamat datang, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
  <p><a href="logout.php">Logout</a></p>

  <script>
    const urlParams = new URLSearchParams(window.location.search);
    const msg = urlParams.get("msg");
    const flash = document.getElementById("flash");

    if (msg === "login") {
      flash.textContent = "Success Login";
      flash.style.display = "block";
      setTimeout(() => flash.style.opacity = "0", 2500);
      setTimeout(() => flash.style.display = "none", 3000);
    }
  </script>
</body>
</html>
