<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 *
 * Todas las rutas pasan por el filtro CSRF (RNF02).
 * El control de roles se hace en los controladores (BaseController::exigirRol).
 * En la Semana 11 se migrará a filtros de autorización.
 */
$routes->group('', ['filter' => 'csrf'], static function (RouteCollection $routes) {

    // ---------- Catálogo público (RF01-RF04) ----------
    $routes->get('/', 'Catalogo::index');
    $routes->get('catalogo', 'Catalogo::index');
    $routes->get('producto/(:num)', 'Catalogo::detalle/$1');

    //ruta, controlador, método
    // ---------- Autenticación (RF06, RF07) ----------
    $routes->get('registro', 'Auth::registro');
    $routes->post('registro', 'Auth::guardarRegistro');
    $routes->get('login', 'Auth::login');
    $routes->post('login', 'Auth::autenticar');
    $routes->get('logout', 'Auth::logout');

    // ---------- Carrito y pedidos del cliente (RF05, RF10, RF11) ----------
    $routes->get('carrito', 'Carrito::index');
    $routes->post('carrito/agregar/(:num)', 'Carrito::agregar/$1');
    $routes->post('carrito/quitar/(:num)', 'Carrito::quitar/$1');
    $routes->post('carrito/vaciar', 'Carrito::vaciar');
    $routes->post('carrito/confirmar', 'Carrito::confirmar');
    $routes->get('mis-pedidos', 'Pedidos::mis');

    // ---------- Panel de administración (RF08, RF13-RF16) ----------
    $routes->group('admin', static function (RouteCollection $routes) {
        $routes->get('/', 'Admin\Dashboard::index');

        $routes->get('productos', 'Admin\Productos::index');
        $routes->get('productos/nuevo', 'Admin\Productos::nuevo');
        $routes->post('productos/guardar', 'Admin\Productos::guardar');
        $routes->get('productos/editar/(:num)', 'Admin\Productos::editar/$1');
        $routes->post('productos/actualizar/(:num)', 'Admin\Productos::actualizar/$1');
        $routes->post('productos/estado/(:num)', 'Admin\Productos::estado/$1');

        $routes->get('categorias', 'Admin\Categorias::index');
        $routes->get('categorias/nuevo', 'Admin\Categorias::nuevo');
        $routes->post('categorias/guardar', 'Admin\Categorias::guardar');
        $routes->get('categorias/editar/(:num)', 'Admin\Categorias::editar/$1');
        $routes->post('categorias/actualizar/(:num)', 'Admin\Categorias::actualizar/$1');
        $routes->post('categorias/estado/(:num)', 'Admin\Categorias::estado/$1');

        $routes->get('marcas', 'Admin\Marcas::index');
        $routes->get('marcas/nuevo', 'Admin\Marcas::nuevo');
        $routes->post('marcas/guardar', 'Admin\Marcas::guardar');
        $routes->get('marcas/editar/(:num)', 'Admin\Marcas::editar/$1');
        $routes->post('marcas/actualizar/(:num)', 'Admin\Marcas::actualizar/$1');
        $routes->post('marcas/estado/(:num)', 'Admin\Marcas::estado/$1');

        $routes->get('stock', 'Admin\Stock::index');
        $routes->post('stock/ajustar/(:num)', 'Admin\Stock::ajustar/$1');

        $routes->get('pedidos', 'Admin\Pedidos::index');
        $routes->post('pedidos/estado/(:num)', 'Admin\Pedidos::cambiarEstado/$1');
    });
});
