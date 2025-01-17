<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUsuarioRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        Gate::authorize('cadastro-usuario');

        $usuarioAtual = auth()->user()->only([
            'id', 
            'perfil'
        ]);

        return Inertia::render('Auth/Register', [
            'usuarioAtual' => $usuarioAtual
        ]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(StoreUsuarioRequest $request): RedirectResponse
    {
        $request->validated();

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'cpf' => $request->cpf,
            'perfil' => $request->perfil,
            'ativo' => $request->ativo,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect('/usuarios')->with('success_message', 'Usuário criado com sucesso');
    }
}
