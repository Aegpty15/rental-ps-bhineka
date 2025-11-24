<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rental Sedang Tutup</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            background-color: #0b1120;
            color: #f8fafc;
            font-family: 'Outfit', sans-serif;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }
        .closed-card {
            background: rgba(30, 41, 59, 0.5);
            border: 1px solid rgba(239, 68, 68, 0.3);
            padding: 50px;
            border-radius: 20px;
            box-shadow: 0 0 50px rgba(239, 68, 68, 0.1);
            max-width: 500px;
            width: 90%;
        }
        .icon-lock {
            font-size: 80px;
            color: #ef4444;
            margin-bottom: 20px;
            animation: float 3s ease-in-out infinite;
        }
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }
    </style>
</head>
<body>

    <div class="closed-card">
        <i class="bi bi-lock-fill icon-lock"></i>
        <h2 class="fw-bold text-white mb-3">MAAF, RENTAL TUTUP</h2>
        <p class="text-white-50 mb-4">
            Saat ini kami sedang tidak beroperasi atau sedang istirahat. 
            Silakan kembali lagi nanti sesuai jam operasional.
        </p>
        
        <div class="alert alert-dark border-secondary d-inline-block px-4 py-2 rounded-pill">
            <i class="bi bi-clock me-2 text-warning"></i> Buka: 09:00 - 22:00 WIB
        </div>

        <div class="mt-4">
            <a href="https://wa.me/628123456789" class="btn btn-outline-light rounded-pill px-4">
                <i class="bi bi-whatsapp me-2"></i> Hubungi Admin
            </a>
        </div>
    </div>

</body>
</html>