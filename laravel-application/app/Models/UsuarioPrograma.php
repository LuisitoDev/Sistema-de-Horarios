<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsuarioPrograma extends Model
{
    use HasFactory;

    public const table_name = "usuarios_programas";
    public const id_usuario = "id_usuario";
    public const id_programa = "id_programa";

    //TODO: RELACION - POR QUE EL id_usuario ES PRIMARY KEY, NO DEBERÍA SER LLAVE COMPUESTA? asi como "usuarios_servicios"
    protected $table = self::table_name;
    protected $fillable = [
        self::id_usuario,
        self::id_programa
    ];

    public function format()
    {
        return $this;
    }
}
