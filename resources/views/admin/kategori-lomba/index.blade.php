@extends('layouts.admin')

@section('title', 'Kategori Lomba')

@section('breadcrumb')
<li class="breadcrumb-item active">Kategori Lomba</li>
@endsection

@section('action-buttons')
<a href="{{ route('admin.kategori-lomba.create') }}" class="btn btn-primary">
    <i class="fas fa-plus"></i> Tambah Kategori
</a>
@endsection

@section('content')
<div class="card shadow">
    <div class="card-body">
        <div class="table-responsive">
            <table id="kategori-table" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Nama Kategori</th>
                        <th>Jumlah Subkategori</th>
                        <th>Dibuat pada</th>
                        <th>Aksi</th>
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
        $('#kategori-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.kategori-lomba.index') }}",
            columns: [
                {data: 'nama', name: 'nama'},
                {data: 'subkategori_lombas_count', name: 'subkategori_lombas_count'},
                {data: 'created_at', name: 'created_at'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ],
            order: [[0, 'asc']]
        });
    });
</script>
@endpush