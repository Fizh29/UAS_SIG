<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Provinsi;  // Pastikan Anda memiliki model Provinsi

class ProvinsiController extends Controller
{
    // Method untuk menangani form submission
    public function store(Request $request)
    {
        // Validasi data yang masuk
        $validated = $request->validate([
            'name' => 'required|max:30',
            'alt_name' => 'required|max:30',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        // Simpan data provinsi ke database
        Provinsi::create([
            'name' => $validated['name'],
            'alt_name' => $validated['alt_name'],
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
        ]);

        // Redirect kembali ke halaman welcome atau halaman lain setelah berhasil
        return redirect()->route('welcome')->with('success', 'Provinsi berhasil ditambahkan!');
    }
}
