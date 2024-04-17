<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PainelController;

Route::group(["prefix" => "", "layout" => "layous.painel"], function() {
    Route::get("/", [PainelController::class, "Inicio"])->name("painel.inicio");
});
