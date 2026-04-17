<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DokumenPeserta;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class DokumenPesertaController extends Controller
{
    use LogsActivity;
    
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = DokumenPeserta::with(['peserta.kontingen'])->select('dokumen_peserta.*');
            
            if ($request->has('peserta_id') && $request->peserta_id) {
                $query->where('peserta_id', $request->peserta_id);
            }
            
            if ($request->has('jenis_dokumen') && $request->jenis_dokumen) {
                $query->where('jenis_dokumen', $request->jenis_dokumen);
            }
            
            if ($request->has('verified') && $request->verified !== '') {
                if ($request->verified == '1') {
                    $query->whereNotNull('verified_at');
                } else {
                    $query->whereNull('verified_at');
                }
            }
            
            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('created_at', function($row) {
                    return $row->created_at->format('d/m/Y');
                })
                ->editColumn('verified_at', function($row) {
                    if ($row->verified_at) {
                        return '<span class="badge bg-success">Terverifikasi</span>';
                    } else {
                        return '<span class="badge bg-warning">Belum Diverifikasi</span>';
                    }
                })
                ->addColumn('action', function($row) {
                    $downloadUrl = route('admin.dokumen.download', $row->id);
                    $previewUrl = asset('storage/' . $row->file_path);
                    $verifyButton = !$row->verified_at 
                        ? '<button class="btn btn-sm btn-success verify-btn" data-id="'.$row->id.'">
                            <i class="fas fa-check"></i> Verifikasi
                          </button>'
                        : '';
                    
                    return '
                        <div class="d-flex">
                            <button class="btn btn-sm btn-info me-1 preview-btn" 
                                data-file-path="'.$row->file_path.'" 
                                data-download-url="'.$downloadUrl.'"
                                data-preview-url="'.$previewUrl.'">
                                <i class="fas fa-eye"></i> Preview
                            </button>
                            <a href="'.$downloadUrl.'" class="btn btn-sm btn-primary me-1">
                                <i class="fas fa-download"></i> Download
                            </a>
                            '.$verifyButton.'
                        </div>
                    ';
                })
                ->rawColumns(['verified_at', 'action'])
                ->make(true);
        }
        
        return view('admin.dokumen.index');
    }
    
    public function download(DokumenPeserta $dokumen)
    {
        // Try public disk first (where uploads typically go), then local/private disk
        $publicDisk = Storage::disk('public');
        if ($publicDisk->exists($dokumen->file_path)) {
            /** @var \Illuminate\Filesystem\FilesystemAdapter $publicDisk */
            return $publicDisk->download($dokumen->file_path);
        }
        
        $localDisk = Storage::disk('local');
        if ($localDisk->exists($dokumen->file_path)) {
            /** @var \Illuminate\Filesystem\FilesystemAdapter $localDisk */
            return $localDisk->download($dokumen->file_path);
        }
        
        // File not found on any disk
        return redirect()->back()->with('error', 'File dokumen tidak ditemukan di server. Kemungkinan file belum diupload atau telah dihapus.');
    }
    
    public function verify(DokumenPeserta $dokumen)
    {
        $dokumen->verified_at = now();
        $dokumen->save();
        
        $this->logActivity('verified_document', $dokumen);
        
        return response()->json(['message' => 'Dokumen berhasil diverifikasi.', 'dokumen' => $dokumen]);
    }
}