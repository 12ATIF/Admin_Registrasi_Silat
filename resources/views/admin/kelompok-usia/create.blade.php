@extends('layouts.admin')

@section('title', 'Tambah Kelompok Usia')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.kelompok-usia.index') }}">Kelompok Usia</a></li>
<li class="breadcrumb-item active">Tambah</li>
@endsection

@section('content')
<div class="card shadow">
    <div class="card-body">
        <form action="{{ route('admin.kelompok-usia.store') }}" method="POST">
            @csrf
            
            <div class="mb-3">
                <label for="nama" class="form-label">Nama Kelompok Usia <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" value="{{ old('nama') }}" required>
                @error('nama')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
                <div class="form-text">
                    Contoh: Usia Dini, Pra Remaja, Remaja, Dewasa
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="rentang_usia_min" class="form-label">Usia Minimum (tahun) <span class="text-danger">*</span></label>
                    <input type="number" class="form-control @error('rentang_usia_min') is-invalid @enderror" id="rentang_usia_min" name="rentang_usia_min" value="{{ old('rentang_usia_min', 0) }}" min="0" required>
                    @error('rentang_usia_min')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="rentang_usia_max" class="form-label">Usia Maksimum (tahun) <span class="text-danger">*</span></label>
                    <input type="number" class="form-control @error('rentang_usia_max') is-invalid @enderror" id="rentang_usia_max" name="rentang_usia_max" value="{{ old('rentang_usia_max', 0) }}" min="0" required>
                    @error('rentang_usia_max')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
            
            <div class="d-flex justify-content-between">
                <a href="{{ route('admin.kelompok-usia.index') }}" class="btn btn-secondary">
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
        // Validate that min age is less than max age
        $('form').on('submit', function(e) {
            var minAge = parseInt($('#rentang_usia_min').val());
            var maxAge = parseInt($('#rentang_usia_max').val());
            
            if (minAge >= maxAge) {
                e.preventDefault();
                alert('Usia minimum harus lebih kecil dari usia maksimum.');
                return false;
            }
        });
    });
</script>
@endpush