<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Dashboard');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(false);

// ==================== DASHBOARD ====================
$routes->get('/', 'Dashboard::index');

// ==================== PRODUCTS ====================
$routes->group('products', function($routes) {
    $routes->get('/', 'Product::index');
    $routes->get('create', 'Product::create');
    $routes->post('/', 'Product::store');
    $routes->get('(:num)/edit', 'Product::edit/$1');
    $routes->post('(:num)', 'Product::update/$1');
    $routes->delete('(:num)', 'Product::delete/$1');
    $routes->get('(:num)/detail', 'Product::detail/$1');
});

// ==================== PURCHASES ====================
$routes->group('purchases', function($routes) {
    $routes->get('/', 'Purchase::index');
    $routes->get('create', 'Purchase::create');
    $routes->post('/', 'Purchase::store');
    $routes->put('(:num)/cancel', 'Purchase::cancel/$1');
    $routes->get('(:num)/detail', 'Purchase::detail/$1');
});