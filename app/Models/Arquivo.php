<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Arquivo extends Model
{
    protected $fillable = [
        'name',
        'path',
        'inscricao_municipal_imovel'
    ];

    public function imovel() {
        return $this->belongsTo('App\Models\Imovel');
    }
}
