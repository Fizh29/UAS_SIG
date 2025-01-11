<?php

namespace App\Http\Controllers;
use App\Models\Provinsi;
use Illuminate\Http\Request;

class PetaController extends Controller
{
    public function index(){
        $list_provinsi = Provinsi::all();
        return view('peta',[
            'list_provinsi' => $list_provinsi
        ]);
    }
}