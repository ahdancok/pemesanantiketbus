<?php
require 'connect.php';

if (!isset($_GET['id_bus'])) {
    die("ID Bus tidak ditemukan.");
}

$id_bus = intval($_GET['id_bus']);
$query = $conn->query("SELECT * FROM tb_bus WHERE id_bus = $id_bus");
$bus = $query->fetch_assoc();

if (!$bus) {
    die("Data bus tidak ditemukan di database.");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Pesan Tiket - <?= htmlspecialchars($bus['nama_bus']) ?></title>
  <link rel="stylesheet" href="">
  <style>
    /* 🌈 GAYA DASAR */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: 'Poppins', sans-serif;
}

body {
  background: linear-gradient(135deg, #00bcd4, #2196f3);
  min-height: 100vh;
  padding: 30px;
  color: #333;
}

/* 🚌 JUDUL HALAMAN */
h2 {
  text-align: center;
  color: white;
  margin-bottom: 25px;
  font-size: 26px;
  letter-spacing: 0.5px;
  text-shadow: 1px 1px 4px rgba(0,0,0,0.3);
}

/* 🧾 INFO BUS */
.bus-info {
  background: white;
  max-width: 500px;
  margin: 0 auto 25px;
  padding: 20px;
  border-radius: 12px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.1);
  line-height: 1.7;
}

.bus-info p {
  margin-bottom: 6px;
}

.bus-info strong {
  color: #0288d1;
}

/* 🧍‍♂️ FORM PEMESANAN */
form {
  background: white;
  max-width: 500px;
  margin: 0 auto;
  padding: 25px;
  border-radius: 12px;
  box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}

label {
  display: block;
  font-weight: 600;
  margin-bottom: 6px;
  color: #333;
}

input[type="text"],
input[type="email"],
input[type="number"] {
  width: 100%;
  padding: 10px;
  border-radius: 8px;
  border: 1px solid #ccc;
  margin-bottom: 15px;
  font-size: 14px;
  transition: 0.2s ease;
}

input:focus {
  outline: none;
  border-color: #0288d1;
  box-shadow: 0 0 5px rgba(2, 136, 209, 0.4);
}

/* 💚 TOMBOL PESAN */
button {
  background: #4caf50;
  color: white;
  border: none;
  padding: 10px;
  border-radius: 8px;
  width: 100%;
  cursor: pointer;
  font-size: 15px;
  font-weight: bold;
  transition: 0.3s;
}

button:hover {
  background: #43a047;
  transform: scale(1.03);
}

/* 🔙 LINK KEMBALI */
p a {
  display: inline-block;
  text-decoration: none;
  color: white;
  font-weight: 500;
  background: #ff0000ff;
  padding: 8px 14px;
  border-radius: 6px;
  margin: 20px auto;
  text-align: center;
  transition: 0.3s;
}

p a:hover {
  background: #01579b;
  transform: scale(1.05);
}

/* 📱 RESPONSIVE */
@media (max-width: 600px) {
  .bus-info, form {
    width: 90%;
  }
  body {
    padding: 20px;
  }
  h2 {
    font-size: 22px;
  }
}

  </style>
</head>
<body>
  <h2>Pesan Tiket Bus</h2>

  <div class="bus-info">
    <p><strong>Nama Bus:</strong> <?= htmlspecialchars($bus['nama_bus']) ?></p>
    <p><strong>Kelas:</strong> <?= htmlspecialchars($bus['kelas']) ?></p>
    <p><strong>Rute:</strong> <?= htmlspecialchars($bus['asal'] . " - " . $bus['tujuan']) ?></p>
    <p><strong>Harga Tiket:</strong> Rp <?= number_format($bus['harga_tiket'], 0, ',', '.') ?></p>
    <p><strong>Jam Berangkat:</strong> <?= htmlspecialchars($bus['jam_berangkat']) ?></p>
    <p><strong>Jam Tiba:</strong> <?= htmlspecialchars($bus['jam_tiba']) ?></p>
    <p><strong>Tanggal Berangkat:</strong> <?= htmlspecialchars($bus['tanggal_berangkat']) ?></p>
    <p><strong>Kursi Tersedia:</strong> <?= htmlspecialchars($bus['jumlah_kursi']) ?></p>
  </div>

  <form action="act_pesan.php" method="POST">
    <input type="hidden" name="id_bus" value="<?= $bus['id_bus'] ?>">

    <label>Nama Pemesan:</label><br>
    <input type="text" name="nama" required><br><br>

    <label>Email Pemesan:</label><br>
    <input type="email" name="email" required><br><br>

    <label>Jumlah Tiket:</label><br>
    <input type="number" name="jumlah_tiket" min="1" max="<?= $bus['jumlah_kursi'] ?>" required><br><br>

    <button type="submit">Pesan Sekarang</button>
  </form>
    <p><a href="index.php">Kembali</a></p>
</body>
</html>
