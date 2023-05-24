<?php
	require_once __DIR__ . '/../conexionbd.php';
	require_once __DIR__ . '/../objetos/contrato.php';
	require_once __DIR__ . '/gestortemporada.php';
	// require_once("/home/montesinyy/www/gestores/gestorequipo.php");
	// require_once("/home/montesinyy/www/gestores/gestorjugadorliga.php");
	// require_once("/home/montesinyy/www/gestores/gestoremail.php");

	function obtenerContratoJugador($pkJugadorliga)
	{
		$sql = "select * from contrato where fk_contrato_jugadorliga=".$pkJugadorliga;

		$result = consultarSql($sql);

		if ($result->num_rows > 0) {
			$row = $result->fetch_assoc();

			$contrato = new Contrato();
			$contrato->pkContrato = $row["pk_contrato"];
			$contrato->fkJugadorliga = $row["fk_contrato_jugadorliga"];
			$contrato->fkEquipo = $row["fk_contrato_equipo"];
			$contrato->fecha = $row["contrato_fecha"];
			$contrato->temporadaFin = obtenerTemporada($row["fk_contrato_temporada_fin"]);

			$contrato->salario = $row["contrato_salario"];

			$contrato->esContratoRookie = ($row["contrato_rookie"] != "0");
			$contrato->esContratoLesionado = ($row["contrato_lld"] != "0");
			$contrato->esContratoCovid = ($row["contrato_covid"] != "0");
			$contrato->esContratoIL = ($row["contrato_activo"] != "0");

			return $contrato;
		}

		return NULL;
	}

	function obtenerNumLLDEquipo($pkEquipo)
	{
		$sql = "select count(*) as numlld from contrato where contrato_lld=1 and fk_contrato_equipo=".$pkEquipo;

		$result = consultarSql($sql);

		if ($result->num_rows > 0) {

			$row = $result->fetch_assoc();

			return $row["numlld"];
		}

		return 0;
	}
?>