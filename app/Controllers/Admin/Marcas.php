<?php

namespace App\Controllers\Admin;

use App\Models\MarcaModel;
use CodeIgniter\Model;

class Marcas extends SimpleCrud
{
    protected function modelo(): Model
    {
        return new MarcaModel();
    }

    protected function config(): array
    {
        return ['titulo' => 'Marcas', 'ruta' => 'marcas', 'descripcion' => false];
    }
}
