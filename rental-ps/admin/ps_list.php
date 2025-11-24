<?php
session_start();
require '../config/koneksi.php';

header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $conn->query("DELETE FROM playstation WHERE id = $id");
    echo "<script>alert('Unit berhasil dihapus!'); window.location='ps_list.php';</script>";
}

$stmt = $conn->query("SELECT * FROM playstation ORDER BY id ASC");
$ps_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Data PS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../assets/css/admin-style.css">
</head>
<body>

    <nav class="navbar navbar-dark bg-primary d-md-none p-3 shadow-sm">
        <span class="navbar-brand fw-bold">ADMIN PANEL</span>
        <button class="btn btn-outline-light btn-sm" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMobile">
            <i class="bi bi-list"></i>
        </button>
    </nav>

    <div class="d-flex">
        
        <div class="d-none d-md-block">
            <?php include 'components/sidebar.php'; ?>
        </div>

        <div class="offcanvas offcanvas-start bg-dark" tabindex="-1" id="sidebarMobile">
            <div class="offcanvas-body p-0">
                <?php include 'components/sidebar.php'; ?>
            </div>
        </div>

        <div class="main-content w-100">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="fw-bold text-white mb-0">🎮 Daftar Unit Console</h3>
                    <p class="text-muted small mb-0">Total Unit: <?= count($ps_list) ?></p>
                </div>
                <a href="ps_form.php" class="btn btn-primary fw-bold shadow-sm">
                    <i class="bi bi-plus-lg me-2"></i> Tambah Unit Baru
                </a>
            </div>

            <div class="card shadow-lg border-0">
                <div class="card-header py-3">
                    <h5 class="mb-0 fw-bold text-white"><i class="bi bi-hdd-stack me-2"></i> List Inventory</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-4">No</th>
                                    <th>Foto</th>
                                    <th>Nama Unit</th>
                                    <th>Tipe</th>
                                    <th>Harga / Jam</th>
                                    <th>Status</th>
                                    <th class="text-end pe-4">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($ps_list as $index => $row): ?>
                                <tr>
                                    <td class="ps-4 text-muted fw-bold"><?= $index + 1 ?></td>
                                    
                                    <td>
                                        <?php 
                                            $img = (!empty($row['foto']) && file_exists('../assets/img/' . $row['foto'])) 
                                                ? '../assets/img/' . $row['foto'] 
                                                : 'https://placehold.co/100x60?text=No+Img';
                                        ?>
                                        <img src="<?= $img ?>" alt="PS" class="rounded border border-secondary" style="width: 60px; height: 40px; object-fit: cover;">
                                    </td>

                                    <td>
                                        <span class="text-white fw-bold"><?= htmlspecialchars($row['nama_ps']) ?></span>
                                    </td>
                                    
                                    <td>
                                        <span class="badge-unit"><?= htmlspecialchars($row['tipe']) ?></span>
                                    </td>
                                    
                                    <td class="fw-bold text-success" style="font-family: monospace; font-size: 1rem;">
                                        Rp <?= number_format($row['harga_per_jam'], 0, ',', '.') ?>
                                    </td>
                                    
                                    <td>
                                        <?php if($row['status'] == 'tersedia'): ?>
                                            <span class="badge bg-success bg-opacity-10 text-success border border-success px-3">Active</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary bg-opacity-25 text-muted border border-secondary px-3">Maintenance</span>
                                        <?php endif; ?>
                                    </td>
                                    
                                    <td class="text-end pe-4">
                                        <a href="ps_form.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-info me-1" title="Edit">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <a href="ps_list.php?hapus=<?= $row['id'] ?>" 
                                           class="btn-trash" 
                                           onclick="return confirm('Yakin ingin menghapus unit ini? Data booking terkait juga akan hilang!')" 
                                           title="Hapus Permanen">
                                            <i class="bi bi-trash3"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <?php if(count($ps_list) == 0): ?>
                        <div class="text-center py-5 opacity-50">
                            <i class="bi bi-controller display-1 text-muted"></i>
                            <p class="text-muted mt-3">Belum ada unit console.</p>
                        </div>
                    <?php endif; ?>

                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>