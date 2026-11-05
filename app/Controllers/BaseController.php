<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\HTTP\RedirectResponse;
use Psr\Log\LoggerInterface;

abstract class BaseController extends Controller
{
    protected $helpers = ['url', 'form', 'tienda'];

    /** Roles con acceso al panel de administración */
    protected const ROLES_STAFF = ['SuperAdmin', 'Administrador', 'Vendedor', 'Almacenero', 'Repartidor'];

    protected $session;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->session = service('session');
    }

    /** Usuario en sesión (id, nombre, email, rol) o null */
    protected function usuario(): ?array
    {
        return $this->session->get('usuario');
    }

    /** Exige sesión iniciada. Devuelve una redirección si no la hay. */
    protected function exigirLogin(): ?RedirectResponse
    {
        if (! $this->usuario()) {
            return redirect()->to('login')->with('error', 'Debes iniciar sesión para continuar.');
        }
        return null;
    }

    /** Exige que el usuario tenga alguno de los roles indicados (RF08). */
    protected function exigirRol(array $roles): ?RedirectResponse
    {
        if ($r = $this->exigirLogin()) {
            return $r;
        }
        if (! in_array($this->usuario()['rol'], $roles, true)) {
            return redirect()->to('/')->with('error', 'No tienes permiso para acceder a esa sección.');
        }
        return null;
    }

    /** Renderiza una vista pasando siempre el usuario actual */
    protected function vista(string $vista, array $datos = []): string
    {
        $datos['usuario'] = $this->usuario();
        return view($vista, $datos);
    }
}
