<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductoModel extends Model
{
    protected $table         = 'productos';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = [
        'categoria_id', 'marca_id', 'nombre', 'descripcion', 'descripcion_larga',
        'precio', 'stock', 'a_pedido', 'imagen', 'activo',
    ];

    /**
     * Consulta base con categoría y marca. Usa Query Builder (RNF02: evita inyección SQL).
     * Uso: $model->conFiltros($q, $cat, $marca)->paginate(12)
     */
    public function conFiltros(string $q = '', int $categoria = 0, int $marca = 0, bool $soloActivos = true): self
    {
        $this->select('productos.*, categorias.nombre AS categoria, marcas.nombre AS marca')
             ->join('categorias', 'categorias.id = productos.categoria_id')
             ->join('marcas', 'marcas.id = productos.marca_id', 'left');

        if ($soloActivos) {
            $this->where('productos.activo', 1)->where('categorias.activo', 1);
        }
        if ($q !== '') {
            $this->groupStart()
                    ->like('productos.nombre', $q)
                    ->orLike('productos.descripcion', $q)
                 ->groupEnd();
        }
        if ($categoria > 0) {
            $this->where('productos.categoria_id', $categoria);
        }
        if ($marca > 0) {
            $this->where('productos.marca_id', $marca);
        }
        return $this->orderBy('productos.nombre', 'ASC');
    }

    /** Productos activos con stock crítico (HU03) */
    public function stockCritico(): array
    {
        return $this->where('activo', 1)
                    ->where('a_pedido', 0)
                    ->where('stock <=', STOCK_CRITICO)
                    ->orderBy('stock', 'ASC')
                    ->findAll();
    }
}
