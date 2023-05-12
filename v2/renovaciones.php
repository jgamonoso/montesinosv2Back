<?php
// require_once __DIR__ . '/../gestores/gestormanager.php';
// require_once __DIR__ . '/../gestores/gestortemporada.php';
// require_once __DIR__ . '/../gestores/gestorparametro.php';
require_once __DIR__ . '/../gestores/gestorjugadorliga.php';
// require_once __DIR__ . '/../gestores/gestordraftpick.php';

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
    case 'obtenerListaJugadoresRenovables':
      // Llamar a la función obtenerListaJugadoresRenovables()
      $pkLiga = $input['pkLiga'];

      $jugadoresRenovables = obtenerListaJugadoresRenovables($pkLiga);
      echo json_encode($jugadoresRenovables);
      break;

    default:
      break;
  }
} else {
  http_response_code(405); // Método no permitido
}
