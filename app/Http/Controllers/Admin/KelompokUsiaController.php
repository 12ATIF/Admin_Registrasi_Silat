<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KelompokUsia;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;

class KelompokUsiaController extends Controller
{
    use LogsActivity;
    
    public function index()
    {
        $kelompokUsias = KelompokUsia::withCount(['subkategoriLombas', 'kelasTandings', 'pesertas'])
            ->paginate(15);
        
        if (request()->expectsJson()) {
            return response()->json($kelompokUsias);
        }
        
        return view('admin.kelompok-usia.index', compact('kelompokUsias'));
    }
    
    public function create()
    {
        return view('admin.kelompok-usia.create');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:kelompok_usia,nama',
            'rentang_usia_min' => 'required|integer|min:0',
            'rentang_usia_max' => 'required|integer|gt:rentang_usia_min',
        ]);
        
        $kelompokUsia = KelompokUsia::create($request->all());
        
        $this->logActivity('created', $kelompokUsia);
        
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Kelompok usia berhasil dibuat.', 'kelompok_usia' => $kelompokUsia], 201);
        }
        
        return redirect()->route('admin.kelompok-usia.index')->with('success', 'Kelompok usia berhasil dibuat.');
    }
    
    public function show(KelompokUsia $kelompokUsia)
    {
        $kelompokUsia->load(['subkategoriLombas', 'kelasTandings', 'aturanUsias']);
        
        if (request()->expectsJson()) {
            return response()->json($kelompokUsia);
        }
        
        return view('admin.kelompok-usia.show', compact('kelompokUsia'));
    }
    
    public function edit(KelompokUsia $kelompokUsia)
    {
        return view('admin.kelompok-usia.edit', compact('kelompokUsia'));
    }
    
    public function update(Request $request, KelompokUsia $kelompokUsia)
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:kelompok_usia,nama,' . $kelompokUsia->id,
            'rentang_usia_min' => 'required|integer|min:0',
            'rentang_usia_max' => 'required|integer|gt:rentang_usia_min',
        ]);
        
        $kelompokUsia->update($request->all());
        
        $this->logActivity('updated', $kelompokUsia);
        
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Kelompok usia berhasil diperbarui.', 'kelompok_usia' => $kelompokUsia]);
        }
        
        return redirect()->route('admin.kelompok-usia.index')->with('success', 'Kelompok usia berhasil diperbarui.');
    }
    
    public function destroy(KelompokUsia $kelompokUsia)
    {
        try {
            $kelompokUsia->delete();
            $this->logActivity('deleted', $kelompokUsia);
            
            if (request()->expectsJson()) {
                return response()->json(['message' => 'Kelompok usia berhasil dihapus.']);
            }
            
            return redirect()->route('admin.kelompok-usia.index')->with('success', 'Kelompok usia berhasil dihapus.');
        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return response()->json(['message' => 'Tidak dapat menghapus kelompok usia karena masih digunakan.'], 422);
            }
            
            return redirect()->back()->with('error', 'Tidak dapat menghapus kelompok usia karena masih digunakan.');
        }
    }
}