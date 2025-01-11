<?php

namespace App\Http\Controllers;
use App\Models\Provinsi;
use Illuminate\Http\Request;
use App\Models\DataDaerah; // Pastikan namespace ini ada
class MapController extends Controller
{
    public function index()
    {
        $data_daerah = DataDaerah::all();
        return view('data-map', compact('data_daerah'));
    }
}
