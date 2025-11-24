<?php
session_start();
require '../config/koneksi.php';

header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

if (isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM admin WHERE username = ?");
    $stmt->execute([$username]);
    $admin = $stmt->fetch();

    if ($admin && $password == $admin['password']) {
        $_SESSION['admin'] = true;
        $_SESSION['admin_name'] = $admin['username'];
        header("Location: index.php");
        exit;
    } else {
        $error = "Username atau Password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Administrator</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            background-color: #0b1120;
            font-family: 'Outfit', sans-serif;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
        }

        body::before {
            content: '';
            position: absolute;
            width: 600px; height: 600px;
            background: radial-gradient(circle, rgba(59,130,246,0.15) 0%, rgba(11,17,32,0) 70%);
            top: -200px; left: -200px;
            z-index: -1;
        }
        body::after {
            content: '';
            position: absolute;
            width: 500px; height: 500px;
            background: radial-gradient(circle, rgba(16,185,129,0.1) 0%, rgba(11,17,32,0) 70%);
            bottom: -100px; right: -100px;
            z-index: -1;
        }

        .login-card {
            background-color: #1e293b;
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 16px;
            width: 100%;
            max-width: 400px;
            padding: 40px 30px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }

        .brand-icon {
            width: 60px; height: 60px;
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            color: white;
            margin: 0 auto 20px;
            box-shadow: 0 10px 20px rgba(59, 130, 246, 0.3);
        }

        .form-control {
            background-color: #0f172a;
            border: 1px solid rgba(255,255,255,0.1);
            color: white;
            padding: 12px 15px;
            padding-left: 45px;
        }
        .form-control:focus {
            background-color: #0f172a;
            color: white;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
        }
        
        .input-wrapper { position: relative; margin-bottom: 20px; }
        .input-icon {
            position: absolute;
            left: 15px; top: 50%;
            transform: translateY(-50%);
            color: #64748b;
            font-size: 1.1rem;
            transition: 0.3s;
        }
        .form-control:focus + .input-icon { color: #3b82f6; }

        .btn-login {
            background: linear-gradient(90deg, #3b82f6, #2563eb);
            border: none;
            padding: 12px;
            font-weight: 700;
            letter-spacing: 0.5px;
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.4);
            transition: transform 0.2s;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(59, 130, 246, 0.6);
        }

        .footer-text { color: #64748b; font-size: 0.85rem; margin-top: 20px; }
    </style>
</head>
<body>

    <div class="container">
        <div class="d-flex justify-content-center">
            
            <div class="login-card">
                <div class="brand-icon">
                    <i class="bi bi-shield-lock-fill"></i>
                </div>
                
                <h4 class="text-center text-white fw-bold mb-1">Admin Panel</h4>
                <p class="text-center text-muted mb-4 small">Silakan login untuk mengelola sistem.</p>

                <?php if(isset($error)): ?>
                    <div class="alert alert-danger py-2 text-center border-0 bg-danger bg-opacity-25 text-danger small">
                        <i class="bi bi-exclamation-circle me-1"></i> <?= $error ?>
                    </div>
                <?php endif; ?>

                <form method="POST">
                    <div class="input-wrapper">
                        <input type="text" name="username" class="form-control" placeholder="Username" required autofocus autocomplete="off">
                        <i class="bi bi-person input-icon"></i>
                    </div>

                    <div class="input-wrapper">
                        <input type="password" name="password" class="form-control" placeholder="Password" required>
                        <i class="bi bi-key input-icon"></i>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 btn-login rounded-3">
                        MASUK SEKARANG <i class="bi bi-arrow-right-short"></i>
                    </button>
                </form>

                <div class="text-center footer-text">
                    &copy; 2025 Rental PS Bhineka System
                </div>
            </div>

        </div>
    </div>

</body>
</html>