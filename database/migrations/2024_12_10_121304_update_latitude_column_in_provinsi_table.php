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
        Schema::table('provinsi', function (Blueprint $table) {
            $table->double('latitude')->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('provinsi', function (Blueprint $table) {
            $table->double('latitude')->nullable(false)->change(); // Anda bisa memilih untuk menghapus default jika diperlukan
        });
    }
};
