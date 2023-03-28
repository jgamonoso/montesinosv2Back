<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Credentials: true");

// Si la solicitud es de tipo OPTIONS, finaliza aquí
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
  exit;
}

require_once 'routes.php';

// Obtiene la ruta actual
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
// Carga las rutas del archivo routes.php
$routes = getRoutes();

// Verifica si la ruta actual está en las rutas definidas
if (array_key_exists($uri, $routes)) {
    // Si la ruta existe, incluye el archivo asociado
    require_once $routes[$uri];
} else {
    // Si la ruta no existe, envía un error 404
    http_response_code(404);
    echo json_encode(['error' => 'Not found']);
}
