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
        $query = Peserta::with(['kontingen.pelatih', 'subkategoriLomba.kategoriLomba', 'kelompokUsia', 'kelasTanding']);
        
        // Apply filters
        if ($request->has('kontingen_id') && $request->kontingen_id) {
            $query->where('kontingen_id', $request->kontingen_id);
        }
        
        if ($request->has('kategori_id') && $request->kategori_id) {
            $query->whereHas('subkategoriLomba', function ($q) use ($request) {
                $q->where('kategori_id', $request->kategori_id);
            });
        }
        
        if ($request->has('kelompok_usia_id') && $request->kelompok_usia_id) {
            $query->where('kelompok_usia_id', $request->kelompok_usia_id);
        }
        
        // Get statistics
        $statistics = [
            'total_peserta' => (clone $query)->count(),
            'peserta_valid' => (clone $query)->where('status_verifikasi', 'valid')->count(),
            'peserta_tidak_valid' => (clone $query)->where('status_verifikasi', 'tidak_valid')->count(),
            'peserta_pending' => (clone $query)->where('status_verifikasi', 'pending')->count(),
        ];
        
        // Get filter options
        $kontingens = Kontingen::with('pelatih')->get();
        $kategoris = KategoriLomba::all();
        $kelompokUsias = KelompokUsia::all();
        
        if ($request->ajax() && !$request->has('exportcsv')) {
            return DataTables::of($query)
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
        $query = Pembayaran::with(['kontingen.pelatih', 'kontingen.pesertas']);
        
        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter by kontingen
        if ($request->has('kontingen_id')) {
            $query->where('kontingen_id', $request->kontingen_id);
        }
        
        // Get filter options
        $kontingens = Kontingen::with('pelatih')->get();
        
        // Get statistics
        $statistics = [
            'total_pembayaran' => clone $query->count(),
            'total_tagihan' => clone $query->sum('total_tagihan'),
            'total_lunas' => clone $query->where('status', 'lunas')->sum('total_tagihan'),
            'belum_bayar' => clone $query->where('status', 'belum_bayar')->count(),
            'menunggu_verifikasi' => clone $query->where('status', 'menunggu_verifikasi')->count(),
            'lunas' => clone $query->where('status', 'lunas')->count(),
        ];
        
        $pembayarans = $query->paginate(20);
        
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
        
        $query = Peserta::with(['kontingen.pelatih', 'subkategoriLomba.kategoriLomba', 'kelompokUsia', 'kelasTanding']);
        
        // Apply filters same as peserta method
        if ($request->has('kontingen_id')) {
            $query->where('kontingen_id', $request->kontingen_id);
        }
        
        if ($request->has('kategori_id')) {
            $query->whereHas('subkategoriLomba', function ($q) use ($request) {
                $q->where('kategori_id', $request->kategori_id);
            });
        }
        
        if ($request->has('kelompok_usia_id')) {
            $query->where('kelompok_usia_id', $request->kelompok_usia_id);
        }
        
        if ($request->has('kelas_tanding_id')) {
            $query->where('kelas_tanding_id', $request->kelas_tanding_id);
        }
        
        $pesertas = $query->get();
        
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
        
        $query = Pembayaran::with(['kontingen.pelatih', 'kontingen.pesertas']);
        
        // Apply filters
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->has('kontingen_id')) {
            $query->where('kontingen_id', $request->kontingen_id);
        }
        
        $pembayarans = $query->get();
        
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