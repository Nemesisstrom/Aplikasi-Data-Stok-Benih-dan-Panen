<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\StokBenih;
use App\Models\CatatanPanen;

class Satker extends Model
{
    use HasFactory;

    protected $table = 'satkers';

    protected $fillable = [
        'kode_satker',
        'nama_satker',
        'provinsi',
    ];

    /**
     * Relasi ke Stok Benih (Satu Satker memiliki banyak catatan stok benih)
     */
    public function stokBenihs(): HasMany
    {
        return $this->hasMany(StokBenih::class, 'satker_id');
    }

    /**
     * Relasi ke Catatan Panen (Satu Satker memiliki banyak catatan hasil panen)
     */
    public function catatanPanens(): HasMany
    {
        return $this->hasMany(CatatanPanen::class, 'satker_id');
    }

    /**
     * Relasi ke User (Satu Satker memiliki banyak pengguna/petugas)
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'satker_id');
    }
}
