@extends('layouts.admin')

@section('title', 'Kelompok Usia')

@section('breadcrumb')
<li class="breadcrumb-item active">Kelompok Usia</li>
@endsection

@section('action-buttons')
<a href="{{ route('admin.kelompok-usia.create') }}" class="btn btn-primary">
    <i class="fas fa-plus"></i> Tambah Kelompok Usia
</a>
@endsection

@section('content')
<div class="card shadow">
    <div class="card-body">
        <div class="table-responsive">
            <table id="kelompok-usia-table" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Rentang Usia</th>
                        <th>Jumlah Subkategori</th>
                        <th>Jumlah Kelas Tanding</th>
                        <th>Jumlah Peserta</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($kelompokUsias as $usia)
                    <tr>
                        <td>{{ $usia->nama }}</td>
                        <td>{{ $usia->rentang_usia_min }} - {{ $usia->rentang_usia_max }} tahun</td>
                        <td>{{ $usia->subkategori_lombas_count }}</td>
                        <td>{{ $usia->kelas_tandings_count }}</td>
                        <td>{{ $usia->pesertas_count }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.kelompok-usia.show', $usia->id) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.kelompok-usia.edit', $usia->id) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.kelompok-usia.destroy', $usia->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kelompok usia ini?');">
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
            {{ $kelompokUsias->links() }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#kelompok-usia-table').DataTable({
            "paging": false,
            "info": false,
            "searching": true,
            "responsive": true,
            "columnDefs": [
                { "orderable": false, "targets": 5 }
            ]
        });
    });
</script>
@endpush