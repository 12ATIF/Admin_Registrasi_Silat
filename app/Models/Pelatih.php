<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model; // Ganti ke Authenticatable jika pelatih bisa login sendiri
// use Illuminate\Foundation\Auth\User as Authenticatable;

class Pelatih extends Model // atau Authenticatable
{
    use HasFactory; // Notifiable jika perlu notifikasi

    protected $table = 'pelatih';

    protected $fillable = [
        'nama',
        'perguruan',
        'no_hp',
        'email',
        'password', // Jika pelatih bisa login
        'is_active' // Tambahkan jika ada fitur nonaktifkan
    ];

    protected $hidden = [
        'password', // Jika pelatih bisa login
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed', // Jika pelatih bisa login
            'is_active' => 'boolean',
        ];
    }

    public function kontingens()
    {
        return $this->hasMany(Kontingen::class);
    }
}