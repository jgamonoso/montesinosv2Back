<?php
// routes.php

function getRoutes()
{
    // Define las rutas y sus archivos correspondientes
    $routes = [
        '/api/v2/login' => 'v2/login.php',
        '/api/v2/noticias' => 'v2/noticias.php',
        '/api/v2/dashboard' => 'v2/dashboard.php',
        '/api/v2/miequipo' => 'v2/miequipo.php',
        '/api/v2/mercado' => 'v2/mercado.php',
        '/api/v2/agencialibre' => 'v2/agencialibre.php',
        '/api/v2/renovaciones' => 'v2/renovaciones.php',
        '/api/v2/comisionado' => 'v2/comisionado.php',
        '/api/v2/sidebar' => 'v2/sidebar.php',
        '/api/v2/liga' => 'v2/liga.php',
    ];

    return $routes;
}

?>