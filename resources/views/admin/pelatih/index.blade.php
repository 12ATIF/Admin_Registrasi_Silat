@extends('layouts.admin')

@section('title', 'Manajemen Pelatih')

@section('breadcrumb')
<li class="breadcrumb-item active">Pelatih</li>
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
                    <input type="text" class="form-control" id="search" name="search" placeholder="Cari nama, email, atau perguruan">
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
            <table id="pelatih-table" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Perguruan</th>
                        <th>Email</th>
                        <th>No HP</th>
                        <th>Jumlah Kontingen</th>
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

<!-- Modal Reset Password -->
<div class="modal fade" id="resetPasswordModal" tabindex="-1" aria-labelledby="resetPasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="resetPasswordModalLabel">Reset Password Pelatih</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="resetPasswordForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="password" class="form-label">Password Baru</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Reset Password</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        var table = $('#pelatih-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('admin.pelatih.index') }}",
                data: function(d) {
                    d.search = $('#search').val();
                    d.is_active = $('#is_active').val();
                }
            },
            columns: [
                {data: 'nama', name: 'nama'},
                {data: 'perguruan', name: 'perguruan'},
                {data: 'email', name: 'email'},
                {data: 'no_hp', name: 'no_hp', defaultContent: '-'},
                {data: 'kontingens_count', name: 'kontingens_count'},
                {data: 'is_active', name: 'is_active', searchable: false},
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
        
        // Reset Password
        $('#pelatih-table').on('click', '.reset-password', function() {
            var pelatihId = $(this).data('id');
            var resetUrl = `{{ route('admin.pelatih.reset-password', ':id') }}`.replace(':id', pelatihId);
            $('#resetPasswordForm').attr('action', resetUrl);
            $('#resetPasswordModal').modal('show');
        });
        
        // Handle reset password form submission
        $('#resetPasswordForm').on('submit', function(e) {
            e.preventDefault();
            
            $.ajax({
                url: $(this).attr('action'),
                type: 'PUT',
                data: $(this).serialize(),
                success: function(response) {
                    $('#resetPasswordModal').modal('hide');
                    $('#password').val('');
                    $('#password_confirmation').val('');
                    alert(response.message);
                },
                error: function(xhr) {
                    alert('Terjadi kesalahan: ' + xhr.responseJSON.message);
                }
            });
        });

        // Toggle Status
        $('#pelatih-table').on('click', '.toggle-status', function() {
            var pelatihId = $(this).data('id');
            var currentStatus = $(this).data('status');
            var statusUrl = `{{ route('admin.pelatih.toggle-status', ':id') }}`.replace(':id', pelatihId);
            var statusText = currentStatus == 1 ? 'nonaktifkan' : 'aktifkan';
            
            if (confirm(`Apakah Anda yakin ingin ${statusText} pelatih ini?`)) {
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