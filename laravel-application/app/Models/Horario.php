<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Horario extends Model
{
    use HasFactory;
    
    protected $table = 'horarios';
    protected $fillable = [
        'id_usuario'
    ];

    public function id_usuario(){
        return $this->hasOne(Usuario::class, 'id', 'id_usuario');
    }
}
