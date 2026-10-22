<?php

namespace App\Controllers;

class Cliente extends BaseController
{
    public function index()
    {
        // Aquí puedes cargar una vista o retornar un mensaje de prueba
        echo "¡Bienvenido al módulo de Clientes!";
    }
}
$routes->get('clientes', 'Cliente::index');