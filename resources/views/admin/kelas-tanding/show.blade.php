@extends('layouts.admin')

@section('title', 'Detail Kelas Tanding')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.kelas-tanding.index') }}">Kelas Tanding</a></li>
    <li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')
<div class="card shadow">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">
            Detail Kelas: {{ $kelasTanding->label_keterangan ?: $kelasTanding->kode_kelas }}
        </h6>
        <a href="{{ route('admin.kelas-tanding.edit', $kelasTanding->id) }}" class="btn btn-warning btn-sm">
            <i class="fas fa-edit fa-sm"></i> Edit Kelas Tanding
        </a>
    </div>
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-md-6">
                <dl class="row">
                    <dt class="col-sm-5">ID Kelas:</dt>
                    <dd class="col-sm-7">{{ $kelasTanding->id }}</dd>

                    <dt class="col-sm-5">Kelompok Usia:</dt>
                    <dd class="col-sm-7">
                        <a href="{{ route('admin.kelompok-usia.show', $kelasTanding->kelompokUsia->id) }}">
                            {{ $kelasTanding->kelompokUsia->nama }}
                        </a>
                        ({{ $kelasTanding->kelompokUsia->rentang_usia_min }} - {{ $kelasTanding->kelompokUsia->rentang_usia_max }} tahun)
                    </dd>

                    <dt class="col-sm-5">Jenis Kelamin:</dt>
                    <dd class="col-sm-7">{{ ucfirst($kelasTanding->jenis_kelamin) }}</dd>

                    <dt class="col-sm-5">Kode Kelas:</dt>
                    <dd class="col-sm-7">{{ $kelasTanding->kode_kelas }}</dd>
                </dl>
            </div>
            <div class="col-md-6">
                <dl class="row">
                    <dt class="col-sm-5">Berat Minimum:</dt>
                    <dd class="col-sm-7">{{ $kelasTanding->berat_min }} kg</dd>

                    <dt class="col-sm-5">Berat Maksimum:</dt>
                    <dd class="col-sm-7">{{ $kelasTanding->is_open_class ? 'Open' : $kelasTanding->berat_max . ' kg' }}</dd>

                    <dt class="col-sm-5">Label Keterangan:</dt>
                    <dd class="col-sm-7">{{ $kelasTanding->label_keterangan ?: '-' }}</dd>

                    <dt class="col-sm-5">Kelas Terbuka:</dt>
                    <dd class="col-sm-7">
                        @if($kelasTanding->is_open_class)
                            <span class="badge bg-success">Ya</span>
                        @else
                            <span class="badge bg-secondary">Tidak</span>
                        @endif
                    </dd>
                </dl>
            </div>
        </div>

        <hr>

        <h5 class="mt-4 mb-3">Peserta Terdaftar di Kelas Ini:</h5>
        @if($kelasTanding->pesertas && $kelasTanding->pesertas->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="pesertaTable">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nama Peserta</th>
                            <th>Kontingen</th>
                            <th>Berat Badan</th>
                            <th>Status Verifikasi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($kelasTanding->pesertas as $index => $peserta)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $peserta->nama }}</td>
                            <td>
                                @if($peserta->kontingen)
                                    <a href="{{ route('admin.kontingen.show', $peserta->kontingen_id) }}">
                                        {{ $peserta->kontingen->nama }}
                                    </a>
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ $peserta->berat_badan }} kg</td>
                            <td>
                                @php
                                    $badgeClass = 'secondary';
                                    if ($peserta->status_verifikasi == 'valid') $badgeClass = 'success';
                                    elseif ($peserta->status_verifikasi == 'pending') $badgeClass = 'warning';
                                    elseif ($peserta->status_verifikasi == 'tidak_valid') $badgeClass = 'danger';
                                @endphp
                                <span class="badge bg-{{ $badgeClass }}">{{ ucfirst($peserta->status_verifikasi) }}</span>
                            </td>
                            <td>
                                <a href="{{ route('admin.peserta.index') }}?search={{ urlencode($peserta->nama) }}" class="btn btn-sm btn-info" title="Lihat Detail Peserta">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="alert alert-secondary text-center" role="alert">
                <i class="fas fa-info-circle me-2"></i>
                Belum ada peserta yang terdaftar untuk kelas tanding ini.
            </div>
        @endif

        <div class="mt-4 d-flex justify-content-between">
            <a href="{{ route('admin.kelas-tanding.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar Kelas Tanding
            </a>
            <form action="{{ route('admin.kelas-tanding.destroy', $kelasTanding->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kelas tanding ini?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-trash me-1"></i> Hapus Kelas Tanding
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#pesertaTable').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Indonesian.json"
            },
            "columnDefs": [
                { "orderable": false, "targets": 5 } // Disable ordering for action column
            ]
        });
    });
</script>
@endpush