<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Peserta;
use App\Models\KelasTanding;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PesertaController extends Controller
{
    use LogsActivity;

    // ... kode lainnya tetap sama

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