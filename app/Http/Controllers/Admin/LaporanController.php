<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Peserta;
use App\Models\Pembayaran;
use App\Models\Kontingen;
use App\Models\KategoriLomba;
use App\Models\KelompokUsia;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function peserta(Request $request)
    {
        // Create base query
        $baseQuery = Peserta::query();
        
        // Apply filters to base query
        if ($request->has('kontingen_id') && $request->kontingen_id) {
            $baseQuery->where('kontingen_id', $request->kontingen_id);
        }
        
        if ($request->has('kategori_id') && $request->kategori_id) {
            $baseQuery->whereHas('subkategoriLomba', function ($q) use ($request) {
                $q->where('kategori_id', $request->kategori_id);
            });
        }
        
        if ($request->has('kelompok_usia_id') && $request->kelompok_usia_id) {
            $baseQuery->where('kelompok_usia_id', $request->kelompok_usia_id);
        }
        
        // Get statistics with separate queries (avoids clone issues)
        $statistics = [
            'total_peserta' => (clone $baseQuery)->count(),
            'peserta_valid' => (clone $baseQuery)->where('status_verifikasi', 'valid')->count(),
            'peserta_tidak_valid' => (clone $baseQuery)->where('status_verifikasi', 'tidak_valid')->count(),
            'peserta_pending' => (clone $baseQuery)->where('status_verifikasi', 'pending')->count(),
        ];
        
        // Get filter options
        $kontingens = Kontingen::with('pelatih')->get();
        $kategoris = KategoriLomba::all();
        $kelompokUsias = KelompokUsia::all();
        
        // Create query for DataTables with relationships
        $dataQuery = clone $baseQuery;
        $dataQuery->with(['kontingen.pelatih', 'subkategoriLomba.kategoriLomba', 'kelompokUsia', 'kelasTanding']);
        
        if ($request->ajax() && !$request->has('exportcsv')) {
            return DataTables::of($dataQuery)
                ->addIndexColumn()
                ->editColumn('jenis_kelamin', function($row) {
                    return $row->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan';
                })
                ->editColumn('tanggal_lahir', function($row) {
                    return $row->tanggal_lahir->format('d/m/Y');
                })
                ->editColumn('berat_badan', function($row) {
                    return $row->berat_badan . ' kg';
                })
                ->addColumn('kontingen', function($row) {
                    return $row->kontingen->nama;
                })
                ->addColumn('kategori', function($row) {
                    return $row->subkategoriLomba->kategoriLomba->nama . ' - ' . $row->subkategoriLomba->nama;
                })
                ->addColumn('kelas_tanding', function($row) {
                    return $row->kelasTanding ? $row->kelasTanding->label_keterangan : 'Belum ditentukan';
                })
                ->editColumn('status_verifikasi', function($row) {
                    $badges = [
                        'valid' => 'success',
                        'pending' => 'warning',
                        'tidak_valid' => 'danger'
                    ];
                    $badge = $badges[$row->status_verifikasi] ?? 'secondary';
                    return '<span class="badge bg-'.$badge.'">'.ucfirst($row->status_verifikasi).'</span>';
                })
                ->rawColumns(['status_verifikasi'])
                ->make(true);
        }
        
        // For export CSV
        if ($request->has('exportcsv')) {
            return $this->exportPeserta($request);
        }
        
        return view('admin.laporan.peserta', compact('statistics', 'kontingens', 'kategoris', 'kelompokUsias'));
    }
    
    public function pembayaran(Request $request)
    {
        // Create base query
        $baseQuery = Pembayaran::query();
        
        // Apply filters
        if ($request->has('status') && $request->status) {
            $baseQuery->where('status', $request->status);
        }
        
        if ($request->has('kontingen_id') && $request->kontingen_id) {
            $baseQuery->where('kontingen_id', $request->kontingen_id);
        }
        
        // Get statistics with separate queries
        $statistics = [
            'total_pembayaran' => (clone $baseQuery)->count(),
            'total_tagihan' => (clone $baseQuery)->sum('total_tagihan'),
            'total_lunas' => (clone $baseQuery)->where('status', 'lunas')->sum('total_tagihan'),
            'belum_bayar' => (clone $baseQuery)->where('status', 'belum_bayar')->count(),
            'menunggu_verifikasi' => (clone $baseQuery)->where('status', 'menunggu_verifikasi')->count(),
            'lunas' => (clone $baseQuery)->where('status', 'lunas')->count(),
        ];
        
        // Get filter options
        $kontingens = Kontingen::with('pelatih')->get();
        
        // Create query for paginated data with relationships
        $dataQuery = clone $baseQuery;
        $dataQuery->with(['kontingen.pelatih', 'kontingen.pesertas']);
        $pembayarans = $dataQuery->paginate(20);
        
        if ($request->expectsJson()) {
            return response()->json([
                'pembayarans' => $pembayarans,
                'statistics' => $statistics
            ]);
        }
        
        return view('admin.laporan.pembayaran', compact('pembayarans', 'statistics', 'kontingens'));
    }
    
    public function exportPeserta(Request $request)
    {
        $filename = 'laporan_peserta_' . date('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        // Create base query
        $baseQuery = Peserta::query();
        
        // Apply filters
        if ($request->has('kontingen_id') && $request->kontingen_id) {
            $baseQuery->where('kontingen_id', $request->kontingen_id);
        }
        
        if ($request->has('kategori_id') && $request->kategori_id) {
            $baseQuery->whereHas('subkategoriLomba', function ($q) use ($request) {
                $q->where('kategori_id', $request->kategori_id);
            });
        }
        
        if ($request->has('kelompok_usia_id') && $request->kelompok_usia_id) {
            $baseQuery->where('kelompok_usia_id', $request->kelompok_usia_id);
        }
        
        if ($request->has('kelas_tanding_id') && $request->kelas_tanding_id) {
            $baseQuery->where('kelas_tanding_id', $request->kelas_tanding_id);
        }
        
        // Add relationships needed for export
        $baseQuery->with(['kontingen.pelatih', 'subkategoriLomba.kategoriLomba', 'kelompokUsia', 'kelasTanding']);
        $pesertas = $baseQuery->get();
        
        $callback = function() use ($pesertas) {
            $file = fopen('php://output', 'w');
            
            // Header columns
            fputcsv($file, [
                'No',
                'Nama Peserta',
                'Jenis Kelamin',
                'Tanggal Lahir',
                'Berat Badan',
                'Kontingen',
                'Pelatih',
                'Kategori',
                'Subkategori',
                'Kelompok Usia',
                'Kelas Tanding',
                'Status Verifikasi'
            ]);
            
            // Data rows
            foreach ($pesertas as $index => $peserta) {
                fputcsv($file, [
                    $index + 1,
                    $peserta->nama,
                    $peserta->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan',
                    $peserta->tanggal_lahir->format('d/m/Y'),
                    $peserta->berat_badan . ' kg',
                    $peserta->kontingen->nama,
                    $peserta->kontingen->pelatih->nama,
                    $peserta->subkategoriLomba->kategoriLomba->nama,
                    $peserta->subkategoriLomba->nama,
                    $peserta->kelompokUsia->nama,
                    $peserta->kelasTanding->label_keterangan ?? '-',
                    ucfirst($peserta->status_verifikasi)
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
    
    public function exportPembayaran(Request $request)
    {
        $filename = 'laporan_pembayaran_' . date('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        // Create base query
        $baseQuery = Pembayaran::query();
        
        // Apply filters
        if ($request->has('status') && $request->status) {
            $baseQuery->where('status', $request->status);
        }
        
        if ($request->has('kontingen_id') && $request->kontingen_id) {
            $baseQuery->where('kontingen_id', $request->kontingen_id);
        }
        
        // Add relationships needed for export
        $baseQuery->with(['kontingen.pelatih', 'kontingen.pesertas']);
        $pembayarans = $baseQuery->get();
        
        $callback = function() use ($pembayarans) {
            $file = fopen('php://output', 'w');
            
            // Header columns
            fputcsv($file, [
                'No',
                'Kontingen',
                'Pelatih',
                'Total Tagihan',
                'Status',
                'Tanggal Verifikasi',
                'Jumlah Peserta'
            ]);
            
            // Data rows
            foreach ($pembayarans as $index => $pembayaran) {
                fputcsv($file, [
                    $index + 1,
                    $pembayaran->kontingen->nama,
                    $pembayaran->kontingen->pelatih->nama,
                    'Rp ' . number_format($pembayaran->total_tagihan, 0, ',', '.'),
                    ucfirst(str_replace('_', ' ', $pembayaran->status)),
                    $pembayaran->verified_at ? $pembayaran->verified_at->format('d/m/Y H:i') : '-',
                    $pembayaran->kontingen->pesertas->count()
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}