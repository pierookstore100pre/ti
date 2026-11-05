<?php

namespace App\Controllers\Admin;

use App\Models\CategoriaModel;
use CodeIgniter\Model;

class Categorias extends SimpleCrud
{
    protected function modelo(): Model
    {
        return new CategoriaModel();
    }

    protected function config(): array
    {
        return ['titulo' => 'Categorías', 'ruta' => 'categorias', 'descripcion' => true];
    }
}
