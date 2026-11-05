<?php

namespace App\Models;

use CodeIgniter\Model;

class VentaModel extends Model
{
    protected $table         = 'ventas';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = [
        'cliente_id', 'usuario_id', 'fecha_venta', 'subtotal', 'igv', 'total',
        'estado', 'metodo_pago', 'fecha_entrega',
    ];

    public const ESTADOS = ['Pendiente', 'Pagado', 'Enviado', 'Entregado', 'Cancelado'];

    /** Pedidos de un cliente (por usuario), más recientes primero (RF11) */
    public function delUsuario(int $usuarioId): array
    {
        return $this->select('ventas.*')
                    ->join('clientes', 'clientes.id = ventas.cliente_id')
                    ->where('clientes.usuario_id', $usuarioId)
                    ->orderBy('ventas.id', 'DESC')
                    ->findAll();
    }
}
