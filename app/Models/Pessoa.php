<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pessoa extends Model
{
    protected $fillable = [
        'nome',
        'cpf',
        'data_nascimento',
        'sexo',
        'email',
        'telefone'
    ];

    public function scopeFilter ($query, array $filters) {

        if (!empty($filters['campo']) && !empty($filters['pesquisa'])) {
            $campo = $filters['campo'];
            $pesquisa = $filters['pesquisa'];
            
            $query->where($campo, 'like', '%' . $pesquisa . '%');
        }

        return $query;
        
    }
}
