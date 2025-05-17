<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KelasTanding;
use App\Models\KelompokUsia;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;
use DataTables; // Pastikan ini diimport jika menggunakan DataTables server-side

class KelasTandingController extends Controller
{
    use LogsActivity;
    
    public function index(Request $request)
    {
        // Jika menggunakan DataTables server-side
        if ($request->ajax() && $request->wantsJson()) {
            $query = KelasTanding::with('kelompokUsia')
                ->withCount('pesertas');
            
            if ($request->has('kelompok_usia_id') && $request->kelompok_usia_id) {
                $query->where('kelompok_usia_id', $request->kelompok_usia_id);
            }
            
            if ($request->has('jenis_kelamin') && $request->jenis_kelamin) {
                $query->where('jenis_kelamin', $request->jenis_kelamin);
            }
            
            return DataTables::of($query)
                ->addColumn('action', function($row) {
                    return '
                        <div class="btn-group" role="group">
                            <a href="'.route('admin.kelas-tanding.show', $row->id).'" class="btn btn-sm btn-info">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="'.route('admin.kelas-tanding.edit', $row->id).'" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="'.route('admin.kelas-tanding.destroy', $row->id).'" method="POST" class="d-inline" onsubmit="return confirm(\'Apakah Anda yakin ingin menghapus kelas ini?\');">
                                '.csrf_field().'
                                '.method_field('DELETE').'
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    ';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        
        // Untuk non-AJAX request atau jika tidak menggunakan DataTables server-side
        $query = KelasTanding::with('kelompokUsia')
            ->withCount('pesertas');
        
        if ($request->has('kelompok_usia_id') && $request->kelompok_usia_id) {
            $query->where('kelompok_usia_id', $request->kelompok_usia_id);
        }
        
        if ($request->has('jenis_kelamin') && $request->jenis_kelamin) {
            $query->where('jenis_kelamin', $request->jenis_kelamin);
        }
        
        // Urutkan data
        $query->orderBy('kelompok_usia_id')->orderBy('jenis_kelamin')->orderBy('berat_min');
        
        $kelasTandings = $query->paginate(15);
        
        return view('admin.kelas-tanding.index', compact('kelasTandings'));
    }
    
    // Method lainnya tidak berubah
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