<?php
	require_once __DIR__ . '/../conexionbd.php';
	require_once __DIR__ . '/../objetos/entrenador.php';
	// require_once __DIR__ . '/gestorjugadorliga.php';

	function obtenerListaEntrenadores()
	{
		$sql = "select * from entrenador";

		$result = consultarSql($sql);

		if ($result->num_rows > 0) {
			$listaEntrenador = array();
			while ($row = $result->fetch_assoc())
			{
				$entrenador = new Entrenador();
				$entrenador->pkEntrenador = $row["pk_entrenador"];
				$entrenador->nombre = $row["entrenador_nombre"];
				$entrenador->equipo = $row["entrenador_equipo"];

				array_push($listaEntrenador, $entrenador);
			}
			return $listaEntrenador;
		}

		return NULL;
	}
?>