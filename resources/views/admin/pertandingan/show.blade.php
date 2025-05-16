@extends('layouts.admin')

@section('title', 'Detail Pertandingan')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.pertandingan.index') }}">Pertandingan</a></li>
<li class="breadcrumb-item active">Detail</li>
@endsection

@section('action-buttons')
<div class="btn-group" role="group">
    <a href="{{ route('admin.pertandingan.edit', $pertandingan->id) }}" class="btn btn-warning">
        <i class="fas fa-edit"></i> Edit
    </a>
    <a href="{{ route('admin.jadwal-pertandingan.create') }}?pertandingan_id={{ $pertandingan->id }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Tambah Jadwal
    </a>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold">Informasi Pertandingan</h6>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    <div class="d-inline-block bg-primary text-white rounded-circle p-3 mb-2" style="width: 100px; height: 100px;">
                        <i class="fas fa-calendar-alt fa-3x mt-2"></i>
                    </div>
                    <h5>{{ $pertandingan->nama_event }}</h5>
                    <p class="text-muted">{{ $pertandingan->tanggal_event->format('d F Y') }}</p>
                </div>
                
                <div class="mb-3">
                    <h6 class="font-weight-bold">Lokasi:</h6>
                    <p>{{ $pertandingan->lokasi_umum }}</p>
                </div>
                
                <div class="mb-3">
                    <h6 class="font-weight-bold">Total Jadwal:</h6>
                    <p>{{ $stats['total_jadwal'] }} jadwal pertandingan</p>
                </div>
                
                <div class="mb-3">
                    <h6 class="font-weight-bold">Jumlah Subkategori:</h6>
                    <p>{{ $stats['subkategori'] }} subkategori</p>
                </div>
                
                <div class="mb-3">
                    <h6 class="font-weight-bold">Jumlah Kelompok Usia:</h6>
                    <p>{{ $stats['kelompok_usia'] }} kelompok usia</p>
                </div>
                
                <div class="mb-3">
                    <h6 class="font-weight-bold">Tanggal Dibuat:</h6>
                    <p>{{ $pertandingan->created_at->format('d/m/Y H:i') }}</p>
                </div>
                
                <div class="mb-3">
                    <h6 class="font-weight-bold">Terakhir Diperbarui:</h6>
                    <p>{{ $pertandingan->updated_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="row">
            <div class="col-md-6">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold">Distribusi Jadwal per Kategori</h6>
                    </div>
                    <div class="card-body">
                        <div class="chart-container" style="position: relative; height:250px;">
                            <canvas id="kategoriChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold">Distribusi Jadwal per Kelompok Usia</h6>
                    </div>
                    <div class="card-body">
                        <div class="chart-container" style="position: relative; height:250px;">
                            <canvas id="usiaChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold">Jadwal Pertandingan</h6>
                <a href="{{ route('admin.jadwal-pertandingan.create') }}?pertandingan_id={{ $pertandingan->id }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus"></i> Tambah Jadwal
                </a>
            </div>
            <div class="card-body">
                @if($pertandingan->jadwalPertandingans->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="jadwal-table">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Waktu</th>
                                    <th>Subkategori</th>
                                    <th>Kelompok Usia</th>
                                    <th>Lokasi Detail</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pertandingan->jadwalPertandingans as $jadwal)
                                <tr>
                                    <td>{{ $jadwal->tanggal->format('d/m/Y') }}</td>
                                    <td>{{ $jadwal->waktu_mulai }} - {{ $jadwal->waktu_selesai ?: 'Selesai' }}</td>
                                    <td>{{ $jadwal->subkategoriLomba->kategoriLomba->nama }} - {{ $jadwal->subkategoriLomba->nama }}</td>
                                    <td>{{ $jadwal->kelompokUsia->nama }}</td>
                                    <td>{{ $jadwal->lokasi_detail ?: '-' }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.jadwal-pertandingan.show', $jadwal->id) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.jadwal-pertandingan.edit', $jadwal->id) }}" class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.jadwal-pertandingan.destroy', $jadwal->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus jadwal ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-3">
                        <p class="text-muted mb-0">Belum ada jadwal pertandingan</p>
                        <a href="{{ route('admin.jadwal-pertandingan.create') }}?pertandingan_id={{ $pertandingan->id }}" class="btn btn-primary mt-2">
                            <i class="fas fa-plus"></i> Tambah Jadwal
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="d-flex justify-content-between mb-4">
    <a href="{{ route('admin.pertandingan.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i> Kembali
    </a>
    
    <form action="{{ route('admin.pertandingan.destroy', $pertandingan->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pertandingan ini? Semua jadwal yang terkait juga akan dihapus.');">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger">
            <i class="fas fa-trash me-1"></i> Hapus Pertandingan
        </button>
    </form>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    $(document).ready(function() {
        $('#jadwal-table').DataTable({
            "responsive": true,
            "paging": false,
            "info": false,
            "columnDefs": [
                { "orderable": false, "targets": 5 }
            ]
        });
        
        // Data for charts
        var kategoriData = @json($pertandingan->jadwalPertandingans->groupBy(function($jadwal) {
            return $jadwal->subkategoriLomba->kategoriLomba->nama;
        })->map->count());
        
        var usiaData = @json($pertandingan->jadwalPertandingans->groupBy(function($jadwal) {
            return $jadwal->kelompokUsia->nama;
        })->map->count());
        
        // Chart for Kategori
        var kategoriCtx = document.getElementById('kategoriChart').getContext('2d');
        var kategoriChart = new Chart(kategoriCtx, {
            type: 'pie',
            data: {
                labels: Object.keys(kategoriData),
                datasets: [{
                    data: Object.values(kategoriData),
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
        
        // Chart for Kelompok Usia
        var usiaCtx = document.getElementById('usiaChart').getContext('2d');
        var usiaChart = new Chart(usiaCtx, {
            type: 'bar',
            data: {
                labels: Object.keys(usiaData),
                datasets: [{
                    label: 'Jumlah Jadwal',
                    data: Object.values(usiaData),
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
    });
</script>
@endpush