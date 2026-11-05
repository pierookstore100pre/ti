<?php

namespace App\Models;

use CodeIgniter\Model;

class UsuarioModel extends Model
{
    protected $table         = 'usuarios';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = ['nombre', 'email', 'password', 'rol', 'activo'];

    public function porEmail(string $email): ?array
    {
        return $this->where('email', $email)->where('activo', 1)->first();
    }
}
