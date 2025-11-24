<?php
require 'config/koneksi.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM playstation WHERE id = ?");
$stmt->execute([$id]);
$ps = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$ps) {
    echo "<script>alert('Data unit tidak ditemukan!'); window.location='index.php';</script>";
    exit;
}

$gambar = "https://placehold.co/600x400?text=No+Image";
if (!empty($ps['foto']) && file_exists('assets/img/' . $ps['foto'])) {
    $gambar = 'assets/img/' . $ps['foto'];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking <?= htmlspecialchars($ps['nama_ps']) ?></title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <nav class="navbar navbar-dark bg-transparent d-md-none p-3 border-bottom border-secondary">
        <span class="navbar-brand fw-bold text-primary">BHINEKA.PS</span>
        <button class="btn btn-outline-primary btn-sm" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMobile">
            <i class="bi bi-list"></i>
        </button>
    </nav>

    <div class="container-fluid">
        <div class="row">
            
            <div class="col-md-3 col-lg-2 px-0 d-none d-md-block position-fixed">
                <?php include 'components/sidebar.php'; ?>
            </div>

            <div class="offcanvas offcanvas-start bg-dark text-white" tabindex="-1" id="sidebarMobile">
                <div class="offcanvas-body p-0">
                    <?php include 'components/sidebar.php'; ?>
                </div>
            </div>

            <div class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4 min-vh-100">
                
                <a href="index.php" class="btn btn-outline-light mb-4 rounded-pill px-4">
                    <i class="bi bi-arrow-left me-2"></i> Kembali
                </a>

                <div class="row g-4">
                    
                    <div class="col-md-5">
                        <div class="card h-100 border-0 shadow-lg overflow-hidden position-relative">
                            <img src="<?= $gambar ?>" class="card-img-top" style="height: 350px; object-fit: cover;">
                            
                            <div class="card-body p-4">
                                <h2 class="fw-bold text-white mb-2"><?= htmlspecialchars($ps['nama_ps']) ?></h2>
                                <span class="badge badge-console mb-3"><?= htmlspecialchars($ps['tipe']) ?></span>
                                
                                <p class="text-white-50"><?= nl2br(htmlspecialchars($ps['deskripsi'])) ?></p>
                                
                                <hr class="border-secondary my-4">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-white-50">Harga Sewa</span>
                                    <h3 class="text-primary fw-bold mb-0">
                                        Rp <?= number_format($ps['harga_per_jam'], 0, ',', '.') ?> 
                                        <span class="fs-6 text-muted">/jam</span>
                                    </h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-7">
                        <div class="card border-0 shadow-lg">
                            <div class="card-header bg-transparent border-bottom border-secondary py-3">
                                <h5 class="mb-0 text-white fw-bold">
                                    <i class="bi bi-calendar-plus text-primary me-2"></i> Formulir Booking
                                </h5>
                            </div>
                            <div class="card-body p-4">
                                <form action="proses_booking.php" method="POST">
                                    <input type="hidden" name="playstation_id" value="<?= $ps['id'] ?>">
                                    <input type="hidden" id="harga_per_jam" value="<?= $ps['harga_per_jam'] ?>">

                                    <div class="mb-3">
                                        <label class="text-white-50 mb-1">Nama Lengkap</label>
                                        <input type="text" name="nama" class="form-control bg-dark text-white border-secondary" placeholder="Masukkan nama..." required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="text-white-50 mb-1">Nomor WhatsApp</label>
                                        <input type="number" name="no_wa" class="form-control bg-dark text-white border-secondary" placeholder="08xxxxxxxxxx" required>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="text-white-50 mb-1">Tanggal Main</label>
                                            <input type="date" name="tanggal" class="form-control bg-dark text-white border-secondary" required min="<?= date('Y-m-d') ?>">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="text-white-50 mb-1">Jam Mulai</label>
                                            <input type="time" name="jam_mulai" class="form-control bg-dark text-white border-secondary" required>
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <label class="text-white-50 mb-1">Durasi Sewa (Jam)</label>
                                        <input type="number" id="durasi" name="durasi" class="form-control bg-dark text-white border-secondary" min="1" max="12" value="1" required>
                                    </div>

                                    <div class="alert alert-dark border-secondary d-flex justify-content-between align-items-center mb-4">
                                        <span class="text-white-50">Total Bayar:</span>
                                        <span id="total_harga" class="fs-3 fw-bold text-success">Rp 0</span>
                                    </div>

                                    <button type="submit" class="btn btn-primary w-100 py-3 fw-bold shadow-lg text-uppercase tracking-wide">
                                        Konfirmasi Booking
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    
    <script>
        const hargaPerJam = document.getElementById('harga_per_jam').value;
        const inputDurasi = document.getElementById('durasi');
        const labelTotal = document.getElementById('total_harga');

        function hitungTotal() {
            let durasi = inputDurasi.value;
            if (durasi < 1) durasi = 1;
            
            let total = durasi * hargaPerJam;
            
            labelTotal.innerText = new Intl.NumberFormat('id-ID', { 
                style: 'currency', 
                currency: 'IDR', 
                minimumFractionDigits: 0 
            }).format(total);
        }

        inputDurasi.addEventListener('input', hitungTotal);
        
        hitungTotal();
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>