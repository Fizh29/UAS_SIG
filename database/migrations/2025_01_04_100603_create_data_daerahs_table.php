<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('data_daerah', function (Blueprint $table) {
            $table->id();
            $table->string('kota', 50);
            $table->decimal('lat', 9, 6);
            $table->decimal('long', 9, 6);
            $table->decimal('umur_harapan_hidup', 4, 2);
            $table->decimal('tingkat_partisipasi_angkatan_kerja', 4, 2);
            $table->decimal('tingkat_pengangguran_terbuka', 4, 2);
            $table->timestamps(); // Optional
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_daerahs');
    }
};
