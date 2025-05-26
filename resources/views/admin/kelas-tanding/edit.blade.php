@extends('layouts.admin')

@section('title', 'Edit Kelas Tanding')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.kelas-tanding.index') }}">Kelas Tanding</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="card shadow">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">
            Edit Kelas: {{ $kelasTanding->label_keterangan ?: $kelasTanding->kode_kelas }}
        </h6>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.kelas-tanding.update', $kelasTanding->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="kelompok_usia_id" class="form-label">Kelompok Usia <span class="text-danger">*</span></label>
                        <select class="form-select @error('kelompok_usia_id') is-invalid @enderror" id="kelompok_usia_id" name="kelompok_usia_id" required>
                            <option value="">Pilih Kelompok Usia</option>
                            @foreach($kelompokUsias as $usia)
                                <option value="{{ $usia->id }}" {{ old('kelompok_usia_id', $kelasTanding->kelompok_usia_id) == $usia->id ? 'selected' : '' }}>
                                    {{ $usia->nama }} ({{ $usia->rentang_usia_min }} - {{ $usia->rentang_usia_max }} tahun)
                                </option>
                            @endforeach
                        </select>
                        @error('kelompok_usia_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="jenis_kelamin" class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                        <select class="form-select @error('jenis_kelamin') is-invalid @enderror" id="jenis_kelamin" name="jenis_kelamin" required>
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="putra" {{ old('jenis_kelamin', $kelasTanding->jenis_kelamin) == 'putra' ? 'selected' : '' }}>Putra</option>
                            <option value="putri" {{ old('jenis_kelamin', $kelasTanding->jenis_kelamin) == 'putri' ? 'selected' : '' }}>Putri</option>
                        </select>
                        @error('jenis_kelamin')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="berat_min" class="form-label">Berat Minimum (kg) <span class="text-danger">*</span></label>
                        <input type="number" step="0.1" class="form-control @error('berat_min') is-invalid @enderror" id="berat_min" name="berat_min" value="{{ old('berat_min', $kelasTanding->berat_min) }}" required {{ $kelasTanding->is_open_class ? 'disabled' : '' }}>
                        @error('berat_min')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="berat_max" class="form-label">Berat Maksimum (kg) <span class="text-danger">*</span></label>
                        <input type="number" step="0.1" class="form-control @error('berat_max') is-invalid @enderror" id="berat_max" name="berat_max" value="{{ old('berat_max', $kelasTanding->berat_max) }}" required {{ $kelasTanding->is_open_class ? 'disabled' : '' }}>
                        @error('berat_max')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="label_keterangan" class="form-label">Label Keterangan (Opsional)</label>
                <input type="text" class="form-control @error('label_keterangan') is-invalid @enderror" id="label_keterangan" name="label_keterangan" value="{{ old('label_keterangan', $kelasTanding->label_keterangan) }}">
                @error('label_keterangan')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
                <div class="form-text">
                    Contoh: Kelas A Putra, Kelas Bebas Putri. Jika dikosongkan, Kode Kelas akan dibuat otomatis.
                </div>
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="is_open_class" name="is_open_class" value="1" {{ old('is_open_class', $kelasTanding->is_open_class) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_open_class">Ini adalah Kelas Terbuka (Open Class)</label>
                <div class="form-text">
                    Jika dicentang, berat minimum dan maksimum akan diabaikan (misal: Kelas Bebas).
                </div>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <a href="{{ route('admin.kelas-tanding.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        function toggleBeratInputs() {
            var isOpenClass = $('#is_open_class').is(':checked');
            $('#berat_min').prop('disabled', isOpenClass);
            $('#berat_max').prop('disabled', isOpenClass);
            if (isOpenClass) {
                $('#berat_min').val(''); // Kosongkan jika open class
                $('#berat_max').val(''); // Kosongkan jika open class
            }
        }

        $('#is_open_class').on('change', function() {
            toggleBeratInputs();
        });

        // Initial call to set state based on current checkbox value
        toggleBeratInputs();
    });
</script>
@endpush