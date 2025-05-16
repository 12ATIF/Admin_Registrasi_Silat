<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PembayaranController extends Controller
{
    use LogsActivity;
    
    public function index(Request $request)
    {
        $query = Pembayaran::with(['kontingen.pelatih']);
        
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->has('kontingen_id')) {
            $query->where('kontingen_id', $request->kontingen_id);
        }
        
        $pembayarans = $query->paginate(15);
        
        if ($request->expectsJson()) {
            return response()->json($pembayarans);
        }
        
        return view('admin.pembayaran.index', compact('pembayarans'));
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