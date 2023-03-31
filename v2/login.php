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
    case 'obtenerTemporadaActual':
      // Llamar a la función obtenerTemporadaActual() y devolver el resultado
      $temporadaActual = obtenerTemporadaActual();
      echo json_encode($temporadaActual);
      break;

    case 'obtenerManagerPorLogin':
        // Llamar a la función obtenerManagerPorLogin() y devolver el resultado
        $login = $input['login'];
        $managerPorLogin = obtenerManagerPorLogin($login);
        echo json_encode($managerPorLogin);
        break;

    default:
      // El código original para el inicio de sesión
      $login = $input['login'];
      $password = $input['password'];

      $validado = validarManager($login, $password);

      if ($validado) {
        $validadoLiga = validarLigaManager($login, $password);

        $response = [
          'status' => 'ok',
          'manager' => $login,
          'liga' => $validadoLiga,
        ];
      } else {
        $response = [
            'status' => 'error',
            'message' => 'Login error',
        ];
      }

      echo json_encode($response);
      break;
  }
} else {
  http_response_code(405); // Método no permitido
}
