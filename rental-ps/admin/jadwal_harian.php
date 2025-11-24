<?php
session_start();
require '../config/koneksi.php';

date_default_timezone_set('Asia/Jakarta');
$jam_sekarang = date('H:i:s');
$tanggal_sekarang = date('Y-m-d');

header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

if (!isset($_SESSION['admin'])) { header("Location: login.php"); exit; }

$stmt_ps = $conn->query("SELECT * FROM playstation ORDER BY id ASC");
$ps_list = $stmt_ps->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Monitoring Jadwal</title>
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
            
            <div class="row align-items-center mb-5">
                <div class="col-md-6">
                    <h3 class="text-white fw-bold mb-1"><i class="bi bi-grid-1x2 me-2 text-info"></i> Monitoring Unit</h3>
                    <p class="text-muted mb-0">Pantau aktivitas unit PlayStation secara real-time.</p>
                </div>
                <div class="col-md-6">
                    <div class="clock-widget p-3 d-flex justify-content-between align-items-center float-md-end w-auto shadow-lg">
                        <div class="text-white me-4">
                            <small class="d-block text-white-50" style="font-size: 0.7rem;">TANGGAL HARI INI</small>
                            <span class="fw-bold"><?= date('d F Y') ?></span>
                        </div>
                        <div class="text-end">
                            <small class="d-block text-white-50" style="font-size: 0.7rem;">WAKTU SERVER</small>
                            <span id="liveClock" class="fw-bold text-warning fs-4" style="font-family: monospace;"><?= $jam_sekarang ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <?php foreach($ps_list as $ps): ?>
                    
                    <?php

                        $q_booking = "SELECT * FROM booking 
                                      WHERE playstation_id = ? 
                                      AND tanggal_booking = ? 
                                      AND status_booking != 'batal' 
                                      ORDER BY jam_mulai ASC";
                        $stmt_b = $conn->prepare($q_booking);
                        $stmt_b->execute([$ps['id'], $tanggal_sekarang]);
                        $jadwal = $stmt_b->fetchAll(PDO::FETCH_ASSOC);

                        $status_unit = "KOSONG";
                        $card_class = ""; 
                        $badge_header = "bg-success";

                        foreach($jadwal as $j) {

                            if ($jam_sekarang >= $j['jam_mulai'] && $jam_sekarang <= $j['jam_selesai']) {
                                if ($j['status_booking'] != 'selesai') {
                                    $status_unit = "SEDANG MAIN";
                                    $card_class = "active-play"; 
                                    $badge_header = "bg-danger animate-pulse";
                                    break; 
                                }
                            }
                        }

                        if($ps['status'] == 'maintenance') {
                            $status_unit = "MAINTENANCE";
                            $badge_header = "bg-secondary";
                        }
                    ?>

                    <div class="col-md-6 col-xl-4">
                        <div class="card-monitor <?= $card_class ?>">
                            
                            <div class="monitor-header">
                                <h5 class="text-white fw-bold mb-0">
                                    <i class="bi bi-controller me-2"></i> <?= htmlspecialchars($ps['nama_ps']) ?>
                                </h5>
                                <span class="badge <?= $badge_header ?> rounded-pill px-3">
                                    <?= $status_unit ?>
                                </span>
                            </div>

                            <div class="p-3">
                                <?php if(count($jadwal) > 0): ?>
                                    <div class="timeline-container">
                                        <?php foreach($jadwal as $row): ?>
                                            
                                            <?php 

                                                $isTimeMatch = ($jam_sekarang >= $row['jam_mulai'] && $jam_sekarang <= $row['jam_selesai']);
                                                $isActive = $isTimeMatch && ($row['status_booking'] != 'selesai');
                                                
                                                $rowClass = 'upcoming';
                                                if($row['status_booking'] == 'selesai') $rowClass = 'done';
                                                elseif($isActive) $rowClass = 'playing';
                                                elseif($row['status_booking'] == 'pending') $rowClass = 'pending';
                                            ?>

                                            <div class="timeline-row <?= $rowClass ?>">
                                                <div class="timeline-dot"></div>
                                                <div class="timeline-content">
                                                    <div class="d-flex justify-content-between mb-1">
                                                        <span class="text-white fw-bold" style="font-size: 0.9rem;">
                                                            <?= substr($row['jam_mulai'], 0, 5) ?> - <?= substr($row['jam_selesai'], 0, 5) ?>
                                                        </span>
                                                        <?php if($isActive): ?>
                                                            <span class="badge bg-danger" style="font-size: 0.6rem;">PLAYING</span>
                                                        <?php elseif($row['status_booking'] == 'selesai'): ?>
                                                            <span class="badge bg-success" style="font-size: 0.6rem;">SELESAI</span>
                                                        <?php elseif($row['status_booking'] == 'pending'): ?>
                                                            <span class="badge bg-warning text-dark" style="font-size: 0.6rem;">PENDING</span>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="text-white-50 small d-flex justify-content-between">
                                                        <span><i class="bi bi-person"></i> <?= htmlspecialchars($row['nama_pelanggan']) ?></span>
                                                    </div>
                                                </div>
                                            </div>

                                        <?php endforeach; ?>
                                    </div>
                                <?php else: ?>
                                    <div class="text-center py-5 opacity-50">
                                        <i class="bi bi-moon-stars display-4 text-muted"></i>
                                        <p class="text-white-50 small mt-2">Tidak ada jadwal hari ini.</p>
                                    </div>
                                <?php endif; ?>
                            </div>

                        </div>
                    </div>

                <?php endforeach; ?>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/jam.js"></script>
</body>
</html>