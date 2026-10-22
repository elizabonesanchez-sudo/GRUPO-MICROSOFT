<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Home
$routes->get('/', 'Home::index');
$routes->get('home', 'Home::index');

// 1. Módulo Persona
$routes->group('persona', static function ($routes) {
    $routes->get('', 'Persona::index');
    $routes->get('actual', 'Persona::actual');
    $routes->get('actual/(:num)', 'Persona::actual/$1');
    $routes->get('elprimero', 'Persona::elprimero');
    $routes->get('elultimo', 'Persona::elultimo');
    $routes->get('siguiente/(:num)', 'Persona::siguiente/$1');
    $routes->get('anterior/(:num)', 'Persona::anterior/$1');
    $routes->get('listar', 'Persona::listar');
    $routes->get('add', 'Persona::add');
    $routes->get('create', 'Persona::add');
    $routes->post('store', 'Persona::store');
    $routes->get('edit/(:num)', 'Persona::edit/$1');
    $routes->post('update/(:num)', 'Persona::update/$1');
    $routes->post('delete/(:num)', 'Persona::delete/$1');
    $routes->get('clientes', 'Cliente::index');
});

// 2. Módulo Sexo
$routes->group('sexo', static function ($routes) {
    $routes->get('', 'Sexo::index');
    $routes->get('actual', 'Sexo::actual');
    $routes->get('actual/(:num)', 'Sexo::actual/$1');
    $routes->get('elprimero', 'Sexo::elprimero');
    $routes->get('elultimo', 'Sexo::elultimo');
    $routes->get('siguiente/(:num)', 'Sexo::siguiente/$1');
    $routes->get('anterior/(:num)', 'Sexo::anterior/$1');
    $routes->get('listar', 'Sexo::listar');
    $routes->get('add', 'Sexo::add');
    $routes->get('create', 'Sexo::add');
    $routes->post('store', 'Sexo::store');
    $routes->get('edit/(:num)', 'Sexo::edit/$1');
    $routes->post('update/(:num)', 'Sexo::update/$1');
    $routes->post('delete/(:num)', 'Sexo::delete/$1');
});

// 3. Módulo Estado Civil
$routes->group('estadocivil', static function ($routes) {
    $routes->get('', 'EstadoCivil::index');
    $routes->get('actual', 'EstadoCivil::actual');
    $routes->get('actual/(:num)', 'EstadoCivil::actual/$1');
    $routes->get('elprimero', 'EstadoCivil::elprimero');
    $routes->get('elultimo', 'EstadoCivil::elultimo');
    $routes->get('siguiente/(:num)', 'EstadoCivil::siguiente/$1');
    $routes->get('anterior/(:num)', 'EstadoCivil::anterior/$1');
    $routes->get('listar', 'EstadoCivil::listar');
    $routes->get('add', 'EstadoCivil::add');
    $routes->get('create', 'EstadoCivil::add');
    $routes->post('store', 'EstadoCivil::store');
    $routes->get('edit/(:num)', 'EstadoCivil::edit/$1');
    $routes->post('update/(:num)', 'EstadoCivil::update/$1');
    $routes->post('delete/(:num)', 'EstadoCivil::delete/$1');
});

// 4. Módulo Dirección
$routes->group('direccion', static function ($routes) {
    $routes->get('', 'Direccion::index');
    $routes->get('actual', 'Direccion::actual');
    $routes->get('actual/(:num)', 'Direccion::actual/$1');
    $routes->get('elprimero', 'Direccion::elprimero');
    $routes->get('elultimo', 'Direccion::elultimo');
    $routes->get('siguiente/(:num)', 'Direccion::siguiente/$1');
    $routes->get('anterior/(:num)', 'Direccion::anterior/$1');
    $routes->get('listar', 'Direccion::listar');
    $routes->get('add', 'Direccion::add');
    $routes->get('add/(:num)', 'Direccion::add/$1');
    $routes->get('create', 'Direccion::add');
    $routes->post('store', 'Direccion::store');
    $routes->get('edit/(:num)', 'Direccion::edit/$1');
    $routes->post('update/(:num)', 'Direccion::update/$1');
    $routes->post('delete/(:num)', 'Direccion::delete/$1');
});

