<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateLatitudeLongitudeInProvinsiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('provinsi', function (Blueprint $table) {
            // Mengubah kolom latitude dan longitude menjadi nullable
            $table->double('latitude')->nullable()->change();
            $table->double('longitude')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('provinsi', function (Blueprint $table) {
            // Mengubah kolom latitude dan longitude kembali ke tidak nullable
            $table->double('latitude')->nullable(false)->change();
            $table->double('longitude')->nullable(false)->change();
        });
    }
};
