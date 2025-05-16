<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peserta extends Model
{
    use HasFactory;

    protected $table = 'peserta';

    protected $fillable = [
        'kontingen_id',
        'nama',
        'jenis_kelamin', // 'L', 'P'
        'tanggal_lahir',
        'berat_badan',
        'subkategori_id',
        'kelompok_usia_id',
        'kelas_tanding_id',
        'is_manual_override',
        'status_verifikasi', // 'pending', 'valid', 'tidak_valid'
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'berat_badan' => 'decimal:2',
        'is_manual_override' => 'boolean',
    ];

    public function kontingen()
    {
        return $this->belongsTo(Kontingen::class);
    }

    public function subkategoriLomba()
    {
        return $this->belongsTo(SubkategoriLomba::class, 'subkategori_id');
    }

    public function kelompokUsia()
    {
        return $this->belongsTo(KelompokUsia::class);
    }

    public function kelasTanding()
    {
        return $this->belongsTo(KelasTanding::class);
    }

    public function dokumenPesertas()
    {
        return $this->hasMany(DokumenPeserta::class);
    }

    public function timAnggota()
    {
        return $this->hasMany(TimAnggota::class);
    }

    // Scope untuk filter
    public function scopeStatus($query, $status)
    {
        if ($status) {
            return $query->where('status_verifikasi', $status);
        }
        return $query;
    }

    public function scopeKategori($query, $kategoriId) // Kategori Lomba ID
    {
        if ($kategoriId) {
            return $query->whereHas('subkategoriLomba', function ($q) use ($kategoriId) {
                $q->where('kategori_id', $kategoriId);
            });
        }
        return $query;
    }
    // Tambahkan scope lain sesuai kebutuhan filter
}