<!-- Sidebar -->
<div class="sidebar d-none d-md-flex flex-column flex-shrink-0 p-3">
    <a href="{{ route('admin.dashboard') }}" class="sidebar-logo d-flex align-items-center mb-3 me-md-auto text-decoration-none">
        <div class="logo-container d-flex align-items-center">
            <img src="{{ asset('https://upload.wikimedia.org/wikipedia/commons/3/35/LogoIPSI_%281%29.png') }}" alt="Logo" height="40" class="me-2">
            <div>
                <span class="fs-5 fw-bold">Pencak Silat</span>
                <div class="small" style="color: var(--primary);">UNPER OPEN</div>
            </div>
        </div>
    </a>
    <hr>
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item">
            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
        </li>
        
        <li class="nav-item mt-2">
            <div class="text-muted small text-uppercase px-3 mb-1">Registrasi</div>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.pelatih.index') }}" class="nav-link {{ request()->routeIs('admin.pelatih.*') ? 'active' : '' }}">
                <i class="fas fa-user-tie"></i> Pelatih
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.kontingen.index') }}" class="nav-link {{ request()->routeIs('admin.kontingen.*') ? 'active' : '' }}">
                <i class="fas fa-users"></i> Kontingen
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.peserta.index') }}" class="nav-link {{ request()->routeIs('admin.peserta.*') ? 'active' : '' }}">
                <i class="fas fa-user-graduate"></i> Peserta
                @if(App\Models\Peserta::where('status_verifikasi', 'pending')->count() > 0)
                    <span class="float-end badge rounded-pill bg-danger">
                        {{ App\Models\Peserta::where('status_verifikasi', 'pending')->count() }}
                    </span>
                @endif
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.dokumen.index') }}" class="nav-link {{ request()->routeIs('admin.dokumen.*') ? 'active' : '' }}">
                <i class="fas fa-file-alt"></i> Dokumen
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.pembayaran.index') }}" class="nav-link {{ request()->routeIs('admin.pembayaran.*') ? 'active' : '' }}">
                <i class="fas fa-money-bill-wave"></i> Pembayaran
                @if(App\Models\Pembayaran::where('status', 'menunggu_verifikasi')->count() > 0)
                    <span class="float-end badge rounded-pill bg-danger">
                        {{ App\Models\Pembayaran::where('status', 'menunggu_verifikasi')->count() }}
                    </span>
                @endif
            </a>
        </li>
        
        <li class="nav-item mt-2">
            <div class="text-muted small text-uppercase px-3 mb-1">Pertandingan</div>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.kategori-lomba.index') }}" class="nav-link {{ request()->routeIs('admin.kategori-lomba.*') ? 'active' : '' }}">
                <i class="fas fa-trophy"></i> Kategori Lomba
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.subkategori-lomba.index') }}" class="nav-link {{ request()->routeIs('admin.subkategori-lomba.*') ? 'active' : '' }}">
                <i class="fas fa-award"></i> Subkategori
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.kelompok-usia.index') }}" class="nav-link {{ request()->routeIs('admin.kelompok-usia.*') ? 'active' : '' }}">
                <i class="fas fa-child"></i> Kelompok Usia
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.kelas-tanding.index') }}" class="nav-link {{ request()->routeIs('admin.kelas-tanding.*') ? 'active' : '' }}">
                <i class="fas fa-weight"></i> Kelas Tanding
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.pertandingan.index') }}" class="nav-link {{ request()->routeIs('admin.pertandingan.*') ? 'active' : '' }}">
                <i class="fas fa-calendar-alt"></i> Pertandingan
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.jadwal-pertandingan.index') }}" class="nav-link {{ request()->routeIs('admin.jadwal-pertandingan.*') ? 'active' : '' }}">
                <i class="fas fa-calendar-check"></i> Jadwal
            </a>
        </li>
        
        <li class="nav-item mt-2">
            <div class="text-muted small text-uppercase px-3 mb-1">Laporan</div>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.laporan.peserta') }}" class="nav-link {{ request()->routeIs('admin.laporan.peserta') ? 'active' : '' }}">
                <i class="fas fa-clipboard-list"></i> Laporan Peserta
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.laporan.pembayaran') }}" class="nav-link {{ request()->routeIs('admin.laporan.pembayaran') ? 'active' : '' }}">
                <i class="fas fa-receipt"></i> Laporan Pembayaran
            </a>
        </li>
        
        <li class="nav-item mt-2">
            <div class="text-muted small text-uppercase px-3 mb-1">Sistem</div>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.logs.index') }}" class="nav-link {{ request()->routeIs('admin.logs.*') ? 'active' : '' }}">
                <i class="fas fa-history"></i> Log Aktivitas
            </a>
        </li>
    </ul>
    <hr>
    <div class="user-dropdown dropdown">
        <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false">
            <div class="user-profile-circle me-2">
                <i class="fas fa-user"></i>
            </div>
            <div>
                <strong>{{ Auth::guard('admin')->user()->nama }}</strong>
                <div class="small text-light">{{ ucfirst(Auth::guard('admin')->user()->role) }}</div>
            </div>
        </a>
        <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="dropdownUser">
            <li><a class="dropdown-item" href="#"><i class="fas fa-user-cog me-2"></i> Profil</a></li>
            <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i> Pengaturan</a></li>
            <li><hr class="dropdown-divider"></li>
            <li>
                <form method="POST" action="{{ route('admin.logout') }}" id="logout-form">
                    @csrf
                    <a class="dropdown-item text-danger" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt me-2"></i> Logout
                    </a>
                </form>
            </li>
        </ul>
    </div>
