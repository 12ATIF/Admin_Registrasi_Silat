<?php
// app/Http/Controllers/Admin/PembayaranController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use DataTables;

class PembayaranController extends Controller
{
    use LogsActivity;
    
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Pembayaran::with(['kontingen.pelatih', 'kontingen.pesertas']);
            
            if ($request->has('status') && $request->status) {
                $query->where('status', $request->status);
            }
            
            if ($request->has('kontingen_id') && $request->kontingen_id) {
                $query->where('kontingen_id', $request->kontingen_id);
            }
            
            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('total_tagihan', function($row) {
                    return 'Rp ' . number_format($row->total_tagihan, 0, ',', '.');
                })
                ->editColumn('status', function($row) {
                    $badges = [
                        'lunas' => 'success',
                        'menunggu_verifikasi' => 'warning',
                        'belum_bayar' => 'danger'
                    ];
                    $badge = $badges[$row->status] ?? 'secondary';
                    return '<span class="badge bg-'.$badge.'">'.ucfirst(str_replace('_', ' ', $row->status)).'</span>';
                })
                ->editColumn('verified_at', function($row) {
                    return $row->verified_at ? $row->verified_at->format('d/m/Y H:i') : '-';
                })
                ->addColumn('pesertas_count', function($row) {
                    return $row->kontingen->pesertas->count();
                })
                ->addColumn('action', function($row) {
                    $previewButton = $row->bukti_transfer 
                        ? '<button class="btn btn-sm btn-info me-1 preview-btn" data-bukti="'.($row->bukti_transfer ? Storage::url($row->bukti_transfer) : '').'">
                            <i class="fas fa-eye"></i> Lihat Bukti
                           </button>'
                        : '';
                            
                    return '
                        <div class="d-flex">
                            '.$previewButton.'
                            <button class="btn btn-sm btn-primary verify-btn" 
                                data-id="'.$row->id.'" 
                                data-kontingen="'.$row->kontingen->nama.'" 
                                data-tagihan="'.$row->total_tagihan.'"
                                data-status="'.$row->status.'">
                                <i class="fas fa-check-circle"></i> Verifikasi
                            </button>
                        </div>
                    ';
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }
        
        return view('admin.pembayaran.index');
    }
    
    public function verifyPayment(Request $request, Pembayaran $pembayaran)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:belum_bayar,menunggu_verifikasi,lunas',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        
        $pembayaran->status = $request->status;
        
        if ($request->status == 'lunas') {
            $pembayaran->verified_at = now();
        }
        
        $pembayaran->save();
        
        $this->logActivity('verified_payment', $pembayaran);
        
        return response()->json(['message' => 'Status pembayaran berhasil diubah.', 'pembayaran' => $pembayaran]);
    }
}