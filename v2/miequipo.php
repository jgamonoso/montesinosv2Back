<?php
require_once __DIR__ . '/../gestores/gestormanager.php';
require_once __DIR__ . '/../gestores/gestortemporada.php';
require_once __DIR__ . '/../gestores/gestorparametro.php';

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

    case 'obtenerValorParametro':
      // Llamar a la función obtenerValorParametro() y devolver el resultado
      $parametro = $input['parametro'];
      $valorParametro = obtenerValorParametro($parametro);
      echo json_encode($valorParametro);
      break;

    case 'obtenerProximasTemporadas':
      // Llamar a la función obtenerProximasTemporadas() y devolver el resultado
      $proximasTemporadas = obtenerProximasTemporadas();
      echo json_encode($proximasTemporadas);
      break;

    case 'obtenerJugadorliga':
      // Llamar a la función obtenerJugadorliga() y devolver el resultado
      $pkJugadorliga = $input['pkJugadorliga'];
      $jugadorliga = obtenerJugadorliga($pkJugadorliga);
      echo json_encode($jugadorliga);
      break;

    case 'activarILDeJugador':
      // Llamar a la función activarILDeJugador()
      $managerId = $input['managerId'];
      $jugadorId = $input['jugadorId'];
      $fkLiga = $input['fkLiga'];
      $pkEquipo = $input['pkEquipo'];

      $activarILDeJugador = activarILDeJugador($managerId, $jugadorId, $fkLiga, $pkEquipo);
      echo json_encode($activarILDeJugador);
      break;

    case 'recuperarJugadordeIL':
      // Llamar a la función recuperarJugadordeIL()
      $managerId = $input['managerId'];
      $jugadorId = $input['jugadorId'];
      $fkLiga = $input['fkLiga'];
      $pkEquipo = $input['pkEquipo'];

      $recuperarJugadordeIL = recuperarJugadordeIL($managerId, $jugadorId, $fkLiga, $pkEquipo);
      echo json_encode($recuperarJugadordeIL);
      break;

    default:
      break;
  }
} else {
  http_response_code(405); // Método no permitido
}
