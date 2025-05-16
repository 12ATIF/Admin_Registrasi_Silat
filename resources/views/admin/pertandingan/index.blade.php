@extends('layouts.admin')

@section('title', 'Pertandingan')

@section('breadcrumb')
<li class="breadcrumb-item active">Pertandingan</li>
@endsection

@section('action-buttons')
<a href="{{ route('admin.pertandingan.create') }}" class="btn btn-primary">
    <i class="fas fa-plus"></i> Tambah Pertandingan
</a>
@endsection

@section('content')
<div class="card shadow">
    <div class="card-body">
        <div class="table-responsive">
            <table id="pertandingan-table" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Nama Event</th>
                        <th>Tanggal</th>
                        <th>Lokasi</th>
                        <th>Jumlah Jadwal</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pertandingans as $pertandingan)
                    <tr>
                        <td>{{ $pertandingan->nama_event }}</td>
                        <td>{{ $pertandingan->tanggal_event->format('d/m/Y') }}</td>
                        <td>{{ $pertandingan->lokasi_umum }}</td>
                        <td>{{ $pertandingan->jadwal_pertandingans_count ?? 0 }}</td>
                        <td>
                            @if($pertandingan->tanggal_event->isPast())
                                <span class="badge bg-secondary">Selesai</span>
                            @elseif($pertandingan->tanggal_event->isToday())
                                <span class="badge bg-success">Berlangsung</span>
                            @else
                                <span class="badge bg-info">Mendatang</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.pertandingan.show', $pertandingan->id) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.pertandingan.edit', $pertandingan->id) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="{{ route('admin.jadwal-pertandingan.create') }}?pertandingan_id={{ $pertandingan->id }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-calendar-plus"></i>
                                </a>
                                <form action="{{ route('admin.pertandingan.destroy', $pertandingan->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pertandingan ini? Semua jadwal yang terkait juga akan dihapus.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="mt-3">
            {{ $pertandingans->links() }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#pertandingan-table').DataTable({
            "paging": false,
            "info": false,
            "searching": true,
            "responsive": true,
            "order": [[1, 'asc']], // Sort by date ascending
            "columnDefs": [
                { "orderable": false, "targets": 5 }
            ]
        });
    });
</script>
@endpush