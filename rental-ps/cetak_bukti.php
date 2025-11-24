<?php
require 'config/koneksi.php';
if (!isset($_GET['id'])) { header("Location: index.php"); exit; }

$id = $_GET['id'];

$stmt = $conn->prepare("SELECT booking.*, playstation.nama_ps, playstation.tipe FROM booking 
                        JOIN playstation ON booking.playstation_id = playstation.id 
                        WHERE booking.id = ?");
$stmt->execute([$id]);
$data = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bukti Booking #<?= $id ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #eee; font-family: 'Courier New', Courier, monospace; }
        .ticket {
            max-width: 400px;
            margin: 30px auto;
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            position: relative;
        }
        .ticket:after {
            content: ""; position: absolute; bottom: -10px; left: 0; right: 0; height: 20px;
            background: radial-gradient(circle, transparent 10px, white 11px);
            background-size: 20px 20px;
            background-position: -10px 0;
        }
        .dashed-line { border-bottom: 2px dashed #ccc; margin: 15px 0; }
        @media print {
            .no-print { display: none !important; }
            body { background-color: white; }
            .ticket { box-shadow: none; margin: 0; }
        }
    </style>
</head>
<body>

    <div class="ticket">
        <div class="text-center mb-3">
            <h5 class="fw-bold text-uppercase">RENTAL PS BHINEKA</h5>
            <small class="text-muted">WA Admin: 085251748632</small> 
        </div>

        <div class="dashed-line"></div>

        <div class="row mb-1">
            <div class="col-5 text-muted">No. Booking</div>
            <div class="col-7 text-end fw-bold">#<?= $data['id'] ?></div>
        </div>
        <div class="row mb-1">
            <div class="col-5 text-muted">Nama</div>
            <div class="col-7 text-end fw-bold"><?= htmlspecialchars($data['nama_pelanggan']) ?></div>
        </div>
        <div class="row mb-1">
            <div class="col-5 text-muted">Unit</div>
            <div class="col-7 text-end"><?= htmlspecialchars($data['nama_ps']) ?></div>
        </div>
        
        <div class="dashed-line"></div>

        <div class="row mb-1">
            <div class="col-5 text-muted">Tanggal</div>
            <div class="col-7 text-end"><?= date('d/m/Y', strtotime($data['tanggal_booking'])) ?></div>
        </div>
        <div class="row mb-1">
            <div class="col-5 text-muted">Jam Main</div>
            <div class="col-7 text-end fw-bold"><?= substr($data['jam_mulai'],0,5) ?> - <?= substr($data['jam_selesai'],0,5) ?></div>
        </div>
        <div class="row mb-1">
            <div class="col-5 text-muted">Durasi</div>
            <div class="col-7 text-end"><?= $data['total_jam'] ?> Jam</div>
        </div>

        <div class="dashed-line"></div>

        <div class="row mb-3 align-items-center">
            <div class="col-5 fw-bold fs-5">TOTAL</div>
            <div class="col-7 text-end fw-bold fs-4">Rp <?= number_format($data['total_harga'],0,',','.') ?></div>
        </div>

        <div class="alert alert-light border text-center py-2 mb-3">
            Metode: <strong><?= htmlspecialchars($data['metode_pembayaran']) ?></strong>
        </div>

        <?php if (!empty($data['bukti_pembayaran']) && file_exists("assets/bukti_bayar/" . $data['bukti_pembayaran'])): ?>
            <div class="text-center mb-3 p-2 border rounded bg-light">
                <small class="text-muted d-block mb-2">- Bukti Pembayaran -</small>
                <img src="assets/bukti_bayar/<?= $data['bukti_pembayaran'] ?>" 
                     alt="Bukti Transfer" 
                     class="img-fluid border rounded" 
                     style="max-height: 150px;">
            </div>
        <?php endif; ?>

        <div class="text-center mt-4 mb-4">
            <p class="small text-muted mb-3">*Simpan bukti ini (Screenshot) dan tunjukkan ke kasir saat datang.</p>
            
            <div class="no-print d-grid gap-2">
                <button onclick="window.print()" class="btn btn-dark fw-bold">
                    <i class="bi bi-printer"></i> Cetak / Simpan PDF
                </button>
                <a href="index.php" class="btn btn-outline-secondary">Kembali ke Menu Utama</a>
            </div>
        </div>
    </div>

</body>
</html>