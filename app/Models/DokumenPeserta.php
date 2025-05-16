<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// Tidak perlu LogsActivity jika perubahan hanya 'verified_at' yang di-log manual

class DokumenPeserta extends Model
{
    use HasFactory;
    protected $table = 'dokumen_peserta';
    protected $fillable = [
        'peserta_id',
        'jenis_dokumen',
        'file_path',
        'verified_at',
    ];

    protected $casts = [
        'verified_at' => 'datetime',
    ];

    public function peserta()
    {
        return $this->belongsTo(Peserta::class);
    }
}