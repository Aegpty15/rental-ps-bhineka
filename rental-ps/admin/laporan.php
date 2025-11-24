<?php
session_start();
require '../config/koneksi.php';

header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

if (!isset($_SESSION['admin'])) { header("Location: login.php"); exit; }


$q_hari = $conn->prepare("SELECT SUM(total_harga) FROM booking WHERE status_booking = 'selesai' AND DATE(tanggal_booking) = CURDATE()");
$q_hari->execute();
$hari_ini = $q_hari->fetchColumn() ?? 0;

$q_minggu = $conn->prepare("SELECT SUM(total_harga) FROM booking WHERE status_booking = 'selesai' AND YEARWEEK(tanggal_booking, 1) = YEARWEEK(CURDATE(), 1)");
$q_minggu->execute();
$minggu_ini = $q_minggu->fetchColumn() ?? 0;

$q_bulan = $conn->prepare("SELECT SUM(total_harga) FROM booking WHERE status_booking = 'selesai' AND MONTH(tanggal_booking) = MONTH(CURDATE()) AND YEAR(tanggal_booking) = YEAR(CURDATE())");
$q_bulan->execute();
$bulan_ini = $q_bulan->fetchColumn() ?? 0;

$q_unit = "SELECT p.nama_ps, COUNT(b.id) as total_main, SUM(b.total_harga) as omset 
           FROM playstation p 
           LEFT JOIN booking b ON p.id = b.playstation_id AND b.status_booking = 'selesai'
           GROUP BY p.id ORDER BY omset DESC";
$stats_unit = $conn->query($q_unit)->fetchAll(PDO::FETCH_ASSOC);

$q_transaksi = "SELECT booking.*, playstation.nama_ps 
                FROM booking 
                JOIN playstation ON booking.playstation_id = playstation.id 
                WHERE status_booking = 'selesai' 
                ORDER BY tanggal_booking DESC, id DESC LIMIT 50";
$history = $conn->query($q_transaksi)->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Keuangan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../assets/css/admin-style.css">
</head>
<body>

    <nav class="navbar navbar-dark bg-primary d-md-none p-3 shadow-sm">
        <span class="navbar-brand fw-bold">ADMIN PANEL</span>
        <button class="btn btn-outline-light btn-sm" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMobile">
            <i class="bi bi-list"></i>
        </button>
    </nav>

    <div class="d-flex">
        
        <div class="d-none d-md-block">
            <?php include 'components/sidebar.php'; ?>
        </div>

        <div class="offcanvas offcanvas-start bg-dark" tabindex="-1" id="sidebarMobile">
            <div class="offcanvas-body p-0">
                <?php include 'components/sidebar.php'; ?>
            </div>
        </div>

        <div class="main-content w-100">
            
            <div class="d-flex justify-content-between align-items-center mb-5">
                <div>
                    <h3 class="text-white fw-bold mb-1"><i class="bi bi-graph-up-arrow me-2 text-success"></i> Laporan Keuangan</h3>
                    <p class="text-muted small mb-0">Ringkasan pendapatan dan performa rental.</p>
                </div>
            </div>

            <div class="row mb-5 g-4">
                <div class="col-md-4">
                    <div class="card summary-card green h-100 p-4">
                        <div class="text-muted text-uppercase fw-bold small mb-2">Pendapatan Hari Ini</div>
                        <div class="money-value text-success">Rp <?= number_format($hari_ini, 0, ',', '.') ?></div>
                        <i class="bi bi-coin card-icon-bg text-success"></i>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card summary-card yellow h-100 p-4">
                        <div class="text-muted text-uppercase fw-bold small mb-2">Minggu Ini</div>
                        <div class="money-value text-warning">Rp <?= number_format($minggu_ini, 0, ',', '.') ?></div>
                        <i class="bi bi-calendar-week card-icon-bg text-warning"></i>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card summary-card blue h-100 p-4">
                        <div class="text-muted text-uppercase fw-bold small mb-2">Bulan Ini (<?= date('F') ?>)</div>
                        <div class="money-value text-primary">Rp <?= number_format($bulan_ini, 0, ',', '.') ?></div>
                        <i class="bi bi-bar-chart-line card-icon-bg text-primary"></i>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                
                <div class="col-lg-5">
                    <div class="card shadow-lg h-100 border-0">
                        <div class="card-header py-3">
                            <h6 class="mb-0 fw-bold text-white"><i class="bi bi-trophy me-2 text-warning"></i> Unit Terlaris</h6>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th class="ps-4">Nama Unit</th>
                                            <th class="text-center">Main</th>
                                            <th class="text-end pe-4">Omset</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($stats_unit as $u): ?>
                                        <tr>
                                            <td class="ps-4">
                                                <span class="badge-unit"><?= htmlspecialchars($u['nama_ps']) ?></span>
                                            </td>
                                            <td class="text-center text-white fw-bold"><?= $u['total_main'] ?>x</td>
                                            <td class="text-end pe-4 fw-bold text-success">
                                                Rp <?= number_format($u['omset'] ?? 0, 0, ',', '.') ?>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-7">
                    <div class="card shadow-lg h-100 border-0">
                        <div class="card-header py-3 d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 fw-bold text-white"><i class="bi bi-clock-history me-2 text-info"></i> Riwayat Transaksi</h6>
                            <span class="badge bg-success bg-opacity-10 text-success border border-success">Last 50</span>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th class="ps-4">Tanggal</th>
                                            <th>Pelanggan</th>
                                            <th>Unit</th>
                                            <th class="text-end pe-4">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($history as $h): ?>
                                        <tr>
                                            <td class="ps-4 text-muted small">
                                                <?= date('d/m/y', strtotime($h['tanggal_booking'])) ?>
                                            </td>
                                            <td>
                                                <span class="customer-name"><?= htmlspecialchars($h['nama_pelanggan']) ?></span>
                                                <span class="customer-wa"><?= htmlspecialchars($h['no_wa']) ?></span>
                                            </td>
                                            <td>
                                                <span class="badge bg-dark border border-secondary text-muted"><?= htmlspecialchars($h['nama_ps']) ?></span>
                                            </td>
                                            <td class="text-end pe-4">
                                                <span class="text-success fw-bold">
                                                    + Rp <?= number_format($h['total_harga'], 0, ',', '.') ?>
                                                </span>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php if(count($history) == 0): ?>
                                <div class="text-center py-5 text-muted small opacity-50">
                                    Belum ada data transaksi selesai.
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>