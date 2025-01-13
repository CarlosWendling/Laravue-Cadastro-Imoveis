<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Imovel extends Model
{
    protected $table = 'imoveis';

    protected $primaryKey = 'inscricao_municipal';
    
    protected $fillable = [
        'tipo',
        'area_terreno',
        'area_edificacao',
        'logradouro',
        'numero',
        'bairro',
        'complemento',
        'pessoa_id',
        'situacao'
    ];

    public function pessoa () {
        return $this->belongsTo(Pessoa::class);
    }
    
    public function arquivos () {
        return $this->hasMany(Arquivo::class);
    }
    
    public function scopeFilter ($query, array $filters) {

        if (!empty($filters['campo']) && !empty($filters['pesquisa'])) {
            $campo = $filters['campo'];
            $pesquisa = $filters['pesquisa'];

            if ($campo === 'situacao') {
                // Comparando diretamente se o campo é true/false
                if ($pesquisa === 'true') {
                    $query->where('situacao', true);  // Ativo
                } else if ($pesquisa === 'false') {
                    $query->where('situacao', false);  // Inativo
                }
            } else {
                $query->where($campo, 'like', "%{$pesquisa}%");
            }
        }

        return $query;
    }
}
