<?php
require 'connect.php'; // session_start() sudah otomatis dari sini

// Pastikan user sudah login
if (!isset($_SESSION['id_pengguna'])) {
  header("Location: login.php");
  exit;
}

$id_pengguna = $_SESSION['id_pengguna'];

// Ambil semua data pemesanan milik pengguna yang login
$stmt = $conn->prepare("
  SELECT 
    pg.nama AS nama_pengguna,
    b.nama_bus,
    b.asal,
    b.tujuan,
    p.jumlah_tiket,
    p.total_harga,
    p.status_pemesanan,
    pay.metode_pembayaran,
    pay.jumlah_bayar,
    pay.status_pembayaran,
    pay.tanggal_bayar
  FROM tb_pemesanan p
  JOIN tb_bus b ON p.id_bus = b.id_bus
  JOIN tb_pengguna pg ON p.id_pengguna = pg.id_pengguna
  LEFT JOIN tb_pembayaran pay ON p.id_pemesanan = pay.id_pemesanan
  WHERE p.id_pengguna = ?
  ORDER BY p.tanggal_pesan DESC
");
$stmt->bind_param("i", $id_pengguna);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Pesanan Tiket Bus</title>
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
  padding: 30px;
  color: #333;
}

/* 🧾 JUDUL */
h2 {
  text-align: center;
  color: white;
  font-size: 26px;
  margin-bottom: 25px;
  text-shadow: 1px 1px 4px rgba(0,0,0,0.3);
}

/* 🧮 TABEL PEMESANAN */
table {
  width: 100%;
  border-collapse: collapse;
  background: white;
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

th, td {
  padding: 12px 10px;
  text-align: center;
  border-bottom: 1px solid #eee;
}

th {
  background-color: #0288d1;
  color: white;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

tr:nth-child(even) {
  background-color: #f4f9ff;
}

tr:hover {
  background-color: #e3f2fd;
  transition: 0.2s;
}

/* 💳 TOMBOL BAYAR */
.btn-bayar {
  display: inline-block;
  background: #4caf50;
  color: white;
  padding: 6px 12px;
  border-radius: 6px;
  text-decoration: none;
  font-weight: 500;
  transition: 0.3s;
}

.btn-bayar:hover {
  background: #43a047;
  transform: scale(1.05);
}

/* ⬅ LINK KEMBALI */
p a {
  display: inline-block;
  text-decoration: none;
  background: #ff0000ff;
  color: white;
  padding: 8px 16px;
  border-radius: 6px;
  transition: 0.3s;
}

p a:hover {
  background: #01579b;
  transform: scale(1.05);
}

/* 📱 RESPONSIVE */
@media (max-width: 768px) {
  table, th, td {
    font-size: 12px;
    padding: 8px;
  }

  h2 {
    font-size: 20px;
  }

  p a {
    padding: 6px 12px;
  }
}
  </style>
</head>
<body>
  <h2>Data Pemesanan Tiket Bus</h2>

  <table>
    <tr>
      <th>Nama Pengguna</th>
      <th>Nama Bus</th>
      <th>Rute</th>
      <th>Jumlah Tiket</th>
      <th>Total Harga</th>
      <th>Status Pemesanan</th>
      <th>Metode Pembayaran</th>
      <th>Jumlah Bayar</th>
      <th>Status Pembayaran</th>
      <th>Tanggal Bayar</th>
      <th>Aksi</th>
    </tr>

    <?php if ($result && $result->num_rows > 0): ?>
      <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?= htmlspecialchars($row['nama_pengguna']) ?></td>
          <td><?= htmlspecialchars($row['nama_bus']) ?></td>
          <td><?= htmlspecialchars($row['asal'] . " - " . $row['tujuan']) ?></td>
          <td><?= $row['jumlah_tiket'] ?></td>
          <td>Rp <?= number_format($row['total_harga'], 0, ',', '.') ?></td>
          <td><?= htmlspecialchars($row['status_pemesanan']) ?></td>
          <td><?= htmlspecialchars($row['metode_pembayaran'] ?? '-') ?></td>
          <td><?= $row['jumlah_bayar'] ? 'Rp ' . number_format($row['jumlah_bayar'], 0, ',', '.') : '-' ?></td>
          <td><?= htmlspecialchars($row['status_pembayaran'] ?? '-') ?></td>
          <td><?= htmlspecialchars($row['tanggal_bayar'] ?? '-') ?></td>
          <td>
            <?php if ($row['status_pemesanan'] == 'Menunggu Pembayaran'): ?>
              <a href="pembayaran.php?id_pemesanan=<?= $row['id_pemesanan'] ?>" class="btn-bayar">Bayar Sekarang</a>
            <?php else: ?>
              <span style="color: gray;">-</span>
            <?php endif; ?>
          </td>
        </tr>
      <?php endwhile; ?>
    <?php else: ?>
      <tr><td colspan="12">Belum ada data pemesanan.</td></tr>
    <?php endif; ?>
  </table>

  <p style="margin-top:20px; text-align:center;">
    <a href="index.php">Kembali</a>
  </p>
</body>
</html>
