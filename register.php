<?php
include 'config/db.php';

if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $role = $_POST['role'];
    $kode_admin = $_POST['kode_admin'] ?? '';

    
    $kode_rahasia = "KETUA123";

   
    if ($role == "admin" && $kode_admin !== $kode_rahasia) {
        echo "<script>alert('Kode admin salah! Anda tidak bisa mendaftar sebagai ketua.');</script>";
        $role = "member"; 
    }

    
    $cek = $conn->query("SELECT * FROM users WHERE username='$username'");
    if ($cek->num_rows > 0) {
        echo "<script>alert('Username sudah digunakan, silakan pilih yang lain.');</script>";
    } else {
        $conn->query("INSERT INTO users (username, password, email, phone, role) 
                      VALUES ('$username', '$password', '$email', '$phone', '$role')");
        echo "<script>alert('Registrasi berhasil! Silakan login.'); window.location='index.php';</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Registrasi Karang Taruna</title>
<style>
body {
  font-family: 'Poppins', sans-serif;
  background: linear-gradient(135deg, #1a73e8, #4a90e2);
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
  margin: 0;
}

.container {
  background: #fff;
  padding: 40px 50px;
  border-radius: 15px;
  box-shadow: 0 5px 20px rgba(0,0,0,0.15);
  text-align: center;
  width: 380px;
}

h2 {
  margin-bottom: 25px;
  color: #1a73e8;
}

input, select {
  width: 100%;
  padding: 10px 12px;
  margin: 8px 0;
  border: 1px solid #ccc;
  border-radius: 8px;
  font-size: 14px;
  transition: 0.3s;
}

input:focus, select:focus {
  border-color: #1a73e8;
  outline: none;
}

button {
  background: #1a73e8;
  color: white;
  border: none;
  padding: 10px 18px;
  border-radius: 8px;
  font-size: 15px;
  cursor: pointer;
  transition: 0.3s;
  width: 100%;
}

button:hover {
  background: #0f5bd6;
}

p {
  margin-top: 15px;
  font-size: 14px;
}

a {
  color: #1a73e8;
  text-decoration: none;
}

a:hover {
  text-decoration: underline;
}

#kodeAdminContainer {
  display: none;
}
</style>
<script>
function toggleKodeAdmin() {
  const role = document.getElementById("role").value;
  const kodeField = document.getElementById("kodeAdminContainer");
  if (role === "admin") {
    kodeField.style.display = "block";
  } else {
    kodeField.style.display = "none";
  }
}
</script>
</head>
<body>

<div class="container">
  <h2>Registrasi Karang Taruna</h2>
  <form method="POST">
      <input type="text" name="username" placeholder="username" required>
      <input type="password" name="password" placeholder="password" required>
      <input type="email" name="email" placeholder="email" required>
      <input type="text" name="phone" placeholder="nomor telepon" required>

      <select name="role" id="role" onchange="toggleKodeAdmin()" required>
          <option value="">-- pilih role --</option>
          <option value="member">anggota</option>
          <option value="admin">ketua / admin</option>
      </select>

      <div id="kodeAdminContainer">
        <input type="text" name="kode_admin" placeholder="masukkan kode admin">
      </div>

      <button type="submit" name="register">Daftar</button>
  </form>

  <p>sudah punya akun? <a href="index.php">login disini</a></p>
</div>

</body>
</html>
