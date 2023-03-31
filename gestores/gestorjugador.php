<?php
	require_once __DIR__ . '/../conexionbd.php';
	require_once __DIR__ . '/../objetos/jugador.php';
	require_once __DIR__ . '/gestorposicion.php';
	// require_once("/home/montesinyy/www/gestores/gestorsuceso.php");

	function obtenerJugador($pkJugador)
	{
		$sql = "select * from jugador where pk_jugador=".$pkJugador;

		$result = consultarSql($sql);

		if ($result->num_rows > 0) {
			$row = $result->fetch_assoc();

			$jugador = new Jugador();
			$jugador->pkJugador = $row["pk_jugador"];
			$jugador->nombre = $row["jugador_nombre"];
			$jugador->apellido = $row["jugador_apellido"];
			$jugador->nombreAbreviado = $row["jugador_abreviado"];
			$jugador->fkEquipoNba = $row["fk_jugador_equiponba"];
			$jugador->notas = $row["jugador_notas"];

			$jugador->posiciones = obtenerPosicionesJugador($pkJugador);

			return $jugador;
		}

		return NULL;
	}

?>