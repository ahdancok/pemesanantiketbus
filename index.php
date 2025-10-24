<?php 
require 'connect.php'; 
if (!isset($_SESSION['id_pengguna'])) {
  header("Location: login.php");
  exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Daftar Bus - Tiket Online</title>
<link rel="stylesheet" href="tampilan.css">
</head>
<body>
<header>
  <h2>Selamat Datang, <?= htmlspecialchars($_SESSION['nama']) ?>!</h2>
  <nav>
    <a href="pesanan_saya.php">Pesanan Saya</a> |
    <a href="logout.php">Logout</a>
  </nav>
</header>

<h3>Daftar Bus Tersedia</h3>
<table border="1" cellpadding="8" cellspacing="0">
<tr>
  <th>Nama Bus</th>
  <th>Kelas</th>
  <th>Asal</th>
  <th>Tujuan</th>
  <th>Harga</th>
  <th>Tanggal</th>
  <th>Aksi</th>
</tr>
<?php
$result = $conn->query("SELECT * FROM tb_bus WHERE jumlah_kursi > 0 ORDER BY tanggal_berangkat ASC");
while ($row = $result->fetch_assoc()) {
  echo "<tr>
    <td>".htmlspecialchars($row['nama_bus'])."</td>
    <td>".htmlspecialchars($row['kelas'])."</td>
    <td>".htmlspecialchars($row['asal'])."</td>
    <td>".htmlspecialchars($row['tujuan'])."</td>
    <td>Rp " . number_format($row['harga_tiket'],0,',','.') . "</td>
    <td>".htmlspecialchars($row['tanggal_berangkat'])."</td>
    <td><a href='pesan.php?id_bus=".urlencode($row['id_bus'])."'>Pesan Sekarang</a></td>
  </tr>";
}
?>
</table>
</body>
</html>