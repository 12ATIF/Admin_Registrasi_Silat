@extends('layouts.admin')

@section('title', 'Manajemen Pembayaran')

@section('breadcrumb')
<li class="breadcrumb-item active">Pembayaran</li>
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
                    <label for="status" class="form-label">Status Pembayaran</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">Semua Status</option>
                        <option value="belum_bayar">Belum Bayar</option>
                        <option value="menunggu_verifikasi">Menunggu Verifikasi</option>
                        <option value="lunas">Lunas</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3 d-flex align-items-end">
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
            <table id="pembayaran-table" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Kontingen</th>
                        <th>Asal Daerah</th>
                        <th>Total Tagihan</th>
                        <th>Jumlah Peserta</th>
                        <th>Status</th>
                        <th>Tgl. Verifikasi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- DataTables will populate this --}}
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Preview Bukti Transfer -->
<div class="modal fade" id="previewBuktiModal" tabindex="-1" aria-labelledby="previewBuktiModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="previewBuktiModalLabel">Preview Bukti Transfer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <img id="bukti-preview" src="" alt="Bukti Transfer" class="img-fluid">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Verifikasi -->
<div class="modal fade" id="verifikasiModal" tabindex="-1" aria-labelledby="verifikasiModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="verifikasiModalLabel">Verifikasi Pembayaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="verifikasiForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <p>Kontingen: <strong id="kontingen-nama"></strong></p>
                    <p>Total Tagihan: <strong id="total-tagihan"></strong></p>
                    
                    <div class="mb-3">
                        <label for="status" class="form-label">Status Pembayaran</label>
                        <select class="form-select" id="status-select" name="status" required>
                            <option value="belum_bayar">Belum Bayar</option>
                            <option value="menunggu_verifikasi">Menunggu Verifikasi</option>
                            <option value="lunas">Lunas</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Ensure CSRF token is set for all AJAX requests
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    
    // Initialize DataTable
    var table = $('#pembayaran-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('admin.pembayaran.index') }}",
            data: function(d) {
                d.status = $('#status').val();
            },
            error: function(xhr, error, thrown) {
                console.log('DataTables error: ' + error + ' - ' + thrown);
                console.log(xhr.responseText);
                alert('Error loading data: ' + error);
            }
        },
        columns: [
            {data: 'kontingen.nama', name: 'kontingen.nama'},
            {data: 'kontingen.asal_daerah', name: 'kontingen.asal_daerah'},
            {data: 'total_tagihan', name: 'total_tagihan'},
            {data: 'pesertas_count', name: 'pesertas_count', searchable: false},
            {data: 'status', name: 'status'},
            {data: 'verified_at', name: 'verified_at'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ],
        order: [[4, 'asc']]
    });
    
    // Filter handling
    $('#btn-filter').on('click', function() {
        table.ajax.reload();
    });
    
    $('#btn-reset').on('click', function() {
        $('#status').val('');
        table.ajax.reload();
    });
    
    // Handle bukti preview
    $('#pembayaran-table').on('click', '.preview-btn', function() {
        var buktiUrl = $(this).data('bukti');
        
        $('#bukti-preview').attr('src', buktiUrl);
        $('#previewBuktiModal').modal('show');
    });
    
    // Handle verify button
    $('#pembayaran-table').on('click', '.verify-btn', function() {
        var pembayaranId = $(this).data('id');
        var kontingenNama = $(this).data('kontingen');
        var totalTagihan = $(this).data('tagihan');
        var currentStatus = $(this).data('status');
        
        var verifyUrl = `{{ route('admin.pembayaran.verify', ':id') }}`.replace(':id', pembayaranId);
        
        $('#verifikasiForm').attr('action', verifyUrl);
        $('#kontingen-nama').text(kontingenNama);
        $('#total-tagihan').text('Rp ' + new Intl.NumberFormat('id-ID').format(totalTagihan));
        $('#status-select').val(currentStatus);
        
        $('#verifikasiModal').modal('show');
    });
    
    // Handle form submission
    $('#verifikasiForm').on('submit', function(e) {
        e.preventDefault();
        
        $.ajax({
            url: $(this).attr('action'),
            type: 'PUT',
            data: $(this).serialize(),
            success: function(response) {
                $('#verifikasiModal').modal('hide');
                alert(response.message);
                table.ajax.reload();
            },
            error: function(xhr) {
                var message = xhr.responseJSON && xhr.responseJSON.message 
                    ? xhr.responseJSON.message 
                    : 'Terjadi kesalahan saat memproses permintaan.';
                alert('Error: ' + message);
            }
        });
    });
});
</script>
@endpush