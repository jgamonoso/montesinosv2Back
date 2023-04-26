<?php
// routes.php

function getRoutes()
{
    // Define las rutas y sus archivos correspondientes
    $routes = [
        '/api/v2/login' => 'v2/login.php',
        '/api/v2/miequipo' => 'v2/miequipo.php',
        '/api/v2/liga' => 'v2/liga.php',
            '/api/v2/liga/noticias' => 'v2/noticias.php',
    ];

    return $routes;
}

?>