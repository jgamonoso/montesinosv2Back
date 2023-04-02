<?php
	require_once __DIR__ . '/../conexionbd.php';
	require_once __DIR__ . '/../objetos/palmares.php';
	require_once __DIR__ . '/gestortemporada.php';

	function obtenerPalmaresEquipo($pkEquipo)
	{
		$sql = "select * from palmares where fk_palmares_equipo=".$pkEquipo." order by fk_palmares_temporada desc";

		$result = consultarSql($sql);

		if ($result->num_rows > 0) {

			$listaPalmares = array();

			while($row = $result->fetch_assoc()){
				$palmares = new Palmares();
				$palmares->pkPalmares = $row["pk_palmares"];
				$palmares->temporada = obtenerTemporada($row["fk_palmares_temporada"]);
				$palmares->fkLiga = $row["fk_palmares_liga"];
				$palmares->fkEquipo = $row["fk_palmares_equipo"];

				$palmares->logro = $row["palmares_logro"];

				array_push($listaPalmares, $palmares);
			}

			return $listaPalmares;
		}

		return NULL;
	}

?>