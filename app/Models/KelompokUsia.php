<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class KelompokUsia extends Model
{
    use HasFactory, LogsActivity;
    
    protected $table = 'kelompok_usia';
    
    protected $fillable = [
        'nama',
        'rentang_usia_min',
        'rentang_usia_max',
    ];
    
    public function subkategoriLombas()
    {
        return $this->belongsToMany(SubkategoriLomba::class, 'subkategori_usia', 'kelompok_usia_id', 'subkategori_id');
    }
    
    public function kelasTandings()
    {
        return $this->hasMany(KelasTanding::class);
    }
    
    public function pesertas()
    {
        return $this->hasMany(Peserta::class);
    }
    
    public function aturanUsias()
    {
        return $this->hasMany(AturanUsia::class);
    }
    
    public function aturanKelas()
    {
        return $this->hasMany(AturanKelas::class);
    }
    
    public function jadwalPertandingans()
    {
        return $this->hasMany(JadwalPertandingan::class);
    }
    
    public function timPesertas()
    {
        return $this->hasMany(TimPeserta::class);
    }
}