<?php
require 'connect.php';

if (!isset($_GET['id_pemesanan'])) {
    die("ID Pemesanan tidak ditemukan.");
}

$id_pemesanan = intval($_GET['id_pemesanan']);
$query = $conn->query("
    SELECT p.*, b.nama_bus, b.harga_tiket 
    FROM tb_pemesanan p 
    JOIN tb_bus b ON p.id_bus = b.id_bus 
    WHERE p.id_pemesanan = $id_pemesanan
");
$pemesanan = $query->fetch_assoc();

if (!$pemesanan) {
    die("Data pemesanan tidak ditemukan.");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Pembayaran Tiket</title>
  <link rel="stylesheet" href="">
  <style>
    /* 🌈 STYLE DASAR */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: 'Poppins', sans-serif;
}

body {
  background: linear-gradient(135deg, #00bcd4, #2196f3);
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  align-items: center;
  padding: 30px;
  color: #333;
}

/* 🧾 JUDUL */
h2 {
  color: white;
  margin-bottom: 25px;
  font-size: 26px;
  text-shadow: 1px 1px 4px rgba(0,0,0,0.3);
}

/* 💳 FORM PEMBAYARAN */
form {
  background: white;
  width: 400px;
  padding: 25px;
  border-radius: 12px;
  box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

form p {
  margin-bottom: 10px;
  font-size: 15px;
  color: #333;
}

strong {
  color: #0288d1;
}

/* 🧾 LABEL & INPUT */
label {
  display: block;
  font-weight: 600;
  margin: 10px 0 5px;
  color: #333;
}

input[type="number"],
select {
  width: 100%;
  padding: 10px;
  border-radius: 8px;
  border: 1px solid #ccc;
  margin-bottom: 15px;
  font-size: 14px;
  transition: 0.2s ease;
}

input:focus,
select:focus {
  border-color: #0288d1;
  outline: none;
  box-shadow: 0 0 5px rgba(2, 136, 209, 0.4);
}

/* 💚 TOMBOL BAYAR */
button {
  background: #4caf50;
  color: white;
  border: none;
  padding: 10px;
  width: 100%;
  border-radius: 8px;
  font-size: 15px;
  font-weight: 600;
  cursor: pointer;
  transition: 0.3s;
}

button:hover {
  background: #43a047;
  transform: scale(1.03);
}

/* 📱 RESPONSIVE */
@media (max-width: 600px) {
  form {
    width: 90%;
    padding: 20px;
  }

  h2 {
    font-size: 22px;
  }
}

  </style>
<body>
  <h2 style="text-align:center;">Pembayaran Tiket</h2>
  <form action="act_pembayaran.php" method="POST">
    <input type="hidden" name="id_pemesanan" value="<?= $pemesanan['id_pemesanan'] ?>">
    <p><strong>Nama Bus:</strong> <?= htmlspecialchars($pemesanan['nama_bus']) ?></p>
    <p><strong>Total Bayar:</strong> Rp <?= number_format($pemesanan['total_harga'], 0, ',', '.') ?></p>

    <label>Metode Pembayaran:</label>
    <select name="metode_pembayaran" required>
      <option value="Transfer Bank">Transfer Bank</option>
      <option value="E-Wallet">E-Wallet</option>
      <option value="Kartu Kredit">Kartu Kredit</option>
    </select>

    <label>Jumlah Bayar:</label>
    <input type="number" name="jumlah_bayar" value="<?= $pemesanan['total_harga'] ?>" readonly>

    <button type="submit">Bayar Sekarang</button>
  </form>
</body>
</html>
