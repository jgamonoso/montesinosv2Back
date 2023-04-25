<?php
// routes.php

function getRoutes()
{
    // Define las rutas y sus archivos correspondientes
    $routes = [
        '/api/v2/login' => 'v2/login.php',
        '/api/v2/noticias' => 'v2/noticias.php',
        '/api/v2/miequipo' => 'v2/miequipo.php',
        '/api/v2/liga' => 'v2/liga.php',
    ];

    return $routes;
}

?>