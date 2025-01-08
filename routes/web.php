<?php

use App\Http\Controllers\ImovelController;
use App\Http\Controllers\PessoaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Symfony\Component\HttpKernel\Profiler\Profile;

Route::get('/', function () {
    return redirect('register');
});

Route::middleware('auth')->group(function () {
    Route::get('/home', function () { return Inertia::render('Home'); })->name('home');

    Route::get('/pessoas', [PessoaController::class, 'index'])->name('pessoas');
    Route::get('/pessoas/cadastro', [PessoaController::class, 'create'])->name('pessoas.cadastro');
    Route::post('/pessoas/store', [PessoaController::class, 'store'])
        ->middleware([HandlePrecognitiveRequests::class])
        ->name('pessoas.store');
    Route::get('/pessoa/{id}', [PessoaController::class, 'show'])->name('pessoa.show');
    Route::put('/pessoa/update', [PessoaController::class, 'update'])
        ->middleware([HandlePrecognitiveRequests::class])
        ->name('pessoa.update');
    Route::delete('/pessoa/destroy/{id}', [PessoaController::class, 'destroy'])->name('pessoa.destroy');

    
    Route::get('/imoveis', [ImovelController::class, 'index'])->name('imoveis');
    Route::get('/imoveis/cadastro', [ImovelController::class, 'create'])->name('imoveis.cadastro');
    Route::post('/imoveis/store', [ImovelController::class, 'store'])
        ->middleware([HandlePrecognitiveRequests::class])
        ->name('imoveis.store');
    Route::get('/imovel/{inscricao_municipal}', [ImovelController::class, 'show'])->name('imovel.show');
    Route::put('/imovel/update/{inscricao_municipal}', [ImovelController::class, 'update'])
        ->middleware([HandlePrecognitiveRequests::class])
        ->name('imovel.update');
    Route::delete('imovel/destroy/{inscricao_municipal}', [ImovelController::class, 'destroy'])->name('imovel.destroy');


    Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
