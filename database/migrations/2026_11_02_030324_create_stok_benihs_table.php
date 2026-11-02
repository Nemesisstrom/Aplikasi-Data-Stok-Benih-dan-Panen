<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('stok_benihs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('satker_id')->constrained('satkers')->onDelete('cascade');
            $table->foreignId('varietas_id')->nullable()->constrained('varietas')->onDelete('set null');
            $table->enum('komoditas', ['padi', 'jagung']);
            $table->enum('kelas_benih', ['FS', 'SS']); // FS: Foundation Seed, SS: Stock Seed
            $table->enum('status_stok', [
                'target',
                'terdistribusi',
                'tersedia_tersertifikasi',
                'proses_sertifikasi',
                'prosesing',
                'persiapan_panen'
            ]);
            $table->decimal('volume_ton', 12, 3)->default(0);
            $table->string('estimasi_panen')->nullable(); // String untuk format fleksibel seperti "Minggu ke 4 Desember"
            $table->date('tanggal_laporan'); // Tanggal cut-off laporan (contoh: 2024-12-12)
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('stok_benihs');
    }
};