// 5. Módulo Correo Electrónico
$routes->group('correoelectronico', static function ($routes) {
    $routes->get('', 'CorreoElectronico::index');
    $routes->get('actual', 'CorreoElectronico::actual');
    $routes->get('actual/(:num)', 'CorreoElectronico::actual/$1');
    $routes->get('elprimero', 'CorreoElectronico::elprimero');
    $routes->get('elultimo', 'CorreoElectronico::elultimo');
    $routes->get('siguiente/(:num)', 'CorreoElectronico::siguiente/$1');
    $routes->get('anterior/(:num)', 'CorreoElectronico::anterior/$1');
    $routes->get('listar', 'CorreoElectronico::listar');
    $routes->get('add', 'CorreoElectronico::add');
    $routes->get('add/(:num)', 'CorreoElectronico::add/$1');
    $routes->get('create', 'CorreoElectronico::add');
    $routes->post('store', 'CorreoElectronico::store');
    $routes->get('edit/(:num)', 'CorreoElectronico::edit/$1');
    $routes->post('update/(:num)', 'CorreoElectronico::update/$1');
    $routes->post('delete/(:num)', 'CorreoElectronico::delete/$1');
});

// Alias '/correo' para Módulo Correo Electrónico
$routes->group('correo', static function ($routes) {
    $routes->get('', 'Correo::index');
    $routes->get('actual', 'Correo::actual');
    $routes->get('actual/(:num)', 'Correo::actual/$1');
    $routes->get('elprimero', 'Correo::elprimero');
    $routes->get('elultimo', 'Correo::elultimo');
    $routes->get('siguiente/(:num)', 'Correo::siguiente/$1');
    $routes->get('anterior/(:num)', 'Correo::anterior/$1');
    $routes->get('listar', 'Correo::listar');
    $routes->get('add', 'Correo::add');
    $routes->get('add/(:num)', 'Correo::add/$1');
    $routes->get('create', 'Correo::add');
    $routes->post('store', 'Correo::store');
    $routes->get('edit/(:num)', 'Correo::edit/$1');
    $routes->post('update/(:num)', 'Correo::update/$1');
    $routes->post('delete/(:num)', 'Correo::delete/$1');
});

// --------------------------------------------------------------------
// RUTAS API REST PARA LA APLICACIÓN EYSFLUIS / SISTEMA EYSFWIS
// --------------------------------------------------------------------
$routes->group('api', ['namespace' => 'App\Controllers\Api'], static function ($routes) {
    // Manejo de CORS preflight
    $routes->options('(:any)', 'PersonaController::options');
    $routes->options('/', 'PersonaController::options');

    // Catálogos
    $routes->get('catalogos', 'PersonaController::catalogos');

    // Personas CRUD
    $routes->get('persona', 'PersonaController::index');
    $routes->get('persona/(:num)', 'PersonaController::show/$1');
    $routes->post('persona', 'PersonaController::create');
    $routes->put('persona/(:num)', 'PersonaController::update/$1');
    $routes->post('persona/(:num)', 'PersonaController::update/$1');
    $routes->post('persona/update/(:num)', 'PersonaController::update/$1');
    $routes->delete('persona/(:num)', 'PersonaController::delete/$1');

    // Sexo CRUD API
    $routes->get('sexo', 'SexoApiController::index');
    $routes->get('sexo/(:num)', 'SexoApiController::show/$1');
    $routes->post('sexo', 'SexoApiController::create');
    $routes->put('sexo/(:num)', 'SexoApiController::update/$1');
    $routes->post('sexo/(:num)', 'SexoApiController::update/$1');
    $routes->delete('sexo/(:num)', 'SexoApiController::delete/$1');

    // Estado Civil CRUD API
    $routes->get('estadocivil', 'EstadoCivilApiController::index');
    $routes->get('estadocivil/(:num)', 'EstadoCivilApiController::show/$1');
    $routes->post('estadocivil', 'EstadoCivilApiController::create');
    $routes->put('estadocivil/(:num)', 'EstadoCivilApiController::update/$1');
    $routes->post('estadocivil/(:num)', 'EstadoCivilApiController::update/$1');
    $routes->delete('estadocivil/(:num)', 'EstadoCivilApiController::delete/$1');

    // Correo CRUD API
    $routes->get('correo', 'CorreoApiController::index');
    $routes->get('correo/(:num)', 'CorreoApiController::show/$1');
    $routes->get('persona/(:num)/correos', 'CorreoApiController::index/$1');
    $routes->post('correo', 'CorreoApiController::create');
    $routes->put('correo/(:num)', 'CorreoApiController::update/$1');
    $routes->post('correo/(:num)', 'CorreoApiController::update/$1');
    $routes->delete('correo/(:num)', 'CorreoApiController::delete/$1');
});

