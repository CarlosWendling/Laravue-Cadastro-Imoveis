<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePessoaRequest;
use App\Models\Pessoa;
use Inertia\Inertia;

class PessoaController extends Controller
{
    function index () {
        $pessoas = Pessoa::all();

        return Inertia::render('Pessoas/Pessoas', ['pessoas' => $pessoas]);
    }

    function create () {
        return Inertia::render('Pessoas/CadastroPessoa');
    }

    function store (StorePessoaRequest $request) {
        
        Pessoa::create($request->validated());

        return redirect('/pessoas');
    }
}
