<?php
session_start();
require 'config/db.php';


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}


if ($_SESSION['role'] !== 'member') {
    header("Location: dashboard_admin.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Dashboard Member</title>
<style>
body {
  font-family: 'Poppins', sans-serif;
  background: linear-gradient(135deg, #a8c0ff, #3f2b96);
  color: #333;
  padding: 40px;
}
.container {
  background: white;
  max-width: 800px;
  margin: auto;
  padding: 30px;
  border-radius: 16px;
  box-shadow: 0 5px 20px rgba(0,0,0,0.2);
  text-align: center;
}
a.btn {
  display: inline-block;
  margin: 10px;
  padding: 10px 15px;
  border-radius: 8px;
  background: linear-gradient(90deg,#3f2b96,#a8c0ff);
  color: white;
  text-decoration: none;
  transition: 0.3s;
}
a.btn:hover { opacity: 0.9; transform: translateY(-2px); }
h1 { color: #3f2b96; }
</style>
</head>
<body>
<div class="container">
  <h1>Selamat Datang, <?= htmlspecialchars($_SESSION['username'] ?? 'Member'); ?>!</h1>

  <p>Silakan pilih menu di bawah ini:</p>

  <a href="_iuran.php" class="btn">Riwayat Iuran</a>
  <a href="riwayat_kegiatan.php" class="btn">Riwayat Kegiatan</a>
  <a href="profil_anggota.php" class="btn"> Profil Saya</a>
  <a href="logout.php" class="btn" style="background:#e74c3c;"> Logout</a>
</div>
</body>
</html>
