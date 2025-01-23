<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Auditoria extends Model
{
    protected $table = 'audits';

    public function user() {
        return $this->belongsTo(User::class);
    }
}
