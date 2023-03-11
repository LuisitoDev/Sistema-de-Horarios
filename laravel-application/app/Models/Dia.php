<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Dia extends Model
{
    protected $table = 'dias';
    protected $fillable = [
        'id',
        'nombre'
    ];

    public function turnoDiario() : HasOne {
        return $this->hasOne(TurnoDiario::class, 'dia', 'id');
    }
}
