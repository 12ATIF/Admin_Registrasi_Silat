@extends('layouts.admin')

@section('title', 'Manajemen Peserta')

@section('breadcrumb')
<li class="breadcrumb-item active">Peserta</li>
@endsection

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold">Filter</h6>
    </div>
    <div class="card-body">
        <form id="filter-form" method="GET">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label for="kategori_id" class="form-label">Kategori</label>
                    <select class="form-select" id="kategori_id" name="kategori_id">
                        <option value="">Semua Kategori</option>
                        @foreach($kategoris as $kategori)
                            <option value="{{ $kategori->id }}">
                                {{ $kategori->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="kelompok_usia_id" class="form-label">Kelompok Usia</label>
                    <select class="form-select" id="kelompok_usia_id" name="kelompok_usia_id">
                        <option value="">Semua Kelompok Usia</option>
                        @foreach($kelompokUsias as $usia)
                            <option value="{{ $usia->id }}">
                                {{ $usia->nama }} ({{ $usia->rentang_usia_min }}-{{ $usia->rentang_usia_max }} tahun)
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="status_verifikasi" class="form-label">Status Verifikasi</label>
                    <select class="form-select" id="status_verifikasi" name="status_verifikasi">
                        <option value="">Semua Status</option>
                        <option value="valid">Valid</option>
                        <option value="pending">Pending</option>
                        <option value="tidak_valid">Tidak Valid</option>
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
            <table id="peserta-table" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Jenis Kelamin</th>
                        <th>Tanggal Lahir</th>
                        <th>Berat Badan</th>
                        <th>Kontingen</th>
                        <th>Kategori</th>
                        <th>Kelompok Usia</th>
                        <th>Kelas Tanding</th>
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

<!-- Modal Override Kelas -->
<div class="modal fade" id="overrideKelasModal" tabindex="-1" aria-labelledby="overrideKelasModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="overrideKelasModalLabel">Ubah Kelas Tanding Peserta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="overrideKelasForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <p>Nama Peserta: <strong id="peserta-nama"></strong></p>
                    <p>Berat Badan: <strong id="peserta-berat"></strong> kg</p>
                    <p>Kelas Tanding Saat Ini: <strong id="peserta-kelas-current"></strong></p>
                    
                    <div class="mb-3">
                        <label for="kelas_tanding_id" class="form-label">Kelas Tanding Baru</label>
                        <select class="form-select" id="kelas_tanding_id" name="kelas_tanding_id" required>
                            <!-- Options will be populated by JS -->
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
        // Initialize DataTable
        var table = $('#peserta-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('admin.peserta.index') }}",
                data: function(d) {
                    d.kategori_id = $('#kategori_id').val();
                    d.kelompok_usia_id = $('#kelompok_usia_id').val();
                    d.status_verifikasi = $('#status_verifikasi').val();
                }
            },
            columns: [
                {data: 'nama', name: 'nama'},
                {data: 'jenis_kelamin', name: 'jenis_kelamin'},
                {data: 'tanggal_lahir', name: 'tanggal_lahir'},
                {data: 'berat_badan', name: 'berat_badan'},
                {data: 'kontingen.nama', name: 'kontingen.nama'},
                {data: 'subkategori_lomba.kategori_lomba.nama', name: 'subkategoriLomba.kategoriLomba.nama'},
                {data: 'kelompok_usia.nama', name: 'kelompokUsia.nama'},
                {data: 'kelas_tanding', name: 'kelasTanding.label_keterangan'},
                {data: 'status_verifikasi', name: 'status_verifikasi'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ],
            order: [[0, 'asc']]
        });
        
        // Handle filter button
        $('#btn-filter').on('click', function() {
            table.ajax.reload();
        });
        
        // Handle reset button
        $('#btn-reset').on('click', function() {
            $('#kategori_id').val('');
            $('#kelompok_usia_id').val('');
            $('#status_verifikasi').val('');
            table.ajax.reload();
        });
        
        // Handle verification buttons
        $('#peserta-table').on('click', '.verify-btn', function() {
            var pesertaId = $(this).data('id');
            var status = $(this).data('status');
            var verifyUrl = `{{ route('admin.peserta.verify', ':id') }}`.replace(':id', pesertaId);
            
            var statusText = status === 'valid' ? 'Valid' : 'Tidak Valid';
            
            if (confirm(`Apakah Anda yakin ingin mengubah status verifikasi peserta menjadi ${statusText}?`)) {
                $.ajax({
                    url: verifyUrl,
                    type: 'PUT',
                    data: {
                        _token: '{{ csrf_token() }}',
                        status_verifikasi: status
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
        
        // Handle override kelas
        $('#peserta-table').on('click', '.override-kelas-btn', function() {
            var pesertaId = $(this).data('id');
            var nama = $(this).data('nama');
            var berat = $(this).data('berat');
            var kelompokUsiaId = $(this).data('kelompok-usia-id');
            var jenisKelamin = $(this).data('jenis-kelamin');
            var kelasTandingId = $(this).data('kelas-tanding-id');
            var kelasTandingName = $(this).data('kelas-tanding-name');
            
            // Set form action
            var overrideUrl = `{{ route('admin.peserta.override-class', ':id') }}`.replace(':id', pesertaId);
            $('#overrideKelasForm').attr('action', overrideUrl);
            
            // Set peserta info
            $('#peserta-nama').text(nama);
            $('#peserta-berat').text(berat);
            $('#peserta-kelas-current').text(kelasTandingName);
            
            // Fetch available classes based on kelompok usia and jenis kelamin
            $.ajax({
                url: '{{ route("admin.kelas-tanding.index") }}',
                type: 'GET',
                data: {
                    kelompok_usia_id: kelompokUsiaId,
                    jenis_kelamin: jenisKelamin === 'L' ? 'putra' : 'putri',
                    expectsJson: true
                },
                success: function(response) {
                    var options = '';
                    response.data.forEach(function(kelas) {
                        var selected = kelas.id == kelasTandingId ? 'selected' : '';
                        options += `<option value="${kelas.id}" ${selected}>${kelas.label_keterangan} (${kelas.berat_min}-${kelas.berat_max} kg)</option>`;
                    });
                    
                    $('#kelas_tanding_id').html(options);
                    $('#overrideKelasModal').modal('show');
                },
                error: function(xhr) {
                    alert('Terjadi kesalahan saat mengambil data kelas tanding.');
                }
            });
        });
        
        // Handle override form submission
        // Handle override form submission
        $('#overrideKelasForm').on('submit', function(e) {
            e.preventDefault();
            
            $.ajax({
                url: $(this).attr('action'),
                type: 'PUT',
                data: $(this).serialize(),
                success: function(response) {
                    $('#overrideKelasModal').modal('hide');
                    alert(response.message);
                    table.ajax.reload();
                },
                error: function(xhr) {
                    alert('Terjadi kesalahan: ' + xhr.responseJSON.message);
                }
            });
        });
    });
</script>
@endpush