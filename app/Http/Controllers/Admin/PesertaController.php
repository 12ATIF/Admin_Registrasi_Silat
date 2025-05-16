<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Peserta;
use App\Models\KelasTanding;
use App\Models\KategoriLomba;
use App\Models\KelompokUsia;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DataTables;

class PesertaController extends Controller
{
    use LogsActivity;

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Peserta::with(['kontingen', 'subkategoriLomba.kategoriLomba', 'kelompokUsia', 'kelasTanding']);
            
            // Filter by kategori
            if ($request->has('kategori_id') && $request->kategori_id) {
                $query->whereHas('subkategoriLomba', function($q) use ($request) {
                    $q->where('kategori_id', $request->kategori_id);
                });
            }
            
            // Filter by kelompok usia
            if ($request->has('kelompok_usia_id') && $request->kelompok_usia_id) {
                $query->where('kelompok_usia_id', $request->kelompok_usia_id);
            }
            
            // Filter by status
            if ($request->has('status_verifikasi') && $request->status_verifikasi) {
                $query->where('status_verifikasi', $request->status_verifikasi);
            }
            
            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('jenis_kelamin', function($row) {
                    return $row->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan';
                })
                ->editColumn('tanggal_lahir', function($row) {
                    return $row->tanggal_lahir->format('d/m/Y');
                })
                ->editColumn('berat_badan', function($row) {
                    return $row->berat_badan . ' kg';
                })
                ->addColumn('kontingen.nama', function($row) {
                    return $row->kontingen->nama . ' (' . $row->kontingen->asal_daerah . ')';
                })
                ->addColumn('subkategori_lomba.kategori_lomba.nama', function($row) {
                    return $row->subkategoriLomba->kategoriLomba->nama . ' - ' . $row->subkategoriLomba->nama;
                })
                ->addColumn('kelas_tanding', function($row) {
                    if ($row->kelasTanding) {
                        $label = $row->kelasTanding->label_keterangan;
                        if ($row->is_manual_override) {
                            $label .= ' <span class="badge bg-warning">Override</span>';
                        }
                        return $label;
                    }
                    return '-';
                })
                ->editColumn('status_verifikasi', function($row) {
                    $badges = [
                        'valid' => 'success',
                        'pending' => 'warning',
                        'tidak_valid' => 'danger'
                    ];
                    $badge = $badges[$row->status_verifikasi] ?? 'secondary';
                    return '<span class="badge bg-'.$badge.'">'.ucfirst($row->status_verifikasi).'</span>';
                })
                ->addColumn('action', function($row) {
                    $verifyButtons = '
                    <div class="btn-group mb-1" role="group">
                        <button class="btn btn-sm btn-success verify-btn" data-id="'.$row->id.'" data-status="valid">
                            <i class="fas fa-check"></i> Valid
                        </button>
                        <button class="btn btn-sm btn-danger verify-btn" data-id="'.$row->id.'" data-status="tidak_valid">
                            <i class="fas fa-times"></i> Tidak Valid
                        </button>
                    </div>';
                    
                    $overrideButton = '
                    <button class="btn btn-sm btn-warning override-kelas-btn mb-1" 
                        data-id="'.$row->id.'" 
                        data-nama="'.$row->nama.'" 
                        data-berat="'.$row->berat_badan.'"
                        data-kelompok-usia-id="'.$row->kelompok_usia_id.'"
                        data-jenis-kelamin="'.$row->jenis_kelamin.'"
                        data-kelas-tanding-id="'.($row->kelas_tanding_id ?: '').'"
                        data-kelas-tanding-name="'.($row->kelasTanding ? $row->kelasTanding->label_keterangan : '-').'">
                        <i class="fas fa-exchange-alt"></i> Ubah Kelas
                    </button>';
                    
                    return '<div class="d-flex flex-column">'.$verifyButtons.$overrideButton.'</div>';
                })
                ->rawColumns(['status_verifikasi', 'kelas_tanding', 'action'])
                ->make(true);
        }
        
        $kategoris = KategoriLomba::all();
        $kelompokUsias = KelompokUsia::all();
        
        return view('admin.peserta.index', compact('kategoris', 'kelompokUsias'));
    }

    public function verify(Request $request, Peserta $peserta)
    {
        $validator = Validator::make($request->all(), [
            'status_verifikasi' => 'required|in:valid,tidak_valid',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $peserta->status_verifikasi = $request->status_verifikasi;
        $peserta->save();

        // Log aktivitas admin
        $this->logActivity('verified', $peserta);

        return response()->json(['message' => 'Status verifikasi peserta berhasil diubah.', 'peserta' => $peserta]);
    }

    public function overrideClass(Request $request, Peserta $peserta)
    {
        $validator = Validator::make($request->all(), [
            'kelas_tanding_id' => 'required|exists:kelas_tanding,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $peserta->kelas_tanding_id = $request->kelas_tanding_id;
        $peserta->is_manual_override = true;
        $peserta->save();

        // Log aktivitas admin
        $this->logActivity('override_class', $peserta);

        return response()->json(['message' => 'Kelas tanding peserta berhasil di-override.', 'peserta' => $peserta]);
    }
}