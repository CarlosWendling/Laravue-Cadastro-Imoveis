<?php

namespace App\Http\Controllers;

use App\Models\Auditoria;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;

class AuditoriaController extends Controller
{
    public function index () {
        $auditorias = Auditoria::with('user:id,name')
            ->orderByDesc('created_at')
            ->paginate(10)
            ->through(fn($auditoria) => [
                'id' => $auditoria->id, 
                'user' => $auditoria->user ? [
                    'id' => $auditoria->user->id,
                    'name' => $auditoria->user->name,
                ] : null,
                'evento' => $auditoria->event, 
                'created_at' => $auditoria->created_at, 
                'tabela' => $auditoria->auditable_type, 
                'id_auditado' => $auditoria->auditable_id, 
            ])
            ->withQueryString();

        //dd($auditorias);

        return Inertia::render('Home', [
            'auditorias' => $auditorias
        ]);
    }
}
