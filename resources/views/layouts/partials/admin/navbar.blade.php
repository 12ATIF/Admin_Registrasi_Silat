<!-- Top Navbar -->
<nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
    <div class="container-fluid">
        <!-- Mobile Menu Toggle Button -->
        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
    <!-- Page Title (Mobile Only) -->
    <a class="navbar-brand d-md-none" href="#">@yield('title')</a>
    
    <!-- Right Side Navbar -->
    <ul class="navbar-nav ms-auto">
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-bell"></i>
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                    {{ App\Models\Peserta::where('status_verifikasi', 'pending')->count() }}
                </span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="navbarDropdown">
                <li><h6 class="dropdown-header">Notifikasi</h6></li>
                <li><a class="dropdown-item" href="{{ route('admin.peserta.index', ['status_verifikasi' => 'pending']) }}">
                    <i class="fas fa-user-graduate me-2"></i> {{ App\Models\Peserta::where('status_verifikasi', 'pending')->count() }} peserta menunggu verifikasi
                </a></li>
                <li><a class="dropdown-item" href="{{ route('admin.pembayaran.index', ['status' => 'menunggu_verifikasi']) }}">
                    <i class="fas fa-money-bill-wave me-2"></i> {{ App\Models\Pembayaran::where('status', 'menunggu_verifikasi')->count() }} pembayaran menunggu verifikasi
                </a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="{{ route('admin.logs.index') }}">Lihat semua aktivitas</a></li>
            </ul>
        </li>
        <li class="nav-item dropdown d-md-none">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-user"></i>
            </a>
            <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="userDropdown">
                <li><a class="dropdown-item" href="#"><i class="fas fa-user-cog me-2"></i> Profil</a></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form method="POST" action="{{ route('admin.logout') }}" id="logout-form-mobile">
                        @csrf
                        <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form-mobile').submit();">
                            <i class="fas fa-sign-out-alt me-2"></i> Logout
                        </a>
                    </form>
                </li>
            </ul>
        </li>
    </ul>
</div>
</nav>