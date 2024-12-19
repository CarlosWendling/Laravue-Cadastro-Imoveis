<?php

namespace App\Http\Controllers;

use App\Models\Pessoa;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PessoaController extends Controller
{
    function index () {
        return Inertia::render('Pessoas/Pessoas');
    }

    function create () {
        return Inertia::render('Pessoas/CadastroPessoa');
    }

    function store (Request $request) {
        $pessoa = new Pessoa;

        $pessoa->nome = $request->nome;
        $pessoa->cpf = $request->cpf;
        $pessoa->data_nascimento = $request->dataNascimento;
        $pessoa->sexo = $request->sexo;
        $pessoa->email = $request->email;
        $pessoa->telefone = $request->telefone;

        $pessoa->save();

        return redirect('/pessoas');
    }
}
