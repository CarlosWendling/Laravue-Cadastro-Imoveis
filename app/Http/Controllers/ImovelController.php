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

        $pessoas = Pessoa::select('id', 'nome')->get();

        // Carregar imóveis com o contribuinte (pessoa associada)
        $imoveis = Imovel::with('pessoa:id,nome')
            ->filter(request(['campo', 'pesquisa']))
            ->paginate(10)
            ->through(fn($imovel) => [
                'inscricao_municipal' => $imovel->inscricao_municipal,
                'tipo' => $imovel->tipo,
                'logradouro' => $imovel->logradouro,
                'numero' => $imovel->numero,
                'bairro' => $imovel->bairro,
                'pessoa' => $imovel->pessoa,
                'situacao' => $imovel->situacao,
            ])
            ->withQueryString();;

        return Inertia::render('Imoveis/Imoveis', 
            [
                'imoveis' => $imoveis, 
                'filtros' => $filtros, 
                'routeName' => $routeName, 
                'pessoas' => $pessoas
            ]);
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
