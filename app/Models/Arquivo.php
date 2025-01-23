<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Arquivo extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    
    protected $fillable = [
        'name',
        'path',
        'inscricao_municipal_imovel'
    ];

    public function imovel() {
        return $this->belongsTo(Imovel::class, 'inscricao_municipal_imovel');
    }
}
