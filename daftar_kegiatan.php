<?php
session_start();
include 'config/db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'member') {
    header("Location: login.php");
    exit();
}

$kegiatan = $conn->query("SELECT * FROM reports ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Daftar Kegiatan - Karang Taruna</title>
<style>

* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: 'Poppins', sans-serif;
}

body {
  background: linear-gradient(135deg, #a8c0ff, #3f2b96);
  min-height: 100vh;
  padding: 30px;
}


.header {
  text-align: center;
  color: white;
  margin-bottom: 40px;
}
.header h1 {
  font-size: 32px;
  font-weight: 700;
  letter-spacing: 1px;
}
.header p {
  font-size: 16px;
  opacity: 0.9;
}


.kegiatan-container {
  max-width: 900px;
  margin: 0 auto;
}

/* 💎 CARD KEGIATAN */
.kegiatan-item {
  background: #fff;
  border-radius: 16px;
  padding: 25px;
  box-shadow: 0 5px 20px rgba(0,0,0,0.15);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  margin-bottom: 25px;
}
.kegiatan-item:hover {
  transform: translateY(-6px);
  box-shadow: 0 10px 25px rgba(0,0,0,0.2);
}
.kegiatan-item h2 {
  font-size: 22px;
  color: #3f2b96;
  margin-bottom: 10px;
}
.kegiatan-item p {
  color: #444;
  font-size: 14px;
  margin-bottom: 10px;
}
.kegiatan-item small {
  color: #666;
}


.btn {
  display: inline-block;
  margin-top: 10px;
  padding: 8px 15px;
  background: linear-gradient(90deg, #3f2b96, #a8c0ff);
  color: white;
  text-decoration: none;
  border-radius: 8px;
  font-size: 14px;
  transition: 0.3s;
}
.btn:hover {
  opacity: 0.85;
}


.back-btn {
  position: fixed;
  top: 20px;
  left: 30px;
  background: #ff5f6d;
  color: white;
  border: none;
  padding: 10px 16px;
  border-radius: 8px;
  cursor: pointer;
  font-size: 14px;
  text-decoration: none;
  transition: 0.3s;
}
.back-btn:hover {
  background: #ff2e49;
}
</style>
</head>
<body>

<a href="dashboard_member.php" class="back-btn">⬅ Kembali</a>

<div class="header">
  <h1>Daftar Kegiatan Karang Taruna</h1>
  <p>lihat semua kegiatan yang pernah dilaksanakan ✨</p>
</div>

<div class="kegiatan-container">
<?php if ($kegiatan && $kegiatan->num_rows > 0): ?>
  <?php while($k = $kegiatan->fetch_assoc()): ?>
    <div class="kegiatan-item">
      <h2> <?php echo htmlspecialchars($k['title']); ?></h2>
      <p><?php echo htmlspecialchars(substr($k['content'], 0, 150)); ?>...</p>
      <small>🗓 
      <?php 
        echo !empty($k['tanggal_kegiatan']) 
             ? date('d M Y', strtotime($k['tanggal_kegiatan'])) 
             : 'Tanggal tidak tersedia'; 
      ?>
      </small><br><br>
      <a href="detail_kegiatan.php?id=<?php echo $k['id']; ?>" class="btn">🔍 Lihat Detail</a>
    </div>
  <?php endwhile; ?>
<?php else: ?>
  <p style="text-align:center; color:white;">belum ada kegiatan tercatat.</p>
<?php endif; ?>
</div>

</body>
</html>
