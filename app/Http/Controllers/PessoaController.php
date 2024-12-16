<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class PessoaController extends Controller
{
    function index () {
        return Inertia::render('Pessoas');
    }
}
