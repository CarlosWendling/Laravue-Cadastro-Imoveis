<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Averbacao extends Model
{
    protected $table = 'averbacoes';

    protected $fillable = [
        'evento',
        'medida',
        'descricao',
        'data_averbacao',
        'inscricao_municipal_imovel',
    ];

    public function imovel () {
        return $this->belongsTo(Imovel::class, 'inscricao_municipal');
    }
}
