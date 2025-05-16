@extends('layouts.admin')

@section('title', 'Tambah Subkategori Lomba')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.subkategori-lomba.index') }}">Subkategori Lomba</a></li>
<li class="breadcrumb-item active">Tambah</li>
@endsection

@section('content')
<div class="card shadow">
    <div class="card-body">
        <form action="{{ route('admin.subkategori-lomba.store') }}" method="POST">
            @csrf
            
            <div class="mb-3">
                <label for="kategori_id" class="form-label">Kategori Lomba <span class="text-danger">*</span></label>
                <select class="form-select @error('kategori_id') is-invalid @enderror" id="kategori_id" name="kategori_id" required>
                    <option value="">Pilih Kategori</option>
                    @foreach($kategoris as $kategori)
                        <option value="{{ $kategori->id }}" {{ old('kategori_id', request('kategori_id')) == $kategori->id ? 'selected' : '' }}>
                            {{ $kategori->nama }}
                        </option>
                    @endforeach
                </select>
                @error('kategori_id')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="nama" class="form-label">Nama Subkategori <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" value="{{ old('nama') }}" required>
                @error('nama')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
                <div class="form-text">
                    Contoh: Tunggal Putra, Ganda Putri
                </div>
            </div>
            
            <div class="mb-3">
                <label for="jenis" class="form-label">Jenis <span class="text-danger">*</span></label>
                <select class="form-select @error('jenis') is-invalid @enderror" id="jenis" name="jenis" required>
                    <option value="">Pilih Jenis</option>
                    <option value="tunggal" {{ old('jenis') == 'tunggal' ? 'selected' : '' }}>Tunggal</option>
                    <option value="ganda" {{ old('jenis') == 'ganda' ? 'selected' : '' }}>Ganda</option>
                    <option value="regu" {{ old('jenis') == 'regu' ? 'selected' : '' }}>Regu</option>
                    <option value="tanding" {{ old('jenis') == 'tanding' ? 'selected' : '' }}>Tanding</option>
                </select>
                @error('jenis')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="jumlah_peserta" class="form-label">Jumlah Peserta <span class="text-danger">*</span></label>
                <input type="number" class="form-control @error('jumlah_peserta') is-invalid @enderror" id="jumlah_peserta" name="jumlah_peserta" value="{{ old('jumlah_peserta', 1) }}" min="1" required>
                @error('jumlah_peserta')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
                <div class="form-text">
                    Contoh: 1 untuk tunggal, 2 untuk ganda, 3 untuk regu
                </div>
            </div>
            
            <div class="mb-3">
                <label for="harga_pendaftaran" class="form-label">Harga Pendaftaran (Rp) <span class="text-danger">*</span></label>
                <input type="number" class="form-control @error('harga_pendaftaran') is-invalid @enderror" id="harga_pendaftaran" name="harga_pendaftaran" value="{{ old('harga_pendaftaran', 0) }}" min="0" required>
                @error('harga_pendaftaran')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label class="form-label">Kelompok Usia <span class="text-danger">*</span></label>
                <div class="card">
                    <div class="card-body">
                        @error('kelompok_usia_ids')
                            <div class="alert alert-danger">
                                {{ $message }}
                            </div>
                        @enderror
                        
                        <div class="row">
                            @foreach($kelompokUsias as $usia)
                                <div class="col-md-4 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="kelompok_usia_ids[]" id="usia_{{ $usia->id }}" value="{{ $usia->id }}" {{ in_array($usia->id, old('kelompok_usia_ids', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="usia_{{ $usia->id }}">
                                            {{ $usia->nama }} ({{ $usia->rentang_usia_min }}-{{ $usia->rentang_usia_max }} tahun)
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="d-flex justify-content-between">
                <a href="{{ route('admin.subkategori-lomba.index') }}" class="btn btn-secondary">
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
        // Auto-set jumlah peserta based on jenis
        $('#jenis').on('change', function() {
            var jenis = $(this).val();
            var jumlahPeserta = 1; // Default: 1 for tunggal and tanding
            
            if (jenis === 'ganda') {
                jumlahPeserta = 2;
            } else if (jenis === 'regu') {
                jumlahPeserta = 3;
            }
            
            $('#jumlah_peserta').val(jumlahPeserta);
        });
    });
</script>
@endpush