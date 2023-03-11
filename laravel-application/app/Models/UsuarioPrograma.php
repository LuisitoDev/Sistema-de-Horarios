<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsuarioPrograma extends Model
{
    use HasFactory;

    //TODO: RELACION - POR QUE EL id_usuario ES PRIMARY KEY, NO DEBERÍA SER LLAVE COMPUESTA? asi como "usuarios_servicios"
    protected $table = 'usuarios_programas';
    protected $fillable = [
        'id_usuario',
        'id_programa'
    ];
}
