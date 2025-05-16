<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class Pertandingan extends Model
{
    use HasFactory, LogsActivity;
    protected $table = 'pertandingan';
    protected $fillable = [
        'nama_event',
        'tanggal_event',
        'lokasi_umum',
    ];

    protected $casts = [
        'tanggal_event' => 'date',
    ];

    public function jadwalPertandingans()
    {
        return $this->hasMany(JadwalPertandingan::class);
    }
}