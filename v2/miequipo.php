<?php
require_once __DIR__ . '/../gestores/gestormanager.php';
require_once __DIR__ . '/../gestores/gestortemporada.php';
require_once __DIR__ . '/../gestores/gestorparametro.php';
require_once __DIR__ . '/../gestores/gestorjugadorliga.php';
require_once __DIR__ . '/../gestores/gestorderecho.php';
require_once __DIR__ . '/../gestores/gestordraftpick.php';
require_once __DIR__ . '/../gestores/gestorequipo.php';
require_once __DIR__ . '/../gestores/gestoroferta.php';

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

    case 'dropJugador':
      // Llamar a la función dropJugador()
      $managerId = $input['managerId'];
      $jugadorId = $input['jugadorId'];
      $pkEquipo = $input['pkEquipo'];
      $sancionAplicable = $input['sancionAplicable'];

      $dropJugador = dropJugador($managerId, $jugadorId, $pkEquipo, $sancionAplicable);
      echo json_encode($dropJugador);
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

    case 'obtenerEquipoPorPk':
      // Llamar a la función obtenerEquipoPorPk()
      $pkEquipo = $input['pkEquipo'];

      $equipo = obtenerEquipoPorPk($pkEquipo);
      echo json_encode($equipo);
      break;

    case 'crearOferta':
      // Llamar a la función crearOferta()
      $pkManager = $input['pkManager'];
      $pkEquipo1 = $input['pkEquipo1'];
      $listaJugadoresEquipo1 = $input['listaJugadoresEquipo1'];
      $listaDerechosEquipo1 = $input['listaDerechosEquipo1'];
      $listaDraftpicksEquipo1 = $input['listaDraftpicksEquipo1'];
      $pkEquipo2 = $input['pkEquipo2'];
      $listaJugadoresEquipo2 = $input['listaJugadoresEquipo2'];
      $listaDerechosEquipo2 = $input['listaDerechosEquipo2'];
      $listaDraftpicksEquipo2 = $input['listaDraftpicksEquipo2'];

      crearOferta($pkManager, $pkEquipo1, $listaJugadoresEquipo1, $listaDerechosEquipo1, $listaDraftpicksEquipo1, $pkEquipo2, $listaJugadoresEquipo2, $listaDerechosEquipo2, $listaDraftpicksEquipo2);

      $response = [
        'status' => 'ok',
      ];

      echo json_encode($response);
      break;

    case 'obtenerListaOfertasRealizadas':
      // Llamar a la función obtenerListaOfertasRealizadas()
      $pkEquipo = $input['pkEquipo'];

      $ofertasRealizadas = obtenerListaOfertasRealizadas($pkEquipo);
      echo json_encode($ofertasRealizadas);
      break;

    case 'obtenerListaOfertasRecibidas':
      // Llamar a la función obtenerListaOfertasRecibidas()
      $pkEquipo = $input['pkEquipo'];

      $ofertasRecibidas = obtenerListaOfertasRecibidas($pkEquipo);
      echo json_encode($ofertasRecibidas);
      break;

    case 'aceptarOferta':
      // Llamar a la función aceptarOferta()
      $pkManager = $input['pkManager'];
      $pkEquipo = $input['pkEquipo'];
      $pkOferta = $input['pkOferta'];
      $pkLiga = $input['pkLiga'];

      aceptarOferta($pkManager, $pkEquipo, $pkOferta, $pkLiga);

      $response = [
        'status' => 'ok',
      ];
      echo json_encode($response);
      break;

    case 'rechazarOferta':
      // Llamar a la función rechazarOferta()
      $pkManager = $input['pkManager'];
      $pkEquipo = $input['pkEquipo'];
      $pkOferta = $input['pkOferta'];

      rechazarOferta($pkManager, $pkEquipo, $pkOferta);

      $response = [
        'status' => 'ok',
      ];
      echo json_encode($response);
      break;

    case 'anularOferta':
      // Llamar a la función anularOferta()
      $pkManager = $input['pkManager'];
      $pkEquipo = $input['pkEquipo'];
      $pkOferta = $input['pkOferta'];

      anularOferta($pkManager, $pkEquipo, $pkOferta);

      $response = [
        'status' => 'ok',
      ];
      echo json_encode($response);
      break;

    case 'recuperarJugadorLesionado':
      // Llamar a la función recuperarJugadorLesionado()
      $pkManager = $input['pkManager'];
      $pkEquipo = $input['pkEquipo'];
      $pkJugadorliga = $input['pkJugadorliga'];
      $pkLiga = $input['pkLiga'];

      recuperarJugadorLesionado($pkManager, $pkEquipo, $pkJugadorliga, $pkLiga);

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
