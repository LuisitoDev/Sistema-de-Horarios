<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsuarioPrograma extends Model
{
    use HasFactory;

    protected $table = 'usuarios_programas';
    protected $fillable = [
        'id_usuario',
        'id_programa'
    ];

    public function id_usuario(){
        return $this->hasOne(Usuario::class, 'id', 'id_usuario');
    }

    public function id_programa(){
        return $this->hasOne(Programa::class, 'id', 'id_programa');
    }
}
