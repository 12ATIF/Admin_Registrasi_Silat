<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class JadwalPertandingan extends Model
{
    use HasFactory, LogsActivity;
    protected $table = 'jadwal_pertandingan';
    protected $fillable = [
        'pertandingan_id',
        'subkategori_id',
        'kelompok_usia_id',
        // 'kelas_tanding_id', // Jika ingin lebih spesifik
        'tanggal',
        'waktu_mulai',
        'waktu_selesai',
        'lokasi_detail',
    ];

    protected $casts = [
        'tanggal' => 'date',
        // 'waktu_mulai' => 'datetime:H:i', // jika pakai datetime
        // 'waktu_selesai' => 'datetime:H:i', // jika pakai datetime
    ];

    public function pertandingan()
    {
        return $this->belongsTo(Pertandingan::class);
    }

    public function subkategoriLomba()
    {
        return $this->belongsTo(SubkategoriLomba::class, 'subkategori_id');
    }

    public function kelompokUsia()
    {
        return $this->belongsTo(KelompokUsia::class);
    }

    // public function kelasTanding()
    // {
    //     return $this->belongsTo(KelasTanding::class);
    // }
}