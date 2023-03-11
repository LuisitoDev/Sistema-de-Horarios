<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsuarioServicio extends Model
{
    use HasFactory;
    protected $table = 'usuarios_servicios';
    protected $fillable = [
        'id_usuario',
        'id_servicio'
    ];

}
