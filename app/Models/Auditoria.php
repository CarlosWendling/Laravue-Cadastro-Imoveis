<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Auditoria extends Model
{
    protected $table = 'audits';

    public function user() {
        return $this->belongsTo(User::class);
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
