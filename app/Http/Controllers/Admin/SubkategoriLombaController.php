<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubkategoriLomba;
use App\Models\KategoriLomba;
use App\Models\KelompokUsia;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;

class SubkategoriLombaController extends Controller
{
    use LogsActivity;
    
    public function index()
    {
        $subkategoris = SubkategoriLomba::with(['kategoriLomba', 'kelompokUsias'])->paginate(15);
        
        if (request()->expectsJson()) {
            return response()->json($subkategoris);
        }
        
        return view('admin.subkategori-lomba.index', compact('subkategoris'));
    }
    
    public function create()
    {
        $kategoris = KategoriLomba::all();
        $kelompokUsias = KelompokUsia::all();
        
        return view('admin.subkategori-lomba.create', compact('kategoris', 'kelompokUsias'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'kategori_id' => 'required|exists:kategori_lomba,id',
            'nama' => 'required|string|max:255',
            'jenis' => 'required|in:tunggal,ganda,regu,tanding',
            'jumlah_peserta' => 'required|integer|min:1',
            'harga_pendaftaran' => 'required|numeric|min:0',
            'kelompok_usia_ids' => 'required|array',
            'kelompok_usia_ids.*' => 'exists:kelompok_usia,id',
        ]);
        
        $subkategori = SubkategoriLomba::create($request->except('kelompok_usia_ids'));
        $subkategori->kelompokUsias()->attach($request->kelompok_usia_ids);
        
        $this->logActivity('created', $subkategori);
        
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Subkategori lomba berhasil dibuat.', 'subkategori' => $subkategori], 201);
        }
        
        return redirect()->route('admin.subkategori-lomba.index')->with('success', 'Subkategori lomba berhasil dibuat.');
    }
    
    public function show(SubkategoriLomba $subkategoriLomba)
    {
        $subkategoriLomba->load(['kategoriLomba', 'kelompokUsias']);
        
        if (request()->expectsJson()) {
            return response()->json($subkategoriLomba);
        }
        
        return view('admin.subkategori-lomba.show', compact('subkategoriLomba'));
    }
    
    public function edit(SubkategoriLomba $subkategoriLomba)
    {
        $kategoris = KategoriLomba::all();
        $kelompokUsias = KelompokUsia::all();
        $subkategoriLomba->load('kelompokUsias');
        
        return view('admin.subkategori-lomba.edit', compact('subkategoriLomba', 'kategoris', 'kelompokUsias'));
    }
    
    public function update(Request $request, SubkategoriLomba $subkategoriLomba)
    {
        $request->validate([
            'kategori_id' => 'required|exists:kategori_lomba,id',
            'nama' => 'required|string|max:255',
            'jenis' => 'required|in:tunggal,ganda,regu,tanding',
            'jumlah_peserta' => 'required|integer|min:1',
            'harga_pendaftaran' => 'required|numeric|min:0',
            'kelompok_usia_ids' => 'required|array',
            'kelompok_usia_ids.*' => 'exists:kelompok_usia,id',
        ]);
        
        $subkategoriLomba->update($request->except('kelompok_usia_ids'));
        $subkategoriLomba->kelompokUsias()->sync($request->kelompok_usia_ids);
        
        $this->logActivity('updated', $subkategoriLomba);
        
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Subkategori lomba berhasil diperbarui.', 'subkategori' => $subkategoriLomba]);
        }
        
        return redirect()->route('admin.subkategori-lomba.index')->with('success', 'Subkategori lomba berhasil diperbarui.');
    }
    
    public function destroy(SubkategoriLomba $subkategoriLomba)
    {
        try {
            $subkategoriLomba->delete();
            $this->logActivity('deleted', $subkategoriLomba);
            
            if (request()->expectsJson()) {
                return response()->json(['message' => 'Subkategori lomba berhasil dihapus.']);
            }
            
            return redirect()->route('admin.subkategori-lomba.index')->with('success', 'Subkategori lomba berhasil dihapus.');
        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return response()->json(['message' => 'Tidak dapat menghapus subkategori karena masih digunakan.'], 422);
            }
            
            return redirect()->back()->with('error', 'Tidak dapat menghapus subkategori karena masih digunakan.');
        }
    }
}