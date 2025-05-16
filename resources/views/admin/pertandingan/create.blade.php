@extends('layouts.admin')

@section('title', 'Tambah Pertandingan')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.pertandingan.index') }}">Pertandingan</a></li>
<li class="breadcrumb-item active">Tambah</li>
@endsection

@section('content')
<div class="card shadow">
    <div class="card-body">
        <form action="{{ route('admin.pertandingan.store') }}" method="POST">
            @csrf
            
            <div class="mb-3">
                <label for="nama_event" class="form-label">Nama Event <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('nama_event') is-invalid @enderror" id="nama_event" name="nama_event" value="{{ old('nama_event') }}" required>
                @error('nama_event')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
                <div class="form-text">
                    Contoh: Kejuaraan Nasional Pencak Silat 2025
                </div>
            </div>
            
            <div class="mb-3">
                <label for="tanggal_event" class="form-label">Tanggal Event <span class="text-danger">*</span></label>
                <input type="date" class="form-control @error('tanggal_event') is-invalid @enderror" id="tanggal_event" name="tanggal_event" value="{{ old('tanggal_event') }}" required>
                @error('tanggal_event')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="lokasi_umum" class="form-label">Lokasi <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('lokasi_umum') is-invalid @enderror" id="lokasi_umum" name="lokasi_umum" value="{{ old('lokasi_umum') }}" required>
                @error('lokasi_umum')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
                <div class="form-text">
                    Contoh: GOR Soemantri Brodjonegoro, Jakarta
                </div>
            </div>
            
            <div class="d-flex justify-content-between">
                <a href="{{ route('admin.pertandingan.index') }}" class="btn btn-secondary">
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