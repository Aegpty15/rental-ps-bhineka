<?php
session_start();
require '../config/koneksi.php';

header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

if (!isset($_SESSION['admin'])) { header("Location: login.php"); exit; }

if (isset($_GET['aksi']) && isset($_GET['id'])) {
    $id = $_GET['id'];
    $aksi = $_GET['aksi'];
    
    if ($aksi == 'konfirmasi') { $conn->query("UPDATE booking SET status_booking = 'confirmed' WHERE id = $id"); } 
    elseif ($aksi == 'selesai') { $conn->query("UPDATE booking SET status_booking = 'selesai' WHERE id = $id"); } 
    elseif ($aksi == 'batal') { $conn->query("UPDATE booking SET status_booking = 'batal' WHERE id = $id"); } 
    elseif ($aksi == 'hapus') { 
        $stmt = $conn->prepare("SELECT bukti_pembayaran FROM booking WHERE id = ?");
        $stmt->execute([$id]);
        $img = $stmt->fetchColumn();
        if($img && file_exists("../assets/bukti_bayar/$img")) { unlink("../assets/bukti_bayar/$img"); }

        $conn->query("DELETE FROM booking WHERE id = $id");
    }

    header("Location: booking.php");
    exit;
}

$query = "SELECT booking.*, playstation.nama_ps 
          FROM booking 
          JOIN playstation ON booking.playstation_id = playstation.id 
          ORDER BY booking.tanggal_booking DESC, booking.jam_mulai DESC";
$stmt = $conn->query($query);
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Booking</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../assets/css/admin-style.css">
</head>
<body>

    <nav class="mobile-nav shadow-sm">
        <span class="navbar-brand fw-bold text-white">ADMIN</span>
        <button class="btn btn-outline-light btn-sm border-secondary" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMobile"><i class="bi bi-list fs-5"></i></button>
    </nav>

    <div class="d-flex">
        
        <div class="sidebar-desktop"><?php include 'components/sidebar.php'; ?></div>
        <div class="offcanvas offcanvas-start bg-dark" id="sidebarMobile"><div class="offcanvas-body p-0"><?php include 'components/sidebar.php'; ?></div></div>

        <div class="main-content">
            
            <h3 class="fw-bold text-white mb-4">📂 Kelola Booking</h3>
            
            <div class="alert alert-info border-0 bg-opacity-10 shadow-sm mb-4 d-flex align-items-center">
                <i class="bi bi-info-circle-fill me-3 fs-4"></i>
                <div class="small">
                    <strong>Tips:</strong> Cek <b>Bukti Bayar</b> sebelum menerima pesanan.
                </div>
            </div>

            <div class="card shadow-lg border-0">
                <div class="card-header py-3">
                    <h5 class="mb-0 fw-bold text-white"><i class="bi bi-table me-2"></i> Daftar Pesanan Masuk</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-4">No</th>
                                    <th>Pelanggan</th>
                                    <th>Unit</th>
                                    <th>Waktu</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th class="text-end pe-4">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($bookings as $index => $row): ?>
                                <tr>
                                    <td class="ps-4 text-muted fw-bold"><?= $index + 1 ?></td>
                                    
                                    <td>
                                        <span class="customer-name"><?= htmlspecialchars($row['nama_pelanggan']) ?></span>
                                        <span class="customer-wa"><i class="bi bi-whatsapp me-1"></i> <?= htmlspecialchars($row['no_wa']) ?></span>
                                    </td>
                                    
                                    <td><span class="badge-unit"><?= htmlspecialchars($row['nama_ps']) ?></span></td>
                                    
                                    <td>
                                        <div class="text-white fw-bold small">
                                            <?= date('d/M', strtotime($row['tanggal_booking'])) ?>
                                        </div>
                                        <div class="text-muted small">
                                            <?= substr($row['jam_mulai'], 0, 5) ?>-<?= substr($row['jam_selesai'], 0, 5) ?>
                                        </div>
                                    </td>
                                    
                                    <td class="fw-bold text-success small">
                                        Rp <?= number_format($row['total_harga'], 0, ',', '.') ?>
                                    </td>
                                    
                                    <td>
                                        <?php 
                                            $st = $row['status_booking'];
                                            $badgeClass = ($st == 'confirmed') ? 'confirmed' : (($st == 'selesai') ? 'selesai' : (($st == 'batal') ? 'batal' : 'pending'));
                                        ?>
                                        <span class="badge-status <?= $badgeClass ?>"><?= strtoupper($st) ?></span>
                                    </td>
                                    
                                    <td class="text-end pe-4">
                                        
                                        <?php if(!empty($row['bukti_pembayaran'])): ?>
                                            <button class="btn btn-sm btn-info text-white me-1" data-bs-toggle="modal" data-bs-target="#modalBukti<?= $row['id'] ?>" title="Cek Bukti">
                                                <i class="bi bi-receipt"></i>
                                            </button>

                                            <div class="modal fade" id="modalBukti<?= $row['id'] ?>" tabindex="-1">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content bg-dark text-white border-secondary">
                                                        <div class="modal-header border-secondary"><h5 class="modal-title">Bukti Bayar</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div>
                                                        <div class="modal-body text-center"><img src="../assets/bukti_bayar/<?= $row['bukti_pembayaran'] ?>" class="img-fluid rounded mb-3 shadow"><div class="alert alert-light bg-opacity-10 border-0 text-white text-start small">Metode: <strong><?= htmlspecialchars($row['metode_pembayaran'] ?? 'Cash') ?></strong></div></div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endif; ?>

                                        <?php if($st == 'pending'): ?>
                                            <a href="booking.php?aksi=konfirmasi&id=<?= $row['id'] ?>" class="btn btn-sm btn-success me-1" title="Terima"><i class="bi bi-check-lg"></i><span class="d-none d-md-inline"> Terima</span></a>
                                            <a href="booking.php?aksi=batal&id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Tolak?')"><i class="bi bi-x-lg"></i><span class="d-none d-md-inline"> Tolak</span></a>
                                        
                                        <?php elseif($st == 'confirmed'): ?>
                                            <a href="booking.php?aksi=selesai&id=<?= $row['id'] ?>" class="btn btn-sm btn-primary fw-bold px-3 shadow-sm">
                                                <i class="bi bi-flag-fill d-md-none"></i><span class="d-none d-md-inline">Selesai</span>
                                            </a>
                                        
                                        <?php else: ?>
                                            <a href="booking.php?aksi=hapus&id=<?= $row['id'] ?>" class="btn-trash" onclick="return confirm('Hapus Permanen?')"><i class="bi bi-trash3"></i></a>
                                        <?php endif; ?>

                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <?php if(count($bookings) == 0): ?>
                        <div class="text-center py-5 opacity-50">
                            <i class="bi bi-inbox display-1 text-muted"></i>
                            <p class="text-muted mt-3">Belum ada data pesanan.</p>
                        </div>
                    <?php endif; ?>

                </div>
            </div>

        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>