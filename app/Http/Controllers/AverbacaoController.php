<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAverbacaoRequest;
use App\Models\Averbacao;
use App\Models\Imovel;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class AverbacaoController extends Controller
{
    public function create ($id) {
        
        $imovel = Imovel::findOrFail($id);

        return Inertia::render('Imoveis/Averbacoes/CadastroAverbacao', [
            'imovel' => $imovel
        ]);
    }

    public function store (StoreAverbacaoRequest $request) {
        $data = $request->validated();

        Averbacao::create($data);

        return redirect('/imovel/' . $request->inscricao_municipal_imovel)->with('success_message', 'Averbação adicionada com sucesso');
    }
}
