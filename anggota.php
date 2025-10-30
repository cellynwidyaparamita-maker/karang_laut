<?php
session_start();
include 'config/db.php';


if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit();
}


if (isset($_POST['tambah'])) {
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $no_hp = $_POST['no_hp'];
    $jabatan = $_POST['jabatan'];

    $conn->query("INSERT INTO anggota (nama, alamat, no_hp, jabatan) 
                  VALUES ('$nama', '$alamat', '$no_hp', '$jabatan')");
    header("Location: anggota.php");
    exit();
}


if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $conn->query("DELETE FROM anggota WHERE id='$id'");
    header("Location: anggota.php");
    exit();
}


$data = $conn->query("SELECT * FROM anggota ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Data Anggota - Karang Taruna</title>
<style>
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: 'Poppins', sans-serif;
}
body {
  background: #f5f6fa;
  display: flex;
  min-height: 100vh;
}
.sidebar {
  width: 240px;
  height: 100vh;
  position: fixed;
  display: flex;
  flex-direction: column;
  background: linear-gradient(180deg, #3f2b96, #a8c0ff);
  color: #fff;
  padding: 20px;
}
.sidebar h2 {
  text-align: center;
  margin-bottom: 30px;
  font-size: 20px;
  font-weight: 600;
}
.sidebar a {
  color: #fff;
  text-decoration: none;
  padding: 12px 15px;
  border-radius: 8px;
  margin-bottom: 8px;
  display: block;
  transition: 0.3s;
}
.sidebar a:hover {
  background: rgba(255,255,255,0.2);
}
.sidebar a.active {
  background: #fff;
  color: #3f2b96;
  font-weight: 600;
}
.main-content {
  flex: 1;
  margin-left: 260px;
  padding: 30px;
}
.header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 30px;
}
.header h1 {
  color: #3f2b96;
  font-size: 24px;
}
.header button {
  background: #ff5f6d;
  color: white;
  border: none;
  padding: 8px 14px;
  border-radius: 8px;
  cursor: pointer;
  transition: 0.3s;
}
.header button:hover {
  background: #ff3c4a;
}
form {
  background: #fff;
  padding: 20px;
  border-radius: 10px;
  box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}
form h3 {
  margin-bottom: 15px;
  color: #3f2b96;
}
label {
  font-weight: 500;
  display: block;
  margin-top: 10px;
}
input, select {
  width: 100%;
  padding: 10px;
  margin-top: 6px;
  border: 1px solid #ccc;
  border-radius: 8px;
}
button[type="submit"] {
  margin-top: 15px;
  background: linear-gradient(90deg,#3f2b96,#a8c0ff);
  color: white;
  border: none;
  padding: 10px 14px;
  border-radius: 8px;
  cursor: pointer;
  transition: 0.3s;
}
button[type="submit"]:hover {
  opacity: 0.85;
}
h3.table-title {
  margin-top: 30px;
  margin-bottom: 10px;
  color: #3f2b96;
}
table {
  width: 100%;
  border-collapse: collapse;
  background: #fff;
  border-radius: 8px;
  overflow: hidden;
  box-shadow: 0 4px 10px rgba(0,0,0,0.05);
}
th, td {
  padding: 12px;
  border-bottom: 1px solid #eee;
  text-align: left;
  font-size: 14px;
}
th {
  background: #3f2b96;
  color: #fff;
  text-transform: uppercase;
  font-size: 13px;
  letter-spacing: 0.5px;
}
tr:hover {
  background: #f8f8ff;
}
.btn-hapus {
  color: white;
  background: #ff5f6d;
  padding: 6px 10px;
  border-radius: 6px;
  text-decoration: none;
  font-size: 13px;
  transition: 0.3s;
}
.btn-hapus:hover {
  background: #ff3c4a;
}
.btn-iuran {
  color: white;
  background: #3f2b96;
  padding: 6px 10px;
  border-radius: 6px;
  text-decoration: none;
  font-size: 13px;
  transition: 0.3s;
  margin-left: 5px;
}
.btn-iuran:hover {
  background: #5e4fc1;
}
</style>
</head>
<body>
  <div class="sidebar">
    <h2>Admin Panel</h2>
    <a href="dashboard_admin.php">🏠 Dashboard</a>
    <a href="anggota.php" class="active">👥 Data Anggota</a>
    <a href="laporan_kegiatan.php">📅 Laporan Kegiatan</a>
    <a href="laporan_iuran.php">💰 Laporan Iuran</a>
    <a href="profil_admin.php">👤 Profil Ketua/Admin</a>
    <a href="laporan_keuangan.php">💰 Laporan Keuangan</a>
    <a href="logout.php">🚪 Logout</a>
  </div>

  <div class="main-content">
    <div class="header">
      <h1>Data Anggota Karang Taruna</h1>
      <button onclick="window.location='logout.php'">Logout</button>
    </div>

    <form method="POST">
      <h3>Tambah Anggota Baru</h3>
      <label>Nama:</label>
      <input type="text" name="nama" required>
      <label>Alamat:</label>
      <input type="text" name="alamat" required>
      <label>No HP:</label>
      <input type="text" name="no_hp" required>
      <label>Jabatan:</label>
      <select name="jabatan" required>
        <option value="">-- pilih jabatan --</option>
        <option value="anggota">Anggota</option>
        <option value="sekretaris">Sekretaris</option>
        <option value="bendahara">Bendahara</option>
        <option value="ketua">Ketua</option>
      </select>
      <button type="submit" name="tambah">Simpan</button>
    </form>

    <h3 class="table-title">📋 Daftar Anggota</h3>
    <table>
      <tr>
        <th>No</th>
        <th>Nama</th>
        <th>Alamat</th>
        <th>No HP</th>
        <th>Jabatan</th>
        <th>Aksi</th>
      </tr>
      <?php 
      if ($data->num_rows > 0) {
        $no = 1;
        while ($d = $data->fetch_assoc()) {
          echo "
          <tr>
            <td>{$no}</td>
            <td>{$d['nama']}</td>
            <td>{$d['alamat']}</td>
            <td>{$d['no_hp']}</td>
            <td>{$d['jabatan']}</td>
            <td>
              <a href='edit.php?id={$d['id']}' class='btn-iuran'>Edit</a>
              <a href='hapus.php?id={$d['id']}' class='btn-hapus' onclick='return confirm(\"Yakin hapus anggota ini?\")'>Hapus</a>
              <a href='riwayat_iuran.php?id={$d['id']}' class='btn-iuran'>Riwayat Iuran</a>
            </td>
          </tr>";
          $no++;
        }
      } else {
        echo "<tr><td colspan='6' style='text-align:center;color:#777;'>Belum ada anggota terdaftar</td></tr>";
      }
      ?>
    </table>
  </div>
</body>
</html>
