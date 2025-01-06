<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Imovel extends Model
{
    protected $table = 'imoveis';
    
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
}
