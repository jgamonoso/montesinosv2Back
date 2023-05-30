<?php
	require_once __DIR__ . '/../conexionbd.php';
	require_once __DIR__ . '/../objetos/subasta.php';
	require_once __DIR__ . '/gestorpuja.php';
	require_once __DIR__ . '/gestorjugadorliga.php';
	require_once __DIR__ . '/gestornoticia.php';
	require_once __DIR__ . '/gestoremail.php';
	require_once __DIR__ . '/gestorequipo.php';
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

	function finalizarSubastasAbiertas()
	{
		$listaSubastasAbiertas = obtenerTodasSubastasAbiertas();

		foreach ((array)$listaSubastasAbiertas as $subasta)
		{
			finalizarSubasta($subasta->pkSubasta);
		}
	}

	function obtenerTodasSubastasAbiertas()
	{
		$sql = "select * from subasta where subasta_estado='ABIERTA' order by subasta_fecha_ini";

		$result = consultarSql($sql);

		if ($result->num_rows > 0) {
			$listaSubastas = array();

			while ($row = $result->fetch_assoc())
			{
				$subasta = new Subasta();
				$subasta->pkSubasta = $row["pk_subasta"];
				$subasta->fkLiga = $row["fk_subasta_liga"];
				$subasta->fkJugadorliga = $row["fk_subasta_jugadorliga"];
				$subasta->estado = $row["subasta_estado"];
				$subasta->fechaIni = $row["subasta_fecha_ini"];
				$subasta->fechaFin = $row["subasta_fecha_fin"];

				$subasta->pujas = obtenerPujas($subasta->pkSubasta);

				array_push($listaSubastas, $subasta);
			}
			return $listaSubastas;
		}

		return NULL;
	}

	function finalizarSubasta($pkSubasta)
	{
		$subasta = obtenerSubastaPorPk($pkSubasta);

		if ($subasta != NULL && $subasta->pujas != NULL)
		{
			$puja = $subasta->pujas[count($subasta->pujas) - 1];
			// crear contrato
			crearContrato($subasta->fkJugadorliga,$puja->fkEquipo,$puja->valor,$puja->anyos,0);

			$sql = "update subasta set subasta_estado='CERRADA' where pk_subasta=".$pkSubasta;
			ejecutarSql($sql);

			$jugadorliga = obtenerJugadorliga($subasta->fkJugadorliga);

			altaNoticia("<b>".obtenerJugadorliga($subasta->fkJugadorliga)->jugador->nombre." ".obtenerJugadorliga($subasta->fkJugadorliga)->jugador->apellido."</b> firma por <b>".obtenerNombreEquipo($puja->fkEquipo)."</b> por ".$puja->valor."M durante ".$puja->anyos." temporadas.", 3, $jugadorliga->fkLiga);
		}
	}

	function obtenerSubastaPorPk($pkSubasta)
	{
		$sql = "select * from subasta where subasta_estado='ABIERTA' and pk_subasta=".$pkSubasta;

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

			return $subasta;
		}

		return NULL;
	}