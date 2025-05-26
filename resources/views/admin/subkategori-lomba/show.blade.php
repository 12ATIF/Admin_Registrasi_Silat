@extends('layouts.admin')

@section('title', 'Detail Subkategori Lomba')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.kategori-lomba.index') }}">Kategori Lomba</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.subkategori-lomba.index') }}">Subkategori Lomba</a></li>
    <li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')
<div class="card shadow">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">Detail Subkategori: {{ $subkategoriLomba->nama }}</h6>
        <a href="{{ route('admin.subkategori-lomba.edit', $subkategoriLomba->id) }}" class="btn btn-warning btn-sm">
            <i class="fas fa-edit fa-sm"></i> Edit Subkategori
        </a>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <dl class="row">
                    <dt class="col-sm-5">ID Subkategori:</dt>
                    <dd class="col-sm-7">{{ $subkategoriLomba->id }}</dd>

                    <dt class="col-sm-5">Nama Subkategori:</dt>
                    <dd class="col-sm-7">{{ $subkategoriLomba->nama }}</dd>

                    <dt class="col-sm-5">Kategori Lomba:</dt>
                    <dd class="col-sm-7">
                        <a href="{{ route('admin.kategori-lomba.show', $subkategoriLomba->kategoriLomba->id) }}">
                            {{ $subkategoriLomba->kategoriLomba->nama }}
                        </a>
                    </dd>

                    <dt class="col-sm-5">Jenis:</dt>
                    <dd class="col-sm-7">{{ ucfirst($subkategoriLomba->jenis) }}</dd>
                </dl>
            </div>
            <div class="col-md-6">
                <dl class="row">
                    <dt class="col-sm-5">Jumlah Peserta:</dt>
                    <dd class="col-sm-7">{{ $subkategoriLomba->jumlah_peserta }} orang</dd>

                    <dt class="col-sm-5">Harga Pendaftaran:</dt>
                    <dd class="col-sm-7">Rp {{ number_format($subkategoriLomba->harga_pendaftaran, 0, ',', '.') }}</dd>

                    <dt class="col-sm-5">Tanggal Dibuat:</dt>
                    <dd class="col-sm-7">{{ $subkategoriLomba->created_at->format('d F Y H:i') }}</dd>

                    <dt class="col-sm-5">Terakhir Diperbarui:</dt>
                    <dd class="col-sm-7">{{ $subkategoriLomba->updated_at->format('d F Y H:i') }}</dd>
                </dl>
            </div>
        </div>

        <hr>

        <h5 class="mt-4 mb-3">Kelompok Usia yang Terkait:</h5>
        @if($subkategoriLomba->kelompokUsias && $subkategoriLomba->kelompokUsias->count() > 0)
            <ul class="list-group">
                @foreach($subkategoriLomba->kelompokUsias as $kelompokUsia)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="{{ route('admin.kelompok-usia.show', $kelompokUsia->id) }}">
                            {{ $kelompokUsia->nama }}
                        </a>
                        <span class="text-muted">({{ $kelompokUsia->rentang_usia_min }} - {{ $kelompokUsia->rentang_usia_max }} tahun)</span>
                    </li>
                @endforeach
            </ul>
        @else
            <div class="alert alert-secondary text-center" role="alert">
                <i class="fas fa-info-circle me-2"></i>
                Tidak ada kelompok usia yang terkait dengan subkategori ini.
            </div>
        @endif

        <div class="mt-4 d-flex justify-content-between">
            <a href="{{ route('admin.subkategori-lomba.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar Subkategori
            </a>
            <form action="{{ route('admin.subkategori-lomba.destroy', $subkategoriLomba->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus subkategori ini?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-trash me-1"></i> Hapus Subkategori
                </button>
            </form>
        </div>
    </div>
</div>
@endsection