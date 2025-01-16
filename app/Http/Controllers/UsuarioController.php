<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UsuarioController extends Controller
{
    function index () {
        $usuarios = User::all();

        return Inertia::render('Usuarios/Usuarios', [
            'usuarios' => $usuarios,
        ]);
    }
}
