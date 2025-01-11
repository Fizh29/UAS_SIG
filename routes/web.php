<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ProvinsiController;

use App\Http\Controllers\PetaController;
use App\Http\Controllers\MapController;
// Route untuk menampilkan halaman welcome (form input)
Route::get('/', function () {
    return view('welcome');  // Menampilkan view welcome.blade.php
});


Route::get('/map', [MapController::class, 'index'])->name('map');

// Route untuk menangani pengiriman form provinsi (POST request)
Route::post('/provinsis', [ProvinsiController::class, 'store'])->name('provinsis.store');

// Route::get('/gempa', function () {
//     return view('gempa');
// });
Route::get('/gempa', function () {
    return view('gempa'); // Pastikan file `gempa.blade.php` ada di folder resources/views
})->name('gempa');

// Route untuk Tingkat Partisipasi Angkatan Kerja
Route::get('/tingkat-partisipasi', function () {
    return view('tingkat_partisipasi'); // File blade: tingkat_partisipasi.blade.php
})->name('tingkat-partisipasi');

Route::get('/tingkat-pengangguran', function () {
    return view('tingkat_pengangguran'); // File blade: tingkat_pengangguran.blade.php
})->name('tingkat-pengangguran');

Route::get('/', [PetaController::class, 'index']); // Route untuk halaman utama
Route::get('/peta', [PetaController::class, 'index']); // Route untuk halaman peta
Route::get('/umur-harapan', function () {
    return view('umurharapan'); // File blade: umurharapan.blade.php
})->name('umur-harapan');


Route::get('/all-geojson-with-data', function () {
    $folderPath = base_path(); // Root folder Laravel (C:\xampp\htdocs\sig01)
    $files = [
        'kab-bangka-barat.geojson',
        'kab-bangka-selatan.geojson',
        'kab-bangka-tengah.geojson',
        'kab-belitung.geojson',
        'kab-bangka.geojson',
        'kab-belitung-timur.geojson',
        'kota-pangkal-pinang.geojson'
    ];

    $allFeatures = []; // Array untuk menyimpan semua fitur

    foreach ($files as $file) {
        $geojsonFile = file_get_contents($folderPath . '/' . $file); // Path lengkap
        $geojson = json_decode($geojsonFile, true);

        // Ambil data dari database
        $dbData = DB::table('data_daerah')->get();

        // Gabungkan data dari database ke GeoJSON
        foreach ($geojson['features'] as &$feature) {
    // Normalisasi nama GeoJSON
    $name = strtolower(trim(str_replace(['kabupaten', 'kota'], '', $feature['properties']['name'])));

    $matchingData = $dbData->first(function ($data) use ($name) {
        // Normalisasi nama dari database
        $dbName = strtolower(trim(str_replace(['kabupaten', 'kota'], '', $data->kota)));
        return $dbName === $name;
    });

    if ($matchingData) {
        // Tambahkan data dari database ke properti GeoJSON
        $feature['properties']['umur_harapan_hidup'] = $matchingData->umur_harapan_hidup;
        $feature['properties']['tingkat_partisipasi_angkatan_kerja'] = $matchingData->tingkat_partisipasi_angkatan_kerja;
        $feature['properties']['tingkat_pengangguran_terbuka'] = $matchingData->tingkat_pengangguran_terbuka;
    } else {
        // Jika tidak ada kecocokan, tambahkan nilai default
        $feature['properties']['umur_harapan_hidup'] = null;
        $feature['properties']['tingkat_partisipasi_angkatan_kerja'] = null;
        $feature['properties']['tingkat_pengangguran_terbuka'] = null;
    }

    $allFeatures[] = $feature; // Tambahkan fitur ke array
}

    }

    // Buat GeoJSON baru dengan semua fitur
    $mergedGeojson = [
        'type' => 'FeatureCollection',
        'features' => $allFeatures
    ];

    return response()->json($mergedGeojson); // Kembalikan data dalam format JSON
})->name('geojson-data');
