<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pelatih;
use App\Models\Kontingen;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PelatihController extends Controller
{
    use LogsActivity;
    
    public function index(Request $request)
    {
        $query = Pelatih::with('kontingens');
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('perguruan', 'like', "%{$search}%");
            });
        }
        
        if ($request->has('is_active')) {
            $query->where('is_active', $request->is_active);
        }
        
        $pelatihs = $query->paginate(15);
        
        if ($request->expectsJson()) {
            return response()->json($pelatihs);
        }
        
        return view('admin.pelatih.index', compact('pelatihs'));
    }
    
    public function show(Pelatih $pelatih)
    {
        $pelatih->load('kontingens.pesertas');
        
        if (request()->expectsJson()) {
            return response()->json($pelatih);
        }
        
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