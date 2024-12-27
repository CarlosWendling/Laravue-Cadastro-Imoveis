<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePessoaRequest;
use App\Models\Pessoa;
use Inertia\Inertia;

class PessoaController extends Controller
{
    public function index () {
        $pessoas = Pessoa::paginate(10)->through(fn($pessoa) => [
            'id' => $pessoa->id,
            'nome' => $pessoa->nome,
            'cpf' => $pessoa->cpf,
            'sexo' => $pessoa->sexo,
            'data_nascimento' => $pessoa->data_nascimento,
            'email' => $pessoa->email,
            'telefone' => $pessoa->telefone
        ]);

        return Inertia::render('Pessoas/Pessoas', ['pessoas' => $pessoas]);
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

    public function update ($id, StorePessoaRequest $request) {

        $pessoa = Pessoa::findOrFail($id);

        $dadosAtualizados = $request->except('cpf');

        $pessoa->update($dadosAtualizados);

        return redirect('/pessoas')->with('success_message', 'Pessoa atualizada com sucesso');
    }

    public function destroy ($id) {
        Pessoa::findOrFail($id)->delete();

        return redirect('/pessoas')->with('success_message', 'Pessoa excluída com sucesso');
    }
}
