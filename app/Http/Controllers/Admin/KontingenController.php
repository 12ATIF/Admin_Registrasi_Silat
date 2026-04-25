<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kontingen;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;
use DataTables;

class KontingenController extends Controller
{
    use LogsActivity;
    
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Kontingen::with('pelatih')->withCount('pesertas');
            
            if ($request->has('search_filter') && !empty($request->search_filter)) {
                $search = $request->search_filter;
                $query->where(function($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%")
                      ->orWhere('asal_daerah', 'like', "%{$search}%");
                });
            }
            
            if ($request->has('pelatih_id') && $request->pelatih_id) {
                $query->where('pelatih_id', $request->pelatih_id);
            }
            
            if ($request->filled('is_active')) {
                $query->where('is_active', $request->is_active);
            }
            
            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('is_active', function($row) {
                    return $row->is_active 
                        ? '<span class="badge bg-success">Aktif</span>' 
                        : '<span class="badge bg-danger">Nonaktif</span>';
                })
                ->addColumn('action', function($row) {
                    $viewUrl = route('admin.kontingen.show', $row->id);
                    $statusButton = $row->is_active 
                        ? '<button class="btn btn-sm btn-warning toggle-status" data-id="'.$row->id.'" data-status="1"><i class="fas fa-ban"></i> Nonaktifkan</button>'
                        : '<button class="btn btn-sm btn-success toggle-status" data-id="'.$row->id.'" data-status="0"><i class="fas fa-check"></i> Aktifkan</button>';
                    
                    return '
                        <div class="d-flex flex-wrap gap-1">
                            <a href="'.$viewUrl.'" class="btn btn-sm btn-info"><i class="fas fa-eye"></i> Detail</a>
                            '.$statusButton.'
                            <button class="btn btn-sm btn-danger delete-kontingen-btn" data-id="'.$row->id.'" data-nama="'.e($row->nama).'"><i class="fas fa-trash"></i> Hapus</button>
                        </div>
                    ';
                })
                ->rawColumns(['is_active', 'action'])
                ->make(true);
        }
        
        return view('admin.kontingen.index');
    }
    
    public function show(Kontingen $kontingen)
    {
        $kontingen->load(['pelatih', 'pesertas', 'pembayarans']);
        
        return view('admin.kontingen.show', compact('kontingen'));
    }
    
    public function destroy(Kontingen $kontingen)
    {
        $nama = $kontingen->nama;
        foreach ($kontingen->pesertas as $peserta) {
            $peserta->dokumenPesertas()->delete();
            $peserta->timAnggota()->delete();
            $peserta->delete();
        }
        $kontingen->pembayarans()->delete();
        $kontingen->timPesertas()->delete();
        $kontingen->delete();

        $this->logActivity('deleted', $kontingen);

        return response()->json(['message' => "Kontingen {$nama} beserta semua data terkait berhasil dihapus."]);
    }

    public function toggleStatus(Kontingen $kontingen)
    {
        $kontingen->is_active = !$kontingen->is_active;
        $kontingen->save();
        
        $this->logActivity($kontingen->is_active ? 'activated' : 'deactivated', $kontingen);
        
        $message = $kontingen->is_active ? 'Kontingen berhasil diaktifkan.' : 'Kontingen berhasil dinonaktifkan.';
        
        return response()->json(['message' => $message, 'kontingen' => $kontingen]);
    }
}