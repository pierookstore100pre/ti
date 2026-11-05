<?php

namespace App\Controllers;

use App\Models\VentaDetalleModel;
use App\Models\VentaModel;

/** Historial de pedidos del cliente (RF11) */
class Pedidos extends BaseController
{
    public function mis()
    {
        if ($r = $this->exigirLogin()) {
            return $r;
        }

        $detalle = new VentaDetalleModel();
        $pedidos = (new VentaModel())->delUsuario($this->usuario()['id']);
        foreach ($pedidos as &$p) {
            $p['items'] = $detalle->deVenta($p['id']);
        }

        return $this->vista('pedidos/mis', ['titulo' => 'Mis pedidos', 'pedidos' => $pedidos]);
    }
}
