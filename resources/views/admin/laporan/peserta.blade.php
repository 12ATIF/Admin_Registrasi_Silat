@extends('layouts.admin')

@section('title', 'Laporan Peserta')

@section('breadcrumb')
<li class="breadcrumb-item active">Laporan</li>
<li class="breadcrumb-item active">Peserta</li>
@endsection

@section('content')
<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
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

    <div class="col-md-3 mb-3">
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

    <div class="col-md-3 mb-3">
        <div class="card border-left-danger shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                            Peserta Tidak Valid</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $statistics['peserta_tidak_valid'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-times-circle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
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
</div>

<!-- Filter Card -->
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold">Filter Laporan</h6>
    </div>
    <div class="card-body">
        <form id="filter-form" method="GET">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label for="kontingen_id" class="form-label">Kontingen</label>
                    <select class="form-select" id="kontingen_id" name="kontingen_id">
                        <option value="">Semua Kontingen</option>
                        @foreach($kontingens as $kontingen)
                            <option value="{{ $kontingen->id }}" {{ request('kontingen_id') == $kontingen->id ? 'selected' : '' }}>
                                {{ $kontingen->nama }} ({{ $kontingen->asal_daerah }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="kategori_id" class="form-label">Kategori Lomba</label>
                    <select class="form-select" id="kategori_id" name="kategori_id">
                        <option value="">Semua Kategori</option>
                        @foreach($kategoris as $kategori)
                            <option value="{{ $kategori->id }}" {{ request('kategori_id') == $kategori->id ? 'selected' : '' }}>
                                {{ $kategori->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="kelompok_usia_id" class="form-label">Kelompok Usia</label>
                    <select class="form-select" id="kelompok_usia_id" name="kelompok_usia_id">
                        <option value="">Semua Kelompok Usia</option>
                        @foreach($kelompokUsias as $usia)
                            <option value="{{ $usia->id }}" {{ request('kelompok_usia_id') == $usia->id ? 'selected' : '' }}>
                                {{ $usia->nama }} ({{ $usia->rentang_usia_min }}-{{ $usia->rentang_usia_max }} tahun)
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 mb-3 d-flex align-items-end">
                    <div class="d-grid gap-2 w-100">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                        <a href="{{ route('admin.laporan.peserta') }}" class="btn btn-secondary">
                            <i class="fas fa-sync"></i> Reset
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="mt-3 d-flex justify-content-end">
                <a href="{{ route('admin.laporan.export-peserta') }}?{{ http_build_query(request()->all()) }}" class="btn btn-success">
                    <i class="fas fa-file-excel"></i> Export Excel
                </a>
            </div>
        </form>
    </div>
</div>

<!-- DataTables Card -->
<div class="card shadow">
    <div class="card-body">
        <div class="table-responsive">
            <table id="peserta-table" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Jenis Kelamin</th>
                        <th>Tanggal Lahir</th>
                        <th>Berat Badan</th>
                        <th>Kontingen</th>
                        <th>Kategori</th>
                        <th>Kelompok Usia</th>
                        <th>Kelas Tanding</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- DataTables will populate this -->
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Initialize DataTable
        var table = $('#peserta-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('admin.laporan.peserta') }}",
                data: function(d) {
                    d.kontingen_id = $('#kontingen_id').val();
                    d.kategori_id = $('#kategori_id').val();
                    d.kelompok_usia_id = $('#kelompok_usia_id').val();
                }
            },
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'nama', name: 'nama'},
                {data: 'jenis_kelamin', name: 'jenis_kelamin'},
                {data: 'tanggal_lahir', name: 'tanggal_lahir'},
                {data: 'berat_badan', name: 'berat_badan'},
                {data: 'kontingen', name: 'kontingen.nama'},
                {data: 'kategori', name: 'subkategoriLomba.kategoriLomba.nama'},
                {data: 'kelompok_usia.nama', name: 'kelompokUsia.nama'},
                {data: 'kelas_tanding', name: 'kelasTanding.label_keterangan'},
                {data: 'status_verifikasi', name: 'status_verifikasi'},
            ],
            order: [[1, 'asc']],
            dom: 'Bfrtip',
            buttons: [
                'copy', 'excel', 'pdf', 'print'
            ]
        });
        
        // Refilter the table when filter form is submitted
        $('#filter-form').on('submit', function(e) {
            e.preventDefault();
            table.ajax.reload();
        });
    });
</script>
@endpush

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
    .border-left-danger {
        border-left: 4px solid #e74a3b;
    }
</style>
@endpush