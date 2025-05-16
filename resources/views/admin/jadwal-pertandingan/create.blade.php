@extends('layouts.admin')

@section('title', 'Tambah Jadwal Pertandingan')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.jadwal-pertandingan.index') }}">Jadwal Pertandingan</a></li>
<li class="breadcrumb-item active">Tambah</li>
@endsection

@section('content')
<div class="card shadow">
    <div class="card-body">
        <form action="{{ route('admin.jadwal-pertandingan.store') }}" method="POST">
            @csrf
            
            <div class="mb-3">
                <label for="pertandingan_id" class="form-label">Pertandingan <span class="text-danger">*</span></label>
                <select class="form-select @error('pertandingan_id') is-invalid @enderror" id="pertandingan_id" name="pertandingan_id" required>
                    <option value="">Pilih Pertandingan</option>
                    @foreach($pertandingans as $pertandingan)
                        <option value="{{ $pertandingan->id }}" 
                            {{ old('pertandingan_id', request('pertandingan_id')) == $pertandingan->id ? 'selected' : '' }}
                            data-tanggal="{{ $pertandingan->tanggal_event->format('Y-m-d') }}"
                            data-lokasi="{{ $pertandingan->lokasi_umum }}">
                            {{ $pertandingan->nama_event }} - {{ $pertandingan->tanggal_event->format('d/m/Y') }}
                        </option>
                    @endforeach
                </select>
                @error('pertandingan_id')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="subkategori_id" class="form-label">Subkategori Lomba <span class="text-danger">*</span></label>
                <select class="form-select @error('subkategori_id') is-invalid @enderror" id="subkategori_id" name="subkategori_id" required>
                    <option value="">Pilih Subkategori</option>
                    @foreach($subkategoris as $subkategori)
                        <option value="{{ $subkategori->id }}" {{ old('subkategori_id') == $subkategori->id ? 'selected' : '' }}>
                            {{ $subkategori->kategoriLomba->nama }} - {{ $subkategori->nama }}
                        </option>
                    @endforeach
                </select>
                @error('subkategori_id')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="kelompok_usia_id" class="form-label">Kelompok Usia <span class="text-danger">*</span></label>
                <select class="form-select @error('kelompok_usia_id') is-invalid @enderror" id="kelompok_usia_id" name="kelompok_usia_id" required>
                    <option value="">Pilih Kelompok Usia</option>
                    @foreach($kelompokUsias as $usia)
                        <option value="{{ $usia->id }}" {{ old('kelompok_usia_id') == $usia->id ? 'selected' : '' }}>
                            {{ $usia->nama }} ({{ $usia->rentang_usia_min }}-{{ $usia->rentang_usia_max }} tahun)
                        </option>
                    @endforeach
                </select>
                @error('kelompok_usia_id')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="tanggal" class="form-label">Tanggal <span class="text-danger">*</span></label>
                <input type="date" class="form-control @error('tanggal') is-invalid @enderror" id="tanggal" name="tanggal" value="{{ old('tanggal') }}" required>
                @error('tanggal')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="waktu_mulai" class="form-label">Waktu Mulai <span class="text-danger">*</span></label>
                    <input type="time" class="form-control @error('waktu_mulai') is-invalid @enderror" id="waktu_mulai" name="waktu_mulai" value="{{ old('waktu_mulai') }}" required>
                    @error('waktu_mulai')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="waktu_selesai" class="form-label">Waktu Selesai</label>
                    <input type="time" class="form-control @error('waktu_selesai') is-invalid @enderror" id="waktu_selesai" name="waktu_selesai" value="{{ old('waktu_selesai') }}">
                    @error('waktu_selesai')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
            
            <div class="mb-3">
                <label for="lokasi_detail" class="form-label">Detail Lokasi</label>
                <input type="text" class="form-control @error('lokasi_detail') is-invalid @enderror" id="lokasi_detail" name="lokasi_detail" value="{{ old('lokasi_detail') }}">
                @error('lokasi_detail')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
                <div class="form-text">
                    Contoh: Gelanggang A, Matras 1
                </div>
            </div>
            
            <div class="d-flex justify-content-between">
                <a href="{{ route('admin.jadwal-pertandingan.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Auto-set tanggal based on pertandingan selection
        $('#pertandingan_id').on('change', function() {
            var selectedOption = $(this).find('option:selected');
            var eventDate = selectedOption.data('tanggal');
            
            if (eventDate) {
                $('#tanggal').val(eventDate);
            }
        });
        
        // Validate subkategori and kelompok usia compatibility
        $('#subkategori_id').on('change', function() {
            checkCompatibility();
        });
        
        $('#kelompok_usia_id').on('change', function() {
            checkCompatibility();
        });
        
        function checkCompatibility() {
            var subkategoriId = $('#subkategori_id').val();
            var kelompokUsiaId = $('#kelompok_usia_id').val();
            
            if (subkategoriId && kelompokUsiaId) {
                $.ajax({
                    url: '{{ route("admin.check-compatibility") }}',
                    type: 'GET',
                    data: {
                        subkategori_id: subkategoriId,
                        kelompok_usia_id: kelompokUsiaId
                    },
                    success: function(response) {
                        if (!response.compatible) {
                            alert('Peringatan: Subkategori ini tidak didukung untuk kelompok usia yang dipilih.');
                        }
                    }
                });
            }
        }
        
        // Validate that waktu_selesai is after waktu_mulai
        $('form').on('submit', function(e) {
            var waktuMulai = $('#waktu_mulai').val();
            var waktuSelesai = $('#waktu_selesai').val();
            
            if (waktuMulai && waktuSelesai && waktuMulai >= waktuSelesai) {
                e.preventDefault();
                alert('Waktu selesai harus setelah waktu mulai.');
                return false;
            }
        });
        
        // Trigger change event on page load to set default values
        if ($('#pertandingan_id').val()) {
            $('#pertandingan_id').trigger('change');
        }
    });
</script>
@endpush