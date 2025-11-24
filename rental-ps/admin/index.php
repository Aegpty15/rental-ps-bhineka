<?php
session_start();
require '../config/koneksi.php';

header("Cache-Control: no-cache, no-store, must-revalidate"); header("Pragma: no-cache"); header("Expires: 0");

if (!isset($_SESSION['admin'])) { header("Location: login.php"); exit; }

if (isset($_POST['toggle_toko'])) {
    $status_baru = $_POST['status_saat_ini'] == 'buka' ? 'tutup' : 'buka';
    $stmt = $conn->prepare("UPDATE pengaturan SET nilai_setting = ? WHERE nama_setting = 'status_toko'");
    $stmt->execute([$status_baru]);
    header("Location: index.php");
    exit;
}

$stmt = $conn->query("SELECT nilai_setting FROM pengaturan WHERE nama_setting = 'status_toko'");
$status_toko = $stmt->fetchColumn();

$stmt = $conn->query("SELECT COUNT(*) FROM booking WHERE status_booking = 'pending'");
$pending = $stmt->fetchColumn();

$hari_ini = date('Y-m-d');
$stmt = $conn->prepare("SELECT COUNT(*) FROM booking WHERE tanggal_booking = ?");
$stmt->execute([$hari_ini]);
$booking_today = $stmt->fetchColumn();

$stmt = $conn->query("SELECT SUM(total_harga) FROM booking WHERE status_booking = 'selesai'");
$pendapatan = $stmt->fetchColumn() ?? 0;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../assets/css/admin-style.css">
</head>
<body>

    <nav class="mobile-nav shadow-sm">
        <span class="navbar-brand fw-bold text-white">ADMIN</span>
        <button class="btn btn-outline-light btn-sm border-secondary" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMobile"><i class="bi bi-list"></i></button>
    </nav>

    <div class="d-flex">
        <div class="sidebar-desktop"><?php include 'components/sidebar.php'; ?></div>
        <div class="offcanvas offcanvas-start bg-dark" id="sidebarMobile"><div class="offcanvas-body p-0"><?php include 'components/sidebar.php'; ?></div></div>

        <div class="main-content w-100">
            
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                <div>
                    <h3 class="fw-bold text-white mb-0">Dashboard Overview</h3>
                    <p class="text-muted small mb-0">Status Toko: 
                        <?php if($status_toko == 'buka'): ?>
                            <span class="badge bg-success">BUKA (ONLINE)</span>
                        <?php else: ?>
                            <span class="badge bg-danger">TUTUP (OFFLINE)</span>
                        <?php endif; ?>
                    </p>
                </div>

                <form method="POST">
                    <input type="hidden" name="toggle_toko" value="1">
                    <input type="hidden" name="status_saat_ini" value="<?= $status_toko ?>">
                    
                    <?php if($status_toko == 'buka'): ?>
                        <button type="submit" class="btn btn-danger fw-bold px-4 shadow-sm">
                            <i class="bi bi-power me-2"></i> TUTUP TOKO SEKARANG
                        </button>
                    <?php else: ?>
                        <button type="submit" class="btn btn-success fw-bold px-4 shadow-sm">
                            <i class="bi bi-power me-2"></i> BUKA TOKO SEKARANG
                        </button>
                    <?php endif; ?>
                </form>
            </div>

            <div class="row g-3">
                <div class="col-12 col-md-4">
                    <div class="card h-100 border-0 bg-warning bg-opacity-10 border-warning">
                        <div class="card-body">
                            <h6 class="text-warning text-uppercase small fw-bold">Perlu Konfirmasi</h6>
                            <h2 class="fw-bold text-white mb-0"><?= $pending ?></h2>
                        </div>
                        <a href="booking.php" class="card-footer bg-transparent border-top border-warning text-warning text-decoration-none small d-flex justify-content-between">Proses Sekarang <i class="bi bi-arrow-right"></i></a>
                    </div>
                </div>

                <div class="col-12 col-md-4">
                    <div class="card h-100 border-0 bg-info bg-opacity-10 border-info">
                        <div class="card-body">
                            <h6 class="text-info text-uppercase small fw-bold">Booking Hari Ini</h6>
                            <h2 class="fw-bold text-white mb-0"><?= $booking_today ?></h2>
                        </div>
                        <a href="jadwal_harian.php" class="card-footer bg-transparent border-top border-info text-info text-decoration-none small d-flex justify-content-between">Cek Jadwal <i class="bi bi-arrow-right"></i></a>
                    </div>
                </div>

                <div class="col-12 col-md-4">
                    <div class="card h-100 border-0 bg-success bg-opacity-10 border-success">
                        <div class="card-body">
                            <h6 class="text-success text-uppercase small fw-bold">Total Pendapatan</h6>
                            <h2 class="fw-bold text-white mb-0">Rp <?= number_format((float)$pendapatan, 0, ',', '.') ?></h2>
                        </div>
                        <a href="laporan.php" class="card-footer bg-transparent border-top border-success text-success text-decoration-none small d-flex justify-content-between">Rincian Keuangan <i class="bi bi-arrow-right"></i></a>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>