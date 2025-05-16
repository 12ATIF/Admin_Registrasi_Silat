<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class KategoriLomba extends Model
{
    use HasFactory, LogsActivity;
    protected $table = 'kategori_lomba';
    protected $fillable = ['nama'];

    public function subkategoriLombas()
    {
        return $this->hasMany(SubkategoriLomba::class, 'kategori_id');
    }
}