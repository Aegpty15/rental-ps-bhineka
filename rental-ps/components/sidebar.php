<div class="sidebar-dark d-flex flex-column flex-shrink-0 p-4" style="height: 100%; min-height: 100vh;">
    
    <a href="index.php" class="d-flex align-items-center mb-4 me-md-auto text-white text-decoration-none">
        <i class="bi bi-playstation fs-1 me-2 text-primary"></i> 
        <span class="fs-4 fw-bold" style="font-family: 'Outfit'; letter-spacing: 1px;">BHINEKA<span class="text-primary">.PS</span></span>
    </a>
    
    <ul class="nav nav-pills flex-column mb-auto gap-2">
        <li class="nav-item">
            <a href="index.php" class="nav-link menu-link <?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : '' ?>">
                <i class="bi bi-controller me-3"></i> Console List
            </a>
        </li>
        <li>
            <a href="cek_booking.php" class="nav-link menu-link <?= basename($_SERVER['PHP_SELF']) == 'cek_booking.php' ? 'active' : '' ?>">
                <i class="bi bi-search me-3"></i> Cek Booking
            </a>
        </li>
        <li>
            <a href="peraturan.php" class="nav-link menu-link <?= basename($_SERVER['PHP_SELF']) == 'peraturan.php' ? 'active' : '' ?>">
                <i class="bi bi-shield-exclamation me-3"></i> Rules
            </a>
        </li>
        <li>
            <a href="lokasi.php" class="nav-link menu-link <?= basename($_SERVER['PHP_SELF']) == 'lokasi.php' ? 'active' : '' ?>">
                <i class="bi bi-geo-alt me-3"></i> Lokasi
            </a>
        </li>
    </ul>

    <div class="mt-4 border-top border-secondary pt-4 text-center">
        <small class="text-muted d-block mb-2">Butuh Bantuan?</small>
        <a href="https://wa.me/085251748632" class="btn btn-outline-primary w-100 rounded-pill">
            <i class="bi bi-whatsapp"></i> Chat Admin
        </a>
    </div>
</div>