<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\Model;

/**
 * CRUD genérico para tablas simples (categorías y marcas).
 * RNF05: permite agregar nuevas categorías (ej. "Artefactos") sin tocar la lógica.
 */
abstract class SimpleCrud extends BaseController
{
    protected const ROLES = ['SuperAdmin', 'Administrador'];

    abstract protected function modelo(): Model;
    abstract protected function config(): array;   // titulo, ruta, descripcion(bool)

    public function index()
    {
        if ($r = $this->exigirRol(self::ROLES)) {
            return $r;
        }
        return $this->vista('admin/simple/index', $this->config() + [
            'filas' => $this->modelo()->orderBy('nombre')->findAll(),
        ]);
    }

    public function nuevo()
    {
        if ($r = $this->exigirRol(self::ROLES)) {
            return $r;
        }
        return $this->vista('admin/simple/form', $this->config() + ['fila' => null]);
    }

    public function guardar()
    {
        if ($r = $this->exigirRol(self::ROLES)) {
            return $r;
        }
        if (! $this->validate(['nombre' => 'required|min_length[2]|max_length[100]'])) {
            return redirect()->back()->withInput()->with('errores', $this->validator->getErrors());
        }
        $this->modelo()->insert($this->datos());
        return redirect()->to('admin/' . $this->config()['ruta'])->with('exito', 'Registro creado.');
    }

    public function editar(int $id)
    {
        if ($r = $this->exigirRol(self::ROLES)) {
            return $r;
        }
        $fila = $this->modelo()->find($id);
        if (! $fila) {
            return redirect()->to('admin/' . $this->config()['ruta'])->with('error', 'No encontrado.');
        }
        return $this->vista('admin/simple/form', $this->config() + ['fila' => $fila]);
    }

    public function actualizar(int $id)
    {
        if ($r = $this->exigirRol(self::ROLES)) {
            return $r;
        }
        if (! $this->validate(['nombre' => 'required|min_length[2]|max_length[100]'])) {
            return redirect()->back()->withInput()->with('errores', $this->validator->getErrors());
        }
        $this->modelo()->update($id, $this->datos());
        return redirect()->to('admin/' . $this->config()['ruta'])->with('exito', 'Registro actualizado.');
    }

    public function estado(int $id)
    {
        if ($r = $this->exigirRol(self::ROLES)) {
            return $r;
        }
        $m = $this->modelo();
        if ($f = $m->find($id)) {
            $m->update($id, ['activo' => $f['activo'] ? 0 : 1]);
        }
        return redirect()->back()->with('exito', 'Estado actualizado.');
    }

    protected function datos(): array
    {
        $post  = $this->request->getPost();
        $datos = ['nombre' => trim($post['nombre']), 'activo' => isset($post['activo']) ? 1 : 0];
        if ($this->config()['descripcion']) {
            $datos['descripcion'] = trim($post['descripcion'] ?? '');
        }
        return $datos;
    }
}
