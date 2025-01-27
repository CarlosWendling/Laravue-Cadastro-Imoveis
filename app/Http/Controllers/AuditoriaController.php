<?php

namespace App\Http\Controllers;

use App\Models\Auditoria;
use App\Models\User;
use Inertia\Inertia;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;

class AuditoriaController extends Controller
{  
    public function index () {
        $filtros = [
            'Usuário',
            'Evento',
            'Data',
            'Tabela'
        ];
        $routeName = Route::currentRouteName();
        $usuarios = User::select('id', 'name')->get();

        $auditorias = Auditoria::with('user:id,name')
            ->filter(request(['campo', 'pesquisa']))
            ->orderByDesc('created_at')
            ->paginate(10)
            ->through(fn($auditoria) => [
                'id' => $auditoria->id, 
                'user' => $auditoria->user ? [
                    'id' => $auditoria->user->id,
                    'name' => $auditoria->user->name,
                ] : null,
                'evento' => $auditoria->event, 
                'created_at' => Carbon::parse($auditoria->created_at)->format('d/m/Y H:i:s'), 
                'tabela' => $auditoria->auditable_type, 
                'id_auditado' => $auditoria->auditable_id, 
            ])
            ->withQueryString();

        return Inertia::render('Auditoria/Home', [
            'auditorias' => $auditorias,
            'filtros' => $filtros,
            'routeName' => $routeName,
            'usuarios' => $usuarios
        ]);
    }

    public function show ($id) {
        $auditoria = Auditoria::with('user:id,name')->findOrFail($id);

        $dados_auditoria = [
            'id' => $auditoria->id,
            'data' => Carbon::parse($auditoria->created_at)->format('d/m/Y H:i:s'),
            'user' => $auditoria->user ? [
                    'id' => $auditoria->user->id,
                    'name' => $auditoria->user->name,
                ] : null,
            'evento' => $auditoria->event,
            'tabela' => $auditoria->auditable_type,
            'id_auditado' => $auditoria->auditable_id,
            'dados_anteriores' => $auditoria->old_values,
            'dados_novos' => $auditoria->new_values,
            'url' => $auditoria->url,
            'ip' => $auditoria->ip_address,
        ];

        $dados_anteriores = json_decode($dados_auditoria['dados_anteriores'], true);
        $dados_novos = json_decode($dados_auditoria['dados_novos'], true);

        return Inertia::render('Auditoria/VisualizarAuditoria', [
            'auditoria' => $dados_auditoria,
            'dados_anteriores' => $dados_anteriores,
            'dados_novos' => $dados_novos
        ]);
    }
}
