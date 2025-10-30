<?php
include 'config/db.php';
session_start();


if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit();
}


$id = $_GET['id'] ?? 0;
$stmt = $conn->prepare("SELECT * FROM laporan_kegiatan WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$kegiatan = $result->fetch_assoc();

if (!$kegiatan) {
    die("Kegiatan tidak ditemukan.");
}


if (isset($_POST['update'])) {
    $nama = $_POST['nama_kegiatan'];
    $tanggal = $_POST['tanggal'];
    $lokasi = $_POST['lokasi'];
    $deskripsi = $_POST['deskripsi'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE laporan_kegiatan SET nama_kegiatan=?, tanggal=?, lokasi=?, deskripsi=?, status=? WHERE id=?");
    $stmt->bind_param("sssssi", $nama, $tanggal, $lokasi, $deskripsi, $status, $id);
    $stmt->execute();

    header("Location: kegiatan.php?edit=1");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Edit Kegiatan</title>
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
    display: flex;
    justify-content: center;
    align-items: center;
  }

  .container {
    background: #ffffff;
    width: 480px;
    padding: 35px 40px;
    border-radius: 16px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    animation: fadeIn 0.6s ease-in-out;
  }

  @keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
  }

  h2 {
    color: #3f2b96;
    text-align: center;
    margin-bottom: 25px;
    font-weight: 600;
    font-size: 22px;
  }

  input[type="text"],
  input[type="date"],
  textarea,
  select {
    width: 100%;
    padding: 12px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 8px;
    font-size: 14px;
    transition: 0.3s;
  }

  input:focus,
  textarea:focus,
  select:focus {
    border-color: #3f2b96;
    box-shadow: 0 0 5px rgba(63,43,150,0.3);
    outline: none;
  }

  button {
    background: linear-gradient(90deg, #3f2b96, #a8c0ff);
    color: white;
    border: none;
    padding: 12px 16px;
    border-radius: 10px;
    font-size: 15px;
    font-weight: 500;
    cursor: pointer;
    width: 100%;
    transition: 0.3s ease;
  }

  button:hover {
    transform: scale(1.03);
    box-shadow: 0 4px 12px rgba(63,43,150,0.3);
  }

  .back-btn {
    display: block;
    text-align: center;
    margin-top: 15px;
    color: #3f2b96;
    text-decoration: none;
    font-weight: 500;
  }

  .back-btn:hover {
    text-decoration: underline;
  }

  @media (max-width: 600px) {
    .container {
      width: 90%;
      padding: 25px;
    }
  }
  </style>
</head>
<body>
<div class="container">
  <h2>Edit Kegiatan</h2>
  <form method="POST">
    <input type="text" name="nama_kegiatan" placeholder="Nama kegiatan" value="<?= htmlspecialchars($kegiatan['nama_kegiatan']) ?>" required>
    <input type="date" name="tanggal" value="<?= htmlspecialchars($kegiatan['tanggal']) ?>" required>
    <input type="text" name="lokasi" placeholder="Lokasi kegiatan" value="<?= htmlspecialchars($kegiatan['lokasi']) ?>" required>
    <textarea name="deskripsi" rows="4" placeholder="Deskripsi kegiatan"><?= htmlspecialchars($kegiatan['deskripsi']) ?></textarea>

    <select name="status">
      <option value="belum dilaksanakan" <?= $kegiatan['status']=="belum dilaksanakan"?'selected':'' ?>>Belum Dilaksanakan</option>
      <option value="sedang berlangsung" <?= $kegiatan['status']=="sedang berlangsung"?'selected':'' ?>>Sedang Berlangsung</option>
      <option value="selesai" <?= $kegiatan['status']=="selesai"?'selected':'' ?>>Selesai</option>
    </select>

    <button type="submit" name="update"> Simpan Perubahan</button>
  </form>

  <a href="kegiatan.php" class="back-btn">← Kembali ke Daftar Kegiatan</a>
</div>
</body>
</html>
