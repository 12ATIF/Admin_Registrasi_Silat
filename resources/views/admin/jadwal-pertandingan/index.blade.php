@extends('layouts.admin')

@section('title', 'Jadwal Pertandingan')

@section('breadcrumb')
<li class="breadcrumb-item active">Jadwal Pertandingan</li>
@endsection

@section('action-buttons')
<a href="{{ route('admin.jadwal-pertandingan.create') }}" class="btn btn-primary">
    <i class="fas fa-plus"></i> Tambah Jadwal
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
                    <label for="pertandingan_id" class="form-label">Pertandingan</label>
                    <select class="form-select" id="pertandingan_id" name="pertandingan_id">
                        <option value="">Semua Pertandingan</option>
                        @foreach(\App\Models\Pertandingan::orderBy('tanggal_event', 'desc')->get() as $pertandingan)
                            <option value="{{ $pertandingan->id }}" {{ request('pertandingan_id') == $pertandingan->id ? 'selected' : '' }}>
                                {{ $pertandingan->nama_event }} ({{ $pertandingan->tanggal_event->format('d/m/Y') }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="subkategori_id" class="form-label">Subkategori</label>
                    <select class="form-select" id="subkategori_id" name="subkategori_id">
                        <option value="">Semua Subkategori</option>
                        @foreach(\App\Models\SubkategoriLomba::with('kategoriLomba')->get() as $subkategori)
                            <option value="{{ $subkategori->id }}" {{ request('subkategori_id') == $subkategori->id ? 'selected' : '' }}>
                                {{ $subkategori->kategoriLomba->nama }} - {{ $subkategori->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
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
            </div>
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary me-2">
                    <i class="fas fa-filter"></i> Filter
                </button>
                <a href="{{ route('admin.jadwal-pertandingan.index') }}" class="btn btn-secondary">
                    <i class="fas fa-sync"></i> Reset
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Calendar View Button -->
<div class="mb-3">
    <a href="{{ route('admin.visualization.index') }}" class="btn btn-info">
        <i class="fas fa-calendar-alt"></i> Lihat Kalender Jadwal
    </a>
</div>

<!-- DataTables Card -->
<div class="card shadow">
    <div class="card-body">
        <div class="table-responsive">
            <table id="jadwal-table" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Pertandingan</th>
                        <th>Tanggal</th>
                        <th>Waktu</th>
                        <th>Kategori</th>
                        <th>Subkategori</th>
                        <th>Kelompok Usia</th>
                        <th>Lokasi Detail</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($jadwals as $jadwal)
                    <tr>
                        <td>{{ $jadwal->pertandingan->nama_event }}</td>
                        <td>{{ $jadwal->tanggal->format('d/m/Y') }}</td>
                        <td>{{ $jadwal->waktu_mulai }} - {{ $jadwal->waktu_selesai ?? 'Selesai' }}</td>
                        <td>{{ $jadwal->subkategoriLomba->kategoriLomba->nama }}</td>
                        <td>{{ $jadwal->subkategoriLomba->nama }}</td>
                        <td>{{ $jadwal->kelompokUsia->nama }}</td>
                        <td>{{ $jadwal->lokasi_detail ?? '-' }}</td>
                        <td>
                            @if($jadwal->tanggal->isPast())
                                <span class="badge bg-secondary">Selesai</span>
                            @elseif($jadwal->tanggal->isToday())
                                <span class="badge bg-success">Hari Ini</span>
                            @else
                                <span class="badge bg-info">Mendatang</span>
                            @endif
                        </td>
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
        
        <div class="mt-3">
            {{ $jadwals->links() }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#jadwal-table').DataTable({
            "paging": false,
            "info": false,
            "searching": true,
            "responsive": true,
            "order": [[1, 'asc'], [2, 'asc']], // Sort by date, then time
            "columnDefs": [
                { "orderable": false, "targets": 8 }
            ]
        });
    });
</script>
@endpush