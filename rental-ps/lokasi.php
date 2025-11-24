<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lokasi Rental</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <nav class="navbar navbar-dark bg-transparent d-md-none p-3 border-bottom border-secondary">
        <span class="navbar-brand fw-bold text-primary">BHINEKA.PS</span>
        <button class="btn btn-outline-primary btn-sm" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMobile">
            <i class="bi bi-list"></i>
        </button>
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
                
                <h2 class="fw-bold text-white mb-4 border-bottom border-secondary pb-3"><i class="bi bi-geo-alt-fill me-2 text-info"></i> Lokasi Rental Bhineka</h2>

                <div class="row justify-content-center g-4">
                    
                    <div class="col-md-10 col-lg-8">
                        <div class="card border-0 shadow-lg bg-dark p-4">
                            
                            <h4 class="text-white fw-bold mb-3">Informasi Kontak & Lokasi</h4>
                            
                            <div class="list-group list-group-flush">
                                
                                <div class="list-group-item bg-transparent border-secondary text-white py-3">
                                    <h6 class="text-primary fw-bold mb-1">ALAMAT LENGKAP</h6>
                                    <p class="mb-0 text-white-50">
                                        Jl. RTA Milono KM 8<br>
                                        (Dekat Alfamart RTA Milono)<br>
                                        Palangka Raya, Kalimantan Tengah
                                    </p>
                                </div>

                                <div class="list-group-item bg-transparent border-secondary text-white py-3">
                                    <h6 class="text-primary fw-bold mb-1">KONTAK ADMIN</h6>
                                    <p class="mb-0 text-white">
                                        <i class="bi bi-whatsapp me-2 text-success"></i> 0852-5174-8632
                                    </p>
                                </div>
                                
                                <div class="list-group-item bg-transparent border-secondary text-white py-3">
                                    <h6 class="text-primary fw-bold mb-1">JAM OPERASIONAL</h6>
                                    <p class="mb-0 text-white-50">Senin - Minggu: 09:00 - 22:00 WIB</p>
                                </div>
                            </div>
                            
                            <hr class="border-secondary mt-4">
                            <p class="text-center text-white-50 small mb-0">
                                Silakan hubungi kontak di atas untuk petunjuk arah atau konfirmasi.
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>