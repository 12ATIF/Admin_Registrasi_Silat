<!-- Top Navbar -->
<nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm sticky-top">
    <div class="container-fluid">
        <!-- Mobile Menu Toggle Button -->
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
            <i class="fas fa-bars text-dark"></i>
        </button>
        
        <!-- Page Title (Mobile Only) -->
        <a class="navbar-brand d-md-none fw-bold" href="#">
            <span class="me-2"><i class="fas fa-trophy text-warning"></i></span>
            @yield('title')
        </a>
        
        <!-- Right Side Navbar -->
        <ul class="navbar-nav ms-auto">
            <!-- Notifications Dropdown -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle position-relative" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-bell fa-lg text-muted"></i>
                    @if(App\Models\Peserta::where('status_verifikasi', 'pending')->count() > 0 || 
                        App\Models\Pembayaran::where('status', 'menunggu_verifikasi')->count() > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            {{ App\Models\Peserta::where('status_verifikasi', 'pending')->count() + 
                               App\Models\Pembayaran::where('status', 'menunggu_verifikasi')->count() }}
                            <span class="visually-hidden">notifikasi</span>
                        </span>
                    @endif
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="navbarDropdown">
                    <li><h6 class="dropdown-header">Notifikasi</h6></li>
                    
                    @if(App\Models\Peserta::where('status_verifikasi', 'pending')->count() > 0)
                        <li>
                            <a class="dropdown-item" href="{{ route('admin.peserta.index', ['status_verifikasi' => 'pending']) }}">
                                <i class="fas fa-user-graduate me-2 text-warning"></i> 
                                <span class="fw-medium">{{ App\Models\Peserta::where('status_verifikasi', 'pending')->count() }}</span> peserta menunggu verifikasi
                            </a>
                        </li>
                    @endif
                    
                    @if(App\Models\Pembayaran::where('status', 'menunggu_verifikasi')->count() > 0)
                        <li>
                            <a class="dropdown-item" href="{{ route('admin.pembayaran.index', ['status' => 'menunggu_verifikasi']) }}">
                                <i class="fas fa-money-bill-wave me-2 text-warning"></i> 
                                <span class="fw-medium">{{ App\Models\Pembayaran::where('status', 'menunggu_verifikasi')->count() }}</span> pembayaran menunggu verifikasi
                            </a>
                        </li>
                    @endif
                    
                    @if(App\Models\Peserta::where('status_verifikasi', 'pending')->count() == 0 && 
                        App\Models\Pembayaran::where('status', 'menunggu_verifikasi')->count() == 0)
                        <li>
                            <div class="dropdown-item">
                                <i class="fas fa-check-circle me-2 text-success"></i> Tidak ada notifikasi baru
                            </div>
                        </li>
                    @endif
                    
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="{{ route('admin.logs.index') }}">
                        <i class="fas fa-history me-2"></i> Lihat semua aktivitas
                    </a></li>
                </ul>
            </li>
            
            <!-- User Dropdown (Mobile Only) -->
            <li class="nav-item dropdown d-md-none">
                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="d-flex align-items-center">
                        <div class="user-profile-circle">
                            <i class="fas fa-user"></i>
                        </div>
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="userDropdown">
                    <li>
                        <div class="px-3 py-2 text-center mb-2">
                            <div class="fw-bold">{{ Auth::guard('admin')->user()->nama }}</div>
                            <div class="small text-muted">{{ ucfirst(Auth::guard('admin')->user()->role) }}</div>
                        </div>
                    </li>
                    <li><a class="dropdown-item" href="#">
                        <i class="fas fa-user-cog me-2"></i> Profil
                    </a></li>
                    <li><a class="dropdown-item" href="#">
                        <i class="fas fa-cog me-2"></i> Pengaturan
                    </a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('admin.logout') }}" id="logout-form-mobile">
                            @csrf
                            <a class="dropdown-item text-danger" href="#" 
                               onclick="event.preventDefault(); document.getElementById('logout-form-mobile').submit();">
                                <i class="fas fa-sign-out-alt me-2"></i> Logout
                            </a>
                        </form>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>