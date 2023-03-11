<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Administrador extends Model
{
    use HasFactory;

    protected $table = 'administradores';
    protected $fillable = [
        'id',
        'username',
        'password'
    ];

    public function format()
    {
        return [
            'id_admin' => $this->id,
            'name' => $this->name,
            'created_by' => $this->created_by,
            'last_updated' => $this->last_updated
        ];
        
    }
}
