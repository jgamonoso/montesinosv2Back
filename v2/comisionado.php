<?php
// require_once __DIR__ . '/../gestores/gestormanager.php';
// require_once __DIR__ . '/../gestores/gestortemporada.php';
// require_once __DIR__ . '/../gestores/gestorparametro.php';
require_once __DIR__ . '/../gestores/gestornoticia.php';
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
    case 'altaNoticiaComi':
      // Llamar a la función altaNoticiaComi()
      $notificacion = $input['notificacion'];
      $pkLiga = $input['pkLiga'];
      $prioridad = $input['prioridad'];
      $pkManager = $input['pkManager'];

      altaNoticiaComi($pkManager, $notificacion, $prioridad, $pkLiga);

      $response = [
        'status' => 'ok',
      ];

      echo json_encode($response);
      break;

    default:
      break;
  }
} else {
  http_response_code(405); // Método no permitido
}
