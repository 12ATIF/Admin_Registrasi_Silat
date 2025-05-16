<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kontingen extends Model
{
    use HasFactory;

    protected $table = 'kontingen';

    protected $fillable = [
        'pelatih_id',
        'nama',
        'asal_daerah',
        'kontak_pendamping',
        // 'is_active' // Tambahkan jika ada fitur nonaktifkan
    ];

    // protected $casts = [
    //     'is_active' => 'boolean',
    // ];

    public function pelatih()
    {
        return $this->belongsTo(Pelatih::class);
    }

    public function pesertas()
    {
        return $this->hasMany(Peserta::class);
    }

    public function pembayarans()
    {
        return $this->hasMany(Pembayaran::class);
    }

    public function timPesertas()
    {
        return $this->hasMany(TimPeserta::class);
    }
}