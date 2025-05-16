<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pelatih;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use DataTables;

class PelatihController extends Controller
{
    use LogsActivity;
    
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Pelatih::withCount('kontingens');
            
            if ($request->has('search') && !empty($request->search)) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('perguruan', 'like', "%{$search}%");
                });
            }
            
            if ($request->has('is_active') && $request->is_active !== '') {
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
                    $viewUrl = route('admin.pelatih.show', $row->id);
                    $statusButton = $row->is_active 
                        ? '<button class="btn btn-sm btn-warning toggle-status" data-id="'.$row->id.'" data-status="1"><i class="fas fa-ban"></i> Nonaktifkan</button>'
                        : '<button class="btn btn-sm btn-success toggle-status" data-id="'.$row->id.'" data-status="0"><i class="fas fa-check"></i> Aktifkan</button>';
                    
                    return '
                        <div class="d-flex">
                            <a href="'.$viewUrl.'" class="btn btn-sm btn-info me-1"><i class="fas fa-eye"></i> Detail</a>
                            <button class="btn btn-sm btn-secondary me-1 reset-password" data-id="'.$row->id.'"><i class="fas fa-key"></i> Reset Password</button>
                            '.$statusButton.'
                        </div>
                    ';
                })
                ->rawColumns(['is_active', 'action'])
                ->make(true);
        }
        
        return view('admin.pelatih.index');
    }
    
    public function show(Pelatih $pelatih)
    {
        $pelatih->load('kontingens.pesertas');
        
        return view('admin.pelatih.show', compact('pelatih'));
    }

    public function resetPassword(Request $request, Pelatih $pelatih)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);
        
        $pelatih->password = Hash::make($request->password);
        $pelatih->save();
        
        $this->logActivity('reset_password', $pelatih);
        
        return response()->json(['message' => 'Password berhasil direset.']);
    }
    
    public function toggleStatus(Pelatih $pelatih)
    {
        $pelatih->is_active = !$pelatih->is_active;
        $pelatih->save();
        
        $this->logActivity($pelatih->is_active ? 'activated' : 'deactivated', $pelatih);
        
        $message = $pelatih->is_active ? 'Pelatih berhasil diaktifkan.' : 'Pelatih berhasil dinonaktifkan.';
        
        return response()->json(['message' => $message, 'pelatih' => $pelatih]);
    }
}