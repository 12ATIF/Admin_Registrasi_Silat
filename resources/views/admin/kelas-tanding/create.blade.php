@extends('layouts.admin')

@section('title', 'Tambah Kelas Tanding')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.kelas-tanding.index') }}">Kelas Tanding</a></li>
<li class="breadcrumb-item active">Tambah</li>
@endsection

@section('content')
<div class="card shadow">
    <div class="card-body">
        <form action="{{ route('admin.kelas-tanding.store') }}" method="POST">
            @csrf
            
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
                <label for="jenis_kelamin" class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                <select class="form-select @error('jenis_kelamin') is-invalid @enderror" id="jenis_kelamin" name="jenis_kelamin" required>
                    <option value="">Pilih Jenis Kelamin</option>
                    <option value="putra" {{ old('jenis_kelamin') == 'putra' ? 'selected' : '' }}>Putra</option>
                    <option value="putri" {{ old('jenis_kelamin') == 'putri' ? 'selected' : '' }}>Putri</option>
                </select>
                @error('jenis_kelamin')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="kode_kelas" class="form-label">Kode Kelas <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('kode_kelas') is-invalid @enderror" id="kode_kelas" name="kode_kelas" value="{{ old('kode_kelas') }}" required>
                @error('kode_kelas')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
                <div class="form-text">
                    Contoh: A, B, C, D, E, F, OPEN
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="berat_min" class="form-label">Berat Minimum (kg) <span class="text-danger">*</span></label>
                    <input type="number" class="form-control @error('berat_min') is-invalid @enderror" id="berat_min" name="berat_min" value="{{ old('berat_min', 0) }}" min="0" step="0.1" required>
                    @error('berat_min')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="berat_max" class="form-label">Berat Maksimum (kg) <span class="text-danger">*</span></label>
                    <input type="number" class="form-control @error('berat_max') is-invalid @enderror" id="berat_max" name="berat_max" value="{{ old('berat_max', 0) }}" min="0" step="0.1" required>
                    @error('berat_max')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
            
            <div class="mb-3">
                <label for="label_keterangan" class="form-label">Label Keterangan</label>
                <input type="text" class="form-control @error('label_keterangan') is-invalid @enderror" id="label_keterangan" name="label_keterangan" value="{{ old('label_keterangan') }}">
                @error('label_keterangan')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
                <div class="form-text">
                    Contoh: Kelas A Putra (30-33 kg)
                </div>
            </div>
            
            <div class="mb-3">
                <div class="form-check">
                    <input class="form-check-input @error('is_open_class') is-invalid @enderror" type="checkbox" id="is_open_class" name="is_open_class" value="1" {{ old('is_open_class') ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_open_class">
                        Kelas Terbuka (Open Class)
                    </label>
                    @error('is_open_class')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="form-text ms-4">
                    Centang jika kelas ini adalah kelas terbuka tanpa batasan berat badan maksimum
                </div>
            </div>
            
            <div class="d-flex justify-content-between">
                <a href="{{ route('admin.kelas-tanding.index') }}" class="btn btn-secondary">
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
        // Auto-generate label based on inputs
        function generateLabel() {
            var kelasText = $('#kode_kelas').val();
            var jenisKelamin = $('#jenis_kelamin').val();
            var beratMin = $('#berat_min').val();
            var beratMax = $('#berat_max').val();
            var isOpen = $('#is_open_class').is(':checked');
            
            if (kelasText && jenisKelamin) {
                var jenisText = jenisKelamin === 'putra' ? 'Putra' : 'Putri';
                var rangeText = '';
                
                if (isOpen) {
                    rangeText = '(>' + beratMin + ' kg)';
                } else if (beratMin && beratMax) {
                    rangeText = '(' + beratMin + '-' + beratMax + ' kg)';
                }
                
                var label = 'Kelas ' + kelasText + ' ' + jenisText + ' ' + rangeText;
                $('#label_keterangan').val(label);
            }
        }
        
        // Update the label when inputs change
        $('#kode_kelas, #jenis_kelamin, #berat_min, #berat_max').on('change keyup', function() {
            generateLabel();
        });
        
        $('#is_open_class').on('change', function() {
            if ($(this).is(':checked')) {
                $('#berat_max').val(999).prop('readonly', true);
            } else {
                $('#berat_max').prop('readonly', false);
            }
            generateLabel();
        });
        
        // Validate that min weight is less than max weight
        $('form').on('submit', function(e) {
            var beratMin = parseFloat($('#berat_min').val());
            var beratMax = parseFloat($('#berat_max').val());
            var isOpen = $('#is_open_class').is(':checked');
            
            if (!isOpen && beratMin >= beratMax) {
                e.preventDefault();
                alert('Berat minimum harus lebih kecil dari berat maksimum.');
                return false;
            }
        });
    });
</script>
@endpush