@extends('layouts.admin')

@section('title', 'Dashboard')

@section('breadcrumb')
<li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
<div class="row">
    <!-- Statistik Utama -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total Peserta</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $statistics['total_peserta'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-user-graduate fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Peserta Terverifikasi</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $statistics['peserta_valid'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Peserta Pending</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $statistics['peserta_pending'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clock fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Total Kontingen</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $statistics['total_kontingen'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Statistik Pembayaran -->
    <div class="col-md-6 mb-4">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold">Statistik Pembayaran</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="row">
                        <div class="col-sm-6">
                            <h5>Total Tagihan:</h5>
                            <h3>Rp {{ number_format($paymentStats['total_tagihan'], 0, ',', '.') }}</h3>
                        </div>
                        <div class="col-sm-6">
                            <h5>Tagihan Lunas:</h5>
                            <h3>Rp {{ number_format($paymentStats['sudah_lunas'], 0, ',', '.') }}</h3>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4 text-center mb-3">
                        <div class="card bg-light">
                            <div class="card-body py-3">
                                <h6>Menunggu Verifikasi</h6>
                                <h4>{{ $paymentStats['menunggu_verifikasi'] }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4 text-center mb-3">
                        <div class="card bg-light">
                            <div class="card-body py-3">
                                <h6>Belum Bayar</h6>
                                <h4>{{ $paymentStats['belum_bayar'] }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4 text-center mb-3">
                        <div class="card bg-light">
                            <div class="card-body py-3">
                                <h6>Progress Pembayaran</h6>
                                <h4>{{ $paymentStats['total_tagihan'] > 0 ? round(($paymentStats['sudah_lunas'] / $paymentStats['total_tagihan']) * 100) : 0 }}%</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pertandingan Mendatang -->
    <div class="col-md-6 mb-4">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold">Pertandingan Mendatang</h6>
            </div>
            <div class="card-body">
                @if($upcomingEvents->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Nama Event</th>
                                    <th>Tanggal</th>
                                    <th>Lokasi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($upcomingEvents as $event)
                                <tr>
                                    <td>{{ $event->nama_event }}</td>
                                    <td>{{ $event->tanggal_event->format('d/m/Y') }}</td>
                                    <td>{{ $event->lokasi_umum }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-3">
                        <p class="mb-0">Tidak ada pertandingan yang akan datang</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Peserta per Kategori -->
    <div class="col-md-6 mb-4">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold">Peserta per Kategori</h6>
            </div>
            <div class="card-body">
                <div class="chart-container" style="position: relative; height:300px;">
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Peserta per Kelompok Usia -->
    <div class="col-md-6 mb-4">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold">Peserta per Kelompok Usia</h6>
            </div>
            <div class="card-body">
                <div class="chart-container" style="position: relative; height:300px;">
                    <canvas id="ageGroupChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Aktivitas Admin Terbaru -->
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold">Aktivitas Admin Terbaru</h6>
                <a href="{{ route('admin.logs.index') }}" class="btn btn-sm btn-primary">Lihat Semua</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Admin</th>
                                <th>Aksi</th>
                                <th>Model</th>
                                <th>Waktu</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentLogs as $log)
                            <tr>
                                <td>{{ $log->admin->nama }}</td>
                                <td>{{ ucfirst($log->aksi) }}</td>
                                <td>{{ class_basename($log->model) }} #{{ $log->model_id }}</td>
                                <td>{{ $log->waktu_aksi->format('d/m/Y H:i') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart for Registration -->
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold">Pendaftaran Harian (7 Hari Terakhir)</h6>
            </div>
            <div class="card-body">
                <div class="chart-container" style="position: relative; height:250px;">
                    <canvas id="registrationChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .border-left-primary {
        border-left: 4px solid #4e73df;
    }
    .border-left-success {
        border-left: 4px solid #1cc88a;
    }
    .border-left-warning {
        border-left: 4px solid #f6c23e;
    }
    .border-left-info {
        border-left: 4px solid #36b9cc;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Chart for Peserta per Kategori
    var categoryData = @json($pesertaPerKategori);
    var categoryCtx = document.getElementById('categoryChart').getContext('2d');
    var categoryChart = new Chart(categoryCtx, {
        type: 'pie',
        data: {
            labels: categoryData.map(function(item) { return item.nama; }),
            datasets: [{
                data: categoryData.map(function(item) { return item.total; }),
                backgroundColor: [
                    '#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#5a5c69', '#858796'
                ],
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right',
                }
            }
        }
    });

    // Chart for Peserta per Kelompok Usia
    var ageData = @json($pesertaPerUsia);
    var ageCtx = document.getElementById('ageGroupChart').getContext('2d');
    var ageChart = new Chart(ageCtx, {
        type: 'bar',
        data: {
            labels: ageData.map(function(item) { return item.nama; }),
            datasets: [{
                label: 'Jumlah Peserta',
                data: ageData.map(function(item) { return item.total; }),
                backgroundColor: '#4e73df',
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            }
        }
    });

    // Chart for Daily Registration
    var registrationData = @json($registrationChart);
    var regCtx = document.getElementById('registrationChart').getContext('2d');
    var regChart = new Chart(regCtx, {
        type: 'line',
        data: {
            labels: registrationData.map(function(item) { 
                var date = new Date(item.date);
                return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' }); 
            }),
            datasets: [{
                label: 'Pendaftaran',
                data: registrationData.map(function(item) { return item.total; }),
                borderColor: '#4e73df',
                backgroundColor: 'rgba(78, 115, 223, 0.05)',
                tension: 0.1,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
</script>
@endpush