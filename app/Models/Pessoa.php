<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Pessoa extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    
    protected $fillable = [
        'nome',
        'cpf',
        'data_nascimento',
        'sexo',
        'email',
        'telefone'
    ];

    public function imoveis () {
        return $this->hasMany(Imovel::class, 'pessoa_id');
    }

    public function scopeFilter ($query, array $filters) {

        if (!empty($filters['campo']) && !empty($filters['pesquisa'])) {
            $campo = $filters['campo'];
            $pesquisa = $filters['pesquisa'];
            
            $query->where($campo, 'like', '%' . $pesquisa . '%');
        }

        return $query; 
    }
}
