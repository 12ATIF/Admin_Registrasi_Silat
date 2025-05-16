@extends('layouts.admin')

@section('title', 'Detail Kontingen')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.kontingen.index') }}">Kontingen</a></li>
<li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold">Informasi Kontingen</h6>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    <div class="d-inline-block bg-primary text-white rounded-circle p-3 mb-2" style="width: 100px; height: 100px;">
                        <i class="fas fa-users fa-3x mt-2"></i>
                    </div>
                    <h5>{{ $kontingen->nama }}</h5>
                    <p class="text-muted">{{ $kontingen->asal_daerah }}</p>
                    <span class="badge {{ $kontingen->is_active ? 'bg-success' : 'bg-danger' }} mb-2">
                        {{ $kontingen->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </div>
                
                <div class="mb-3">
                    <h6 class="font-weight-bold">Pelatih:</h6>
                    <p>{{ $kontingen->pelatih->nama }} ({{ $kontingen->pelatih->perguruan }})</p>
                </div>
                
                <div class="mb-3">
                    <h6 class="font-weight-bold">Email Pelatih:</h6>
                    <p>{{ $kontingen->pelatih->email }}</p>
                </div>
                
                <div class="mb-3">
                    <h6 class="font-weight-bold">Kontak Pendamping:</h6>
                    <p>{{ $kontingen->kontak_pendamping ?: '-' }}</p>
                </div>
                
                <div class="mb-3">
                    <h6 class="font-weight-bold">Tanggal Registrasi:</h6>
                    <p>{{ $kontingen->created_at->format('d/m/Y H:i') }}</p>
                </div>
                
                <div class="mb-3">
                    <h6 class="font-weight-bold">Terakhir Diperbarui:</h6>
                    <p>{{ $kontingen->updated_at->format('d/m/Y H:i') }}</p>
                </div>
                
                <div class="d-grid gap-2">
                    <button class="btn btn-warning toggle-status" data-id="{{ $kontingen->id }}" data-status="{{ $kontingen->is_active }}">
                        <i class="fas {{ $kontingen->is_active ? 'fa-ban' : 'fa-check' }}"></i> 
                        {{ $kontingen->is_active ? 'Nonaktifkan Kontingen' : 'Aktifkan Kontingen' }}
                    </button>
                </div>
            </div>
        </div>
        
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold">Status Pembayaran</h6>
            </div>
            <div class="card-body">
                @if($kontingen->pembayarans->count() > 0)
                    @php
                        $pembayaran = $kontingen->pembayarans->first();
                    @endphp
                    <div class="text-center mb-3">
                        <div class="mb-2">
                            <span class="badge {{ $pembayaran->status == 'lunas' ? 'bg-success' : ($pembayaran->status == 'menunggu_verifikasi' ? 'bg-warning' : 'bg-danger') }} fs-6 p-2">
                                {{ ucfirst(str_replace('_', ' ', $pembayaran->status)) }}
                            </span>
                        </div>
                        <h4>Rp {{ number_format($pembayaran->total_tagihan, 0, ',', '.') }}</h4>
                    </div>
                    
                    @if($pembayaran->bukti_transfer)
                        <div class="mb-3">
                            <h6 class="font-weight-bold">Bukti Transfer:</h6>
                            <p><a href="{{ Storage::url($pembayaran->bukti_transfer) }}" target="_blank" class="btn btn-sm btn-primary">Lihat Bukti Transfer</a></p>
                        </div>
                    @endif
                    
                    @if($pembayaran->verified_at)
                        <div class="mb-3">
                            <h6 class="font-weight-bold">Diverifikasi pada:</h6>
                            <p>{{ $pembayaran->verified_at->format('d/m/Y H:i') }}</p>
                        </div>
                    @endif
                    
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.pembayaran.index', ['kontingen_id' => $kontingen->id]) }}" class="btn btn-info">
                            <i class="fas fa-info-circle"></i> Detail Pembayaran
                        </a>
                    </div>
                @else
                    <div class="text-center py-3">
                        <p class="text-muted mb-0">Belum ada data pembayaran</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold">Daftar Peserta</h6>
            </div>
            <div class="card-body">
                @if($kontingen->pesertas->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="peserta-table">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Jenis Kelamin</th>
                                    <th>Tanggal Lahir</th>
                                    <th>Berat Badan</th>
                                    <th>Kategori</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($kontingen->pesertas as $peserta)
                                <tr>
                                    <td>{{ $peserta->nama }}</td>
                                    <td>{{ $peserta->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                                    <td>{{ $peserta->tanggal_lahir->format('d/m/Y') }}</td>
                                    <td>{{ $peserta->berat_badan }} kg</td>
                                    <td>
                                        {{ $peserta->subkategoriLomba->kategoriLomba->nama }} - 
                                        {{ $peserta->subkategoriLomba->nama }}
                                    </td>
                                    <td>
                                        <span class="badge {{ $peserta->status_verifikasi == 'valid' ? 'bg-success' : ($peserta->status_verifikasi == 'pending' ? 'bg-warning' : 'bg-danger') }}">
                                            {{ ucfirst($peserta->status_verifikasi) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.peserta.index', ['id' => $peserta->id]) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i> Detail
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-3">
                        <p class="text-muted mb-0">Kontingen belum memiliki peserta</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#peserta-table').DataTable({
            responsive: true
        });
        
        // Toggle Status
        $('.toggle-status').on('click', function() {
            var kontingenId = $(this).data('id');
            var currentStatus = $(this).data('status');
            var statusUrl = `{{ route('admin.kontingen.toggle-status', ':id') }}`.replace(':id', kontingenId);
            var statusText = currentStatus == 1 ? 'nonaktifkan' : 'aktifkan';
            
            if (confirm(`Apakah Anda yakin ingin ${statusText} kontingen ini?`)) {
                $.ajax({
                    url: statusUrl,
                    type: 'PUT',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        alert(response.message);
                        location.reload();
                    },
                    error: function(xhr) {
                        alert('Terjadi kesalahan: ' + xhr.responseJSON.message);
                    }
                });
            }
        });
    });
</script>
@endpush