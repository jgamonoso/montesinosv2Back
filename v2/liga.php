<?php
require_once __DIR__ . '/../gestores/gestormanager.php';
require_once __DIR__ . '/../gestores/gestorequipo.php';
require_once __DIR__ . '/../gestores/gestorpalmares.php';
require_once __DIR__ . '/../gestores/gestorrecord.php';
require_once __DIR__ . '/../gestores/gestorequiponba.php';
require_once __DIR__ . '/../gestores/gestorentrenador.php';

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
    case 'obtenerPalmaresLiga':
      // Llamar a la función obtenerPalmaresLiga() y devolver el resultado
      $pkLiga = $input['pkLiga'];
      $listaPalmares = obtenerPalmaresLiga($pkLiga);
      echo json_encode($listaPalmares);
      break;
    case 'obtenerRecordsLiga':
      // Llamar a la función obtenerRecordsLiga() y devolver el resultado
      $pkLiga = $input['pkLiga'];
      $listaRecords = obtenerRecordsLiga($pkLiga);
      echo json_encode($listaRecords);
      break;
    case 'obtenerListaEquiposLiga':
      // Llamar a la función obtenerListaEquiposLiga() y devolver el resultado
      $pkLiga = $input['pkLiga'];
      $listaEquiposLiga = obtenerListaEquiposLiga($pkLiga);
      echo json_encode($listaEquiposLiga);
      break;

    case 'obtenerListaEquiposLigaApuestas':
      // Llamar a la función obtenerListaEquiposLigaApuestas() y devolver el resultado
      $pkLiga = $input['pkLiga'];
      $listaEquiposLiga = obtenerListaEquiposLigaApuestas($pkLiga);
      echo json_encode($listaEquiposLiga);
      break;
    case 'obtenerListaEquiposNba':
      // Llamar a la función obtenerListaEquiposNba() y devolver el resultado
      $listaEquiposNbaNombre = obtenerListaEquiposNba();
      echo json_encode($listaEquiposNbaNombre);
      break;
    case 'obtenerListaEntrenadores':
      // Llamar a la función obtenerListaEntrenadores() y devolver el resultado
      $listaEntrenadoresNba = obtenerListaEntrenadores();
      echo json_encode($listaEntrenadoresNba);
      break;
    default:
      break;
  }
} else {
  http_response_code(405); // Método no permitido
}
