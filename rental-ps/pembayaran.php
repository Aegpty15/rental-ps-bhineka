<?php
require 'config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ps_id      = $_POST['playstation_id'];
    $nama       = htmlspecialchars($_POST['nama']);
    $no_wa      = htmlspecialchars($_POST['no_wa']);
    $tanggal    = $_POST['tanggal'];
    $jam_mulai  = $_POST['jam_mulai'];
    $durasi     = $_POST['durasi'];
    
    $stmt = $conn->prepare("SELECT * FROM playstation WHERE id = ?");
    $stmt->execute([$ps_id]);
    $ps = $stmt->fetch();
    
    if (!$ps) { header("Location: index.php"); exit; }

    $total_bayar = $ps['harga_per_jam'] * $durasi;

    $qris_img = file_exists('assets/img/qris.jpg') ? 'assets/img/qris.jpg' : 'https://placehold.co/150x150?text=No+QRIS';

} else {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Pembayaran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <nav class="navbar navbar-dark bg-transparent d-md-none p-3 border-bottom border-secondary">
        <span class="navbar-brand fw-bold text-primary">BHINEKA.PS</span>
    </nav>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                
                <div class="text-center mb-4">
                    <h3 class="text-white fw-bold">Metode Pembayaran</h3>
                    <p class="text-white-50">Selesaikan pesananmu sekarang.</p>
                </div>

                <div class="card border-0 shadow-lg mb-4 bg-dark text-white border-secondary">
                    <div class="card-header bg-transparent border-secondary">
                        <h5 class="mb-0 text-primary fw-bold"><i class="bi bi-receipt me-2"></i> Rincian Booking</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-dark table-borderless table-sm mb-0">
                            <tr><td class="text-white-50">Unit</td><td class="text-end fw-bold"><?= $ps['nama_ps'] ?></td></tr>
                            <tr><td class="text-white-50">Nama</td><td class="text-end"><?= $nama ?></td></tr>
                            <tr><td class="text-white-50">Jadwal</td><td class="text-end"><?= date('d/m/Y', strtotime($tanggal)) ?> | <?= $jam_mulai ?></td></tr>
                            <tr><td class="text-white-50">Durasi</td><td class="text-end"><?= $durasi ?> Jam</td></tr>
                            <tr class="border-top border-secondary"><td class="pt-3 fs-5 fw-bold">Total</td><td class="pt-3 fs-5 fw-bold text-success">Rp <?= number_format($total_bayar, 0, ',', '.') ?></td></tr>
                        </table>
                    </div>
                </div>

                <form id="paymentForm" action="proses_booking.php" method="POST">
                    
                    <input type="hidden" name="playstation_id" value="<?= $ps_id ?>">
                    <input type="hidden" name="nama" value="<?= $nama ?>">
                    <input type="hidden" name="no_wa" value="<?= $no_wa ?>">
                    <input type="hidden" name="tanggal" value="<?= $tanggal ?>">
                    <input type="hidden" name="jam_mulai" value="<?= $jam_mulai ?>">
                    <input type="hidden" name="durasi" value="<?= $durasi ?>">

                    <h5 class="text-white mb-3">Pilih Cara Bayar:</h5>

                    <div class="list-group mb-4 shadow-lg">
                        
                        <label class="list-group-item d-flex align-items-center p-3 bg-dark border-secondary text-white action-hover" style="cursor: pointer;">
                            <input class="form-check-input me-3 payment-radio" type="radio" name="metode_pembayaran" value="QRIS" data-type="online" required>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-0 fw-bold">QRIS (Scan)</h6>
                                        <small class="text-white-50">Dana / Gopay / Shopee / Bank</small>
                                    </div>
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#modalQRIS">
                                        <img src="<?= $qris_img ?>" width="50" height="50" class="rounded border border-secondary bg-white" style="object-fit: contain;">
                                    </a>
                                </div>
                            </div>
                        </label>

                        <label class="list-group-item d-flex align-items-center p-3 bg-dark border-secondary text-white action-hover" style="cursor: pointer;">
                            <input class="form-check-input me-3 payment-radio" type="radio" name="metode_pembayaran" value="Transfer Bank" data-type="online">
                            <div class="flex-grow-1">
                                <h6 class="mb-0 fw-bold">Transfer Bank BCA</h6>
                                <small class="text-white-50">123-456-7890 (A.n Admin Rental)</small>
                            </div>
                            <i class="bi bi-bank fs-3 text-info"></i>
                        </label>

                        <label class="list-group-item d-flex align-items-center p-3 bg-dark border-secondary text-white action-hover" style="cursor: pointer;">
                            <input class="form-check-input me-3 payment-radio" type="radio" name="metode_pembayaran" value="Bayar di Tempat" data-type="offline">
                            <div class="flex-grow-1">
                                <h6 class="mb-0 fw-bold">Bayar Tunai (Cash)</h6>
                                <small class="text-white-50">Bayar di kasir saat datang</small>
                            </div>
                            <i class="bi bi-cash-coin fs-3 text-success"></i>
                        </label>

                    </div>

                    <button type="submit" id="btnSubmit" class="btn btn-primary w-100 py-3 fw-bold shadow-lg text-uppercase tracking-wide mb-3">
                        SELESAIKAN PESANAN <i class="bi bi-check-circle-fill ms-2"></i>
                    </button>
                    
                    <a href="javascript:history.back()" class="btn btn-outline-secondary w-100 border-0 text-white-50">Batalkan</a>
                </form>

            </div>
        </div>
    </div>

    <div class="modal fade" id="modalQRIS" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-dark text-white border-secondary">
                <div class="modal-header border-secondary">
                    <h5 class="modal-title">Scan QRIS</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center bg-white rounded-bottom">
                    <img src="<?= $qris_img ?>" class="img-fluid" style="max-height: 400px;">
                    <p class="text-dark mt-2 fw-bold">Scan untuk membayar</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        const radios = document.querySelectorAll('.payment-radio');
        const form = document.getElementById('paymentForm');
        const btn = document.getElementById('btnSubmit');

        radios.forEach(radio => {
            radio.addEventListener('change', function() {
                
                if (this.dataset.type === 'online') {
                    form.action = "bayar_qris.php";
                    btn.innerHTML = "LANJUTKAN PEMBAYARAN <i class='bi bi-arrow-right ms-2'></i>";
                    btn.className = "btn btn-success w-100 py-3 fw-bold shadow-lg text-uppercase tracking-wide mb-3";
                
                } else {
                    form.action = "proses_booking.php";
                    btn.innerHTML = "SELESAIKAN PESANAN <i class='bi bi-check-circle-fill ms-2'></i>";
                    btn.className = "btn btn-primary w-100 py-3 fw-bold shadow-lg text-uppercase tracking-wide mb-3";
                }
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>