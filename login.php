<?php
require 'connect.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST['email'];
  $password = $_POST['password'];
  $query = $conn->prepare("SELECT * FROM tb_pengguna WHERE email=? AND password=?");
  $query->bind_param("ss", $email, $password);
  $query->execute();
  $result = $query->get_result();
  if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $_SESSION['id_pengguna'] = $user['id_pengguna'];
    $_SESSION['nama'] = $user['nama'];
    header("Location: index.php");
    exit;
  } else {
    $error = "Email atau password salah!";
  }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Login Pengguna</title>
<link rel="stylesheet" href="">
<style>
  /* 🌍 RESET & FONT */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: 'Poppins', sans-serif;
}

/* 🚌 LATAR BELAKANG */
body {
  background: url('bis.jpeg') no-repeat center center fixed; /* 🔹 ganti "bus.jpg" sesuai nama file gambarmu */
  background-size: cover;
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
}

/* 🧾 FORM LOGIN */
form {
  background: rgba(255, 255, 255, 0.9);
  padding: 30px 25px;
  border-radius: 12px;
  box-shadow: 0 4px 20px rgba(0,0,0,0.3);
  width: 320px;
  text-align: center;
}

/* 🔹 JUDUL */
h2 {
  color: white;
  text-shadow: 1px 1px 6px rgba(0,0,0,0.5);
  margin-bottom: 25px;
  font-size: 28px;
}

/* 📨 INPUT FIELD */
input[type="email"],
input[type="password"] {
  width: 100%;
  padding: 10px;
  margin: 8px 0 15px;
  border: 1px solid #ccc;
  border-radius: 8px;
  font-size: 14px;
  transition: 0.3s;
}

input:focus {
  border-color: #0288d1;
  box-shadow: 0 0 6px rgba(2,136,209,0.4);
  outline: none;
}

/* 💚 TOMBOL LOGIN */
button {
  background: #0288d1;
  color: white;
  border: none;
  padding: 10px;
  width: 100%;
  border-radius: 8px;
  font-size: 16px;
  cursor: pointer;
  font-weight: 600;
  transition: 0.3s;
}

button:hover {
  background: #0277bd;
  transform: scale(1.05);
}

/* 📄 LINK DAFTAR */
p {
  margin-top: 15px;
  color: white;
  text-shadow: 1px 1px 4px rgba(0,0,0,0.4);
}

p a {
  color: #ffeb3b;
  text-decoration: none;
  font-weight: 600;
}

p a:hover {
  text-decoration: underline;
}

/* ⚠️ PESAN ERROR */
p[style*="color:red"] {
  background: rgba(255, 0, 0, 0.8);
  color: white !important;
  padding: 8px;
  border-radius: 6px;
  margin-top: 10px;
  font-size: 14px;
}

/* 📱 RESPONSIVE */
@media (max-width: 500px) {
  form {
    width: 85%;
  }
  h2 {
    font-size: 24px;
  }
}

</style>
</head>
<body>
<h2>Login Pengguna</h2>
<form method="post">
  <input type="email" name="email" placeholder="Email" required><br>
  <input type="password" name="password" placeholder="Password" required><br>
  <button type="submit">Login</button>
</form>
<p>Belum punya akun? <a href="register.php">Daftar</a></p>
<?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
</body>
</html>