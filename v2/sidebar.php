<?php
require_once __DIR__ . '/../gestores/gestorsubasta.php';
require_once __DIR__ . '/../gestores/gestoroferta.php';
require_once __DIR__ . '/../gestores/gestorwaiverclaim.php';
require_once __DIR__ . '/../gestores/gestorcontrato.php';
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
    case 'obtenerNumOfertasRealizadasEquipo':
      // Llamar a la función obtenerNumOfertasRealizadasEquipo()
      $pkEquipo = $input['pkEquipo'];

      $numOfertasRealizadasEquipo = obtenerNumOfertasRealizadasEquipo($pkEquipo);
      echo json_encode($numOfertasRealizadasEquipo);
      break;

    case 'obtenerNumOfertasRecibidasEquipo':
      // Llamar a la función obtenerNumOfertasRecibidasEquipo()
      $pkEquipo = $input['pkEquipo'];

      $numOfertasRecibidasEquipo = obtenerNumOfertasRecibidasEquipo($pkEquipo);
      echo json_encode($numOfertasRecibidasEquipo);
      break;

    case 'obtenerNumSubastasAbiertasEquipo':
      // Llamar a la función obtenerNumSubastasAbiertasEquipo()
      $pkEquipo = $input['pkEquipo'];

      $numSubastasAbiertasEquipo = obtenerNumSubastasAbiertasEquipo($pkEquipo);
      echo json_encode($numSubastasAbiertasEquipo);
      break;

    case 'obtenerNumClaimsEquipo':
      // Llamar a la función obtenerNumClaimsEquipo()
      $pkEquipo = $input['pkEquipo'];

      $numClaimsEquipo = obtenerNumClaimsEquipo($pkEquipo);
      echo json_encode($numClaimsEquipo);
      break;

    case 'obtenerNumLLDEquipo':
      // Llamar a la función obtenerNumLLDEquipo()
      $pkEquipo = $input['pkEquipo'];

      $numLLDEquipo = obtenerNumLLDEquipo($pkEquipo);
      echo json_encode($numLLDEquipo);
      break;

    case 'obtenerNumSubastasAbiertas':
      // Llamar a la función obtenerNumSubastasAbiertas()
      $pkLiga = $input['pkLiga'];

      $numSubastasAbiertas = obtenerNumSubastasAbiertas($pkLiga);
      echo json_encode($numSubastasAbiertas);
      break;

    default:
      break;
  }
} else {
  http_response_code(405); // Método no permitido
}
