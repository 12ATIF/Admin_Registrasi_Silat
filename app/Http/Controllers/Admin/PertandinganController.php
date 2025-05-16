<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pertandingan;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;

class PertandinganController extends Controller
{
    use LogsActivity;
    
    public function index()
    {
        $pertandingans = Pertandingan::withCount('jadwalPertandingans')
            ->orderBy('tanggal_event', 'desc')
            ->paginate(15);
        
        if (request()->expectsJson()) {
            return response()->json($pertandingans);
        }
        
        return view('admin.pertandingan.index', compact('pertandingans'));
    }
    
    public function create()
    {
        return view('admin.pertandingan.create');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'nama_event' => 'required|string|max:255',
            'tanggal_event' => 'required|date',
            'lokasi_umum' => 'required|string|max:255',
        ]);
        
        $pertandingan = Pertandingan::create($request->all());
        
        $this->logActivity('created', $pertandingan);
        
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Pertandingan berhasil dibuat.', 'pertandingan' => $pertandingan], 201);
        }
        
        return redirect()->route('admin.pertandingan.index')->with('success', 'Pertandingan berhasil dibuat.');
    }
    
    public function show(Pertandingan $pertandingan)
    {
        $pertandingan->load('jadwalPertandingans.subkategoriLomba', 'jadwalPertandingans.kelompokUsia');
        
        // Statistik
        $stats = [
            'total_jadwal' => $pertandingan->jadwalPertandingans->count(),
            'subkategori' => $pertandingan->jadwalPertandingans->pluck('subkategori_id')->unique()->count(),
            'kelompok_usia' => $pertandingan->jadwalPertandingans->pluck('kelompok_usia_id')->unique()->count(),
        ];
        
        if (request()->expectsJson()) {
            return response()->json([
                'pertandingan' => $pertandingan,
                'statistics' => $stats
            ]);
        }
        
        return view('admin.pertandingan.show', compact('pertandingan', 'stats'));
    }
    
    public function edit(Pertandingan $pertandingan)
    {
        return view('admin.pertandingan.edit', compact('pertandingan'));
    }
    
    public function update(Request $request, Pertandingan $pertandingan)
    {
        $request->validate([
            'nama_event' => 'required|string|max:255',
            'tanggal_event' => 'required|date',
            'lokasi_umum' => 'required|string|max:255',
        ]);
        
        $pertandingan->update($request->all());
        
        $this->logActivity('updated', $pertandingan);
        
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Pertandingan berhasil diperbarui.', 'pertandingan' => $pertandingan]);
        }
        
        return redirect()->route('admin.pertandingan.index')->with('success', 'Pertandingan berhasil diperbarui.');
    }
    
    public function destroy(Pertandingan $pertandingan)
    {
        try {
            $pertandingan->delete();
            $this->logActivity('deleted', $pertandingan);
            
            if (request()->expectsJson()) {
                return response()->json(['message' => 'Pertandingan berhasil dihapus.']);
            }
            
            return redirect()->route('admin.pertandingan.index')->with('success', 'Pertandingan berhasil dihapus.');
        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return response()->json(['message' => 'Tidak dapat menghapus pertandingan karena memiliki jadwal terkait.'], 422);
            }
            
            return redirect()->back()->with('error', 'Tidak dapat menghapus pertandingan karena memiliki jadwal terkait.');
        }
    }
}