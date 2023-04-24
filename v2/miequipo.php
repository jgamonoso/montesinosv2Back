<?php
require_once __DIR__ . '/../gestores/gestormanager.php';
require_once __DIR__ . '/../gestores/gestortemporada.php';

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
    case 'obtenerManager':
      // Llamar a la función obtenerManager() y devolver el resultado
      $pkManager = $input['pkManager'];
      $manager = obtenerManager($pkManager);
      echo json_encode($manager);
      break;

    case 'obtenerProximasTemporadas':
      // Llamar a la función obtenerProximasTemporadas() y devolver el resultado
      $proximasTemporadas = obtenerProximasTemporadas();
      echo json_encode($proximasTemporadas);
      break;

    default:
      break;
  }
} else {
  http_response_code(405); // Método no permitido
}
