@extends('layouts.admin')

@section('title', 'Detail Pelatih')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.pelatih.index') }}">Pelatih</a></li>
<li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold">Informasi Pelatih</h6>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    <div class="d-inline-block bg-primary text-white rounded-circle p-3 mb-2" style="width: 100px; height: 100px;">
                        <i class="fas fa-user-tie fa-3x mt-2"></i>
                    </div>
                    <h5>{{ $pelatih->nama }}</h5>
                    <p class="text-muted">{{ $pelatih->perguruan }}</p>
                    <span class="badge {{ $pelatih->is_active ? 'bg-success' : 'bg-danger' }} mb-2">
                        {{ $pelatih->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </div>
                
                <div class="mb-3">
                    <h6 class="font-weight-bold">Email:</h6>
                    <p>{{ $pelatih->email }}</p>
                </div>
                
                <div class="mb-3">
                    <h6 class="font-weight-bold">Nomor HP:</h6>
                    <p>{{ $pelatih->no_hp ?: '-' }}</p>
                </div>
                
                <div class="mb-3">
                    <h6 class="font-weight-bold">Tanggal Registrasi:</h6>
                    <p>{{ $pelatih->created_at->format('d/m/Y H:i') }}</p>
                </div>
                
                <div class="mb-3">
                    <h6 class="font-weight-bold">Terakhir Diperbarui:</h6>
                    <p>{{ $pelatih->updated_at->format('d/m/Y H:i') }}</p>
                </div>
                
                <div class="d-grid gap-2">
                    <button class="btn btn-warning toggle-status" data-id="{{ $pelatih->id }}" data-status="{{ $pelatih->is_active }}">
                        <i class="fas {{ $pelatih->is_active ? 'fa-ban' : 'fa-check' }}"></i> 
                        {{ $pelatih->is_active ? 'Nonaktifkan Pelatih' : 'Aktifkan Pelatih' }}
                    </button>
                    <button class="btn btn-secondary reset-password" data-id="{{ $pelatih->id }}">
                        <i class="fas fa-key"></i> Reset Password
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold">Daftar Kontingen</h6>
            </div>
            <div class="card-body">
                @if($pelatih->kontingens->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Nama Kontingen</th>
                                    <th>Asal Daerah</th>
                                    <th>Jumlah Peserta</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pelatih->kontingens as $kontingen)
                                <tr>
                                    <td>{{ $kontingen->nama }}</td>
                                    <td>{{ $kontingen->asal_daerah }}</td>
                                    <td>{{ $kontingen->pesertas->count() }}</td>
                                    <td>
                                        <span class="badge {{ $kontingen->is_active ? 'bg-success' : 'bg-danger' }}">
                                            {{ $kontingen->is_active ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.kontingen.show', $kontingen->id) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i> Detail
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-3">
                        <p class="text-muted mb-0">Pelatih belum memiliki kontingen</p>
                    </div>
                @endif
            </div>
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
        // Reset Password
        $('.reset-password').on('click', function() {
            var pelatihId = $(this).data('id');
            var resetUrl = `{{ route('admin.pelatih.reset-password', ':id') }}`.replace(':id', pelatihId);
            $('#resetPasswordForm').attr('action', resetUrl);
            $('#resetPasswordModal').modal('show');
        });

        // Toggle Status
        $('.toggle-status').on('click', function() {
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
                        location.reload();
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