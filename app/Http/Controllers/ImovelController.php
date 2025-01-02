<?php

namespace App\Http\Controllers;

use App\Models\Imovel;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ImovelController extends Controller
{
    function index () {
        $imoveis = Imovel::all();

        return Inertia::render('Imoveis/Imoveis', ['imoveis' => $imoveis]);
    }
}
