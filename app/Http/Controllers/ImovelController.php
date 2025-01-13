<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreImovelRequest;
use App\Models\Arquivo;
use App\Models\Imovel;
use App\Models\Pessoa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
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
        $imoveis = Imovel::with('pessoa:id,nome,cpf')
            ->filter(request(['campo', 'pesquisa']))
            ->paginate(10)
            ->through(fn($imovel) => [
                'inscricao_municipal' => $imovel->inscricao_municipal,
                'tipo' => $imovel->tipo,
                'logradouro' => $imovel->logradouro,
                'numero' => $imovel->numero,
                'bairro' => $imovel->bairro,
                'pessoa' => $imovel->pessoa ? [
                    'nome' => $imovel->pessoa->nome,
                    'cpf' => $imovel->pessoa->cpf,
                ] : null,
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

        if ($request->has('contribuinte')) {
            $data['pessoa_id'] = $request->input('contribuinte');
        }

        $imovel = Imovel::create($data);

        if ($request->hasFile('files')) {
            foreach($request->file('files') as $file) {
                if ($file->isValid()) {
                    $name = $file->getClientOriginalName();
                    $path = $file->store('arquivos', 'public');

                    Arquivo::create([
                        'name' => $name,
                        'path' => $path,
                        'inscricao_municipal_imovel' => $imovel->inscricao_municipal
                    ]);
                }
            }
        }

        return redirect('/imoveis')->with('success_message', 'Imóvel cadastrado com sucesso');
    }

    public function show ($inscricao_municipal) {
        $imovel = Imovel::findOrFail($inscricao_municipal);
        $pessoas = Pessoa::all(['id', 'nome']);
        $arquivos = Arquivo::where('inscricao_municipal_imovel', $inscricao_municipal)->get(['name', 'path']);

        return Inertia::render('Imoveis/VisualizarImovel', ['imovel' => $imovel, 
                                                            'pessoas' => $pessoas, 
                                                            'arquivos' => $arquivos
                                                            ]);
    }

    public function update(StoreImovelRequest $request) {
        $imovel = Imovel::findOrFail($request->inscricao_municipal);
    
        $dadosAtualizados = $request->except('situacao');
    
        if ($request->has('contribuinte')) {
            $dadosAtualizados['pessoa_id'] = $request->input('contribuinte');
        }

        $imovel->update($dadosAtualizados);
        
        // Gera erro ao tentar adicionar um arquivo
        if ($request->hasFile('files')) {
            foreach($request->file('files') as $file) {
                if ($file->isValid()) {
                    $name = $file->getClientOriginalName();
                    $path = $file->store('arquivos', 'public');

                    Arquivo::create([
                        'name' => $name,
                        'path' => $path,
                        'inscricao_municipal_imovel' => $imovel->inscricao_municipal
                    ]);
                }
            }
        }
    
        return redirect('/imoveis')->with('success_message', 'Imóvel atualizado com sucesso');
    }

    public function destroy ($inscricao_municipal) {
        Imovel::findOrFail($inscricao_municipal)->delete();

        return redirect('/imoveis')->with('success_message', 'Imóvel deletado com sucesso');
    }   
}
