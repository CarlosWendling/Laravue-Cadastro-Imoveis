<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePessoaRequest;
use App\Models\Pessoa;
use Inertia\Inertia;

class PessoaController extends Controller
{
    function index () {
        return Inertia::render('Pessoas/Pessoas');
    }

    function create () {
        return Inertia::render('Pessoas/CadastroPessoa');
    }

    function store (StorePessoaRequest $request) {
        
        Pessoa::create($request->validated());

        return redirect('/pessoas');
    }
}
