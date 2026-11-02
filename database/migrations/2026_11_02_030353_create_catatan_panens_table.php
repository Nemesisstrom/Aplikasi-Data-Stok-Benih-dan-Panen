<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('catatan_panens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('satker_id')->constrained('satkers')->onDelete('cascade');
            $table->foreignId('varietas_id')->nullable()->constrained('varietas')->onDelete('set null');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('komoditas', ['padi', 'jagung']);
            $table->decimal('luas_lahan_hektar', 10, 2);
            $table->decimal('jumlah_panen_ton', 12, 3);
            $table->date('tanggal_panen');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('catatan_panens');
    }
};
