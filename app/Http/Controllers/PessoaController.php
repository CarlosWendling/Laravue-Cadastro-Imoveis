<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePessoaRequest;
use App\Models\Pessoa;
use Inertia\Inertia;

class PessoaController extends Controller
{
    public function index () {
        $pessoas = Pessoa::all();

        return Inertia::render('Pessoas/Pessoas', ['pessoas' => $pessoas]);
    }

    public function create () {
        return Inertia::render('Pessoas/CadastroPessoa');
    }

    public function store (StorePessoaRequest $request) {
        
        Pessoa::create($request->validated());

        return redirect('/pessoas');
    }

    public function show ($id) {
        $pessoa = Pessoa::findOrFail($id);

        return Inertia::render('Pessoas/VisualizarPessoa', ['pessoa' => $pessoa]);
    }

    public function update ($id, StorePessoaRequest $request) {

        $pessoa = Pessoa::findOrFail($id);

        $dadosAtualizados = $request->except('cpf');

        $pessoa->update($dadosAtualizados);

        return redirect('/pessoas');
    }

    public function destroy ($id) {
        Pessoa::findOrFail($id)->delete();

        return redirect('/pessoas');
    }
}
