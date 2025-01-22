<?php

namespace App\Http\Controllers;

use App\Models\Averbacao;
use App\Models\Imovel;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfController extends Controller
{
    public function downloadRelatorioSintetico () {
        $imoveis = Imovel::with('pessoa:id,nome')
            ->get()
            ->map(fn($imovel) => [
                'inscricao_municipal' => $imovel->inscricao_municipal,
                'tipo' => $imovel->tipo,
                'logradouro' => $imovel->logradouro,
                'numero' => $imovel->numero,
                'bairro' => $imovel->bairro,
                'area_terreno' => $imovel->area_terreno,
                'area_edificacao' => $imovel->area_edificacao,
                'pessoa' => $imovel->pessoa ? [
                    'nome' => $imovel->pessoa->nome,
                ] : null,
                'situacao' => $imovel->situacao,
            ])
            ->toArray();

        $pdf = Pdf::loadView('relatorioSintetico', ['imoveis' => $imoveis])
            ->setPaper('a4', 'landscape')
            ->setOption('isRemoteEnabled', true);
        return $pdf->stream('Relatório_Sintético.pdf');
    }

    public function downloadRelatorioAnalitico ($inscricao_municipal) {
        $imovel = Imovel::with('pessoa:id,nome')->findOrFail($inscricao_municipal);
        $averbacoes = Averbacao::where('inscricao_municipal_imovel', $imovel->inscricao_municipal)->get();

        $dados_imovel = [
            'inscricao_municipal' => $imovel->inscricao_municipal,
            'tipo' => $imovel->tipo,
            'logradouro' => $imovel->logradouro,
            'numero' => $imovel->numero,
            'bairro' => $imovel->bairro,
            'area_terreno' => $imovel->area_terreno,
            'complemento' => $imovel->complemento,
            'area_edificacao' => $imovel->area_edificacao,
            'pessoa' => $imovel->pessoa ? [
                'nome' => $imovel->pessoa->nome,
            ] : null,
            'situacao' => $imovel->situacao,
        ];

        $pdf = Pdf::loadView('relatorioAnalitico', ['imovel' => $dados_imovel, 'averbacoes' => $averbacoes])
            ->setOption('isRemoteEnabled', true);

        return $pdf->stream('Relatório_Analítico.pdf');
    }
}
