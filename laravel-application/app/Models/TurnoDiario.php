<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TurnoDiario extends Model
{
    use HasFactory;

    protected $table = 'turnos_diarios';
    protected $fillable = [
        'hora_entrada',
        'hora_salida',
        'dia',
        'id_horario'
    ];

    public function id_horario(){
        return $this->hasOne(Horario::class, 'id', 'id_horario');
    }
}
