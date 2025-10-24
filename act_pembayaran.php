<?php
require 'connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_pemesanan = intval($_POST['id_pemesanan']);
    $metode = $_POST['metode_pembayaran'];
    $jumlah = floatval($_POST['jumlah_bayar']);
    $tanggal = date('Y-m-d H:i:s');

    // Simpan data ke tabel pembayaran
    $stmt = $conn->prepare("
        INSERT INTO tb_pembayaran 
        (id_pemesanan, metode_pembayaran, jumlah_bayar, tanggal_bayar, status_pembayaran) 
        VALUES (?, ?, ?, ?, 'Berhasil')
    ");
    $stmt->bind_param("isds", $id_pemesanan, $metode, $jumlah, $tanggal);

    if ($stmt->execute()) {
        // Update status pemesanan menjadi 'Dibayar' (status valid di ENUM kamu)
        $conn->query("UPDATE tb_pemesanan SET status_pemesanan = 'Dibayar' WHERE id_pemesanan = $id_pemesanan");

        echo "<script>
            alert('✅ Pembayaran berhasil dikonfirmasi!');
            window.location='pesanan_saya.php';
        </script>";
    } else {
        echo "<script>
            alert('❌ Gagal menyimpan data pembayaran.');
            window.location='pembayaran.php?id_pemesanan=$id_pemesanan';
        </script>";
    }

    $stmt->close();
} else {
    header("Location: pesanan_saya.php");
    exit();
}
?>
