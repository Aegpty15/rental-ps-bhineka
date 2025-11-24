<?php
$page = basename($_SERVER['PHP_SELF']);
?>

<div class="admin-sidebar p-4">
    
    <a href="index.php" class="sidebar-brand">
        <i class="bi bi-xbox text-primary fs-1"></i>
        <div>
            <span class="text-white">ADMIN</span><span class="text-primary">.PANEL</span>
        </div>
    </a>

    <nav class="nav flex-column">
        
        <div class="nav-label">Menu Utama</div>
        
        <a href="index.php" class="nav-link <?= $page == 'index.php' ? 'active' : '' ?>">
            <i class="bi bi-grid-fill"></i> <span>Dashboard</span>
        </a>
        
        <a href="booking.php" class="nav-link <?= $page == 'booking.php' ? 'active' : '' ?>">
            <i class="bi bi-list-check"></i> <span>Kelola Booking</span>
        </a>

        <a href="jadwal_harian.php" class="nav-link <?= $page == 'jadwal_harian.php' ? 'active' : '' ?>">
            <i class="bi bi-calendar-week"></i> <span>Monitoring Jadwal</span>
        </a>

        <div class="nav-label">Manajemen</div>

        <a href="ps_list.php" class="nav-link <?= ($page == 'ps_list.php' || $page == 'ps_form.php') ? 'active' : '' ?>">
            <i class="bi bi-hdd-stack"></i> <span>Unit Console</span>
        </a>

        <a href="laporan.php" class="nav-link <?= $page == 'laporan.php' ? 'active' : '' ?>">
            <i class="bi bi-graph-up-arrow"></i> <span>Laporan Keuangan</span>
        </a>

    </nav>

    <div class="btn-logout-container">
        <div class="text-center mb-3">
            <small class="text-muted">Login sebagai: <strong class="text-white">Admin</strong></small>
        </div>
        <a href="logout.php" class="btn-logout">
            <i class="bi bi-box-arrow-left me-2"></i> LOGOUT
        </a>
    </div>

</div>