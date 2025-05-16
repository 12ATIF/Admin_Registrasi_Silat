<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DokumenPeserta;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DokumenPesertaController extends Controller
{
    use LogsActivity;
    
    public function index(Request $request)
    {
        $query = DokumenPeserta::with(['peserta.kontingen']);
        
        if ($request->has('peserta_id')) {
            $query->where('peserta_id', $request->peserta_id);
        }
        
        if ($request->has('jenis_dokumen')) {
            $query->where('jenis_dokumen', $request->jenis_dokumen);
        }
        
        if ($request->has('verified')) {
            if ($request->verified == '1') {
                $query->whereNotNull('verified_at');
            } else {
                $query->whereNull('verified_at');
            }
        }
        
        $dokumens = $query->paginate(15);
        
        if ($request->expectsJson()) {
            return response()->json($dokumens);
        }
        
        return view('admin.dokumen.index', compact('dokumens'));
    }
    
    public function download(DokumenPeserta $dokumen)
    {
        if (!Storage::exists($dokumen->file_path)) {
            abort(404, 'File tidak ditemukan.');
        }
        
        return Storage::download($dokumen->file_path);
    }
    
    public function verify(DokumenPeserta $dokumen)
    {
        $dokumen->verified_at = now();
        $dokumen->save();
        
        $this->logActivity('verified_document', $dokumen);
        
        return response()->json(['message' => 'Dokumen berhasil diverifikasi.', 'dokumen' => $dokumen]);
    }
}