<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyLongitudeColumnInProvinsiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        // Menambahkan nilai default pada kolom 'longitude'
        Schema::table('provinsi', function (Blueprint $table) {
            $table->double('longitude')->default(0)->change(); // Set default value to 0
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        // Menghapus nilai default dan mengembalikan kolom 'longitude' menjadi nullable
        Schema::table('provinsi', function (Blueprint $table) {
            $table->double('longitude')->nullable()->change(); // Remove default value and set nullable
        });
    }
}
