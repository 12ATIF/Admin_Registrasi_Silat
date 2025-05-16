<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kontingen;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;

class KontingenController extends Controller
{
    use LogsActivity;
    
    public function index(Request $request)
    {
        $query = Kontingen::with('pelatih');
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('asal_daerah', 'like', "%{$search}%");
            });
        }
        
        if ($request->has('pelatih_id')) {
            $query->where('pelatih_id', $request->pelatih_id);
        }
        
        if ($request->has('is_active')) {
            $query->where('is_active', $request->is_active);
        }
        
        $kontingens = $query->paginate(15);
        
        if ($request->expectsJson()) {
            return response()->json($kontingens);
        }
        
        return view('admin.kontingen.index', compact('kontingens'));
    }
    
    public function show(Kontingen $kontingen)
    {
        $kontingen->load(['pelatih', 'pesertas', 'pembayarans']);
        
        if (request()->expectsJson()) {
            return response()->json($kontingen);
        }
        
        return view('admin.kontingen.show', compact('kontingen'));
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