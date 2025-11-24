<?php
require 'config/koneksi.php';

$hasil_cari = [];
if (isset($_GET['wa'])) {
    $wa = $_GET['wa'];
    
    $stmt = $conn->prepare("SELECT booking.*, playstation.nama_ps 
                            FROM booking 
                            JOIN playstation ON booking.playstation_id = playstation.id 
                            WHERE no_wa = ? 
                            ORDER BY id DESC");
    $stmt->execute([$wa]);
    $hasil_cari = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cek Status Booking</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <nav class="navbar navbar-dark bg-transparent d-md-none p-3 border-bottom border-secondary">
        <span class="navbar-brand fw-bold text-primary">BHINEKA.PS</span>
        <button class="btn btn-outline-primary btn-sm" data-bs-toggle="offcanvas" data-bs-target="#sidebarMobile">
            <i class="bi bi-list"></i>
        </button>
    </nav>

    <div class="container-fluid">
        <div class="row">
            
            <div class="col-md-3 col-lg-2 px-0 d-none d-md-block position-fixed">
                <?php include 'components/sidebar.php'; ?>
            </div>
            
            <div class="offcanvas offcanvas-start bg-dark text-white" id="sidebarMobile">
                <div class="offcanvas-body p-0">
                    <?php include 'components/sidebar.php'; ?>
                </div>
            </div>

            <div class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4 min-vh-100">
                
                <div class="card border-0 shadow-lg p-4 mb-5" style="background: linear-gradient(45deg, #1e293b, #0f172a);">
                    <h3 class="text-white fw-bold mb-2">
                        <i class="bi bi-search text-primary me-2"></i> Cek Status Booking
                    </h3>
                    <p class="text-white-50">Masukkan nomor WhatsApp yang kamu gunakan saat booking.</p>
                    
                    <form method="GET" action="" class="mt-3">
                        <div class="input-group input-group-lg">
                            <input type="number" name="wa" class="form-control bg-dark text-white border-secondary" placeholder="Contoh: 08123456789" required value="<?= isset($_GET['wa']) ? htmlspecialchars($_GET['wa']) : '' ?>">
                            <button class="btn btn-primary px-4 fw-bold" type="submit">CARI DATA</button>
                        </div>
                    </form>
                </div>

                <?php if(isset($_GET['wa'])): ?>
                    <h5 class="text-white mb-3 border-start border-4 border-primary ps-3">Hasil Pencarian: <?= htmlspecialchars($_GET['wa']) ?></h5>
                    
                    <?php if(count($hasil_cari) > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-dark table-hover border-secondary align-middle shadow-sm">
                                <thead class="table-secondary">
                                    <tr>
                                        <th>Unit PS</th>
                                        <th>Tanggal</th>
                                        <th>Jam Main</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($hasil_cari as $row): ?>
                                    <tr>
                                        <td class="fw-bold text-white">
                                            <i class="bi bi-controller me-2 text-muted"></i>
                                            <?= htmlspecialchars($row['nama_ps']) ?>
                                        </td>
                                        <td class="text-white-50">
                                            <?= date('d M Y', strtotime($row['tanggal_booking'])) ?>
                                        </td>
                                        <td class="text-white-50">
                                            <span class="badge bg-dark border border-secondary">
                                                <?= substr($row['jam_mulai'],0,5) ?> - <?= substr($row['jam_selesai'],0,5) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php 
                                                $status = $row['status_booking'];
                                                $badgeColor = 'secondary';
                                                
                                                if($status == 'confirmed') $badgeColor = 'primary';
                                                elseif($status == 'pending') $badgeColor = 'warning text-dark';
                                                elseif($status == 'selesai') $badgeColor = 'success';
                                                elseif($status == 'batal') $badgeColor = 'danger';
                                            ?>
                                            <span class="badge bg-<?= $badgeColor ?>">
                                                <?= strtoupper($status) ?>
                                            </span>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-danger border-0 bg-danger bg-opacity-25 text-danger d-flex align-items-center">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <div>Data tidak ditemukan. Pastikan nomor WA benar atau belum pernah booking.</div>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>