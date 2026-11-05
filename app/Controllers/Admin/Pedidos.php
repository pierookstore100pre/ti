<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\VentaDetalleModel;
use App\Models\VentaModel;

/** Gestión de pedidos y cambio de estado (RF15, RF16, HU06) */
class Pedidos extends BaseController
{
    private const ROLES = ['SuperAdmin', 'Administrador', 'Vendedor', 'Repartidor'];

    /** Estados a los que puede mover cada rol */
    private const PERMITIDOS = [
        'SuperAdmin'    => ['Pendiente', 'Pagado', 'Enviado', 'Entregado', 'Cancelado'],
        'Administrador' => ['Pendiente', 'Pagado', 'Enviado', 'Entregado', 'Cancelado'],
        'Vendedor'      => ['Pagado', 'Cancelado'],
        'Repartidor'    => ['Enviado', 'Entregado'],
    ];

    public function index()
    {
        if ($r = $this->exigirRol(self::ROLES)) {
            return $r;
        }
        $rol    = $this->usuario()['rol'];
        $filtro = (string) $this->request->getGet('estado');

        $ventas = new VentaModel();
        $ventas->select('ventas.*, clientes.nombres, clientes.apellidos, clientes.direccion, clientes.telefono')
               ->join('clientes', 'clientes.id = ventas.cliente_id')
               ->orderBy('ventas.id', 'DESC');

        if ($rol === 'Repartidor') {
            $ventas->whereIn('ventas.estado', ['Pagado', 'Enviado']);   // HU06
        } elseif (in_array($filtro, VentaModel::ESTADOS, true)) {
            $ventas->where('ventas.estado', $filtro);
        }

        $pedidos = $ventas->findAll();
        $detalle = new VentaDetalleModel();
        foreach ($pedidos as &$p) {
            $p['items'] = $detalle->deVenta($p['id']);
        }

        return $this->vista('admin/pedidos/index', [
            'titulo'     => 'Pedidos',
            'pedidos'    => $pedidos,
            'permitidos' => self::PERMITIDOS[$rol],
            'filtro'     => $filtro,
        ]);
    }

    public function cambiarEstado(int $id)
    {
        if ($r = $this->exigirRol(self::ROLES)) {
            return $r;
        }
        $rol   = $this->usuario()['rol'];
        $nuevo = (string) $this->request->getPost('estado');

        if (! in_array($nuevo, self::PERMITIDOS[$rol], true)) {
            return redirect()->back()->with('error', 'No puedes asignar ese estado.');
        }

        $ventas = new VentaModel();
        $venta  = $ventas->find($id);
        if (! $venta) {
            return redirect()->back()->with('error', 'Pedido no encontrado.');
        }

        // El repartidor solo avanza Pagado→Enviado y Enviado→Entregado
        if ($rol === 'Repartidor') {
            $valido = ($venta['estado'] === 'Pagado' && $nuevo === 'Enviado')
                   || ($venta['estado'] === 'Enviado' && $nuevo === 'Entregado');
            if (! $valido) {
                return redirect()->back()->with('error', 'Transición de estado no permitida.');
            }
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $cambios = ['estado' => $nuevo];
        if ($nuevo === 'Entregado') {
            $cambios['fecha_entrega'] = date('Y-m-d H:i:s');   // HU06: registra fecha y hora
        }
        $ventas->update($id, $cambios);

        // Si se cancela, se devuelve el stock de los productos que no son "A Pedido"
        if ($nuevo === 'Cancelado' && $venta['estado'] !== 'Cancelado') {
            foreach ((new VentaDetalleModel())->deVenta($id) as $d) {
                $db->table('productos')
                   ->set('stock', 'stock + ' . (int) $d['cantidad'], false)
                   ->where('id', $d['producto_id'])
                   ->where('a_pedido', 0)
                   ->update();
            }
        }

        $db->transComplete();

        return redirect()->back()->with('exito', 'Pedido #' . $id . ' actualizado a "' . $nuevo . '".');
    }
}
