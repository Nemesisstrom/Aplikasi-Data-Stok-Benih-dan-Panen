<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Provinsi extends Model
{
    use HasFactory;

    protected $table = 'provinsis';

    protected $fillable = [
        'kode_provinsi',
        'nama_provinsi',
        'ibu_kota',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'provinsi_id');
    }
}
