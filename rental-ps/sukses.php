<?php
require 'config/koneksi.php';

if (!isset($_GET['id'])) { header("Location: index.php"); exit; }

$id = $_GET['id'];

$sql = "SELECT booking.*, playstation.nama_ps 
        FROM booking 
        JOIN playstation ON booking.playstation_id = playstation.id 
        WHERE booking.id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$id]);
$data = $stmt->fetch();

if (!$data) { echo "Data tidak ditemukan"; exit; }
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Berhasil!</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card border-success shadow">
                    <div class="card-header bg-success text-white text-center">
                        <h4>✅ Booking Berhasil!</h4>
                    </div>
                    <div class="card-body text-center">
                        <h1 class="display-1">🎮</h1>
                        <h5 class="card-title">Terima kasih, <?= htmlspecialchars($data['nama_pelanggan']) ?></h5>
                        <p class="card-text">Pesanan kamu sudah masuk ke sistem kami.</p>
                        
                        <div class="alert alert-secondary text-start">
                            <p class="mb-1"><strong>Unit:</strong> <?= htmlspecialchars($data['nama_ps']) ?></p>
                            <p class="mb-1"><strong>Tanggal:</strong> <?= $data['tanggal_booking'] ?></p>
                            <p class="mb-1"><strong>Jam:</strong> <?= substr($data['jam_mulai'], 0, 5) ?> - <?= substr($data['jam_selesai'], 0, 5) ?></p>
                            <p class="mb-0"><strong>Total Bayar:</strong> Rp <?= number_format($data['total_harga'], 0, ',', '.') ?></p>
                        </div>

                        <p class="small text-muted">Silakan datang 10 menit sebelum jam main.</p>
                        
                        <a href="https://wa.me/6282358570522?text=Halo%20Admin,%20saya%20sudah%20booking%20<?= $data['nama_ps'] ?>%20atas%20nama%20<?= $data['nama_pelanggan'] ?>" 
                           class="btn btn-success w-100">
                           Konfirmasi ke WhatsApp Admin 📲
                        </a>

                        <a href="index.php" class="btn btn-outline-dark w-100 mt-2">Kembali ke Menu Utama</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>