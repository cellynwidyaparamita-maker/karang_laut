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

$user_id = $_SESSION['user_id'];


$stmt = $conn->prepare("SELECT * FROM iuran WHERE user_id = ? ORDER BY tanggal DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$riwayat_iuran = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Riwayat Iuran</title>
<style>
body {
  background: linear-gradient(135deg, #a8c0ff, #3f2b96);
  font-family: 'Poppins', sans-serif;
  padding: 40px;
  color: #333;
}
.container {
  background: #fff;
  border-radius: 16px;
  padding: 25px;
  max-width: 1000px;
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
}
th, td {
  padding: 12px;
  text-align: left;
  border-bottom: 1px solid #ddd;
}
th {
  background-color: #3f2b96;
  color: white;
}
tr:hover { background-color: #f2f2ff; }
.status {
  padding: 6px 12px;
  border-radius: 20px;
  font-size: 13px;
  font-weight: bold;
}
.status.lunas { background: #d4edda; color: #155724; }
.status.belum-lunas { background: #f8d7da; color: #721c24; }
a.btn {
  display: inline-block;
  margin-top: 20px;
  background: linear-gradient(90deg, #3f2b96, #a8c0ff);
  color: white;
  padding: 10px 16px;
  text-decoration: none;
  border-radius: 8px;
}
a.btn:hover { opacity: 0.85; transform: translateY(-2px); }
</style>
</head>
<body>
<div class="container">
  <h1>Riwayat Iuran Saya</h1>

  <?php if ($riwayat_iuran && $riwayat_iuran->num_rows > 0): ?>
    <table>
      <thead>
        <tr>
          <th>No</th>
          <th>Nama</th>
          <th>Bulan</th>
          <th>Jumlah</th>
          <th>Tanggal</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        <?php $no=1; while($row=$riwayat_iuran->fetch_assoc()): ?>
        <tr>
          <td><?= $no++; ?></td>
          <td><?= htmlspecialchars($row['nama']); ?></td>
          <td><?= htmlspecialchars($row['bulan']); ?></td>
          <td>Rp <?= number_format($row['jumlah'],0,',','.'); ?></td>
          <td><?= date('d M Y', strtotime($row['tanggal'])); ?></td>
          <td><span class="status <?= strtolower(str_replace(' ','-',$row['status'])); ?>">
              <?= htmlspecialchars($row['status']); ?></span></td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  <?php else: ?>
    <p style="text-align:center;">Belum ada riwayat iuran.</p>
  <?php endif; ?>

  <div style="text-align:center;">
    <a href="dashboard_member.php" class="btn">← Kembali ke Dashboard</a>
  </div>
</div>
</body>
</html>
