@extends('layouts.admin')

@section('title', 'Edit Kelompok Usia')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.kelompok-usia.index') }}">Kelompok Usia</a></li>
<li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="card shadow">
    <div class="card-body">
        <form action="{{ route('admin.kelompok-usia.update', ['kelompok_usium' => $kelompokUsia->id]) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-3">
                <label for="nama" class="form-label">Nama Kelompok Usia <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" value="{{ old('nama', $kelompokUsia->nama) }}" required>
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
                    <input type="number" class="form-control @error('rentang_usia_min') is-invalid @enderror" id="rentang_usia_min" name="rentang_usia_min" value="{{ old('rentang_usia_min', $kelompokUsia->rentang_usia_min) }}" min="0" required>
                    @error('rentang_usia_min')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="rentang_usia_max" class="form-label">Usia Maksimum (tahun) <span class="text-danger">*</span></label>
                    <input type="number" class="form-control @error('rentang_usia_max') is-invalid @enderror" id="rentang_usia_max" name="rentang_usia_max" value="{{ old('rentang_usia_max', $kelompokUsia->rentang_usia_max) }}" min="0" required>
                    @error('rentang_usia_max')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
            
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i> Perubahan kelompok usia akan mempengaruhi kelas tanding dan kategori lomba yang terkait.
            </div>
            
            <div class="d-flex justify-content-between">
                <a href="{{ route('admin.kelompok-usia.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Detail Relasi -->
<div class="row mt-4">
    <!-- Subkategori yang terkait -->
    <div class="col-md-6 mb-4">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold">Subkategori Terkait</h6>
            </div>
            <div class="card-body">
                @if($kelompokUsia->subkategoriLombas->count() > 0)
                    <ul class="list-group">
                        @foreach($kelompokUsia->subkategoriLombas as $subkategori)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>{{ $subkategori->nama }}</strong>
                                    <p class="text-muted mb-0">{{ $subkategori->kategoriLomba->nama }}</p>
                                </div>
                                <span class="badge bg-primary rounded-pill">{{ ucfirst($subkategori->jenis) }}</span>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-center text-muted">Tidak ada subkategori yang terkait</p>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Kelas Tanding yang terkait -->
    <div class="col-md-6 mb-4">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold">Kelas Tanding Terkait</h6>
            </div>
            <div class="card-body">
                @if($kelompokUsia->kelasTandings->count() > 0)
                    <ul class="list-group">
                        @foreach($kelompokUsia->kelasTandings as $kelas)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>{{ $kelas->kode_kelas }}</strong>
                                    <p class="text-muted mb-0">{{ $kelas->berat_min }}-{{ $kelas->is_open_class ? 'Open' : $kelas->berat_max }} kg ({{ ucfirst($kelas->jenis_kelamin) }})</p>
                                </div>
                                <span class="badge {{ $kelas->is_open_class ? 'bg-success' : 'bg-info' }} rounded-pill">
                                    {{ $kelas->is_open_class ? 'Open' : 'Regular' }}
                                </span>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-center text-muted">Tidak ada kelas tanding yang terkait</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Validasi usia minimum < usia maksimum
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