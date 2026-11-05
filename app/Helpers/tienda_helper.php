<?php

/**
 * Helper de la tienda. Se carga desde BaseController ($helpers = [..., 'tienda']).
 */

defined('STOCK_CRITICO') || define('STOCK_CRITICO', 5); // <= 5 unidades: "Pocas unidades" / alerta roja (HU03)
defined('IGV_TASA') || define('IGV_TASA', 0.18);        // IGV Perú 18% (los precios ya lo incluyen)

if (! function_exists('estado_stock')) {
    /** Devuelve: Disponible | Pocas unidades | Agotado | A Pedido (RF04, HU03) */
    function estado_stock(array $producto): string
    {
        if ((int) ($producto['a_pedido'] ?? 0) === 1) {
            return 'A Pedido';
        }
        $stock = (int) ($producto['stock'] ?? 0);
        if ($stock <= 0) {
            return 'Agotado';
        }
        return $stock <= STOCK_CRITICO ? 'Pocas unidades' : 'Disponible';
    }
}

if (! function_exists('clase_stock')) {
    function clase_stock(string $estado): string
    {
        return match ($estado) {
            'Disponible'     => 'success',
            'Pocas unidades' => 'warning text-dark',
            'Agotado'        => 'danger',
            default          => 'info text-dark',
        };
    }
}

if (! function_exists('soles')) {
    function soles($valor): string
    {
        return 'S/ ' . number_format((float) $valor, 2);
    }
}

if (! function_exists('clase_estado_pedido')) {
    function clase_estado_pedido(string $estado): string
    {
        return match ($estado) {
            'Pendiente' => 'secondary',
            'Pagado'    => 'primary',
            'Enviado'   => 'info text-dark',
            'Entregado' => 'success',
            default     => 'danger',
        };
    }
}

if (! function_exists('imagen_producto')) {
    function imagen_producto(?string $imagen): string
    {
        return $imagen
            ? base_url('uploads/' . $imagen)
            : 'https://placehold.co/400x300?text=Sin+imagen';
    }
}
