<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ProductoModel;
use App\Models\VentaModel;

/** Panel principal del personal. Muestra "Stock Crítico" (HU03) */
class Dashboard extends BaseController
{
    public function index()
    {
        if ($r = $this->exigirRol(self::ROLES_STAFF)) {
            return $r;
        }

        $ventas = new VentaModel();
        return $this->vista('admin/dashboard', [
            'titulo'         => 'Panel de administración',
            'criticos'       => (new ProductoModel())->stockCritico(),
            'totalProductos' => (new ProductoModel())->where('activo', 1)->countAllResults(),
            'pendientes'     => $ventas->where('estado', 'Pendiente')->countAllResults(),
            'porEntregar'    => $ventas->whereIn('estado', ['Pagado', 'Enviado'])->countAllResults(),
        ]);
    }
}
