<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class KelasTanding extends Model
{
    use HasFactory, LogsActivity;
    protected $table = 'kelas_tanding';
    protected $fillable = [
        'kelompok_usia_id',
        'jenis_kelamin',
        'kode_kelas',
        'berat_min',
        'berat_max',
        'label_keterangan',
        'is_open_class',
    ];

    protected $casts = [
        'is_open_class' => 'boolean',
    ];

    public function kelompokUsia()
    {
        return $this->belongsTo(KelompokUsia::class);
    }

    public function pesertas()
    {
        return $this->hasMany(Peserta::class);
    }
}