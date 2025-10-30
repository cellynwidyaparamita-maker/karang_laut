<?php
session_start();
include 'config/db.php';


if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

$query = "SELECT * FROM iuran ORDER BY tanggal DESC";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Laporan Iuran - Admin</title>
  <style>
    body {
      background: linear-gradient(135deg, #a8c0ff, #3f2b96);
      font-family: 'Poppins', sans-serif;
      padding: 40px;
      color: #333;
      min-height: 100vh;
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
      margin-bottom: 20px;
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
    tr:hover { background-color: #f5f5ff; }
    .status {
      padding: 6px 12px;
      border-radius: 20px;
      font-size: 13px;
      font-weight: bold;
      text-transform: capitalize;
    }
    .lunas {
      background: #d4edda; color: #155724;
    }
    .belum-lunas {
      background: #f8d7da; color: #721c24;
    }
    .btn {
      display: inline-block;
      margin: 10px;
      background: linear-gradient(90deg, #3f2b96, #a8c0ff);
      color: white;
      padding: 10px 16px;
      text-decoration: none;
      border-radius: 8px;
      transition: 0.3s;
    }
    .btn:hover {
      opacity: 0.85;
      transform: translateY(-2px);
    }
    .btn-container {
      text-align: center;
      margin-top: 20px;
    }

    
    @media print {
      .btn-container {
        display: none;
      }
      body {
        background: white;
        padding: 0;
      }
      .container {
        box-shadow: none;
        margin: 0;
        padding: 0;
      }
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>Laporan Iuran Anggota</h1>

    <?php if ($result && $result->num_rows > 0): ?>
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
          <?php 
          $no = 1;
          while ($row = $result->fetch_assoc()):
            $status_class = strtolower(str_replace(' ', '-', $row['status']));
            $jumlah = 'Rp ' . number_format($row['jumlah'], 0, ',', '.');
            $tanggal = date('d M Y', strtotime($row['tanggal']));
          ?>
          <tr>
            <td><?= $no++; ?></td>
            <td><?= htmlspecialchars($row['nama']); ?></td>
            <td><?= htmlspecialchars($row['bulan']); ?></td>
            <td><?= htmlspecialchars($jumlah); ?></td>
            <td><?= htmlspecialchars($tanggal); ?></td>
            <td><span class="status <?= $status_class; ?>"><?= htmlspecialchars($row['status']); ?></span></td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    <?php else: ?>
      <p style="text-align:center;">Belum ada data iuran.</p>
    <?php endif; ?>

    <div class="btn-container">
      <a href="dashboard_admin.php" class="btn">← Kembali ke Dashboard</a>
      <a href="#" onclick="window.print();" class="btn">🖨 Cetak Laporan</a>
    </div>
  </div>
</body>
</html>
