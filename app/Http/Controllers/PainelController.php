<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PainelController extends Controller
{
    public function Inicio() {
        return view("inicio.index");
    }
}
