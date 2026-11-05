<?php

namespace App\Models;

use CodeIgniter\Model;

class VentaDetalleModel extends Model
{
    protected $table         = 'venta_detalle';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = ['venta_id', 'producto_id', 'variante_id', 'cantidad', 'precio_unitario', 'subtotal'];

    public function deVenta(int $ventaId): array
    {
        return $this->select('venta_detalle.*, productos.nombre')
                    ->join('productos', 'productos.id = venta_detalle.producto_id')
                    ->where('venta_id', $ventaId)
                    ->findAll();
    }
}
