<?php

namespace App\Http\Controllers;

use App\Http\Requests\ArquivoRequest;
use App\Http\Requests\StoreImovelRequest;
use App\Models\Arquivo;
use App\Models\Averbacao;
use App\Models\Imovel;
use App\Models\Pessoa;
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
            ->withQueryString();

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
            foreach ($request->file('files') as $file) {
                $path = $file->store('arquivos', 'public');
                Arquivo::create([
                    'name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'inscricao_municipal_imovel' => $imovel->inscricao_municipal,
                ]);
            }
        }

        return redirect('/imoveis')->with('success_message', 'Imóvel cadastrado com sucesso');
    }

    public function show ($inscricao_municipal) {
        $imovel = Imovel::findOrFail($inscricao_municipal);
        $pessoas = Pessoa::all(['id', 'nome']);
        $averbacoes = Averbacao::where('inscricao_municipal_imovel', $inscricao_municipal)->get();

        $arquivos = Arquivo::where('inscricao_municipal_imovel', $inscricao_municipal)->get(['name', 'path', 'id']);

        return Inertia::render('Imoveis/VisualizarImovel', ['imovel' => $imovel, 
                                                            'pessoas' => $pessoas, 
                                                            'averbacoes' => $averbacoes, 
                                                            'arquivos' => $arquivos
                                                            ]);
    }

    public function update(StoreImovelRequest $request) {
        $imovel = Imovel::findOrFail($request->inscricao_municipal);
    
        $dadosAtualizados = $request->except(['situacao']);
    
        if ($request->has('contribuinte')) {
            $dadosAtualizados['pessoa_id'] = $request->input('contribuinte');
        }
    
        $imovel->update($dadosAtualizados);
    
        return redirect('/imoveis')->with('success_message', 'Imóvel atualizado com sucesso');
    }

    public function destroy ($inscricao_municipal) {
        $imovel = Imovel::findOrFail($inscricao_municipal);

        if ($imovel) {
            if ($imovel->arquivos->isEmpty()) {
                $imovel->delete();
            } else {
                $arquivos = Arquivo::where('inscricao_municipal_imovel', $inscricao_municipal)->get();
                foreach($arquivos as $arquivo) {
                    $path = $arquivo->path;
                    if (Storage::disk('public')->exists($path)) {
                        Storage::disk('public')->delete($path);
                    }
                }
                $imovel->delete();
            }
        }

        return redirect('/imoveis')->with('success_message', 'Imóvel deletado com sucesso');
    }

    // Arquivos
    public function arquivosStore (ArquivoRequest $request) {
        foreach($request->file('files') as $file) {
            if ($file->isValid()) {
                $name = $file->getClientOriginalName();
                $path = $file->store('arquivos', 'public');

                Arquivo::create([
                    'name' => $name,
                    'path' => $path,
                    'inscricao_municipal_imovel' => $request->input('inscricao_municipal_imovel')
                ]);
            }
        }

        return redirect()->back()->with('success_message', 'Arquivo adicionado com sucesso');
    }
    
    
    public function arquivosDestroy ($id) {
        $arquivo = Arquivo::findOrFail($id);
        $path = $arquivo->path;

        // Vai deletar o arquivo e o seu caminho caso ambos existam
        if ($arquivo) {
            if (Storage::disk('public')->exists($path)) {
                $arquivo->delete();
                Storage::disk('public')->delete($path);
            } else {
                return redirect()->back()->with('error_message', 'Arquivo não encontrado');
            }

            return redirect()->back()->with('success_message', 'Arquivo deletado com sucesso');
        }
    }

    public function arquivosDownload (Arquivo $file) {
        $path = $file->path;
        $name = $file->name;

        if(Storage::disk('public')->exists($path)){
            return Storage::disk('public')->download($path, $name);
        } else {
            return response()->json(['message' => 'Arquivo não encontrado'], 404);
        }
    }
}
