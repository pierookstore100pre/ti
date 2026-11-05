<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ProductoModel;

/** Gestión de stock: entradas y salidas (RF14, HU03) */
class Stock extends BaseController
{
    private const ROLES = ['SuperAdmin', 'Administrador', 'Almacenero'];

    public function index()
    {
        if ($r = $this->exigirRol(self::ROLES)) {
            return $r;
        }
        return $this->vista('admin/stock/index', [
            'titulo'    => 'Gestión de stock',
            'productos' => (new ProductoModel())->where('activo', 1)->orderBy('stock', 'ASC')->findAll(),
        ]);
    }

    public function ajustar(int $id)
    {
        if ($r = $this->exigirRol(self::ROLES)) {
            return $r;
        }
        $tipo     = $this->request->getPost('tipo');
        $cantidad = (int) $this->request->getPost('cantidad');
        $m        = new ProductoModel();
        $p        = $m->find($id);

        if (! $p || $cantidad < 1 || ! in_array($tipo, ['entrada', 'salida'], true)) {
            return redirect()->back()->with('error', 'Datos inválidos.');
        }

        $nuevo = $tipo === 'entrada' ? $p['stock'] + $cantidad : $p['stock'] - $cantidad;
        if ($nuevo < 0) {
            return redirect()->back()->with('error', 'La salida supera el stock disponible (' . $p['stock'] . ').');
        }

        $m->update($id, ['stock' => $nuevo]);
        return redirect()->back()->with('exito', ucfirst($tipo) . ' registrada. Stock de "' . $p['nombre'] . '": ' . $nuevo);
    }
}
