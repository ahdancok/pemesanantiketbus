<?php
require 'connect.php'; // di sini session_start() sudah otomatis aktif

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_bus = intval($_POST['id_bus']);
    $jumlah_tiket = intval($_POST['jumlah_tiket']);
    $id_pengguna = $_SESSION['id_pengguna']; // 🧠 ambil dari session aktif

    // Ambil harga tiket
    $result = $conn->query("SELECT harga_tiket, jumlah_kursi FROM tb_bus WHERE id_bus = $id_bus");
    $bus = $result->fetch_assoc();

    if (!$bus) {
        die("Bus tidak ditemukan.");
    }

    // Hitung total harga
    $total_harga = $bus['harga_tiket'] * $jumlah_tiket;

    // Pastikan kursi masih cukup
    if ($jumlah_tiket > $bus['jumlah_kursi']) {
        die("Kursi tidak cukup.");
    }

    // Simpan data ke tb_pemesanan
    $stmt = $conn->prepare("
        INSERT INTO tb_pemesanan 
        (id_pengguna, id_bus, jumlah_tiket, total_harga, status_pemesanan, tanggal_pesan)
        VALUES (?, ?, ?, ?, 'Menunggu Pembayaran', NOW())
    ");
    $stmt->bind_param("iiid", $id_pengguna, $id_bus, $jumlah_tiket, $total_harga);
    
    if ($stmt->execute()) {
        // Kurangi jumlah kursi
        $conn->query("UPDATE tb_bus SET jumlah_kursi = jumlah_kursi - $jumlah_tiket WHERE id_bus = $id_bus");
        header("Location: pesanan_saya.php");
        exit;
    } else {
        echo "❌ Gagal menyimpan data pemesanan.";
    }
}
?>
