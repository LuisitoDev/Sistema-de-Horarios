<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entrada extends Model
{
    use HasFactory;

    protected $table = 'entradas';
    protected $fillable = [
        'hora_entrada_programada',
        'hora_salida_programada',
        'horas_realizadas_programada',
        'hora_entrada',
        'hora_salida',
        'horas_realizadas',
        'reporte_diario',
        'id_status',
        'id_usuario'
    ];

    public function id_status(){
        return $this->hasOne(Status::class, 'id', 'id_status');
    }

    public function id_usuario(){
        return $this->hasOne(Usuario::class, 'id', 'id_usuario');
    }
}
