<?php
session_start();
require '../config/koneksi.php';

header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

if (!isset($_SESSION['admin'])) { header("Location: login.php"); exit; }

$id = "";
$nama_ps = "";
$tipe = "";
$harga = "";
$status = "tersedia";
$deskripsi = "";
$foto_lama = "default.jpg"; 
$judul_halaman = "Tambah Unit Baru";

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM playstation WHERE id = ?");
    $stmt->execute([$id]);
    $data = $stmt->fetch();

    if ($data) {
        $nama_ps = $data['nama_ps'];
        $tipe = $data['tipe'];
        $harga = $data['harga_per_jam'];
        $status = $data['status'];
        $deskripsi = $data['deskripsi'];
        $foto_lama = $data['foto'];
        $judul_halaman = "Edit Unit PS";
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_ps = $_POST['nama_ps'];
    $tipe = $_POST['tipe'];
    $harga = $_POST['harga'];
    $status = $_POST['status'];
    $deskripsi = $_POST['deskripsi'];

    $nama_foto = $foto_lama; 

    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['foto']['tmp_name'];
        $nama_asli = $_FILES['foto']['name'];
        $nama_foto = time() . '_' . $nama_asli; 
        move_uploaded_file($file_tmp, "../assets/img/" . $nama_foto);
    }

    if ($id) {

        $sql = "UPDATE playstation SET nama_ps=?, tipe=?, harga_per_jam=?, status=?, deskripsi=?, foto=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$nama_ps, $tipe, $harga, $status, $deskripsi, $nama_foto, $id]);
    } else {

        $sql = "INSERT INTO playstation (nama_ps, tipe, harga_per_jam, status, deskripsi, foto) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$nama_ps, $tipe, $harga, $status, $deskripsi, $nama_foto]);
    }

    header("Location: ps_list.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= $judul_halaman ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../assets/css/admin-style.css">
</head>
<body>

    <nav class="mobile-nav shadow-sm">
        <span class="navbar-brand fw-bold text-white">ADMIN</span>
        <button class="btn btn-outline-light btn-sm border-secondary" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMobile">
            <i class="bi bi-list fs-5"></i>
        </button>
    </nav>

    <div class="d-flex">
        
        <div class="sidebar-desktop">
            <?php include 'components/sidebar.php'; ?>
        </div>
        <div class="offcanvas offcanvas-start bg-dark" id="sidebarMobile">
            <div class="offcanvas-body p-0">
                <?php include 'components/sidebar.php'; ?>
            </div>
        </div>

        <div class="main-content w-100">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="fw-bold text-white mb-0"><?= $judul_halaman ?></h3>
                    <p class="text-muted small mb-0">Isi data unit PlayStation dengan lengkap.</p>
                </div>
                <a href="ps_list.php" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-2"></i> Kembali
                </a>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-8">
                    
                    <div class="card shadow-lg border-0">
                        <div class="card-header py-3">
                            <h6 class="mb-0 text-white fw-bold"><i class="bi bi-pencil-square me-2 text-primary"></i> Form Data Unit</h6>
                        </div>
                        <div class="card-body p-4">
                            
                            <form method="POST" enctype="multipart/form-data">
                                
                                <div class="mb-4">
                                    <label class="form-label text-white-50 small text-uppercase fw-bold">Nama Unit</label>
                                    <input type="text" name="nama_ps" class="form-control form-control-lg bg-dark text-white border-secondary" value="<?= $nama_ps ?>" required placeholder="Contoh: Unit 01 - PS5">
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-4">
                                        <label class="form-label text-white-50 small text-uppercase fw-bold">Tipe Console</label>
                                        <select name="tipe" class="form-select bg-dark text-white border-secondary">
                                            <option value="PS3" <?= $tipe == 'PS3' ? 'selected' : '' ?>>PS3</option>
                                            <option value="PS4" <?= $tipe == 'PS4' ? 'selected' : '' ?>>PS4</option>
                                        </select>
                                    </div>
                                    
                                    <div class="col-md-6 mb-4">
                                        <label class="form-label text-white-50 small text-uppercase fw-bold">Harga per Jam (Rp)</label>
                                        <input type="number" name="harga" class="form-control bg-dark text-white border-secondary" value="<?= $harga ?>" required>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label text-white-50 small text-uppercase fw-bold">Foto Unit</label>
                                    <input type="file" name="foto" class="form-control bg-dark text-white border-secondary" accept="image/*">
                                    
                                    <?php if($foto_lama && $foto_lama != 'default.jpg'): ?>
                                        <div class="mt-3 p-2 border border-secondary rounded bg-dark d-inline-block">
                                            <img src="../assets/img/<?= $foto_lama ?>" width="120" class="rounded">
                                            <div class="small text-muted mt-1 text-center">Foto Saat Ini</div>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label text-white-50 small text-uppercase fw-bold">Status Ketersediaan</label>
                                    <select name="status" class="form-select bg-dark text-white border-secondary">
                                        <option value="tersedia" <?= $status == 'tersedia' ? 'selected' : '' ?>>✅ Tersedia (Bisa Dibooking)</option>
                                        <option value="maintenance" <?= $status == 'maintenance' ? 'selected' : '' ?>>⚠️ Maintenance (Rusak/Perbaikan)</option>
                                    </select>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label text-white-50 small text-uppercase fw-bold">Deskripsi / Fasilitas</label>
                                    <textarea name="deskripsi" class="form-control bg-dark text-white border-secondary" rows="4" required placeholder="Contoh: 2 Stik Original, TV 4K 50 Inch..."><?= $deskripsi ?></textarea>
                                </div>

                                <hr class="border-secondary my-4">

                                <div class="d-flex justify-content-end gap-2">
                                    <a href="ps_list.php" class="btn btn-outline-secondary px-4">Batal</a>
                                    <button type="submit" class="btn btn-success px-5 fw-bold shadow-lg">
                                        <i class="bi bi-save me-2"></i> SIMPAN DATA
                                    </button>
                                </div>

                            </form>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>