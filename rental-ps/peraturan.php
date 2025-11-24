<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peraturan Rental</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/style.css">

    <style>
        
        .denda-list span {
            color: white !important;
            opacity: 1 !important;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-dark bg-transparent d-md-none p-3 border-bottom border-secondary">
        <span class="navbar-brand fw-bold text-primary">BHINEKA.PS</span>
        <button class="btn btn-outline-primary btn-sm" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMobile"><i class="bi bi-list"></i></button>
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
                
                <h2 class="fw-bold text-white mb-4 border-bottom border-secondary pb-3"><i class="bi bi-shield-check me-2 text-success"></i> Peraturan & Tata Tertib</h2>

                <div class="row g-4">
                    
                    <div class="col-md-8">
                        <div class="card border-0 bg-dark border border-secondary h-100">
                            <div class="card-header bg-primary text-white fw-bold py-3"><i class="bi bi-exclamation-circle me-2"></i> Aturan Wajib</div>
                            <div class="list-group list-group-flush">
                                
                                <div class="list-group-item bg-transparent text-white-50 border-secondary py-3">
                                    <strong class="text-white d-block mb-1">1. Dilarang Makan/Minum di Meja PS</strong>
                                    Untuk mencegah tumpahan air mengenai mesin atau stik.
                                </div>
                                <div class="list-group-item bg-transparent text-white-50 border-secondary py-3">
                                    <strong class="text-white d-block mb-1">2. Kerusakan Alat = Ganti Rugi Penuh</strong>
                                    Penyewa bertanggung jawab jika stik dibanting atau kabel putus.
                                </div>
                                <div class="list-group-item bg-transparent text-white-50 border-secondary py-3">
                                    <strong class="text-white d-block mb-1">3. Dilarang Merokok (Area AC)</strong>
                                    Merokok hanya diperbolehkan di area outdoor.
                                </div>
                                <div class="list-group-item bg-transparent text-white-50 border-secondary py-3">
                                    <strong class="text-white d-block mb-1">4. Waktu Habis = Stop</strong>
                                    Harap segera menyimpan game jika waktu sewa selesai.
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        
                        <div class="card border-0 bg-danger bg-opacity-10 border-danger mb-4">
                            <div class="card-body">
                                <h5 class="card-title text-white fw-bold mb-3">
                                    <i class="bi bi-cone-striped me-2 text-warning"></i> Denda Kerusakan
                                </h5>
                                
                                <ul class="list-unstyled denda-list mb-0">
                                    <li class="mb-2 pb-2 border-bottom border-secondary d-flex justify-content-between">
                                        <span class="text-white">Stik PS4</span> <span class="text-white fw-bold">Rp 250.000</span>
                                    </li>
                                    <li class="mb-2 pb-2 border-bottom border-secondary d-flex justify-content-between">
                                        <span class="text-white">Stik PS5</span> <span class="text-white fw-bold">Rp 900.000</span>
                                    </li>
                                    <li class="d-flex justify-content-between">
                                        <span class="text-white">HDMI Patah</span> <span class="text-white fw-bold">Rp 50.000</span>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="card border-0 bg-dark border border-secondary">
                            <div class="card-body text-center py-4">
                                <h5 class="text-primary fw-bold"><i class="bi bi-clock-history me-2"></i> Jam Operasional</h5>
                                <p class="text-white mt-2 mb-0">Senin - Minggu</p>
                                <h3 class="text-white fw-bold">09:00 - 22:00</h3>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>