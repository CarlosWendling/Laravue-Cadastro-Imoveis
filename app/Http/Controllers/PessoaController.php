<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePessoaRequest;
use App\Models\Imovel;
use App\Models\Pessoa;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PessoaController extends Controller
{
    public function index () {
        $filtros = ['Nome', 'Cpf', 'Data de Nascimento', 'Sexo'];
        $routeName = Route::currentRouteName();

        $pessoas = Pessoa::
            filter(request(['campo', 'pesquisa']))
            ->paginate(10)
            ->through(fn($pessoa) => [
                'id' => $pessoa->id,
                'nome' => $pessoa->nome,
                'cpf' => $pessoa->cpf,
                'sexo' => $pessoa->sexo,
                'data_nascimento' => $pessoa->data_nascimento,
                'email' => $pessoa->email,
                'telefone' => $pessoa->telefone
            ])
            ->withQueryString();

        return Inertia::render('Pessoas/Pessoas', ['pessoas' => $pessoas, 'filtros' => $filtros, 'routeName' => $routeName]);
    }

    public function create () {
        return Inertia::render('Pessoas/CadastroPessoa');
    }

    public function store (StorePessoaRequest $request) {
        
        Pessoa::create($request->validated());

        return redirect('/pessoas')->with('success_message', 'Pessoa cadastrada com sucesso');
    }

    public function show ($id) {
        $pessoa = Pessoa::findOrFail($id);

        return Inertia::render('Pessoas/VisualizarPessoa', ['pessoa' => $pessoa]);
    }

    public function update (StorePessoaRequest $request) {

        $pessoa = Pessoa::findOrFail($request->id);

        $dadosAtualizados = $request->except('cpf');

        $pessoa->update($dadosAtualizados);

        return redirect('/pessoas')->with('success_message', 'Pessoa atualizada com sucesso');
    }

    public function destroy ($id) {
        $pessoa = Pessoa::findOrFail($id);

        if ($pessoa->imoveis->isEmpty()) {
            $pessoa->delete();
            return redirect('/pessoas')->with('success_message', 'Pessoa excluída com sucesso');
        } else {
            $pessoa->delete();
            return redirect('/pessoas')->with('success_message', 'Pessoa e seus imóveis excluídos com sucesso');
        }
    }
}
