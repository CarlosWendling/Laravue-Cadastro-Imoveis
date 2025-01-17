<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\NewPasswordRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;

class PasswordController extends Controller
{
    /**
     * Mostra o formuário para a nova senha
     */
    public function show($id)
    {
        $user = User::findOrFail($id);

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