</div>

<!-- Offcanvas Mobile Menu -->
<div class="offcanvas offcanvas-start d-md-none" tabindex="-1" id="sidebarMenu" aria-labelledby="sidebarMenuLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title d-flex align-items-center" id="sidebarMenuLabel">
            <img src="{{ asset('https://upload.wikimedia.org/wikipedia/commons/3/35/LogoIPSI_%281%29.png') }}" alt="Logo" height="30" class="me-2">
            <div>
                <span>Pencak Silat</span>
                <div style="font-size: 12px; color: #FFC107;">UNPER OPEN</div>
            </div>
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body p-0">
        <div class="p-3">
            <ul class="nav nav-pills flex-column mb-auto">
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                    </a>
                </li>
                
                <li class="nav-item mt-2">
                    <div class="text-muted small text-uppercase px-3 mb-1">Registrasi</div>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.pelatih.index') }}" class="nav-link {{ request()->routeIs('admin.pelatih.*') ? 'active' : '' }}">
                        <i class="fas fa-user-tie me-2"></i> Pelatih
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.kontingen.index') }}" class="nav-link {{ request()->routeIs('admin.kontingen.*') ? 'active' : '' }}">
                        <i class="fas fa-users me-2"></i> Kontingen
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.peserta.index') }}" class="nav-link {{ request()->routeIs('admin.peserta.*') ? 'active' : '' }}">
                        <i class="fas fa-user-graduate me-2"></i> Peserta
                        @if(App\Models\Peserta::where('status_verifikasi', 'pending')->count() > 0)
                            <span class="float-end badge rounded-pill bg-danger">
                                {{ App\Models\Peserta::where('status_verifikasi', 'pending')->count() }}
                            </span>
                        @endif
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.dokumen.index') }}" class="nav-link {{ request()->routeIs('admin.dokumen.*') ? 'active' : '' }}">
                        <i class="fas fa-file-alt me-2"></i> Dokumen
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.pembayaran.index') }}" class="nav-link {{ request()->routeIs('admin.pembayaran.*') ? 'active' : '' }}">
                        <i class="fas fa-money-bill-wave me-2"></i> Pembayaran
                        @if(App\Models\Pembayaran::where('status', 'menunggu_verifikasi')->count() > 0)
                            <span class="float-end badge rounded-pill bg-danger">
                                {{ App\Models\Pembayaran::where('status', 'menunggu_verifikasi')->count() }}
                            </span>
                        @endif
                    </a>
                </li>
                
                <li class="nav-item mt-2">
                    <div class="text-muted small text-uppercase px-3 mb-1">Pertandingan</div>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.kategori-lomba.index') }}" class="nav-link {{ request()->routeIs('admin.kategori-lomba.*') ? 'active' : '' }}">
                        <i class="fas fa-trophy me-2"></i> Kategori Lomba
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.subkategori-lomba.index') }}" class="nav-link {{ request()->routeIs('admin.subkategori-lomba.*') ? 'active' : '' }}">
                        <i class="fas fa-award me-2"></i> Subkategori
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.kelompok-usia.index') }}" class="nav-link {{ request()->routeIs('admin.kelompok-usia.*') ? 'active' : '' }}">
                        <i class="fas fa-child me-2"></i> Kelompok Usia
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.kelas-tanding.index') }}" class="nav-link {{ request()->routeIs('admin.kelas-tanding.*') ? 'active' : '' }}">
                        <i class="fas fa-weight me-2"></i> Kelas Tanding
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.pertandingan.index') }}" class="nav-link {{ request()->routeIs('admin.pertandingan.*') ? 'active' : '' }}">
                        <i class="fas fa-calendar-alt me-2"></i> Pertandingan
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.jadwal-pertandingan.index') }}" class="nav-link {{ request()->routeIs('admin.jadwal-pertandingan.*') ? 'active' : '' }}">
                        <i class="fas fa-calendar-check me-2"></i> Jadwal
                    </a>
                </li>
                
                <li class="nav-item mt-2">
                    <div class="text-muted small text-uppercase px-3 mb-1">Laporan</div>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.laporan.peserta') }}" class="nav-link {{ request()->routeIs('admin.laporan.peserta') ? 'active' : '' }}">
                        <i class="fas fa-clipboard-list me-2"></i> Laporan Peserta
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.laporan.pembayaran') }}" class="nav-link {{ request()->routeIs('admin.laporan.pembayaran') ? 'active' : '' }}">
                        <i class="fas fa-receipt me-2"></i> Laporan Pembayaran
                    </a>
                </li>
                
                <li class="nav-item mt-2">
                    <div class="text-muted small text-uppercase px-3 mb-1">Sistem</div>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.logs.index') }}" class="nav-link {{ request()->routeIs('admin.logs.*') ? 'active' : '' }}">
                        <i class="fas fa-history me-2"></i> Log Aktivitas
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>