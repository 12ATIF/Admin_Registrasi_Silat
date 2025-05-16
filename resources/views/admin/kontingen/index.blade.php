@extends('layouts.admin')

@section('title', 'Manajemen Kontingen')

@section('breadcrumb')
<li class="breadcrumb-item active">Kontingen</li>
@endsection

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold">Filter</h6>
    </div>
    <div class="card-body">
        <form id="filter-form">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="search" class="form-label">Pencarian</label>
                    <input type="text" class="form-control" id="search" name="search" placeholder="Cari nama kontingen atau asal daerah">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="is_active" class="form-label">Status</label>
                    <select class="form-select" id="is_active" name="is_active">
                        <option value="">Semua Status</option>
                        <option value="1">Aktif</option>
                        <option value="0">Nonaktif</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3 d-flex align-items-end">
                    <div class="d-grid gap-2 w-100">
                        <button type="button" id="btn-filter" class="btn btn-primary">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                        <button type="button" id="btn-reset" class="btn btn-secondary">
                            <i class="fas fa-sync"></i> Reset
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card shadow">
    <div class="card-body">
        <div class="table-responsive">
            <table id="kontingen-table" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Nama Kontingen</th>
                        <th>Asal Daerah</th>
                        <th>Pelatih</th>
                        <th>Kontak Pendamping</th>
                        <th>Jumlah Peserta</th>
                        <th>Status</th>
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
        var table = $('#kontingen-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('admin.kontingen.index') }}",
                data: function(d) {
                    d.search = $('#search').val();
                    d.is_active = $('#is_active').val();
                }
            },
            columns: [
                {data: 'nama', name: 'nama'},
                {data: 'asal_daerah', name: 'asal_daerah'},
                {
                    data: 'pelatih.nama', 
                    name: 'pelatih.nama',
                    render: function(data, type, row) {
                        return data + ' (' + row.pelatih.perguruan + ')';
                    }
                },
                {data: 'kontak_pendamping', name: 'kontak_pendamping', defaultContent: '-'},
                {data: 'pesertas_count', name: 'pesertas_count'},
                {data: 'is_active', name: 'is_active'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ],
            order: [[0, 'asc']]
        });

        // Filter handling
        $('#btn-filter').on('click', function() {
            table.ajax.reload();
        });
        
        $('#btn-reset').on('click', function() {
            $('#search').val('');
            $('#is_active').val('');
            table.ajax.reload();
        });

        // Toggle Status
        $('#kontingen-table').on('click', '.toggle-status', function() {
            var kontingenId = $(this).data('id');
            var currentStatus = $(this).data('status');
            var statusUrl = `{{ route('admin.kontingen.toggle-status', ':id') }}`.replace(':id', kontingenId);
            var statusText = currentStatus == 1 ? 'nonaktifkan' : 'aktifkan';
            
            if (confirm(`Apakah Anda yakin ingin ${statusText} kontingen ini?`)) {
                $.ajax({
                    url: statusUrl,
                    type: 'PUT',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        alert(response.message);
                        table.ajax.reload();
                    },
                    error: function(xhr) {
                        alert('Terjadi kesalahan: ' + xhr.responseJSON.message);
                    }
                });
            }
        });
    });
</script>
@endpush