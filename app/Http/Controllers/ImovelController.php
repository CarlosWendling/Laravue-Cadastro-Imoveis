<?php

namespace App\Http\Controllers;

use App\Models\Imovel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

class ImovelController extends Controller
{
    function index () {
        $filtros = ['Inscrição Municipal', 
                    'Tipo', 
                    'Logradouro', 
                    'Número', 
                    'Bairro', 
                    'Contribuinte', 
                    'Situação'
                    ];

        $routeName = Route::currentRouteName();

        $imoveis = Imovel::all();

        return Inertia::render('Imoveis/Imoveis', ['imoveis' => $imoveis, 'filtros' => $filtros, 'routeName' => $routeName]);
    }
}
