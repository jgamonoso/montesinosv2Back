<?php
require_once "../gestores/gestornoticias.php";

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
  header("Access-Control-Allow-Origin: *");
  header("Access-Control-Allow-Headers: *");
  header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
  header("Access-Control-Allow-Credentials: true");
  exit;
}

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Credentials: true");

$method = $_SERVER['REQUEST_METHOD'];

if ($method == 'GET') {
    if (isset($_GET['pagina'])) {
        $pagina = $_GET['pagina'];
    } else {
        $pagina = 1;
    }

    if (isset($_GET['pkLiga'])) {
        $pkLiga = $_GET['pkLiga'];
    } else {
        $pkLiga = 1; // Utiliza un valor predeterminado si no se proporciona pkLiga
    }

    $listaFechas = obtenerListaFechas($pagina);
    $noticias = array();

    foreach ($listaFechas as $fecha) {
        $listaNoticias = obtenerNoticiasDia($fecha, $pkLiga);
        if ($listaNoticias != NULL) {
            $noticias[date('Y-m-d', $fecha)] = $listaNoticias;
        }
    }

    echo json_encode($noticias);
}
?>
