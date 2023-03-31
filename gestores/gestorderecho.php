<?php
	require_once __DIR__ . '/../conexionbd.php';
	require_once __DIR__ . '/../objetos/derecho.php';
	require_once __DIR__ . '/gestortemporada.php';
	// require_once("/home/montesinyy/www/gestores/gestorequipo.php");
	// require_once("/home/montesinyy/www/gestores/gestorjugadorliga.php");
	// require_once("/home/montesinyy/www/gestores/gestorcontrato.php");
	// require_once("/home/montesinyy/www/gestores/gestorsuceso.php");

	function obtenerDerechoJugador($pkJugadorliga)
	{
		$sql = "select * from derecho where fk_derecho_jugadorliga=".$pkJugadorliga;

		$result = consultarSql($sql);

		if ($result->num_rows > 0) {

			$row = $result->fetch_assoc();

			$der = new Derecho();
			$der->pkDerecho = $row["pk_derecho"];
			$der->fkJugadorliga = $row["fk_derecho_jugadorliga"];
			$der->fkEquipo = $row["fk_derecho_equipo"];
			$der->temporadaFin = obtenerTemporada($row["fk_derecho_temporada_fin"]);

			$der->salario = $row["derecho_salario"];

			return $der;
		}

		return NULL;
	}

?>