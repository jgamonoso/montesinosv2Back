<?php
	require_once __DIR__ . '/../conexionbd.php';
	require_once __DIR__ . '/../objetos/equipo.php';
	// require_once("/home/montesinyy/www/objetos/equiposorteo.php");
	require_once __DIR__ . '/gestorjugadorliga.php';
	require_once __DIR__ . '/gestordraftpick.php';
	require_once __DIR__ . '/gestorsancion.php';
	require_once __DIR__ . '/gestorbonus.php';
	require_once __DIR__ . '/gestorapuesta.php';
	require_once __DIR__ . '/gestorpalmares.php';
	require_once __DIR__ . '/gestorrecord.php';
	require_once __DIR__ . '/gestoroferta.php';
	require_once __DIR__ . '/gestortemporada.php';
	require_once __DIR__ . '/gestorwaiver.php';
	require_once __DIR__ . '/gestornoticia.php';
	require_once __DIR__ . '/gestorsuceso.php';
	require_once __DIR__ . '/gestortrade.php';
	// require_once("/home/montesinyy/www/gestores/gestorcontrato.php");
	// require_once("/home/montesinyy/www/gestores/gestorparametro.php");
	// require_once("/home/montesinyy/www/gestores/gestoremail.php");

	function obtenerEquipo($pkManager)
	{
		$sql = "select * from equipo where fk_equipo_manager=".$pkManager;

		$result = consultarSql($sql);

		if ($result->num_rows > 0) {
			$row = $result->fetch_assoc();

			$eq = new Equipo();
			$eq->pkEquipo = $row["pk_equipo"];
			$eq->nombre = $row["equipo_nombre"];
			$eq->capLibre = $row["equipo_cap_libre"];
			$eq->waiver = $row["equipo_waiver"];
			$eq->fkLiga = $row["fk_equipo_liga"];

			$eq->corteGratisHabilitado = $row["equipo_corte_gratis"];
			$eq->bloqueado = $row["equipo_bloqueado"];
			$eq->numMovesDisponibles = $row["equipo_moves_semanales"];

			$eq->jugadoresConContrato = obtenerJugadoresConContratoEquipo($eq->pkEquipo);
			$eq->jugadoresIL = obtenerJugadoresIL($eq->pkEquipo);
			$eq->jugadoresLesionados = obtenerJugadoresLesionadosEquipo($eq->pkEquipo);
			$eq->jugadoresCovid = obtenerJugadoresCOVID($eq->pkEquipo);
			$eq->jugadoresConDerecho = obtenerJugadoresConDerechoEquipo($eq->pkEquipo);
			$eq->draftpicks = obtenerDraftpicksEquipo($eq->pkEquipo);

			$eq->sanciones = obtenerSancionesEquipo($eq->pkEquipo);
			$eq->bonus = obtenerBonusEquipo($eq->pkEquipo);

			$eq->apuesta = obtenerApuestaEquipo($eq->pkEquipo);

			$eq->palmares = obtenerPalmaresEquipo($eq->pkEquipo);
			$eq->records = obtenerRecordsEquipo($eq->pkEquipo);

			return $eq;
		}

		return NULL;
	}

	function obtenerDatosListadoEquipos($pkManager)
	{
		$sql = "select * from equipo where fk_equipo_manager=".$pkManager;

		$result = consultarSql($sql);

		if ($result->num_rows > 0) {
			$row = $result->fetch_assoc();

			$eq = new Equipo();
			$eq->pkEquipo = $row["pk_equipo"];
			$eq->nombre = $row["equipo_nombre"];
			$eq->capLibre = $row["equipo_cap_libre"];
			$eq->waiver = $row["equipo_waiver"];
			$eq->numJugadoresConContrato = obtenerNumJugadoresConContratoEquipo($eq->pkEquipo);

			return $eq;
		}

		return NULL;
	}

	function obtenerNombreEquipo($pkEquipo)
	{
		$nombre = "";
		$sql = "select equipo_nombre from equipo where pk_equipo=".$pkEquipo;

		$result = consultarSql($sql);

		if ($result->num_rows > 0) {
			$row = $result->fetch_assoc();

			$nombre = $row["equipo_nombre"];
		}

		return $nombre;
	}

	function obtenerListaEquiposNombre()
	{
		$sql = "select * from equipo";

		$result = consultarSql($sql);

		if ($result->num_rows > 0) {

			$listaEquipos = array();

			while ($row = $result->fetch_assoc())
			{
				$eq = new Equipo();
				$eq->pkEquipo = $row["pk_equipo"];
				$eq->nombre = $row["equipo_nombre"];

				array_push($listaEquipos, $eq);
			}

			return $listaEquipos;
		}

		return NULL;
	}

	function dropJugador($pkManager,$pkJugadorliga,$pkEquipo,$sancionAplicable)
	{
		$jugadorliga = obtenerJugadorliga($pkJugadorliga);


		// obtener contrato
		$contrato = obtenerContratoJugador($pkJugadorliga);

		// Actualizar equipo que lo drop&oacute;
		$sql = "update jugadorliga set fk_jugadorliga_equipo_drop=".$pkEquipo.", jugadorliga_exequipo_salario = ".$contrato->salario.", jugadorliga_tradingblock=0 where pk_jugadorliga=".$pkJugadorliga;
		ejecutarSql($sql);

		cancelarOfertasConContrato($contrato->pkContrato);
		cancelarTradesConContrato($contrato->pkContrato);

		// borrar contratos
		$sql = "delete from contrato where fk_contrato_jugadorliga=".$pkJugadorliga;
		ejecutarSql($sql);

		// actualizar cap libre equipo
		$sql = "update equipo set equipo_cap_libre=ROUND(equipo_cap_libre + ".$contrato->salario.",1) where pk_equipo=".$pkEquipo;
		ejecutarSql($sql);

		// comprobar sancion por drop
		if ($sancionAplicable && $contrato->salario >= 2)
		{
			$sql = "select equipo_corte_gratis from equipo where pk_equipo=".$pkEquipo;
			$result = consultarSql($sql);
			$row = $result->fetch_assoc();
			$corteGratis = $row["equipo_corte_gratis"];

			if ($corteGratis == 0)
			{
				$temporadaActual = obtenerTemporadaActual();
				if (!$contrato->esContratoRookie || $temporadaActual->estado != "TEAM_OPTION")
				{
					// crear sancion
					for ($i=$temporadaActual->pkTemporada; $i <= $contrato->temporadaFin->pkTemporada; $i++)
					{
						altaSancion($pkEquipo, round(($contrato->salario / 2),1), $i, "Drop del jugador ".obtenerJugadorliga($pkJugadorliga)->jugador->nombre." ".obtenerJugadorliga($pkJugadorliga)->jugador->apellido,$jugadorliga->fkLiga);
					}
				}
			}
			else{
				$sql = "update equipo set equipo_corte_gratis=0 where pk_equipo=".$pkEquipo;
				ejecutarSql($sql);
			}
		}

		// crear waiver. No crearlo si se dropa al jugador el mismo d&iacute;a que se a&ntilde;adio
		// if ($contrato->fecha != date('Ymd')) altaWaiver($pkJugadorliga,$pkEquipo,$jugadorliga->fkLiga);

		// MODIFICADO 11/07/2019 (JUAN).
		// crear waiver. a partir de ahora se hace waiver siempre que el corte sea fuera de la regular season o siempre que se haga en distinto dia al fichaje
		if ($contrato->fecha != date('Ymd') || $temporadaActual->estado != "SEASON")
		{
			altaWaiver($pkJugadorliga, $pkEquipo, $jugadorliga->fkLiga);
		}

		altaNoticia("<b>".obtenerNombreEquipo($pkEquipo)."</b> suelta al jugador <b>".obtenerJugadorliga($pkJugadorliga)->jugador->nombre." ".obtenerJugadorliga($pkJugadorliga)->jugador->apellido."</b>", 3,$jugadorliga->fkLiga);

		crearSuceso($pkManager,$pkEquipo, "DROP_JUGADOR", $pkJugadorliga);
	}
?>