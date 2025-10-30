<?php
session_start();
include 'config/db.php';


if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit();
}

if (isset($_POST['tambah'])) {
    $nama = $_POST['nama_kegiatan'];
    $tgl = $_POST['tanggal'];
    $lokasi = $_POST['lokasi'];
    $deskripsi = $_POST['deskripsi'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("INSERT INTO laporan_kegiatan (nama_kegiatan, tanggal, lokasi, deskripsi, status) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $nama, $tgl, $lokasi, $deskripsi, $status);
    $stmt->execute();
    header("Location: kegiatan.php?success=1");
    exit();
}

$kegiatan = $conn->query("SELECT * FROM laporan_kegiatan ORDER BY tanggal DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Kelola Kegiatan - Admin</title>
<style>
body {
  font-family: 'Poppins', sans-serif;
  margin: 0;
  background: #f5f6fa;
  padding: 30px;
}
.container {
  width: 90%;
  margin: auto;
  background: #fff;
  padding: 25px;
  border-radius: 16px;
  box-shadow: 0 5px 20px rgba(0,0,0,0.1);
}
h2 {
  color: #3f2b96;
  margin-bottom: 20px;
}
form {
  background: #f0f2ff;
  padding: 20px;
  border-radius: 12px;
  margin-bottom: 25px;
  display: grid;
  gap: 12px;
}
input, textarea, select, button {
  padding: 10px;
  border-radius: 6px;
  border: 1px solid #ccc;
  font-size: 14px;
}
button {
  cursor: pointer;
  border: none;
  color: #fff;
  background: linear-gradient(90deg,#3f2b96,#a8c0ff);
  transition: 0.3s;
}
button:hover { opacity: 0.85; }
.table-container { overflow-x: auto; }
table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 10px;
}
th, td {
  padding: 12px;
  border-bottom: 1px solid #eee;
  text-align: left;
}
th { background: #3f2b96; color: #fff; }
tr:hover { background: #f1f3ff; }
a.btn-hapus, a.btn-edit {
  padding: 6px 10px;
  border-radius: 6px;
  text-decoration: none;
  color: white;
  font-size: 13px;
}
a.btn-hapus { background: #ff5f6d; }
a.btn-hapus:hover { background: #ff3c4a; }
a.btn-edit { background: #ffc107; }
a.btn-edit:hover { background: #e0a800; }
.alert {
  padding: 10px 15px;
  border-radius: 8px;
  margin-bottom: 15px;
}
.alert.success { background: #dff0d8; color: #3c763d; }
</style>
</head>
<body>

<div class="container">
<h2>Kelola Laporan Kegiatan</h2>

<?php if(isset($_GET['success'])): ?>
  <div class="alert success"> Kegiatan berhasil ditambahkan!</div>
<?php endif; ?>
<?php if(isset($_GET['edit'])): ?>
  <div class="alert success"> Kegiatan berhasil diedit!</div>
<?php endif; ?>
<?php if(isset($_GET['hapus'])): ?>
  <div class="alert success">Kegiatan berhasil dihapus!</div>
<?php endif; ?>

<form method="POST">
  <input type="text" name="nama_kegiatan" placeholder="Nama kegiatan" required>
  <input type="date" name="tanggal" required>
  <input type="text" name="lokasi" placeholder="Lokasi kegiatan">
  <textarea name="deskripsi" placeholder="Deskripsi kegiatan"></textarea>
  <select name="status">
    <option value="belum dilaksanakan">Belum Dilaksanakan</option>
    <option value="sedang berlangsung">Sedang Berlangsung</option>
    <option value="selesai">Selesai</option>
  </select>
  <button type="submit" name="tambah">Tambah Kegiatan</button>
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
    <a href="edit.php?id=<?= $row['id'] ?>" class="btn-edit">Edit</a>
    <a href="hapus.php?id=<?= $row['id'] ?>" class="btn-hapus" onclick="return confirm('Yakin ingin hapus kegiatan ini?')">Hapus</a>
  </td>
</tr>
<?php endwhile; ?>
<?php if($kegiatan->num_rows == 0): ?>
<tr><td colspan="7" style="text-align:center; color:#666;">Belum ada kegiatan</td></tr>
<?php endif; ?>
</table>
</div>
</div>

</body>
</html>
