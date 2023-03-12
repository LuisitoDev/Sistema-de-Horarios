<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Administrador extends Model
{
    use HasFactory;

    public const table_name = "administradores";
    public const id = "id";
    public const username = "username";
    public const password = "password";

    protected $table = self::table_name;
    protected $fillable = [
        self::id,
        self::username,
        self::password
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
