<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KategoriLomba;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;

class KategoriLombaController extends Controller
{
    use LogsActivity;
    
    public function index()
    {
        $kategoris = KategoriLomba::with('subkategoriLombas')->paginate(15);
        
        if (request()->expectsJson()) {
            return response()->json($kategoris);
        }
        
        return view('admin.kategori-lomba.index', compact('kategoris'));
    }
    
    public function create()
    {
        return view('admin.kategori-lomba.create');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:kategori_lomba,nama',
        ]);
        
        $kategori = KategoriLomba::create($request->only('nama'));
        
        $this->logActivity('created', $kategori);
        
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Kategori lomba berhasil dibuat.', 'kategori' => $kategori], 201);
        }
        
        return redirect()->route('admin.kategori-lomba.index')->with('success', 'Kategori lomba berhasil dibuat.');
    }
    
    public function show(KategoriLomba $kategoriLomba)
    {
        $kategoriLomba->load('subkategoriLombas');
        
        if (request()->expectsJson()) {
            return response()->json($kategoriLomba);
        }
        
        return view('admin.kategori-lomba.show', compact('kategoriLomba'));
    }
    
    public function edit(KategoriLomba $kategoriLomba)
    {
        return view('admin.kategori-lomba.edit', compact('kategoriLomba'));
    }
    
    public function update(Request $request, KategoriLomba $kategoriLomba)
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:kategori_lomba,nama,' . $kategoriLomba->id,
        ]);
        
        $kategoriLomba->update($request->only('nama'));
        
        $this->logActivity('updated', $kategoriLomba);
        
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Kategori lomba berhasil diperbarui.', 'kategori' => $kategoriLomba]);
        }
        
        return redirect()->route('admin.kategori-lomba.index')->with('success', 'Kategori lomba berhasil diperbarui.');
    }
    
    public function destroy(KategoriLomba $kategoriLomba)
    {
        try {
            $kategoriLomba->delete();
            $this->logActivity('deleted', $kategoriLomba);
            
            if (request()->expectsJson()) {
                return response()->json(['message' => 'Kategori lomba berhasil dihapus.']);
            }
            
            return redirect()->route('admin.kategori-lomba.index')->with('success', 'Kategori lomba berhasil dihapus.');
        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return response()->json(['message' => 'Tidak dapat menghapus kategori karena masih memiliki subkategori.'], 422);
            }
            
            return redirect()->back()->with('error', 'Tidak dapat menghapus kategori karena masih memiliki subkategori.');
        }
    }
}