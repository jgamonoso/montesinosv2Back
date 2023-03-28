<?php
// routes.php

function getRoutes()
{
    // Define las rutas y sus archivos correspondientes
    $routes = [
        '/api/v2/noticias' => 'v2/noticias.php',
        '/api/v2/login' => 'v2/login.php',
    ];

    return $routes;
}

?>