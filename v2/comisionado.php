<?php
require_once __DIR__ . '/../gestores/gestornoticia.php';
require_once __DIR__ . '/../gestores/gestortrade.php';
require_once __DIR__ . '/../gestores/gestorbonus.php';
require_once __DIR__ . '/../gestores/gestorsancion.php';
require_once __DIR__ . '/../gestores/gestorequipo.php';
require_once __DIR__ . '/../gestores/gestortemporada.php';
require_once __DIR__ . '/../gestores/gestorwaiver.php';
require_once __DIR__ . '/../gestores/gestorsubasta.php';
require_once __DIR__ . '/../gestores/gestorjugadorliga.php';
require_once __DIR__ . '/../gestores/gestorcontrato.php';
// require_once __DIR__ . '/../gestores/gestorparametro.php';
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

    case 'obtenerListaTradesPendientes':
      // Llamar a la función obtenerListaTradesPendientes()

      $tradesPendientes = obtenerListaTradesPendientes();
      echo json_encode($tradesPendientes);
      break;

    case 'validarTrade':
      // Llamar a la función validarTrade()
      $pkManager = $input['pkManager'];
      $pkTrade = $input['pkTrade'];

      validarTrade($pkManager, $pkTrade);

      $response = [
        'status' => 'ok',
      ];
      echo json_encode($response);
      break;

    case 'vetarTrade':
      // Llamar a la función vetarTrade()
      $pkManager = $input['pkManager'];
      $pkTrade = $input['pkTrade'];

      vetarTrade($pkManager, $pkTrade);

      $response = [
        'status' => 'ok',
      ];
      echo json_encode($response);
      break;

    case 'altaBonusComi':
      // Llamar a la función altaBonusComi()
      $pkManager = $input['pkManager'];
      $equipo = $input['pkEquipo'];
      $cantidad = $input['cantidad'];
      $temporada = $input['temporada'];
      $motivo = $input['motivo'];
      $pkLiga = $input['pkLiga'];

      altaBonusComi($pkManager, $equipo, $cantidad, $temporada, $motivo, $pkLiga);

      $response = [
        'status' => 'ok',
      ];

      echo json_encode($response);
      break;

    case 'altaSancionComi':
      // Llamar a la función altaSancionComi()
      $pkManager = $input['pkManager'];
      $equipo = $input['pkEquipo'];
      $cantidad = $input['cantidad'];
      $temporada = $input['temporada'];
      $motivo = $input['motivo'];
      $pkLiga = $input['pkLiga'];

      altaSancionComi($pkManager, $equipo, $cantidad, $temporada, $motivo, $pkLiga);

      $response = [
        'status' => 'ok',
      ];

      echo json_encode($response);
      break;

    case 'cambiarEstadoTemporadaActual':
      // Llamar a la función cambiarEstadoTemporadaActual()
      $pkManager = $input['pkManager'];
      $estado = $input['estado'];

      switch ($estado) {
        case 'RENOVACIONES':
          desactivarCorteGratis();
          // Procesar inicio de nueva temporada (preparar jugadores restringidos para renovaciones, derechos que expiran, contratos que expiran, sanciones y bonus que expiran / entran en vigor...)
          cambiarTemporada();
          // Poner todos los jugadores en waivers 1 d&iacute;a
          crearWaiverJugadoresRenovables();
          break;

        case 'AL_OFFSEASON':
          desactivarCorteGratis();
          // finalizar pujas activas
          finalizarSubastasAbiertas();
          // liberar jugadores restringidos
          liberarJugadoresRestringidos();
          // Poner todos los jugadores en waivers 1 d&iacute;a
          crearWaiverJugadoresLibres();
          break;

        case 'SEASON':
          // finalizar pujas activas
          finalizarSubastasAbiertas();
          // Poner todos los jugadores en waivers 1 d&iacute;a
          crearWaiverJugadoresLibres();
          // comprobar exceso jugadores
          comprobarExcesoJugadoresSeason();
          desactivarCorteGratis();
          break;

        case 'TEAM_OPTION':
          desactivarCorteGratis();
          break;

        case 'CORTE_GRATIS':
          // convertir en FAs los LLD no recuperados
          liberarContratosLLD();
          // activar corte gratis
          activarCorteGratis();
          break;

        default:
          desactivarCorteGratis();
          break;
      }

      cambiarEstadoTemporadaActual($pkManager, $estado);

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
