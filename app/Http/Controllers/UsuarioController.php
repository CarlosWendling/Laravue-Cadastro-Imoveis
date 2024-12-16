<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class UsuarioController extends Controller
{
    function index () {
        return Inertia::render('Usuarios');
    }
}
