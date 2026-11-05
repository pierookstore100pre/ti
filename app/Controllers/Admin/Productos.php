<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CategoriaModel;
use App\Models\MarcaModel;
use App\Models\ProductoModel;

/** CRUD de productos (RF13, HU05). "Eliminar" = desactivar, para no romper ventas históricas. */
class Productos extends BaseController
{
    private const ROLES = ['SuperAdmin', 'Administrador'];

    private function reglas(): array
    {
        return [
            'nombre'       => 'required|min_length[3]|max_length[150]',
            'categoria_id' => 'required|is_not_unique[categorias.id]',
            'marca_id'     => 'permit_empty|is_not_unique[marcas.id]',
            'precio'       => 'required|decimal|greater_than[0]',   // HU05: precio > 0
            'stock'        => 'permit_empty|is_natural',
        ];
    }

    private function formulario(?array $producto)
    {
        return $this->vista('admin/productos/form', [
            'titulo'     => $producto ? 'Editar producto' : 'Nuevo producto',
            'producto'   => $producto,
            'categorias' => (new CategoriaModel())->where('activo', 1)->orderBy('nombre')->findAll(),
            'marcas'     => (new MarcaModel())->where('activo', 1)->orderBy('nombre')->findAll(),
        ]);
    }

    /** Sube la imagen (si hay) a public/uploads y devuelve su nombre */
    private function subirImagen(): ?string
    {
        $file = $this->request->getFile('imagen');
        if (! $file || ! $file->isValid() || $file->hasMoved()) {
            return null;
        }
        $ext = strtolower($file->getExtension());
        if (! in_array($ext, ['jpg', 'jpeg', 'png', 'webp'], true) || $file->getSizeByUnit('mb') > 2) {
            return null;
        }
        $nombre = $file->getRandomName();
        $file->move(FCPATH . 'uploads', $nombre);
        return $nombre;
    }

    private function datos(): array
    {
        $post = $this->request->getPost();
        return [
            'categoria_id'      => (int) $post['categoria_id'],
            'marca_id'          => ($post['marca_id'] ?? '') !== '' ? (int) $post['marca_id'] : null,
            'nombre'            => trim($post['nombre']),
            'descripcion'       => trim($post['descripcion'] ?? ''),
            'descripcion_larga' => trim($post['descripcion_larga'] ?? ''),
            'precio'            => $post['precio'],
            'stock'             => (int) ($post['stock'] ?? 0),
            'a_pedido'          => isset($post['a_pedido']) ? 1 : 0,
            'activo'            => isset($post['activo']) ? 1 : 0,
        ];
    }

    public function index()
    {
        if ($r = $this->exigirRol(self::ROLES)) {
            return $r;
        }
        $m = new ProductoModel();
        return $this->vista('admin/productos/index', [
            'titulo'    => 'Productos',
            'productos' => $m->conFiltros('', 0, 0, false)->paginate(15),
            'pager'     => $m->pager,
        ]);
    }

    public function nuevo()
    {
        if ($r = $this->exigirRol(self::ROLES)) {
            return $r;
        }
        return $this->formulario(null);
    }

    public function guardar()
    {
        if ($r = $this->exigirRol(self::ROLES)) {
            return $r;
        }
        if (! $this->validate($this->reglas())) {
            return redirect()->back()->withInput()->with('errores', $this->validator->getErrors());
        }
        $datos = $this->datos();
        if ($img = $this->subirImagen()) {
            $datos['imagen'] = $img;
        }
        (new ProductoModel())->insert($datos);   // aparece de inmediato en el catálogo (HU05)
        return redirect()->to('admin/productos')->with('exito', 'Producto creado.');
    }

    public function editar(int $id)
    {
        if ($r = $this->exigirRol(self::ROLES)) {
            return $r;
        }
        $p = (new ProductoModel())->find($id);
        if (! $p) {
            return redirect()->to('admin/productos')->with('error', 'Producto no encontrado.');
        }
        return $this->formulario($p);
    }

    public function actualizar(int $id)
    {
        if ($r = $this->exigirRol(self::ROLES)) {
            return $r;
        }
        if (! $this->validate($this->reglas())) {
            return redirect()->back()->withInput()->with('errores', $this->validator->getErrors());
        }
        $datos = $this->datos();
        if ($img = $this->subirImagen()) {
            $datos['imagen'] = $img;
        }
        (new ProductoModel())->update($id, $datos);
        return redirect()->to('admin/productos')->with('exito', 'Producto actualizado.');
    }

    public function estado(int $id)
    {
        if ($r = $this->exigirRol(self::ROLES)) {
            return $r;
        }
        $m = new ProductoModel();
        if ($p = $m->find($id)) {
            $m->update($id, ['activo' => $p['activo'] ? 0 : 1]);
        }
        return redirect()->back()->with('exito', 'Estado del producto actualizado.');
    }
}
