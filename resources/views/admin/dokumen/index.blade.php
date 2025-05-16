@extends('layouts.admin')

@section('title', 'Verifikasi Dokumen')

@section('breadcrumb')
<li class="breadcrumb-item active">Dokumen</li>
@endsection

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold">Filter</h6>
    </div>
    <div class="card-body">
        <form id="filter-form">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="jenis_dokumen" class="form-label">Jenis Dokumen</label>
                    <select class="form-select" id="jenis_dokumen" name="jenis_dokumen">
                        <option value="">Semua Jenis</option>
                        <option value="KTP">KTP</option>
                        <option value="Akta Lahir">Akta Lahir</option>
                        <option value="Foto">Foto</option>
                        <option value="Surat Keterangan">Surat Keterangan</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="verified" class="form-label">Status Verifikasi</label>
                    <select class="form-select" id="verified" name="verified">
                        <option value="">Semua Status</option>
                        <option value="1">Terverifikasi</option>
                        <option value="0">Belum Diverifikasi</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3 d-flex align-items-end">
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
            <table id="dokumen-table" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Nama Peserta</th>
                        <th>Kontingen</th>
                        <th>Jenis Dokumen</th>
                        <th>Tgl Upload</th>
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

<!-- Modal Preview Dokumen -->
<div class="modal fade" id="previewDokumenModal" tabindex="-1" aria-labelledby="previewDokumenModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="previewDokumenModalLabel">Preview Dokumen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <div id="preview-container">
                        <!-- Image or PDF will be loaded here -->
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <a href="#" id="download-link" class="btn btn-primary" target="_blank">Download</a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Initialize DataTable
        var table = $('#dokumen-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('admin.dokumen.index') }}",
                data: function(d) {
                    d.jenis_dokumen = $('#jenis_dokumen').val();
                    d.verified = $('#verified').val();
                }
            },
            columns: [
                {data: 'peserta.nama', name: 'peserta.nama'},
                {data: 'peserta.kontingen.nama', name: 'peserta.kontingen.nama'},
                {data: 'jenis_dokumen', name: 'jenis_dokumen'},
                {data: 'created_at', name: 'created_at'},
                {data: 'verified_at', name: 'verified_at'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ],
            order: [[4, 'asc'], [0, 'asc']]
        });
        
        // Filter handling
        $('#btn-filter').on('click', function() {
            table.ajax.reload();
        });
        
        $('#btn-reset').on('click', function() {
            $('#jenis_dokumen').val('');
            $('#verified').val('');
            table.ajax.reload();
        });
        
        // Document preview
        $('#dokumen-table').on('click', '.preview-btn', function() {
            var filePath = $(this).data('file-path');
            var downloadUrl = $(this).data('download-url');
            var fileExtension = filePath.split('.').pop().toLowerCase();
            
            $('#download-link').attr('href', downloadUrl);
            
            // Clear previous content
            $('#preview-container').empty();
            
            // Create preview based on file type
            if (fileExtension === 'pdf') {
                $('#preview-container').html(`
                    <object data="${downloadUrl}" type="application/pdf" width="100%" height="500px">
                        <p>Your browser doesn't support PDF preview. <a href="${downloadUrl}">Download instead</a>.</p>
                    </object>
                `);
            } else if (['jpg', 'jpeg', 'png', 'gif'].includes(fileExtension)) {
                $('#preview-container').html(`<img src="${downloadUrl}" alt="Preview" class="img-fluid">`);
            } else {
                $('#preview-container').html(`
                    <div class="alert alert-info">
                        <p>Preview tidak tersedia untuk tipe file ini. Silakan download file untuk melihatnya.</p>
                    </div>
                `);
            }
            
            $('#previewDokumenModal').modal('show');
        });
        
        // Handle verify button
        $('#dokumen-table').on('click', '.verify-btn', function() {
            var dokumenId = $(this).data('id');
            var verifyUrl = `{{ route('admin.dokumen.verify', ':id') }}`.replace(':id', dokumenId);
            
            if (confirm('Apakah Anda yakin ingin memverifikasi dokumen ini?')) {
                $.ajax({
                    url: verifyUrl,
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