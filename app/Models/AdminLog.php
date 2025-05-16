<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminLog extends Model
{
    use HasFactory;

    protected $table = 'admin_logs';
    public $timestamps = false; // Karena sudah ada waktu_aksi

    protected $fillable = [
        'admin_id',
        'aksi',
        'model',
        'model_id',
        'waktu_aksi',
        // 'perubahan', // Jika Anda menambahkannya
    ];

    protected $casts = [
        'waktu_aksi' => 'datetime',
        // 'perubahan' => 'array', // Jika Anda menambahkannya dan ingin di-cast ke array
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
}