<?php
require_once __DIR__ . '/../gestores/gestormanager.php';
require_once __DIR__ . '/../gestores/gestortemporada.php';
require_once __DIR__ . '/../gestores/gestorparametro.php';
require_once __DIR__ . '/../gestores/gestorjugadorliga.php';
require_once __DIR__ . '/../gestores/gestorderecho.php';
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

    case 'activarCovidDeJugador':
      // Llamar a la función activarCovidDeJugador()
      $managerId = $input['managerId'];
      $jugadorId = $input['jugadorId'];
      $fkLiga = $input['fkLiga'];
      $pkEquipo = $input['pkEquipo'];

      $activarCovidDeJugador = activarCovidDeJugador($managerId, $jugadorId, $fkLiga, $pkEquipo);
      echo json_encode($activarCovidDeJugador);
      break;

    case 'recuperarJugadordeCovid':
      // Llamar a la función recuperarJugadordeCovid()
      $managerId = $input['managerId'];
      $jugadorId = $input['jugadorId'];
      $fkLiga = $input['fkLiga'];
      $pkEquipo = $input['pkEquipo'];

      $recuperarJugadordeCovid = recuperarJugadordeCovid($managerId, $jugadorId, $fkLiga, $pkEquipo);
      echo json_encode($recuperarJugadordeCovid);
      break;

    case 'addTradingBlock':
      // Llamar a la función addTradingBlock()
      $managerId = $input['managerId'];
      $pkEquipo = $input['pkEquipo'];
      $jugadorId = $input['jugadorId'];
      $derechoId = $input['derechoId'];
      $draftPickId = $input['draftPickId'];

      if ($jugadorId !== null) {
          $addTradingBlock = addJugadorTradingBlock($managerId, $pkEquipo, $jugadorId);
      } elseif ($derechoId !== null) {
          $addTradingBlock = addDerechoTradingBlock($managerId, $pkEquipo, $derechoId);
      } elseif ($draftPickId !== null) {
          $addTradingBlock = addDraftpickTradingBlock($managerId, $pkEquipo, $draftPickId);
      } else {
          // Aquí puedes manejar el caso en el que todas las variables sean null
          $addTradingBlock = "Todos los IDs son null";
      }
      echo json_encode($addTradingBlock);
      break;

    case 'recuperarDeTradingBlock':
      // Llamar a la función recuperarDeTradingBlock()
      $managerId = $input['managerId'];
      $pkEquipo = $input['pkEquipo'];
      $jugadorId = $input['jugadorId'];
      $derechoId = $input['derechoId'];
      $draftPickId = $input['draftPickId'];

      if ($jugadorId !== null) {
          $recuperarDeTradingBlock = quitarJugadorTradingBlock($managerId, $pkEquipo, $jugadorId);
      } elseif ($derechoId !== null) {
          $recuperarDeTradingBlock = quitarDerechoTradingBlock($managerId, $pkEquipo, $derechoId);
      } elseif ($draftPickId !== null) {
          $recuperarDeTradingBlock = quitarDraftpickTradingBlock($managerId, $pkEquipo, $draftPickId);
      } else {
          // Aquí puedes manejar el caso en el que todas las variables sean null
          $recuperarDeTradingBlock = "Todos los IDs son null";
      }

      echo json_encode($recuperarDeTradingBlock);
      break;








    case 'obtenerJugadoresLesionadosEquipo':
      // Llamar a la función obtenerJugadoresLesionadosEquipo()
      $pkEquipo = $input['pkEquipo'];

      $jugadoresLesionados = obtenerJugadoresLesionadosEquipo($pkEquipo);
      echo json_encode($jugadoresLesionados);
      break;

    case 'obtenerJugadoresLLDConContrato':
      // Llamar a la función obtenerJugadoresLLDConContrato()
      $pkLiga = $input['pkLiga'];

      $jugadoresLLDConContrato = obtenerJugadoresLLDConContrato($pkLiga);
      echo json_encode($jugadoresLLDConContrato);
      break;

    case 'obtenerJugadoresCOVIDConContrato':
      // Llamar a la función obtenerJugadoresCOVIDConContrato()
      $pkLiga = $input['pkLiga'];

      $jugadoresCOVIDConContrato = obtenerJugadoresCOVIDConContrato($pkLiga);
      echo json_encode($jugadoresCOVIDConContrato);
      break;

    case 'obtenerJugadoresILLiga':
      // Llamar a la función obtenerJugadoresILLiga()
      $pkLiga = $input['pkLiga'];

      $jugadoresILLiga = obtenerJugadoresILLiga($pkLiga);
      echo json_encode($jugadoresILLiga);
      break;

    default:
      break;
  }
} else {
  http_response_code(405); // Método no permitido
}
