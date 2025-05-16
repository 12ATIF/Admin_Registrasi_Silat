<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class SubkategoriLomba extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'subkategori_lomba';

    protected $fillable = [
        'kategori_id',
        'nama',
        'jenis',
        'jumlah_peserta',
        'harga_pendaftaran',
    ];

    protected $casts = [
        'harga_pendaftaran' => 'decimal:2',
    ];

    public function kategoriLomba()
    {
        return $this->belongsTo(KategoriLomba::class, 'kategori_id');
    }

    public function kelompokUsias()
    {
        return $this->belongsToMany(KelompokUsia::class, 'subkategori_usia', 'subkategori_id', 'kelompok_usia_id');
    }

    public function pesertas()
    {
        return $this->hasMany(Peserta::class, 'subkategori_id');
    }

    public function timPesertas()
    {
        return $this->hasMany(TimPeserta::class, 'subkategori_id');
    }

    public function jadwalPertandingans()
    {
        return $this->hasMany(JadwalPertandingan::class, 'subkategori_id');
    }
}