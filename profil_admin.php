<?php
session_start();
include 'config/db.php';


if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit();
}

$admin_id = $_SESSION['user_id'] ?? 0;


$stmt = $conn->prepare("SELECT * FROM users WHERE id=?");
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();

if (!$admin) {
    die("Data admin tidak ditemukan. Pastikan akun login tersimpan di tabel users.");
}

$success_message = '';
$error_message = '';

if (isset($_POST['update_profile'])) {
    $nama_lengkap = $_POST['nama_lengkap'];
    $email = $_POST['email'];
    $telepon = $_POST['nomor_telepon'];
    $alamat = $_POST['alamat'];
    $bio = $_POST['bio'];

    $stmt = $conn->prepare("UPDATE users SET nama_lengkap=?, email=?, nomor_telepon=?, alamat=?, bio=? WHERE id=?");
    $stmt->bind_param("sssssi", $nama_lengkap, $email, $telepon, $alamat, $bio, $admin_id);
    if ($stmt->execute()) {
        $success_message = "Profil berhasil diperbarui.";
        $result = $conn->query("SELECT * FROM users WHERE id=$admin_id");
        $admin = $result->fetch_assoc();
    } else {
        $error_message = "Gagal memperbarui profil.";
    }
}


if (isset($_POST['change_password'])) {
    $pass_lama = $_POST['password_lama'];
    $pass_baru = $_POST['password_baru'];
    $konfirmasi = $_POST['konfirmasi_password'];

    if (password_verify($pass_lama, $admin['password'])) {
        if ($pass_baru === $konfirmasi) {
            $hashed = password_hash($pass_baru, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users SET password=? WHERE id=?");
            $stmt->bind_param("si", $hashed, $admin_id);
            $stmt->execute();
            $success_message = "Password berhasil diubah.";
        } else {
            $error_message = "Konfirmasi password tidak cocok.";
        }
    } else {
        $error_message = "Password lama salah.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Profil Admin</title>
<style>
body {
  background: linear-gradient(135deg, #a8c0ff, #3f2b96);
  font-family: 'Poppins', sans-serif;
  padding: 40px;
}
.container {
  max-width: 800px;
  background: white;
  margin: auto;
  padding: 30px;
  border-radius: 16px;
  box-shadow: 0 5px 20px rgba(0,0,0,0.15);
}
h1 { color: #3f2b96; text-align: center; }
input, textarea {
  width: 100%;
  padding: 10px;
  border: 1px solid #ddd;
  border-radius: 8px;
  margin-bottom: 12px;
  font-size: 14px;
}
button {
  background: linear-gradient(90deg, #3f2b96, #a8c0ff);
  color: white;
  border: none;
  padding: 10px 15px;
  border-radius: 8px;
  cursor: pointer;
  transition: 0.3s;
}
button:hover { opacity: 0.9; }
.alert {
  padding: 10px;
  border-radius: 8px;
  margin-bottom: 15px;
}
.alert-success { background: #d4edda; color: #155724; }
.alert-error { background: #f8d7da; color: #721c24; }
.center { text-align: center; margin-bottom: 25px; }
.center h2 { color: #3f2b96; }
</style>
</head>
<body>
<div class="container">
  <h1>Profil Admin</h1>

  <div class="center">
    <h2><?= htmlspecialchars($admin['nama_lengkap'] ?? $admin['username']) ?></h2>
    <p><?= htmlspecialchars($admin['email']) ?></p>
  </div>

  <?php if ($success_message): ?>
    <div class="alert alert-success"><?= htmlspecialchars($success_message) ?></div>
  <?php endif; ?>

  <?php if ($error_message): ?>
    <div class="alert alert-error"><?= htmlspecialchars($error_message) ?></div>
  <?php endif; ?>

  
  <form method="POST">
    <input type="hidden" name="update_profile" value="1">
    
    <label>Nama Lengkap</label>
    <input type="text" name="nama_lengkap" value="<?= htmlspecialchars($admin['nama_lengkap'] ?? '') ?>" required>

    <label>Email</label>
    <input type="email" name="email" value="<?= htmlspecialchars($admin['email'] ?? '') ?>" required>

    <label>Nomor Telepon</label>
    <input type="text" name="nomor_telepon" value="<?= htmlspecialchars($admin['nomor_telepon'] ?? '') ?>">

    <label>Alamat</label>
    <textarea name="alamat"><?= htmlspecialchars($admin['alamat'] ?? '') ?></textarea>

    <label>Bio</label>
    <textarea name="bio"><?= htmlspecialchars($admin['bio'] ?? '') ?></textarea>

    <button type="submit">Simpan Perubahan</button>
  </form>

  <hr>

 
  <form method="POST">
    <input type="hidden" name="change_password" value="1">

    <label>Password Lama</label>
    <input type="password" name="password_lama" required>

    <label>Password Baru</label>
    <input type="password" name="password_baru" required>

    <label>Konfirmasi Password</label>
    <input type="password" name="konfirmasi_password" required>

    <button type="submit">Ganti Password</button>
  </form>
</div>
</body>
</html>
