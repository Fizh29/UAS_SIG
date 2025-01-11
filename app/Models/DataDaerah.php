<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class DataDaerah extends Model
{
    use HasFactory;

    // Tambahkan properti jika perlu
    protected $table = 'data_daerah'; // Nama tabel di database
    protected $fillable = [
        'kota', 
        'lat', 
        'long', 
        'umur_harapan_hidup', 
        'tingkat_partisipasi_angkatan_kerja', 
        'tingkat_pengangguran_terbuka'
    ];
}
