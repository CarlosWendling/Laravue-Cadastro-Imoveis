<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreImovelRequest;
use App\Models\Imovel;
use App\Models\Pessoa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

class ImovelController extends Controller
{
    public function index () {
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

    public function create () {
        $pessoas = Pessoa::all(['id', 'nome']);

        return Inertia::render('Imoveis/CadastroImovel', ['pessoas' => $pessoas]);
    }

    public function store (StoreImovelRequest $request) {
        $data = $request->validated();

        $data['pessoa_id'] = $data['contribuinte'];
        unset($data['contribuinte']);

        Imovel::create($data);

        return redirect('/imoveis')->with('success_message', 'Imóvel cadastrado com sucesso');
    }
}
