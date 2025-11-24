<?php
if ($_SERVER['REQUEST_METHOD'] != 'POST') { header("Location: index.php"); exit; }

$data = $_POST; 
$metode = $data['metode_pembayaran'];

$qris_img = file_exists('assets/img/qris.jpg') ? 'assets/img/qris.jpg' : 'https://placehold.co/300x300?text=QRIS+Image';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selesaikan Pembayaran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container py-5 min-vh-100 d-flex align-items-center">
        <div class="row justify-content-center w-100">
            <div class="col-md-5">
                
                <div class="card border-0 shadow-lg bg-dark text-white">
                    <div class="card-header bg-transparent border-secondary text-center pt-4">
                        <h4 class="fw-bold mb-1">Pembayaran <?= $metode ?></h4>
                        <p class="text-white-50 small">Silakan transfer sesuai nominal.</p>
                    </div>
                    <div class="card-body p-4 text-center">
                        
                        <?php if ($metode == 'QRIS'): ?>
                            
                            <div class="bg-white p-3 rounded d-inline-block mb-4">
                                <img src="<?= $qris_img ?>" width="200" class="img-fluid">
                            </div>
                            <div class="alert alert-warning border-0 text-dark fw-bold mb-4">
                                Scan menggunakan DANA / Gopay / ShopeePay
                            </div>

                        <?php else: ?>

                            <div class="mb-4">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/5c/Bank_Central_Asia.svg/1200px-Bank_Central_Asia.svg.png" width="100" class="mb-3 bg-white p-2 rounded">
                                
                                <div class="card bg-primary bg-opacity-25 border-primary mb-2">
                                    <div class="card-body p-3">
                                        <small class="text-white-50 text-uppercase">Nomor Rekening BCA</small>
                                        <h2 class="fw-bold text-white mb-0" id="noRek">123 456 7890</h2>
                                        <small class="text-white">a.n Admin Rental Bhineka</small>
                                    </div>
                                </div>
                                
                                <button onclick="copyRek()" class="btn btn-sm btn-outline-light w-100 border-secondary text-white-50">
                                    <i class="bi bi-clipboard me-1"></i> Salin Nomor Rekening
                                </button>
                            </div>

                        <?php endif; ?>
                        <div class="alert alert-info bg-opacity-10 border-info text-info small text-start">
                            <ol class="mb-0 ps-3">
                                <li>Lakukan pembayaran.</li>
                                <li>Screenshot/Foto bukti transfer.</li>
                                <li>Upload buktinya di bawah ini.</li>
                            </ol>
                        </div>

                        <form action="proses_booking.php" method="POST" enctype="multipart/form-data">
                            
                            <?php foreach($data as $key => $val): ?>
                                <input type="hidden" name="<?= $key ?>" value="<?= $val ?>">
                            <?php endforeach; ?>

                            <div class="mb-3 text-start">
                                <label class="form-label fw-bold text-white">Upload Bukti Transfer</label>
                                <input type="file" name="bukti_bayar" class="form-control bg-dark text-white border-secondary" accept="image/*" required>
                            </div>

                            <button type="submit" class="btn btn-success w-100 py-3 fw-bold shadow-lg mt-2">
                                KIRIM BUKTI BAYAR <i class="bi bi-send-fill ms-2"></i>
                            </button>
                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        function copyRek() {
            var text = document.getElementById("noRek").innerText.replace(/\s/g, '');
            navigator.clipboard.writeText(text);
            alert("Nomor Rekening Disalin: " + text);
        }
    </script>

</body>
</html>