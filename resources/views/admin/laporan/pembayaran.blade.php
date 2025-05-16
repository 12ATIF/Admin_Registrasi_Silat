@extends('layouts.admin')

@section('title', 'Laporan Pembayaran')

@section('breadcrumb')
<li class="breadcrumb-item active">Laporan</li>
<li class="breadcrumb-item active">Pembayaran</li>
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
                            Total Tagihan</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($statistics['total_tagihan'], 0, ',', '.') }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
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
                            Tagihan Lunas</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($statistics['total_lunas'], 0, ',', '.') }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-check-circle fa-2x text-gray-300"></i>
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
                            Menunggu Verifikasi</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $statistics['menunggu_verifikasi'] }} pembayaran</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clock fa-2x text-gray-300"></i>
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
                            Belum Bayar</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $statistics['belum_bayar'] }} pembayaran</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-exclamation-circle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Progress Pembayaran -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold">Progress Pembayaran</h6>
    </div>
    <div class="card-body">
        @php
            $progressPembayaran = $statistics['total_tagihan'] > 0 ? round(($statistics['total_lunas'] / $statistics['total_tagihan']) * 100) : 0;
        @endphp
        <h4 class="small font-weight-bold">Total Lunas <span class="float-end">{{ $progressPembayaran }}%</span></h4>
        <div class="progress mb-4">
            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $progressPembayaran }}%" aria-valuenow="{{ $progressPembayaran }}" aria-valuemin="0" aria-valuemax="100"></div>
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
                <div class="col-md-6 mb-3">
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
                <div class="col-md-6 mb-3">
                    <label for="status" class="form-label">Status Pembayaran</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">Semua Status</option>
                        <option value="belum_bayar" {{ request('status') == 'belum_bayar' ? 'selected' : '' }}>Belum Bayar</option>
                        <option value="menunggu_verifikasi" {{ request('status') == 'menunggu_verifikasi' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                        <option value="lunas" {{ request('status') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                    </select>
                </div>
            </div>
            
            <div class="mt-3 d-flex justify-content-between">
                <div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                    <a href="{{ route('admin.laporan.pembayaran') }}" class="btn btn-secondary">
                        <i class="fas fa-sync"></i> Reset
                    </a>
                </div>
                <div>
                    <a href="{{ route('admin.laporan.export-pembayaran') }}?{{ http_build_query(request()->all()) }}" class="btn btn-success">
                        <i class="fas fa-file-excel"></i> Export Excel
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- DataTables Card -->
<div class="card shadow">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped" id="pembayaran-table">
                <thead>
                    <tr>
                        <th>Kontingen</th>
                        <th>Asal Daerah</th>
                        <th>Pelatih</th>
                        <th>Total Tagihan</th>
                        <th>Jumlah Peserta</th>
                        <th>Status</th>
                        <th>Tanggal Verifikasi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pembayarans as $pembayaran)
                    <tr>
                        <td>{{ $pembayaran->kontingen->nama }}</td>
                        <td>{{ $pembayaran->kontingen->asal_daerah }}</td>
                        <td>{{ $pembayaran->kontingen->pelatih->nama }}</td>
                        <td>Rp {{ number_format($pembayaran->total_tagihan, 0, ',', '.') }}</td>
                        <td>{{ $pembayaran->kontingen->pesertas->count() }}</td>
                        <td>
                            @php
                                $badgeClass = [
                                    'lunas' => 'bg-success',
                                    'menunggu_verifikasi' => 'bg-warning',
                                    'belum_bayar' => 'bg-danger'
                                ][$pembayaran->status] ?? 'bg-secondary';
                            @endphp
                            <span class="badge {{ $badgeClass }}">
                                {{ ucfirst(str_replace('_', ' ', $pembayaran->status)) }}
                            </span>
                        </td>
                        <td>{{ $pembayaran->verified_at ? $pembayaran->verified_at->format('d/m/Y H:i') : '-' }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.kontingen.show', $pembayaran->kontingen_id) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i> Detail
                                </a>
                                @if($pembayaran->bukti_transfer)
                                <a href="{{ Storage::url($pembayaran->bukti_transfer) }}" class="btn btn-sm btn-primary" target="_blank">
                                    <i class="fas fa-file-image"></i> Bukti
                                </a>
                                @endif
                                <a href="{{ route('admin.pembayaran.index') }}?kontingen_id={{ $pembayaran->kontingen_id }}" class="btn btn-sm btn-secondary">
                                    <i class="fas fa-edit"></i> Kelola
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="mt-3">
            {{ $pembayarans->links() }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#pembayaran-table').DataTable({
            "paging": false,
            "info": false,
            "searching": true,
            "responsive": true,
            "columnDefs": [
                { "orderable": false, "targets": 7 }
            ]
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