<?php
session_start();
include 'config/db.php';


if (!$conn) {
    die("koneksi database gagal: " . $conn->connect_error);
}


if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("location: index.php");
    exit();
}


if (isset($_POST['tambah'])) {
    $nama = trim($_POST['nama_kegiatan']);
    $tgl = $_POST['tanggal'];
    $lokasi = trim($_POST['lokasi']);
    $deskripsi = trim($_POST['deskripsi']);
    $status = $_POST['status'];

    if ($nama && $tgl) {
        $stmt = $conn->prepare("INSERT INTO laporan_kegiatan (nama_kegiatan, tanggal, lokasi, deskripsi, status) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $nama, $tgl, $lokasi, $deskripsi, $status);
        if ($stmt->execute()) {
            header("location: kegiatan.php?berhasil=1");
            exit();
        } else {
            $error_msg = "gagal menambah kegiatan: " . $stmt->error;
        }
    } else {
        $error_msg = "nama dan tanggal kegiatan wajib diisi!";
    }
}


if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    if ($id > 0) {
        $stmt = $conn->prepare("DELETE FROM laporan_kegiatan WHERE id=?");
        if ($stmt) {
            $stmt->bind_param("i", $id);
            if ($stmt->execute()) {
                header("location: kegiatan.php?hapus=1");
                exit();
            } else {
                $error_msg = "gagal menghapus kegiatan: " . $stmt->error;
            }
        } else {
            $error_msg = "query hapus gagal: " . $conn->error;
        }
    } else {
        $error_msg = "id kegiatan tidak valid!";
    }
}


$kegiatan = $conn->query("SELECT * FROM laporan_kegiatan ORDER BY tanggal DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Laporan Kegiatan - Admin</title>
<style>
body {
  margin: 0;
  font-family: 'Poppins', sans-serif;
  background: linear-gradient(135deg, #a8c0ff, #3f2b96);
  min-height: 100vh;
  padding: 40px;
}
.container {
  max-width: 950px;
  background: #fff;
  margin: auto;
  padding: 25px 35px;
  border-radius: 18px;
  box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}
h2 {
  color: #3f2b96;
  margin-bottom: 25px;
  text-align: center;
}
form {
  background: #f0f2ff;
  padding: 20px;
  border-radius: 12px;
  margin-bottom: 30px;
}
input, textarea, select {
  width: 100%;
  padding: 10px;
  margin-bottom: 12px;
  border: 1px solid #ccc;
  border-radius: 6px;
  font-size: 14px;
}
button {
  background: linear-gradient(90deg, #3f2b96, #a8c0ff);
  color: #fff;
  border: none;
  padding: 10px 16px;
  border-radius: 8px;
  cursor: pointer;
  font-weight: bold;
  transition: 0.2s;
}
button:hover {
  opacity: 0.9;
}
.table-container {
  overflow-x: auto;
}
table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 15px;
}
th, td {
  padding: 10px 12px;
  text-align: left;
  border-bottom: 1px solid #eee;
}
th {
  background: #3f2b96;
  color: #fff;
}
tr:hover {
  background: #f7f7ff;
}
.btn {
  display: inline-block;
  padding: 6px 10px;
  border-radius: 6px;
  text-decoration: none;
  font-size: 13px;
}
.btn-edit {
  background: #4CAF50;
  color: #fff;
}
.btn-edit:hover { background: #45a049; }
.btn-hapus {
  background: #ff5f6d;
  color: #fff;
}
.btn-hapus:hover { background: #ff3c4a; }
.alert {
  padding: 10px 15px;
  border-radius: 8px;
  margin-bottom: 15px;
}
.alert-success {
  background: #d4edda;
  color: #155724;
  border-left: 6px solid #3f2b96;
}
.alert-error {
  background: #f8d7da;
  color: #721c24;
  border-left: 6px solid #721c24;
}
</style>
</head>
<body>
<div class="container">
  <h2> Daftar Laporan Kegiatan</h2>

  <?php if(isset($_GET['berhasil'])): ?>
    <div class="alert alert-success">kegiatan berhasil ditambahkan!</div>
  <?php endif; ?>
  <?php if(isset($_GET['hapus'])): ?>
    <div class="alert alert-success"> kegiatan berhasil dihapus!</div>
  <?php endif; ?>
  <?php if(isset($_GET['edit'])): ?>
    <div class="alert alert-success"> kegiatan berhasil diedit!</div>
  <?php endif; ?>
  <?php if(isset($error_msg)): ?>
    <div class="alert alert-error"><?= htmlspecialchars($error_msg) ?></div>
  <?php endif; ?>

  
  <form method="POST">
    <h3>Tambah Kegiatan Baru</h3>
    <input type="text" name="nama_kegiatan" placeholder="nama kegiatan" required>
    <input type="date" name="tanggal" required>
    <input type="text" name="lokasi" placeholder="lokasi kegiatan">
    <textarea name="deskripsi" placeholder="deskripsi kegiatan"></textarea>
    <select name="status">
      <option value="belum dilaksanakan">belum dilaksanakan</option>
      <option value="sedang berlangsung">sedang berlangsung</option>
      <option value="selesai">selesai</option>
    </select>
    <button type="submit" name="tambah">+ Tambah Kegiatan</button>
  </form>

  
  <div class="table-container">
    <table>
      <tr>
        <th>No</th>
        <th>Nama Kegiatan</th>
        <th>Tanggal</th>
        <th>Lokasi</th>
        <th>Status</th>
        <th>Deskripsi</th>
        <th>Aksi</th>
      </tr>
      <?php 
      $no = 1;
      if($kegiatan && $kegiatan->num_rows > 0):
        while($row = $kegiatan->fetch_assoc()):
      ?>
      <tr>
        <td><?= $no++ ?></td>
        <td><?= htmlspecialchars($row['nama_kegiatan']) ?></td>
        <td><?= htmlspecialchars($row['tanggal']) ?></td>
        <td><?= htmlspecialchars($row['lokasi']) ?></td>
        <td><?= htmlspecialchars($row['status']) ?></td>
        <td><?= htmlspecialchars($row['deskripsi']) ?></td>
        <td>
          <a href="edit_kegiatan.php?id=<?= $row['id'] ?>" class="btn btn-edit">Edit</a>
          <a href="hapus.php?id=<?= $row['id'] ?>" 
   onclick="return confirm('Yakin ingin menghapus kegiatan ini?')"
   class="btn btn-danger">Hapus</a>

        </td>
      </tr>
      <?php endwhile; else: ?>
      <tr><td colspan="7" style="text-align:center; color:#666;">belum ada kegiatan</td></tr>
      <?php endif; ?>
    </table>
  </div>
</div>
</body>
</html>
