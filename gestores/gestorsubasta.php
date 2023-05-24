<?php
	require_once __DIR__ . '/../conexionbd.php';
	require_once __DIR__ . '/../objetos/subasta.php';
	require_once __DIR__ . '/gestorpuja.php';
	// require_once __DIR__ . '/gestoremail.php';
	// require_once __DIR__ . '/gestorsuceso.php';

	function obtenerSubasta($pkJugadorliga, $pkEquipo)
	{
		$sql = "select * from subasta where subasta_estado='ABIERTA' and fk_subasta_jugadorliga=".$pkJugadorliga;

		$result = consultarSql($sql);

		if ($result->num_rows > 0) {
			$row = $result->fetch_assoc();

			$subasta = new Subasta();
			$subasta->pkSubasta = $row["pk_subasta"];
			$subasta->fkLiga = $row["fk_subasta_liga"];
			$subasta->fkJugadorliga = $row["fk_subasta_jugadorliga"];
			$subasta->estado = $row["subasta_estado"];
			$subasta->fechaIni = $row["subasta_fecha_ini"];
			$subasta->fechaFin = $row["subasta_fecha_fin"];

			$subasta->pujas = obtenerPujas($subasta->pkSubasta);
			$subasta->numPujasRestantes = obtenerNumPujasRestantes($subasta, $pkEquipo);

			return $subasta;
		}

		return NULL;
	}

	function obtenerNumSubastasAbiertas($pkLiga)
	{
		$sql = "select count(*) as numsubastas from subasta where fk_subasta_liga = ".$pkLiga." and subasta_estado='ABIERTA' order by subasta_fecha_ini";

		$result = consultarSql($sql);

		if ($result->num_rows > 0) {

			$row = $result->fetch_assoc();

			return $row["numsubastas"];
		}

		return 0;
	}

	function obtenerNumSubastasAbiertasEquipo($pkEquipo)
	{
		$sql = "select count(*) as numsubastas from subasta where subasta_estado='ABIERTA' and pk_subasta in (select distinct(fk_puja_subasta) from puja where fk_puja_equipo = ".$pkEquipo.") order by subasta_fecha_ini";

		$result = consultarSql($sql);

		if ($result->num_rows > 0) {

			$row = $result->fetch_assoc();

			return $row["numsubastas"];
		}

		return 0;
	}
?>