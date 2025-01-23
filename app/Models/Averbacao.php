<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Averbacao extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    
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
