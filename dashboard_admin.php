<?php
session_start();
include 'config/db.php';


if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard Admin - Karang Taruna</title>
<style>
* {
  margin: 0; padding: 0; box-sizing: border-box;
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
.header h1 { font-size: 32px; font-weight: 700; }
.header p { font-size: 16px; opacity: 0.9; }
.dashboard-container {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
  gap: 25px;
  max-width: 1200px;
  margin: 0 auto;
}
.card {
  background: #fff;
  border-radius: 16px;
  padding: 25px;
  box-shadow: 0 5px 20px rgba(0,0,0,0.15);
  transition: 0.3s;
}
.card:hover { transform: translateY(-6px); }
.card h2 {
  font-size: 20px; color: #3f2b96;
  margin-bottom: 15px;
  border-left: 5px solid #a8c0ff;
  padding-left: 10px;
}
.btn {
  display: inline-block;
  padding: 8px 15px;
  background: linear-gradient(90deg, #3f2b96, #a8c0ff);
  color: white;
  text-decoration: none;
  border-radius: 8px;
  font-size: 14px;
  transition: 0.3s;
}
.btn:hover { opacity: 0.85; }
.logout {
  position: fixed; top: 20px; right: 30px;
  background: #ff5f6d; color: white; border: none;
  padding: 10px 16px; border-radius: 8px; cursor: pointer;
}
.logout:hover { background: #ff2e49; }
</style>
</head>
<body>

<button class="logout" onclick="window.location='logout.php'">Logout</button>

<div class="header">
  <h1>Dashboard Admin Karang Taruna</h1>
  <p>Kelola data anggota, kegiatan, iuran, dan keuangan di sini.</p>
</div>

<div class="dashboard-container">
  <div class="card">
    <h2> Data Anggota</h2>
    <p>Kelola data anggota karang taruna.</p>
    <a href="anggota.php" class="btn">Kelola Anggota</a>
  </div>

  <div class="card">
    <h2> Laporan Kegiatan</h2>
    <p>Catat dan pantau semua kegiatan.</p>
    <a href="laporan_kegiatan.php" class="btn">Lihat Kegiatan</a>
  </div>

  <div class="card">
    <h2> Laporan Iuran</h2>
    <p>Kelola data pembayaran iuran anggota.</p>
    <a href="laporan_iuran.php" class="btn">Lihat Iuran</a>
  </div>

  <div class="card">
    <h2>Laporan Keuangan</h2>
    <p>Lihat data keuangan karang taruna.</p>
    <a href="laporan_keuangan.php" class="btn">Lihat Keuangan</a>
  </div>
</div>

</body>
</html>
