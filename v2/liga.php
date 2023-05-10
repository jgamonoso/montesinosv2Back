<?php
require_once __DIR__ . '/../gestores/gestormanager.php';
require_once __DIR__ . '/../gestores/gestorequipo.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Credentials: true");

// Recupera la petición HTTP
$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

if ($method === 'POST') {
  $action = isset($input['action']) ? $input['action'] : '';

  switch ($action) {
    case 'obtenerListadoManagersConEquipo':
      // Llamar a la función obtenerListadoManagersConEquipo() y devolver el resultado
      $pkLiga = $input['pkLiga'];
      $listadoManagersConEquipo = obtenerListadoManagersConEquipo($pkLiga);
      echo json_encode($listadoManagersConEquipo);
      break;
    case 'obtenerListaEquiposNombre':
      // Llamar a la función obtenerListaEquiposNombre() y devolver el resultado
      $listaEquiposNombre = obtenerListaEquiposNombre();
      echo json_encode($listaEquiposNombre);
      break;

    default:
      break;
  }
} else {
  http_response_code(405); // Método no permitido
}
