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

        $imovel = Imovel::where('inscricao_municipal', $request->inscricao_municipal_imovel)->first();

        $evento = $request->input('evento');

        switch ($evento) {
            case 'Cancelamento':
                if ($imovel->situacao == 0) {
                    return redirect('/imovel/' . $request->inscricao_municipal_imovel)->with('error_message', 'Imóvel já está inativo');
                } else {
                    $imovel->situacao = 0;
                    $imovel->save();
                }
                break;
            
            case 'Reativação':
                if ($imovel->situacao == 1) {
                   return redirect('/imovel/' . $request->inscricao_municipal_imovel)->with('error_message', 'Imóvel já está ativo');
                } else {
                    $imovel->situacao = 1;
                    $imovel->save();
                }
                break;
                
            case 'Aumento Área Construída':
                $medida = $request->medida;
                if ($imovel->area_edificacao >= $medida) {
                    return redirect('/imovel/' . $request->inscricao_municipal_imovel)->with('error_message', 'A área deve ser maior que a área cadastrada');
                } else {
                    $imovel->area_edificacao = $medida;
                    $imovel->save();
                }
                break;
                
                case 'Redução Área Construída':
                    $medida = $request->medida;
                    if ($imovel->area_edificacao <= $medida) {
                        return redirect('/imovel/' . $request->inscricao_municipal_imovel)->with('error_message', 'A área deve ser menor que a área cadastrada');
                    } else {
                        $imovel->area_edificacao = $medida;
                        $imovel->save();
                    }
                    break;
        }

        Averbacao::create($data);

        return redirect('/imovel/' . $request->inscricao_municipal_imovel)->with('success_message', 'Averbação adicionada com sucesso');
    }
}
