<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KelasTanding;
use App\Models\KelompokUsia;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;

class KelasTandingController extends Controller
{
    use LogsActivity;
    
    public function index(Request $request)
    {
        $query = KelasTanding::with('kelompokUsia');
        
        if ($request->has('kelompok_usia_id')) {
            $query->where('kelompok_usia_id', $request->kelompok_usia_id);
        }
        
        if ($request->has('jenis_kelamin')) {
            $query->where('jenis_kelamin', $request->jenis_kelamin);
        }
        
        $kelasTandings = $query->paginate(15);
        
        if ($request->expectsJson()) {
            return response()->json($kelasTandings);
        }
        
        return view('admin.kelas-tanding.index', compact('kelasTandings'));
    }
    
    public function create()
    {
        $kelompokUsias = KelompokUsia::all();
        return view('admin.kelas-tanding.create', compact('kelompokUsias'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'kelompok_usia_id' => 'required|exists:kelompok_usia,id',
            'jenis_kelamin' => 'required|in:putra,putri',
            'kode_kelas' => 'required|string|max:255',
            'berat_min' => 'required|integer|min:0',
            'berat_max' => 'required|integer|gte:berat_min',
            'label_keterangan' => 'nullable|string|max:255',
            'is_open_class' => 'boolean',
        ]);
        
        $kelasTanding = KelasTanding::create($request->all());
        
        $this->logActivity('created', $kelasTanding);
        
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Kelas tanding berhasil dibuat.', 'kelas_tanding' => $kelasTanding], 201);
        }
        
        return redirect()->route('admin.kelas-tanding.index')->with('success', 'Kelas tanding berhasil dibuat.');
    }
    
    public function show(KelasTanding $kelasTanding)
    {
        $kelasTanding->load('kelompokUsia', 'pesertas');
        
        if (request()->expectsJson()) {
            return response()->json($kelasTanding);
        }
        
        return view('admin.kelas-tanding.show', compact('kelasTanding'));
    }
    
    public function edit(KelasTanding $kelasTanding)
    {
        $kelompokUsias = KelompokUsia::all();
        return view('admin.kelas-tanding.edit', compact('kelasTanding', 'kelompokUsias'));
    }
    
    public function update(Request $request, KelasTanding $kelasTanding)
    {
        $request->validate([
            'kelompok_usia_id' => 'required|exists:kelompok_usia,id',
            'jenis_kelamin' => 'required|in:putra,putri',
            'kode_kelas' => 'required|string|max:255',
            'berat_min' => 'required|integer|min:0',
            'berat_max' => 'required|integer|gte:berat_min',
            'label_keterangan' => 'nullable|string|max:255',
            'is_open_class' => 'boolean',
        ]);
        
        $kelasTanding->update($request->all());
        
        $this->logActivity('updated', $kelasTanding);
        
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Kelas tanding berhasil diperbarui.', 'kelas_tanding' => $kelasTanding]);
        }
        
        return redirect()->route('admin.kelas-tanding.index')->with('success', 'Kelas tanding berhasil diperbarui.');
    }
    
    public function destroy(KelasTanding $kelasTanding)
    {
        try {
            $kelasTanding->delete();
            $this->logActivity('deleted', $kelasTanding);
            
            if (request()->expectsJson()) {
                return response()->json(['message' => 'Kelas tanding berhasil dihapus.']);
            }
            
            return redirect()->route('admin.kelas-tanding.index')->with('success', 'Kelas tanding berhasil dihapus.');
        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return response()->json(['message' => 'Tidak dapat menghapus kelas tanding karena masih digunakan.'], 422);
            }
            
            return redirect()->back()->with('error', 'Tidak dapat menghapus kelas tanding karena masih digunakan.');
        }
    }
}