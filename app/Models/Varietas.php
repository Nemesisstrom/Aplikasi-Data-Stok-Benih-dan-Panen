<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\StokBenih;
use App\Models\CatatanPanen;

class Varietas extends Model
{
    use HasFactory;

    protected $table = 'varietas';

    protected $fillable = [
        'komoditas',
        'nama_varietas',
        'deskripsi',
    ];

    /**
     * Relasi ke Stok Benih
     */
    public function stokBenihs(): HasMany
    {
        return $this->hasMany(StokBenih::class, 'varietas_id');
    }

    /**
     * Relasi ke Catatan Panen
     */
    public function catatanPanens(): HasMany
    {
        return $this->hasMany(CatatanPanen::class, 'varietas_id');
    }

    /**
     * Scope filter berdasarkan komoditas (Padi / Jagung)
     */
    public function scopeKomoditas($query, string $komoditas)
    {
        return $query->where('komoditas', $komoditas);
    }
}
