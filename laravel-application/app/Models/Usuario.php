<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Usuario extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'usuarios';
    protected $fillable = [
        'nombre',
        'apellido_pat',
        'apellido_mat',
        'matricula',
        'correo_universitario',
        'fecha_creacion',
        'estado',
        'imagen',
        'id_carrera',
        'id_ciclo_escolar',
        'id_rotacion'
    ];



    public function id_carrera(){
        return $this->hasOne(Carrera::class, 'id', 'id_carrera');
    }

    public function id_ciclo_escolar(){
        return $this->hasOne(CicloEscolar::class, 'id', 'id_ciclo_escolar');
    }


    public function id_rotacion(){
        return $this->hasOne(Rotacion::class, 'id', 'id_rotacion');
    }
}
