<?php
require 'config/koneksi.php';
$stmt_toko = $conn->query("SELECT nilai_setting FROM pengaturan WHERE nama_setting = 'status_toko'");
$status_toko = $stmt_toko->fetchColumn();

if ($status_toko == 'tutup') {
    header("Location: maintenance.php");
    exit;
}

date_default_timezone_set('Asia/Jakarta');
$jam_sekarang = date('H:i:s');
$tanggal_sekarang = date('Y-m-d');

$stmt = $conn->query("SELECT * FROM playstation ORDER BY id ASC");
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

$hasil_cari = [];
$search_triggered = false;
if (isset($_GET['cari_wa'])) {
    $search_triggered = true;
    $wa = htmlspecialchars($_GET['cari_wa']);
    $q_cari = "SELECT booking.*, playstation.nama_ps 
               FROM booking JOIN playstation ON booking.playstation_id = playstation.id 
               WHERE no_wa = ? AND status_booking IN ('pending', 'confirmed') ORDER BY id DESC";     
    $stmt_cari = $conn->prepare($q_cari);
    $stmt_cari->execute([$wa]);
    $hasil_cari = $stmt_cari->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rental PS Bhineka</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <nav class="navbar navbar-dark bg-transparent d-md-none p-3 border-bottom border-secondary">
        <span class="navbar-brand fw-bold text-primary">BHINEKA.PS</span>
        <button class="btn btn-outline-primary btn-sm" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMobile"><i class="bi bi-list"></i></button>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3 col-lg-2 px-0 d-none d-md-block position-fixed"><?php include 'components/sidebar.php'; ?></div>
            <div class="offcanvas offcanvas-start bg-dark text-white" id="sidebarMobile"><div class="offcanvas-body p-0"><?php include 'components/sidebar.php'; ?></div></div>

            <div class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4 min-vh-100">
                
                <div class="d-flex justify-content-between align-items-center mb-5 mt-2">
                    <div>
                        <h2 class="fw-bold text-white mb-0">🔥 Pilih Console & Mainkan</h2>
                        <p class="text-white-50 mb-0 small">Cek antrian unit hari ini (<?= date('d M Y') ?>).</p>
                    </div>
                    <span class="badge bg-primary rounded-pill px-3 py-2">Total: <?= count($result) ?> Unit</span>
                </div>

                <?php if($search_triggered): ?>
                    <div class="alert alert-dark border-secondary mb-5">
                        <h5 class="alert-heading text-white">Hasil Pencarian: <?= $wa ?></h5>
                        <hr class="border-secondary">
                        <?php if(count($hasil_cari) > 0): ?>
                            <div class="table-responsive">
                                <table class="table table-dark table-hover mb-0">
                                    <thead><tr><th>Unit</th><th>Waktu</th><th>Status</th></tr></thead>
                                    <tbody>
                                        <?php foreach($hasil_cari as $b): ?>
                                            <tr>
                                                <td class="text-white"><?= htmlspecialchars($b['nama_ps']) ?></td>
                                                <td class="text-white-50"><?= substr($b['jam_mulai'],0,5) ?> - <?= substr($b['jam_selesai'],0,5) ?></td>
                                                <td><span class="badge bg-<?= $b['status_booking'] == 'confirmed' ? 'success' : 'warning' ?>"><?= strtoupper($b['status_booking']) ?></span></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <p class="text-danger mb-0">Data tidak ditemukan.</p>
                        <?php endif; ?>
                        <a href="index.php" class="btn btn-sm btn-outline-light mt-3">Tutup</a>
                    </div>
                <?php endif; ?>

                <div class="row">
                    <?php foreach($result as $row): ?>
                        
                        <?php 
                            $q_jadwal = "SELECT * FROM booking 
                                         WHERE playstation_id = ? 
                                         AND tanggal_booking = ? 
                                         AND status_booking IN ('pending', 'confirmed')
                                         ORDER BY jam_mulai ASC";
                            $stmt_jadwal = $conn->prepare($q_jadwal);
                            $stmt_jadwal->execute([$row['id'], $tanggal_sekarang]);
                            $list_booking = $stmt_jadwal->fetchAll(PDO::FETCH_ASSOC);

                            $status_tampil = "TERSEDIA";
                            $badge_color = "bg-success";
                            $tombol_aksi = '<a href="detail.php?id='.$row['id'].'" class="btn btn-primary w-100 fw-bold">BOOKING SEKARANG</a>';
                            
                            $sedang_main = false;

                            if ($row['status'] == 'maintenance') {
                                $status_tampil = "MAINTENANCE"; 
                                $badge_color = "bg-secondary";
                                $tombol_aksi = '<button class="btn btn-dark w-100 text-muted" disabled>PERBAIKAN</button>';
                            } else {
                                foreach($list_booking as $bk) {
                                    if ($jam_sekarang >= $bk['jam_mulai'] && $jam_sekarang <= $bk['jam_selesai']) {
                                        $sedang_main = true;
                                        break;
                                    }
                                }

                                if ($sedang_main) {
                                    $status_tampil = "SEDANG MAIN";
                                    $badge_color = "bg-danger";

                                    $tombol_aksi = '
                                    <button type="button" class="btn btn-outline-danger w-100 fw-bold" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#modalJadwal'.$row['id'].'">
                                        <i class="bi bi-calendar-week me-1"></i> LIHAT ANTRIAN
                                    </button>';
                                } elseif (count($list_booking) > 0) {

                                    $tombol_aksi = '
                                    <div class="d-flex gap-2">
                                        <a href="detail.php?id='.$row['id'].'" class="btn btn-primary w-100 fw-bold">BOOKING</a>
                                        <button type="button" class="btn btn-outline-light w-50" data-bs-toggle="modal" data-bs-target="#modalJadwal'.$row['id'].'"><i class="bi bi-list"></i></button>
                                    </div>';
                                }
                            }

                            $gambar = (!empty($row['foto']) && file_exists('assets/img/' . $row['foto'])) ? 'assets/img/' . $row['foto'] : "https://placehold.co/600x400?text=No+Image"; 
                        ?>

                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card h-100 position-relative">
                                <span class="status-badge <?= $badge_color ?> text-white shadow-lg">
                                    <?php if($status_tampil == 'SEDANG MAIN'): ?><span class="spinner-grow spinner-grow-sm me-1" style="width: 0.5rem; height: 0.5rem;"></span><?php endif; ?>
                                    <?= $status_tampil ?>
                                </span>
                                <img src="<?= $gambar ?>" class="card-img-top" alt="Foto PS">
                                <div class="card-body">
                                    <h5 class="card-title fw-bold mb-2 text-white"><?= htmlspecialchars($row['nama_ps']) ?></h5>
                                    <span class="badge badge-console mb-3"><?= htmlspecialchars($row['tipe']) ?></span>
                                    <p class="text-white-50 small mb-3 text-truncate"><?= htmlspecialchars($row['deskripsi']) ?></p>
                                    <div class="d-flex justify-content-between align-items-end">
                                        <div><small class="text-white-50 text-uppercase" style="font-size: 0.7rem;">Harga Sewa</small><h4 class="text-primary fw-bold mb-0">Rp <?= number_format($row['harga_per_jam'], 0, ',', '.') ?></h4></div>
                                    </div>
                                </div>
                                <div class="p-3 pt-0 border-0 bg-transparent"><?= $tombol_aksi ?></div>
                            </div>
                        </div>

                        <div class="modal fade" id="modalJadwal<?= $row['id'] ?>" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header border-secondary">
                                        <h5 class="modal-title text-white fw-bold"><i class="bi bi-clock-history me-2"></i> Jadwal Unit Ini</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body p-0">
                                        <div class="list-group list-group-flush">
                                            <?php if(count($list_booking) > 0): ?>
                                                <?php foreach($list_booking as $bk): ?>
                                                    <?php 
                                                    
                                                        $isActive = ($jam_sekarang >= $bk['jam_mulai'] && $jam_sekarang <= $bk['jam_selesai']);
                                                        $bgClass = $isActive ? 'bg-danger bg-opacity-10' : 'bg-transparent';
                                                        $borderClass = $isActive ? 'border-danger' : 'border-secondary';
                                                    ?>
                                                    <div class="list-group-item <?= $bgClass ?> border-bottom <?= $borderClass ?> text-white py-3">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <div>
                                                                <h5 class="mb-1 fw-bold">
                                                                    <?= substr($bk['jam_mulai'], 0, 5) ?> - <?= substr($bk['jam_selesai'], 0, 5) ?>
                                                                </h5>
                                                                <small class="text-white-50">Player: <?= htmlspecialchars($bk['nama_pelanggan']) ?></small>
                                                            </div>
                                                            <?php if($isActive): ?>
                                                                <span class="badge bg-danger animate-pulse">SEDANG MAIN</span>
                                                            <?php else: ?>
                                                                <span class="badge bg-secondary">Booked</span>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <div class="p-4 text-center text-white-50">
                                                    <i class="bi bi-calendar-check fs-1 mb-2 d-block"></i>
                                                    Belum ada antrian hari ini.
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="modal-footer border-secondary">
                                        <a href="detail.php?id=<?= $row['id'] ?>" class="btn btn-primary w-100">Booking Untuk Jam Lain</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                    <?php endforeach; ?>
                </div>

            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>