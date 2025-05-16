<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JadwalPertandingan;
use App\Models\Pertandingan;
use App\Models\SubkategoriLomba;
use App\Models\KelompokUsia;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;

class JadwalPertandinganController extends Controller
{
    use LogsActivity;
    
    public function index(Request $request)
    {
        $query = JadwalPertandingan::with(['pertandingan', 'subkategoriLomba', 'kelompokUsia']);
        
        if ($request->has('pertandingan_id')) {
            $query->where('pertandingan_id', $request->pertandingan_id);
        }
        
        if ($request->has('subkategori_id')) {
            $query->where('subkategori_id', $request->subkategori_id);
        }
        
        if ($request->has('kelompok_usia_id')) {
            $query->where('kelompok_usia_id', $request->kelompok_usia_id);
        }
        
        $jadwals = $query->orderBy('tanggal')->orderBy('waktu_mulai')->paginate(15);
        
        if ($request->expectsJson()) {
            return response()->json($jadwals);
        }
        
        return view('admin.jadwal-pertandingan.index', compact('jadwals'));
    }
    
    public function create()
    {
        $pertandingans = Pertandingan::all();
        $subkategoris = SubkategoriLomba::all();
        $kelompokUsias = KelompokUsia::all();
        
        return view('admin.jadwal-pertandingan.create', compact('pertandingans', 'subkategoris', 'kelompokUsias'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'pertandingan_id' => 'required|exists:pertandingan,id',
            'subkategori_id' => 'required|exists:subkategori_lomba,id',
            'kelompok_usia_id' => 'required|exists:kelompok_usia,id',
            'tanggal' => 'required|date',
            'waktu_mulai' => 'required|date_format:H:i',
            'waktu_selesai' => 'nullable|date_format:H:i|after:waktu_mulai',
            'lokasi_detail' => 'nullable|string|max:255',
        ]);
        
        $jadwal = JadwalPertandingan::create($request->all());
        
        $this->logActivity('created', $jadwal);
        
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Jadwal pertandingan berhasil dibuat.', 'jadwal' => $jadwal], 201);
        }
        
        return redirect()->route('admin.jadwal-pertandingan.index')->with('success', 'Jadwal pertandingan berhasil dibuat.');
    }
    
    public function show(JadwalPertandingan $jadwalPertandingan)
    {
        $jadwalPertandingan->load(['pertandingan', 'subkategoriLomba', 'kelompokUsia']);
        
        // Get peserta yang masuk dalam jadwal ini
        $pesertaQuery = \App\Models\Peserta::where('subkategori_id', $jadwalPertandingan->subkategori_id)
            ->where('kelompok_usia_id', $jadwalPertandingan->kelompok_usia_id)
            ->where('status_verifikasi', 'valid')
            ->with('kontingen');
            
        $pesertas = $pesertaQuery->get();
        
        if (request()->expectsJson()) {
            return response()->json([
                'jadwal' => $jadwalPertandingan,
                'pesertas' => $pesertas
            ]);
        }
        
        return view('admin.jadwal-pertandingan.show', compact('jadwalPertandingan', 'pesertas'));
    }
    
    public function edit(JadwalPertandingan $jadwalPertandingan)
    {
        $pertandingans = Pertandingan::all();
        $subkategoris = SubkategoriLomba::all();
        $kelompokUsias = KelompokUsia::all();
        
        return view('admin.jadwal-pertandingan.edit', compact('jadwalPertandingan', 'pertandingans', 'subkategoris', 'kelompokUsias'));
    }
    
    public function update(Request $request, JadwalPertandingan $jadwalPertandingan)
    {
        $request->validate([
            'pertandingan_id' => 'required|exists:pertandingan,id',
            'subkategori_id' => 'required|exists:subkategori_lomba,id',
            'kelompok_usia_id' => 'required|exists:kelompok_usia,id',
            'tanggal' => 'required|date',
            'waktu_mulai' => 'required|date_format:H:i',
            'waktu_selesai' => 'nullable|date_format:H:i|after:waktu_mulai',
            'lokasi_detail' => 'nullable|string|max:255',
        ]);
        
        $jadwalPertandingan->update($request->all());
        
        $this->logActivity('updated', $jadwalPertandingan);
        
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Jadwal pertandingan berhasil diperbarui.', 'jadwal' => $jadwalPertandingan]);
        }
        
        return redirect()->route('admin.jadwal-pertandingan.index')->with('success', 'Jadwal pertandingan berhasil diperbarui.');
    }
    
    public function destroy(JadwalPertandingan $jadwalPertandingan)
    {
        $jadwalPertandingan->delete();
        $this->logActivity('deleted', $jadwalPertandingan);
        
        if (request()->expectsJson()) {
            return response()->json(['message' => 'Jadwal pertandingan berhasil dihapus.']);
        }
        
        return redirect()->route('admin.jadwal-pertandingan.index')->with('success', 'Jadwal pertandingan berhasil dihapus.');
    }
}