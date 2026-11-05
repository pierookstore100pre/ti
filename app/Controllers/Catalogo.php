<?php

namespace App\Controllers;

use App\Models\CategoriaModel;
use App\Models\MarcaModel;
use App\Models\ProductoModel;

/** Catálogo público: no requiere sesión (RF01, HU01) */
class Catalogo extends BaseController
{
    public function index()
    {
        $q     = trim((string) $this->request->getGet('q'));
        $cat   = (int) $this->request->getGet('categoria');
        $marca = (int) $this->request->getGet('marca');

        $productos = new ProductoModel();
        $lista     = $productos->conFiltros($q, $cat, $marca)->paginate(12);

        return $this->vista('catalogo/index', [
            'titulo'     => 'Catálogo',
            'productos'  => $lista,
            'pager'      => $productos->pager,
            'categorias' => (new CategoriaModel())->where('activo', 1)->orderBy('nombre')->findAll(),
            'marcas'     => (new MarcaModel())->where('activo', 1)->orderBy('nombre')->findAll(),
            'q' => $q, 'cat' => $cat, 'marca' => $marca,
        ]);
    }

    public function detalle(int $id)
    {
        $producto = (new ProductoModel())->conFiltros()->where('productos.id', $id)->first();
        if (! $producto) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Producto no encontrado');
        }
        return $this->vista('catalogo/detalle', [
            'titulo'   => $producto['nombre'],
            'producto' => $producto,
        ]);
    }
}
