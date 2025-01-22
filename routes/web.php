<?php

use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\AverbacaoController;
use App\Http\Controllers\ImovelController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\PessoaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UsuarioController;
use App\Models\Imovel;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Symfony\Component\HttpKernel\Profiler\Profile;

Route::get('/', function () {
    return redirect('login');
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
    Route::put('/imovel/update', [ImovelController::class, 'update'])
        ->middleware([HandlePrecognitiveRequests::class])
        ->name('imovel.update');
    Route::delete('imovel/destroy/{inscricao_municipal}', [ImovelController::class, 'destroy'])->name('imovel.destroy');

    // Arquivos
    Route::post('/arquivos/store', [ImovelController::class, 'arquivosStore'])
        ->middleware([HandlePrecognitiveRequests::class])
        ->name('arquivos.store');
    Route::delete('/arquivos/destroy/{id}', [ImovelController::class, 'arquivosDestroy'])->name('arquivos.destoy');
    Route::get('/arquivos/download/{file}', [ImovelController::class, 'arquivosDownload'])->where('path', '.*')->name('arquivos.download');

    // Averbações
    Route::get('/imovel/averbacoes/{id}', [AverbacaoController::class, 'create'])->name('averbacao.create');
    Route::post('/imovel/averbacoes/store', [AverbacaoController::class, 'store'])
        ->middleware([HandlePrecognitiveRequests::class])
        ->name('averbacao.store');
    Route::get('/imovel/averbacao/{id}', [AverbacaoController::class, 'show'])->name('averbacao.show');


    Route::get('/pdf/download/relatorio-sintetico', [PdfController::class, 'downloadRelatorioSintetico'])->name('pdf.download');


    Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios');
    Route::get('/usuarios/nova-senha/{id}', [PasswordController::class, 'show'])->name('password.show');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
