<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CatatanPanen extends Model
{
    use HasFactory;

    protected $table = 'catatan_panens';

    protected $fillable = [
        'satker_id',
        'varietas_id',
        'user_id',
        'komoditas',
        'luas_lahan_hektar',
        'jumlah_panen_ton',
        'tanggal_panen',
        'catatan',
    ];

    protected $casts = [
        'luas_lahan_hektar' => 'float',
        'jumlah_panen_ton' => 'float',
        'tanggal_panen' => 'date',
    ];

    /**
     * Relasi balik ke Satker
     */
    public function satker(): BelongsTo
    {
        return $this->belongsTo(Satker::class, 'satker_id');
    }

    /**
     * Relasi balik ke Varietas
     */
    public function varietas(): BelongsTo
    {
        return $this->belongsTo(Varietas::class, 'varietas_id');
    }

    /**
     * Relasi ke User yang menginput data
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Accessor untuk menghitung produktivitas (Ton / Hektar)
     */
    public function getProduktivitasAttribute(): float
    {
        if ($this->luas_lahan_hektar > 0) {
            return round($this->jumlah_panen_ton / $this->luas_lahan_hektar, 2);
        }
        return 0;
    }
}
