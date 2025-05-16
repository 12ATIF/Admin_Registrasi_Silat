<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// Tidak perlu LogsActivity jika perubahan hanya status & verified_at yang di-log manual

class Pembayaran extends Model
{
    use HasFactory;
    protected $table = 'pembayaran';
    protected $fillable = [
        'kontingen_id',
        'total_tagihan',
        'bukti_transfer',
        'status',
        'verified_at',
    ];

    protected $casts = [
        'total_tagihan' => 'decimal:2',
        'verified_at' => 'datetime',
    ];

    public function kontingen()
    {
        return $this->belongsTo(Kontingen::class);
    }
}