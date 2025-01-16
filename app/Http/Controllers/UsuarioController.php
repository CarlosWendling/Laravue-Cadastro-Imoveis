<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

class UsuarioController extends Controller
{
    function index () {
        $filtros = ['Nome', 'Email', 'Perfil', 'Situação'];
        $routeName = Route::currentRouteName();

        $usuarios = User::
            filter(request(['campo', 'pesquisa']))
            ->paginate(10)
            ->through(fn($usuario) => [
                'id' => $usuario->id,
                'name' => $usuario->name,
                'cpf' => $usuario->cpf,
                'email' => $usuario->email,
                'perfil' => $usuario->perfil,
                'ativo' => $usuario->ativo,
            ])
            ->withQueryString();

        return Inertia::render('Usuarios/Usuarios', [
            'usuarios' => $usuarios,
            'filtros' => $filtros,
            'routeName' => $routeName,
        ]);
    }
}
