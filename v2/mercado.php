<?php
// require_once __DIR__ . '/../gestores/gestormanager.php';
// require_once __DIR__ . '/../gestores/gestortemporada.php';
// require_once __DIR__ . '/../gestores/gestorparametro.php';
require_once __DIR__ . '/../gestores/gestorjugadorliga.php';
require_once __DIR__ . '/../gestores/gestordraftpick.php';

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
    case 'obtenerJugadoresConContrato':
      // Llamar a la función obtenerJugadoresConContrato()
      $pkLiga = $input['pkLiga'];

      $jugadoresConContrato = obtenerJugadoresConContrato($pkLiga);
      echo json_encode($jugadoresConContrato);
      break;
    case 'obtenerListaJugadoresConDerecho':
      // Llamar a la función obtenerListaJugadoresConDerecho()
      $pkLiga = $input['pkLiga'];

      $jugadoresConDerecho = obtenerListaJugadoresConDerecho($pkLiga);
      echo json_encode($jugadoresConDerecho);
      break;
    case 'obtenerListaDraftpicks':
      // Llamar a la función obtenerListaDraftpicks()
      $pkLiga = $input['pkLiga'];

      $listaDraftpicks = obtenerListaDraftpicks($pkLiga);
      echo json_encode($listaDraftpicks);
      break;
    case 'obtenerListaJugadoresConContratoTradingBlock':
      // Llamar a la función obtenerListaJugadoresConContratoTradingBlock()
      $pkLiga = $input['pkLiga'];

      $jugadoresConContrato = obtenerListaJugadoresConContratoTradingBlock($pkLiga);
      echo json_encode($jugadoresConContrato);
      break;
    case 'obtenerListaJugadoresConDerechoTradingBlock':
      // Llamar a la función obtenerListaJugadoresConDerechoTradingBlock()
      $pkLiga = $input['pkLiga'];

      $jugadoresConDerecho = obtenerListaJugadoresConDerechoTradingBlock($pkLiga);
      echo json_encode($jugadoresConDerecho);
      break;
    case 'obtenerListaDraftpicksTradingBlock':
      // Llamar a la función obtenerListaDraftpicksTradingBlock()
      $pkLiga = $input['pkLiga'];

      $listaDraftpicks = obtenerListaDraftpicksTradingBlock($pkLiga);
      echo json_encode($listaDraftpicks);
      break;

    default:
      break;
  }
} else {
  http_response_code(405); // Método no permitido
}
