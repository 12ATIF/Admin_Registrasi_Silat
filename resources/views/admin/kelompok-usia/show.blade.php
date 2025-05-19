@extends('layouts.admin')

@section('title', 'Detail Kelompok Usia')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.kelompok-usia.index') }}">Kelompok Usia</a></li>
<li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')
<div class="card shadow">
    <div class="card-body">
        <h4>{{ $kelompokUsia->nama }}</h4>
        <p>Rentang Usia: {{ $kelompokUsia->rentang_usia_min }} - {{ $kelompokUsia->rentang_usia_max }} tahun</p>
        
        <h5 class="mt-4">Subkategori Lomba</h5>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Kategori</th>
                        <th>Jenis</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($kelompokUsia->subkategoriLombas as $subkategori)
                    <tr>
                        <td>{{ $subkategori->nama }}</td>
                        <td>{{ $subkategori->kategoriLomba->nama }}</td>
                        <td>{{ ucfirst($subkategori->jenis) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <h5 class="mt-4">Kelas Tanding</h5>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Kode Kelas</th>
                        <th>Jenis Kelamin</th>
                        <th>Berat Min</th>
                        <th>Berat Max</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
@if($kelompokUsia->kelasTandings->count() > 0)
    @foreach($kelompokUsia->kelasTandings as $kelas)
    <tr>
        <td>{{ $kelas->kode_kelas }}</td>
        <td>{{ ucfirst($kelas->jenis_kelamin) }}</td>
        <td>{{ $kelas->berat_min }} kg</td>
        <td>{{ $kelas->is_open_class ? 'Open' : $kelas->berat_max.' kg' }}</td>
        <td>{{ $kelas->label_keterangan }}</td>
    </tr>
    @endforeach
@else
    <tr>
        <td colspan="5" class="text-center">Tidak ada kelas tanding yang terkait</td>
    </tr>
@endif
                </tbody>
            </table>
        </div>
        
        <div class="d-flex justify-content-between mt-4">
            <a href="{{ route('admin.kelompok-usia.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
            <a href="{{ route('admin.kelompok-usia.edit', $kelompokUsia) }}" class="btn btn-warning">
            <i class="fas fa-edit me-1"></i> Edit
            </a>
        </div>
    </div>
</div>
@endsection