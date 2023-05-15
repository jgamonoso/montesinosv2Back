<?php
	require_once __DIR__ . '/../conexionbd.php';
	require_once __DIR__ . '/../objetos/waiver.php';
	require_once __DIR__ . '/gestorwaiverclaim.php';

	function obtenerWaiver($pkJugadorliga)
	{
		$sql = "select * from waiver where fk_waiver_jugadorliga=".$pkJugadorliga;

		$result = consultarSql($sql);

		if ($result->num_rows > 0) {

			$row = $result->fetch_assoc();

			$waiver = new Waiver();
			$waiver->pkWaiver = $row["pk_waiver"];
			$waiver->fkEquipo = $row["fk_waiver_equipo"];
			$waiver->fkLiga = $row["fk_waiver_liga"];
			$waiver->fkJugadorliga = $row["fk_waiver_jugadorliga"];
			$waiver->fechaIni = $row["waiver_fecha_ini"];
			$waiver->fechaFin = $row["waiver_fecha_fin"];

			$waiver->claims = obtenerClaimsJugador($waiver->fkJugadorliga);

			return $waiver;
		}

		return NULL;
	}

	function altaWaiver($pkJugadorliga,$pkEquipo,$pkLiga)
	{
		$finWaiver = date("Ymd", strtotime('tomorrow'));
		if (date("H")>8) $finWaiver = date("Ymd", strtotime("+2 days"));

		$sql = "insert into waiver (fk_waiver_equipo, fk_waiver_jugadorliga, fk_waiver_liga, waiver_fecha_ini, waiver_fecha_fin) values (".$pkEquipo.",".$pkJugadorliga.",".$pkLiga.",'".date('Ymd')."','".$finWaiver."') ";
		ejecutarSql($sql);
	}
?>