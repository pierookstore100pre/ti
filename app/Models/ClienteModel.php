<?php

namespace App\Models;

use CodeIgniter\Model;

class ClienteModel extends Model
{
    protected $table         = 'clientes';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = ['usuario_id', 'nombres', 'apellidos', 'dni_ruc', 'telefono', 'direccion', 'ciudad', 'email'];

    /** Obtiene (o crea si no existe) el cliente asociado a un usuario */
    public function deUsuario(array $usuario): array
    {
        $cliente = $this->where('usuario_id', $usuario['id'])->first();
        if ($cliente) {
            return $cliente;
        }
        $partes = explode(' ', trim($usuario['nombre']), 2);
        $id = $this->insert([
            'usuario_id' => $usuario['id'],
            'nombres'    => $partes[0],
            'apellidos'  => $partes[1] ?? '-',
            'email'      => $usuario['email'],
        ], true);
        return $this->find($id);
    }
}
