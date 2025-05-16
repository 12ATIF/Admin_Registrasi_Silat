@extends('layouts.admin')

@section('title', 'Manajemen Kelas Tanding')

@section('breadcrumb')
<li class="breadcrumb-item active">Kelas Tanding</li>
@endsection

@section('action-buttons')
<a href="{{ route('admin.kelas-tanding.create') }}" class="btn btn-primary">
    <i class="fas fa-plus"></i> Tambah Kelas
</a>
@endsection

@section('content')
<!-- Filter Card -->
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold">Filter</h6>
    </div>
    <div class="card-body">
        <form id="filter-form" method="GET">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="kelompok_usia_id" class="form-label">Kelompok Usia</label>
                    <select class="form-select" id="kelompok_usia_id" name="kelompok_usia_id">
                        <option value="">Semua Kelompok Usia</option>
                        @foreach(\App\Models\KelompokUsia::all() as $usia)
                            <option value="{{ $usia->id }}" {{ request('kelompok_usia_id') == $usia->id ? 'selected' : '' }}>
                                {{ $usia->nama }} ({{ $usia->rentang_usia_min }}-{{ $usia->rentang_usia_max }} tahun)
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                    <select class="form-select" id="jenis_kelamin" name="jenis_kelamin">
                        <option value="">Semua</option>
                        <option value="putra" {{ request('jenis_kelamin') == 'putra' ? 'selected' : '' }}>Putra</option>
                        <option value="putri" {{ request('jenis_kelamin') == 'putri' ? 'selected' : '' }}>Putri</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3 d-flex align-items-end">
                    <div class="d-grid gap-2 w-100">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                        <a href="{{ route('admin.kelas-tanding.index') }}" class="btn btn-secondary">
                            <i class="fas fa-sync"></i> Reset
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- DataTables Card -->
<div class="card shadow">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped" id="kelas-table">
                <thead>
                    <tr>
                        <th>Kelompok Usia</th>
                        <th>Jenis Kelamin</th>
                        <th>Kode Kelas</th>
                        <th>Berat Min</th>
                        <th>Berat Max</th>
                        <th>Label Keterangan</th>
                        <th>Jumlah Peserta</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($kelasTandings as $kelas)
                    <tr>
                        <td>{{ $kelas->kelompokUsia->nama }}</td>
                        <td>{{ ucfirst($kelas->jenis_kelamin) }}</td>
                        <td>{{ $kelas->kode_kelas }}</td>
                        <td>{{ $kelas->berat_min }} kg</td>
                        <td>{{ $kelas->is_open_class ? 'Open' : $kelas->berat_max . ' kg' }}</td>
                        <td>{{ $kelas->label_keterangan }}</td>
                        <td>{{ $kelas->pesertas_count ?? 0 }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.kelas-tanding.show', $kelas->id) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.kelas-tanding.edit', $kelas->id) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.kelas-tanding.destroy', $kelas->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kelas ini?');">
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
        
        <div class="mt-3">
            {{ $kelasTandings->links() }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#kelas-table').DataTable({
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