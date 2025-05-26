@extends('layouts.admin')

@section('title', 'Detail Kategori Lomba')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.kategori-lomba.index') }}">Kategori Lomba</a></li>
    <li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card shadow">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Informasi Kategori: {{ $kategoriLomba->nama }}</h6>
                <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end shadow animated--fade-in"
                        aria-labelledby="dropdownMenuLink">
                        <div class="dropdown-header">Opsi:</div>
                        <a class="dropdown-item" href="{{ route('admin.kategori-lomba.edit', $kategoriLomba->id) }}">
                            <i class="fas fa-edit fa-sm fa-fw me-2 text-gray-400"></i>
                            Edit Kategori
                        </a>
                        <div class="dropdown-divider"></div>
                        <form action="{{ route('admin.kategori-lomba.destroy', $kategoriLomba->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus kategori ini? Semua subkategori yang terkait juga akan terhapus.')">
                                <i class="fas fa-trash fa-sm fa-fw me-2"></i>
                                Hapus Kategori
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <dl class="row">
                            <dt class="col-sm-4">ID Kategori:</dt>
                            <dd class="col-sm-8">{{ $kategoriLomba->id }}</dd>

                            <dt class="col-sm-4">Nama Kategori:</dt>
                            <dd class="col-sm-8">{{ $kategoriLomba->nama }}</dd>
                        </dl>
                    </div>
                    <div class="col-md-6">
                        <dl class="row">
                            <dt class="col-sm-4">Tanggal Dibuat:</dt>
                            <dd class="col-sm-8">{{ $kategoriLomba->created_at->format('d F Y H:i') }}</dd>

                            <dt class="col-sm-4">Terakhir Diperbarui:</dt>
                            <dd class="col-sm-8">{{ $kategoriLomba->updated_at->format('d F Y H:i') }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Daftar Subkategori</h6>
                <a href="{{ route('admin.subkategori-lomba.create', ['kategori_id' => $kategoriLomba->id]) }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus fa-sm"></i> Tambah Subkategori
                </a>
            </div>
            <div class="card-body">
                @if($kategoriLomba->subkategoriLombas && $kategoriLomba->subkategoriLombas->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="subkategoriTable">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Subkategori</th>
                                    <th>Jenis</th>
                                    <th>Jumlah Peserta</th>
                                    <th>Harga Pendaftaran</th>
                                    <th>Kelompok Usia Terkait</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($kategoriLomba->subkategoriLombas as $index => $subkategori)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $subkategori->nama }}</td>
                                    <td>{{ ucfirst($subkategori->jenis) }}</td>
                                    <td>{{ $subkategori->jumlah_peserta }}</td>
                                    <td>Rp {{ number_format($subkategori->harga_pendaftaran, 0, ',', '.') }}</td>
                                    <td>
                                        @forelse($subkategori->kelompokUsias as $kelompokUsia)
                                            <span class="badge bg-info me-1">{{ $kelompokUsia->nama }}</span>
                                        @empty
                                            <span class="badge bg-secondary">Belum ada</span>
                                        @endforelse
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.subkategori-lomba.show', $subkategori->id) }}" class="btn btn-sm btn-info" title="Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.subkategori-lomba.edit', $subkategori->id) }}" class="btn btn-sm btn-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.subkategori-lomba.destroy', $subkategori->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus subkategori ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
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
                        <i class="fas fa-folder-open fa-3x text-gray-400 mb-3"></i>
                        <p class="text-muted mb-0">Belum ada subkategori untuk kategori ini.</p>
                        <p class="text-muted">Anda dapat menambahkannya dengan menekan tombol "Tambah Subkategori" di atas.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="mt-4">
    <a href="{{ route('admin.kategori-lomba.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar Kategori
    </a>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#subkategoriTable').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Indonesian.json"
            },
            "columnDefs": [
                { "orderable": false, "targets": 6 } // Disable ordering for action column
            ]
        });
    });
</script>
@endpush