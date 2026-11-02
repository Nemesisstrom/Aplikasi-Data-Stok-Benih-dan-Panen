<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('varietas', function (Blueprint $table) {
            $table->id();
            $table->enum('komoditas', ['padi', 'jagung']);
            $table->string('nama_varietas');
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('varietas');
    }
};
