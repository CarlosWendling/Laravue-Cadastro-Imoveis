<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\NewPasswordRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;
use Illuminate\Support\Facades\Gate;

class PasswordController extends Controller
{
    /**
     * Mostra o formuário para a nova senha
     */
    public function show($id)
    {
        $user = User::findOrFail($id);

        Gate::authorize('editar-usuario', $user);

        return Inertia::render('Usuarios/NovaSenha', [
            'user' => $user,
        ]);
    }

    /**
     * Update the user's password.
     */
    public function update(NewPasswordRequest $request, $id): RedirectResponse
    {
        $validated = $request->validated();
        
        $user = User::findOrFail($id);

        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'A senha atual está incorreta.']);
        }

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success_message', 'Senha alterada com sucesso');
    }
}
