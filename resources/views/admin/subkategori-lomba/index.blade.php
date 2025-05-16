@extends('layouts.admin')

@section('title', 'Subkategori Lomba')

@section('breadcrumb')
<li class="breadcrumb-item active">Subkategori Lomba</li>
@endsection

@section('action-buttons')
<a href="{{ route('admin.subkategori-lomba.create') }}" class="btn btn-primary">
    <i class="fas fa-plus"></i> Tambah Subkategori
</a>
@endsection

@section('content')
<div class="card shadow">
    <div class="card-body">
        <div class="table-responsive">
            <table id="subkategori-table" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Kategori</th>
                        <th>Nama Subkategori</th>
                        <th>Jenis</th>
                        <th>Jumlah Peserta</th>
                        <th>Harga Pendaftaran</th>
                        <th>Kelompok Usia</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($subkategoris as $subkategori)
                    <tr>
                        <td>{{ $subkategori->kategoriLomba->nama }}</td>
                        <td>{{ $subkategori->nama }}</td>
                        <td>{{ ucfirst($subkategori->jenis) }}</td>
                        <td>{{ $subkategori->jumlah_peserta }}</td>
                        <td>Rp {{ number_format($subkategori->harga_pendaftaran, 0, ',', '.') }}</td>
                        <td>
                            @if($subkategori->kelompokUsias->count() > 0)
                                @foreach($subkategori->kelompokUsias as $usia)
                                    <span class="badge bg-info">{{ $usia->nama }}</span>
                                @endforeach
                            @else
                                <span class="badge bg-secondary">Tidak ada</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.subkategori-lomba.show', $subkategori->id) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.subkategori-lomba.edit', $subkategori->id) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.subkategori-lomba.destroy', $subkategori->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus subkategori ini?');">
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
            {{ $subkategoris->links() }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#subkategori-table').DataTable({
            "paging": false,
            "info": false,
            "searching": true,
            "responsive": true,
            "columnDefs": [
                { "orderable": false, "targets": 6 }
            ]
        });
    });
</script>
@endpush