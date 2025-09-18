<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: index.html?msg=Silakan%20login');
    exit;
}
$username = $_SESSION['username'];
$uploadDir = __DIR__ . '/uploads/';
if (!is_dir($uploadDir)) @mkdir($uploadDir, 0777, true);

// ambil file gambar
$files = [];
$scan = array_diff(scandir($uploadDir), ['.','..']);
foreach ($scan as $f) {
    $files[] = $f;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>Dashboard</title>
  <style>
    .flash { position:fixed; top:20px; right:20px; padding:10px; color:#fff; background:#4CAF50; border-radius:6px; }
    .gallery { display:flex; flex-wrap:wrap; gap:10px; margin-top:20px; }
    .gallery img { width:150px; height:auto; border:1px solid #ddd; border-radius:6px; }
  </style>
</head>
<body>
  <div id="flash" class="flash" style="display:none;"></div>

  <h1>Halo, <?php echo htmlspecialchars($username); ?></h1>
  <p><a href="logout.php">Logout</a></p>

  <h2>Upload Foto</h2>
  <form action="upload.php" method="post" enctype="multipart/form-data">
    <input type="file" name="foto" accept="image/*" required>
    <button type="submit">Upload</button>
  </form>

  <h2>Galeri</h2>
  <div class="gallery">
    <?php if (count($files) === 0): ?>
      <p>Belum ada foto.</p>
    <?php else: ?>
      <?php foreach ($files as $f): ?>
        <img src="uploads/<?php echo rawurlencode($f); ?>" alt="">
      <?php endforeach; ?>
    <?php endif; ?>
  </div>

  <script>
    const urlParams = new URLSearchParams(window.location.search);
    const msg = urlParams.get('msg');
    const flash = document.getElementById('flash');
    if (msg) {
      if (msg === 'login') flash.textContent = 'Success Login';
      else if (msg === 'upload_ok') flash.textContent = 'Upload berhasil';
      else flash.textContent = msg;
      flash.style.display = 'block';
      setTimeout(()=> flash.style.opacity='0', 2500);
      setTimeout(()=> flash.style.display='none', 3000);
    }
  </script>
</body>
</html>
