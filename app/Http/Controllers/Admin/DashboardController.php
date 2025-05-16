<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Peserta;
use App\Models\Kontingen;
use App\Models\Pembayaran;
use App\Models\KategoriLomba;
use App\Models\KelompokUsia;
use App\Models\Pertandingan;
use App\Models\AdminLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistik Utama
        $statistics = [
            'total_peserta' => Peserta::count(),
            'peserta_valid' => Peserta::where('status_verifikasi', 'valid')->count(),
            'peserta_pending' => Peserta::where('status_verifikasi', 'pending')->count(),
            'total_kontingen' => Kontingen::count(),
        ];

        // Statistik Pembayaran
        $paymentStats = [
            'total_tagihan' => Pembayaran::sum('total_tagihan'),
            'sudah_lunas' => Pembayaran::where('status', 'lunas')->sum('total_tagihan'),
            'menunggu_verifikasi' => Pembayaran::where('status', 'menunggu_verifikasi')->count(),
            'belum_bayar' => Pembayaran::where('status', 'belum_bayar')->count(),
        ];

        // Peserta per Kategori
        $pesertaPerKategori = DB::table('peserta')
            ->join('subkategori_lomba', 'peserta.subkategori_id', '=', 'subkategori_lomba.id')
            ->join('kategori_lomba', 'subkategori_lomba.kategori_id', '=', 'kategori_lomba.id')
            ->select('kategori_lomba.nama', DB::raw('count(peserta.id) as total'))
            ->groupBy('kategori_lomba.id', 'kategori_lomba.nama')
            ->get();

        // Peserta per Kelompok Usia
        $pesertaPerUsia = DB::table('peserta')
            ->join('kelompok_usia', 'peserta.kelompok_usia_id', '=', 'kelompok_usia.id')
            ->select('kelompok_usia.nama', DB::raw('count(peserta.id) as total'))
            ->groupBy('kelompok_usia.id', 'kelompok_usia.nama')
            ->get();

        // Pertandingan Mendatang
        $upcomingEvents = Pertandingan::where('tanggal_event', '>=', now())
            ->orderBy('tanggal_event')
            ->limit(5)
            ->get();

        // Aktivitas Admin Terbaru
        $recentLogs = AdminLog::with('admin')
            ->orderBy('waktu_aksi', 'desc')
            ->limit(10)
            ->get();

        // Chart Data untuk Pendaftaran Harian (7 hari terakhir)
        $registrationChart = DB::table('peserta')
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        if (request()->expectsJson()) {
            return response()->json([
                'statistics' => $statistics,
                'payment_stats' => $paymentStats,
                'peserta_per_kategori' => $pesertaPerKategori,
                'peserta_per_usia' => $pesertaPerUsia,
                'upcoming_events' => $upcomingEvents,
                'recent_logs' => $recentLogs,
                'registration_chart' => $registrationChart,
            ]);
        }

        return view('admin.dashboard', compact(
            'statistics', 
            'paymentStats', 
            'pesertaPerKategori', 
            'pesertaPerUsia', 
            'upcomingEvents', 
            'recentLogs',
            'registrationChart'
        ));
    }
}