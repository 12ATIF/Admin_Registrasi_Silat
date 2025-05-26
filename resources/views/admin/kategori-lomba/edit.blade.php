@extends('layouts.admin')

@section('title', 'Edit Kategori Lomba')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.kategori-lomba.index') }}">Kategori Lomba</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="card shadow">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Edit Kategori: {{ $kategoriLomba->nama }}</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.kategori-lomba.update', $kategoriLomba->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="nama" class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" value="{{ old('nama', $kategoriLomba->nama) }}" required>
                @error('nama')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
                <div class="form-text">
                    Contoh: Seni, Tanding
                </div>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <a href="{{ route('admin.kategori-lomba.index') }}" class="btn btn-secondary">
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