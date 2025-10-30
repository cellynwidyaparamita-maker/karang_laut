<?php
session_start();
include 'config/db.php';


if (!$conn) {
    die("Koneksi database gagal.");
}


if (!isset($_SESSION['role']) || $_SESSION['role'] != 'member') {
    header("Location: login.php");
    exit();
}


$kegiatan = $conn->query("SELECT * FROM laporan_kegiatan ORDER BY tanggal DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Riwayat Kegiatan - Karang Taruna</title>
<style>
body {
  background: linear-gradient(135deg, #a8c0ff, #3f2b96);
  min-height: 100vh;
  font-family: 'Poppins', sans-serif;
  padding: 40px;
  color: #333;
}
.container {
  background: #fff;
  border-radius: 16px;
  padding: 25px;
  max-width: 900px;
  margin: auto;
  box-shadow: 0 6px 25px rgba(0,0,0,0.2);
}
h1 {
  text-align: center;
  color: #3f2b96;
  margin-bottom: 25px;
}
table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 10px;
}
th, td {
  padding: 10px;
  text-align: left;
  border-bottom: 1px solid #ddd;
}
th {
  background-color: #3f2b96;
  color: white;
}
tr:hover {
  background-color: #f2f2ff;
}
.status {
  padding: 4px 10px;
  border-radius: 8px;
  font-size: 13px;
  font-weight: bold;
  text-transform: capitalize;
}
.status.belum\ dilaksanakan {
  background: #fff3cd;
  color: #856404;
}
.status.sedang\ berlangsung {
  background: #c8f7c5;
  color: #2e7d32;
}
.status.selesai {
  background: #d1ecf1;
  color: #0c5460;
}
a.btn {
  display: inline-block;
  margin-top: 20px;
  background: linear-gradient(90deg, #3f2b96, #a8c0ff);
  color: white;
  padding: 10px 16px;
  text-decoration: none;
  border-radius: 8px;
  transition: 0.3s;
}
a.btn:hover {
  opacity: 0.85;
}
</style>
</head>
<body>

<div class="container">
  <h1>Riwayat Kegiatan</h1>

  <?php if ($kegiatan && $kegiatan->num_rows > 0): ?>
    <table>
      <thead>
        <tr>
          <th>No</th>
          <th>Nama Kegiatan</th>
          <th>Tanggal</th>
          <th>Lokasi</th>
          <th>Status</th>
          <th>Deskripsi</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php 
        $no = 1;
        while ($row = $kegiatan->fetch_assoc()): 
          $tanggal = !empty($row['tanggal']) ? date('d M Y', strtotime($row['tanggal'])) : '-';
          $status = strtolower($row['status'] ?? 'belum dilaksanakan');
        ?>
        <tr>
          <td><?= $no++; ?></td>
          <td><?= htmlspecialchars($row['nama_kegiatan']); ?></td>
          <td><?= htmlspecialchars($tanggal); ?></td>
          <td><?= htmlspecialchars($row['lokasi']); ?></td>
          <td><span class="status <?= $status; ?>"><?= htmlspecialchars($row['status']); ?></span></td>
          <td><?= htmlspecialchars(substr($row['deskripsi'],0,100)) ?>...</td>
          <td><a href="laporan_kegiatan.php?id=<?= $row['id']; ?>" class="btn">Lihat</a></td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  <?php else: ?>
    <p style="text-align:center;">belum ada riwayat kegiatan.</p>
  <?php endif; ?>

  <a href="dashboard_member.php" class="btn">← Kembali ke Dashboard</a>
</div>

</body>
</html>
