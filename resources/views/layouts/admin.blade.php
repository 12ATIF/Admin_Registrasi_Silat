<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - Admin Pencak Silat</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
    <!-- DataTables Bootstrap 5 CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.3.2/css/fixedHeader.bootstrap5.min.css">
    
    <!-- Custom CSS -->
    <style>
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .sidebar {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            z-index: 100;
        }
        .main-content {
            padding-top: 56px; // Reduce to match navbar height
        }
        @media (min-width: 768px) {
            body {
                flex-direction: row;
            }
            .sidebar {
                width: 250px;
                min-height: 100vh;
            }
            .content {
                flex: 1;
                min-height: 100vh;
            }
            .navbar-top {
                height: 56px;
                position: relative;
            }
            .main-content {
                padding-top: 20px;
            }
        }
        .sidebar .nav-link {
            color: #333;
            padding: 0.5rem 1rem;
            border-radius: 0.25rem;
            margin: 0.2rem 0;
        }
        .sidebar .nav-link:hover {
            background-color: #f0f0f0;
        }
        .sidebar .nav-link.active {
            background-color: #6c757d;
            color: white;
        }
        .dropdown-submenu {
            position: relative;
        }
        .dropdown-submenu .dropdown-menu {
            top: 0;
            left: 100%;
            margin-top: -1px;
        }
        
        /* DataTables Custom Styling */
        div.dataTables_wrapper div.dataTables_processing {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 200px;
            margin-left: -100px;
            margin-top: -26px;
            text-align: center;
            padding: 1em 0;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 0.25rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar bg-light d-none d-md-flex flex-column flex-shrink-0 p-3">
        <a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-decoration-none">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" height="40" class="me-2">
            <span class="fs-5 fw-semibold">Pencak Silat</span>
        </a>
        <hr>
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
        <hr>
        <div class="dropdown">
            <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false">
                <div class="bg-primary text-white rounded-circle p-2 me-2" style="width: 38px; height: 38px; text-align: center;">
                    <i class="fas fa-user"></i>
                </div>
                <div>
                    <strong>{{ Auth::guard('admin')->user()->nama }}</strong>
                    <div class="small text-muted">{{ ucfirst(Auth::guard('admin')->user()->role) }}</div>
                </div>
            </a>
            <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="dropdownUser">
                <li><a class="dropdown-item" href="#"><i class="fas fa-user-cog me-2"></i> Profil</a></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form method="POST" action="{{ route('admin.logout') }}" id="logout-form">
                        @csrf
                        <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt me-2"></i> Logout
                        </a>
                    </form>
                </li>
            </ul>
        </div>
    </div>

    <!-- Content -->
    <div class="content d-flex flex-column">
        <!-- Top Navbar -->
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm fixed-top">
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

        <!-- Offcanvas Mobile Menu -->
        <div class="offcanvas offcanvas-start d-md-none" tabindex="-1" id="sidebarMenu" aria-labelledby="sidebarMenuLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="sidebarMenuLabel">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" height="30" class="me-2">
                    Pencak Silat
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
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

        <!-- Main Content -->
        <main class="main-content p-3 p-md-4 flex-grow-1">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0">@yield('title')</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            @yield('breadcrumb')
                        </ol>
                    </nav>
                </div>
                <div>
                    @yield('action-buttons')
                </div>
            </div>
            
            @yield('content')
        </main>
        
        <!-- Footer -->
        <footer class="bg-light py-3 px-3 px-md-4 mt-auto">
            <div class="container-fluid">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                    <div class="text-center text-md-start">
                        <p class="mb-0">&copy; {{ date('Y') }} Sistem Pendaftaran Pencak Silat</p>
                    </div>
                    <div class="text-center text-md-end mt-2 mt-md-0">
                        <p class="mb-0">Versi 1.0</p>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.1/js/responsive.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/fixedheader/3.3.2/js/dataTables.fixedHeader.min.js"></script>
    
    <!-- DataTables Global Configuration -->
    <script>
        // Set default configuration for DataTables
        $.extend(true, $.fn.dataTable.defaults, {
            language: {
                processing: '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>',
                search: 'Cari:',
                lengthMenu: 'Tampilkan _MENU_ data',
                info: 'Menampilkan _START_ hingga _END_ dari _TOTAL_ data',
                infoEmpty: 'Tidak ada data yang tersedia',
                infoFiltered: '(difilter dari _MAX_ total data)',
                loadingRecords: 'Memuat...',
                zeroRecords: 'Tidak ditemukan data yang sesuai',
                emptyTable: 'Tidak ada data yang tersedia',
                paginate: {
                    first: 'Pertama',
                    previous: 'Sebelumnya',
                    next: 'Selanjutnya',
                    last: 'Terakhir'
                },
                aria: {
                    sortAscending: ': aktifkan untuk mengurutkan kolom secara menaik',
                    sortDescending: ': aktifkan untuk mengurutkan kolom secara menurun'
                }
            },
            responsive: true,
            processing: true,
            autoWidth: false,
            stateSave: true,
            pageLength: 10,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'Semua']],
        });
        
        // Ajax setup for CSRF token
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function() {
            // Set timeout for alert messages
            setTimeout(function() {
                $('.alert').alert('close');
            }, 5000);
            
            // Enable all tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html>