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


$stmt = $conn->prepare("SELECT username, email, phone, alamat FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();


if (isset($_POST['update'])) {
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $alamat = trim($_POST['alamat']);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Format email tidak valid!";
    } else {
       
        $update = $conn->prepare("UPDATE users SET email=?, phone=?, alamat=? WHERE id=?");
        $update->bind_param("sssi", $email, $phone, $alamat, $user_id);
        $update->execute();

        
        echo "<script>
            alert('Profil berhasil diperbarui!');
            window.location.href = 'dashboard_member.php';
        </script>";
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Profil Saya</title>
<style>
body {
  font-family: "Poppins", sans-serif;
  background: linear-gradient(135deg, #e8ecff, #f9faff);
  padding: 40px;
}
.container {
  background: white;
  max-width: 600px;
  margin: auto;
  padding: 30px 40px;
  border-radius: 16px;
  box-shadow: 0 5px 20px rgba(0,0,0,0.15);
}
h1 {
  color: #3f2b96;
  text-align: center;
  margin-bottom: 25px;
}
form {
  display: flex;
  flex-direction: column;
  gap: 15px;
}
input, textarea {
  padding: 10px;
  border-radius: 8px;
  border: 1px solid #ccc;
  font-size: 14px;
}
button {
  background: linear-gradient(135deg, #3f2b96, #a8c0ff);
  color: white;
  border: none;
  padding: 10px;
  border-radius: 8px;
  cursor: pointer;
  font-size: 15px;
  transition: 0.3s;
}
button:hover {
  opacity: 0.9;
  transform: translateY(-2px);
}
a.back {
  display: inline-block;
  margin-bottom: 20px;
  text-decoration: none;
  color: #3f2b96;
  font-weight: bold;
}
.alert {
  padding: 10px;
  margin-bottom: 15px;
  border-radius: 8px;
  font-size: 14px;
}
.error {
  background: #f8d7da;
  color: #721c24;
}
</style>
</head>
<body>


<a href="dashboard_member.php" class="back">Kembali ke Dashboard</a>

<div class="container">
  <h1>Profil Saya</h1>

  <?php if (!empty($error)): ?>
    <div class="alert error"><?= htmlspecialchars($error); ?></div>
  <?php endif; ?>

  <form method="POST">
      <label>Username</label>
      <input type="text" value="<?= htmlspecialchars($data['username']); ?>" readonly>

      <label>Email</label>
      <input type="email" name="email" value="<?= htmlspecialchars($data['email']); ?>" required>

      <label>No. Telepon</label>
      <input type="text" name="phone" value="<?= htmlspecialchars($data['phone']); ?>" required>

      <label>Alamat</label>
      <textarea name="alamat" rows="3"><?= htmlspecialchars($data['alamat']); ?></textarea>

      <button type="submit" name="update">Simpan Perubahan</button>
  </form>
</div>

</body>
</html>
