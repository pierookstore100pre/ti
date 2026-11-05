<?php

namespace App\Controllers;

use App\Models\ClienteModel;
use App\Models\ProductoModel;
use App\Models\VentaDetalleModel;
use App\Models\VentaModel;

/** Carrito en sesión (solo usuarios autenticados) y generación de pedido (RF05, RF10, HU04) */
class Carrito extends BaseController
{
    private function items(): array
    {
        return $this->session->get('carrito') ?? [];   // [producto_id => cantidad]
    }

    public function index()
    {
        if ($r = $this->exigirLogin()) {
            return $r;
        }

        $lineas = [];
        $total  = 0;
        foreach ($this->items() as $id => $cant) {
            $p = (new ProductoModel())->find($id);
            if (! $p || ! $p['activo']) {
                continue;
            }
            $sub      = $p['precio'] * $cant;
            $total   += $sub;
            $lineas[] = ['producto' => $p, 'cantidad' => $cant, 'subtotal' => $sub, 'estado' => estado_stock($p)];
        }

        return $this->vista('carrito/index', [
            'titulo' => 'Mi carrito',
            'lineas' => $lineas,
            'total'  => $total,
        ]);
    }

    public function agregar(int $id)
    {
        if ($r = $this->exigirLogin()) {
            return $r;
        }

        $p = (new ProductoModel())->find($id);
        if (! $p || ! $p['activo']) {
            return redirect()->back()->with('error', 'El producto no existe.');
        }

        $estado = estado_stock($p);
        if ($estado === 'Agotado') {
            return redirect()->back()->with('error', 'Producto agotado: no se puede añadir al carrito.');
        }

        $cantidad = max(1, (int) $this->request->getPost('cantidad'));
        $carrito  = $this->items();
        $nueva    = ($carrito[$id] ?? 0) + $cantidad;

        // Solo los productos "A Pedido" pueden superar el stock
        if ($estado !== 'A Pedido' && $nueva > (int) $p['stock']) {
            $nueva = (int) $p['stock'];
            $this->session->setFlashdata('error', 'Solo hay ' . $p['stock'] . ' unidades disponibles.');
        }

        $carrito[$id] = $nueva;
        $this->session->set('carrito', $carrito);

        return redirect()->to('carrito')->with('exito', 'Producto añadido al carrito.');
    }

    public function quitar(int $id)
    {
        $carrito = $this->items();
        unset($carrito[$id]);
        $this->session->set('carrito', $carrito);
        return redirect()->to('carrito');
    }

    public function vaciar()
    {
        $this->session->remove('carrito');
        return redirect()->to('carrito');
    }

    /** Convierte el carrito en un pedido: guarda venta + detalle y descuenta stock */
    public function confirmar()
    {
        if ($r = $this->exigirLogin()) {
            return $r;
        }
        $carrito = $this->items();
        if (! $carrito) {
            return redirect()->to('carrito')->with('error', 'Tu carrito está vacío.');
        }

        $metodo = (string) $this->request->getPost('metodo_pago');
        if (! in_array($metodo, ['Yape', 'Plin', 'Transferencia', 'Contra entrega'], true)) {
            return redirect()->to('carrito')->with('error', 'Selecciona un método de pago válido.');
        }

        $productos = new ProductoModel();
        $db        = \Config\Database::connect();
        $total     = 0;
        $detalles  = [];

        $db->transStart();

        foreach ($carrito as $id => $cant) {
            $p = $productos->find($id);
            if (! $p || ! $p['activo']) {
                continue;
            }
            $estado = estado_stock($p);
            if ($estado === 'Agotado' || ($estado !== 'A Pedido' && $cant > (int) $p['stock'])) {
                $db->transRollback();
                return redirect()->to('carrito')->with('error', 'Stock insuficiente para "' . $p['nombre'] . '".');
            }
            $sub        = round($p['precio'] * $cant, 2);
            $total     += $sub;
            $detalles[] = ['producto' => $p, 'cantidad' => $cant, 'subtotal' => $sub, 'a_pedido' => $estado === 'A Pedido'];
        }

        if (! $detalles) {
            $db->transRollback();
            return redirect()->to('carrito')->with('error', 'No hay productos válidos en el carrito.');
        }

        // Los precios incluyen IGV: se desglosa el subtotal (base imponible)
        $subtotal = round($total / (1 + IGV_TASA), 2);
        $igv      = round($total - $subtotal, 2);

        $usuario = $this->usuario();
        $cliente = (new ClienteModel())->deUsuario($usuario);

        $ventaId = (new VentaModel())->insert([
            'cliente_id'  => $cliente['id'],
            'usuario_id'  => $usuario['id'],
            'fecha_venta' => date('Y-m-d H:i:s'),
            'subtotal'    => $subtotal,
            'igv'         => $igv,
            'total'       => $total,
            'estado'      => 'Pendiente',  // HU04: "A Pedido" queda Pendiente hasta confirmar entrega
            'metodo_pago' => $metodo,
        ], true);

        $detalleModel = new VentaDetalleModel();
        foreach ($detalles as $d) {
            $detalleModel->insert([
                'venta_id'        => $ventaId,
                'producto_id'     => $d['producto']['id'],
                'cantidad'        => $d['cantidad'],
                'precio_unitario' => $d['producto']['precio'],
                'subtotal'        => $d['subtotal'],
            ]);
            if (! $d['a_pedido']) {
                $db->table('productos')
                   ->set('stock', 'stock - ' . (int) $d['cantidad'], false)
                   ->where('id', $d['producto']['id'])
                   ->update();
            }
        }

        $db->transComplete();

        if (! $db->transStatus()) {
            return redirect()->to('carrito')->with('error', 'No se pudo registrar el pedido. Intenta nuevamente.');
        }

        $this->session->remove('carrito');
        return redirect()->to('mis-pedidos')->with('exito', 'Pedido #' . $ventaId . ' registrado correctamente.');
    }
}
