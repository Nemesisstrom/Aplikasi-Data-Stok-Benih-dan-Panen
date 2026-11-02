<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StokBenih extends Model
{
    use HasFactory;

    protected $table = 'stok_benihs';

    protected $fillable = [
        'satker_id',
        'varietas_id',
        'komoditas',
        'kelas_benih',
        'status_stok',
        'volume_ton',
        'estimasi_panen',
        'tanggal_laporan',
        'catatan',
    ];

    protected $casts = [
        'volume_ton' => 'float',
        'tanggal_laporan' => 'date',
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
     * Scope filter berdasarkan status stok
     */
    public function scopeStatusStok($query, string $status)
    {
        return $query->where('status_stok', $status);
    }

    /**
     * Scope filter berdasarkan kelas benih (FS / SS)
     */
    public function scopeKelasBenih($query, string $kelas)
    {
        return $query->where('kelas_benih', $kelas);
    }
}
