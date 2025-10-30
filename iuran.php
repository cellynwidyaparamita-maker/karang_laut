<?php
session_start();
include 'config/db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location:index.php");
    exit();
}


 $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    header("Location: iuran.php");
    exit();
}


 $stmt = $conn->prepare("SELECT * FROM iuran WHERE id = ?");
 $stmt->bind_param("i", $id);
 $stmt->execute();
 $result = $stmt->get_result();
 $data_iuran = $result->fetch_assoc();

if (!$data_iuran) {
    header("Location: iuran.php");
    exit();
}


if (isset($_POST['update'])) {
    $nama = $_POST['nama'];
    $bulan = $_POST['bulan'];
    $jumlah = $_POST['jumlah'];
    $status = $_POST['status'];

    
    $stmt_update = $conn->prepare("UPDATE iuran SET nama=?, bulan=?, jumlah=?, status=? WHERE id=?");
    $stmt_update->bind_param("ssisi", $nama, $bulan, $jumlah, $status, $id);
    
    if ($stmt_update->execute()) {
        echo "<script>alert('Data iuran berhasil diperbarui!'); window.location='iuran.php';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui data!');</script>";
    }
    $stmt_update->close();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Iuran - Karang Taruna</title>

<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
   
    <header class="header">
        <div class="brand">
            <h1>Admin Panel</h1>
        </div>
        <nav class="nav">
            <a href="dashboard.php">Dashboard</a>
            <a href="iuran.php" class="active">Iuran</a>
            <a href="profil.php">Profil</a>
            <a href="logout.php" class="btn btn-danger">Logout</a>
        </nav>
    </header>

    
    <div class="card">
        <div class="card-header">
            <h2> Edit Data Iuran</h2>
        </div>

        <form method="POST">
            <div class="group">
                <label for="nama">Nama Anggota</label>
                <input type="text" name="nama" id="nama" class="form-control" value="<?= htmlspecialchars($data_iuran['nama']) ?>" required>
            </div>
            <div class="group">
                <label for="bulan">Bulan</label>
                <input type="text" name="bulan" id="bulan" class="form-control" value="<?= htmlspecialchars($data_iuran['bulan']) ?>" required>
            </div>
            <div class="group">
                <label for="jumlah">Jumlah (Rp)</label>
                <input type="number" name="jumlah" id="jumlah" class="form-control" value="<?= htmlspecialchars($data_iuran['jumlah']) ?>" required>
            </div>
            <div class="group">
                <label for="status">Status</label>
                <select name="status" id="status" class="form-control" required>
                    <option value="Lunas" <?= $data_iuran['status'] == 'Lunas' ? 'selected' : '' ?>>Lunas</option>
                    <option value="Belum Lunas" <?= $data_iuran['status'] == 'Belum Lunas' ? 'selected' : '' ?>>Belum Lunas</option>
                </select>
            </div>
            
            <div style="display: flex; gap: 10px; margin-top: 20px;">
                <button type="submit" name="update" class="btn">Simpan Perubahan</button>
                <a href="iuran.php" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
</body>
</html>