<?php
	require_once __DIR__ . '/../conexionbd.php';
	require_once __DIR__ . '/../objetos/equipo.php';
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
	require_once __DIR__ . '/gestorparametro.php';
	// require_once("/home/montesinyy/www/objetos/equiposorteo.php");
	// require_once("/home/montesinyy/www/gestores/gestorcontrato.php");
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

	function obtenerEquipoPorPk($pkEquipo)
	{
		$sql = "select * from equipo where pk_equipo=".$pkEquipo;

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

	function desactivarCorteGratis()
	{
		$sql = "update equipo set equipo_corte_gratis = 0";
		ejecutarSql($sql);
	}

	function obtenerListaEquipos()
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
				$eq->capLibre = $row["equipo_cap_libre"];
				$eq->waiver = $row["equipo_waiver"];
				$eq->fkLiga = $row["fk_equipo_liga"];

				$eq->corteGratisHabilitado = $row["equipo_corte_gratis"];
				$eq->bloqueado = $row["equipo_bloqueado"];
				$eq->numMovesDisponibles = $row["equipo_moves_semanales"];

				$eq->jugadoresConContrato = obtenerJugadoresConContratoEquipo($eq->pkEquipo);
				$eq->jugadoresIL = obtenerJugadoresIL($eq->pkEquipo);
				$eq->jugadoresLesionados = obtenerJugadoresLesionados($eq->pkEquipo);
				$eq->jugadoresCovid = obtenerJugadoresCOVID($eq->pkEquipo);
				$eq->jugadoresConDerecho = obtenerJugadoresConDerechoEquipo($eq->pkEquipo);
				$eq->draftpicks = obtenerDraftpicksEquipo($eq->pkEquipo);

				$eq->sanciones = obtenerSancionesEquipo($eq->pkEquipo);
				$eq->bonus = obtenerBonusEquipo($eq->pkEquipo);

				$eq->apuesta = obtenerApuestaEquipo($eq->pkEquipo);

				$eq->palmares = obtenerPalmaresEquipo($eq->pkEquipo);
				$eq->records = obtenerRecordsEquipo($eq->pkEquipo);

				array_push($listaEquipos, $eq);
			}

			return $listaEquipos;
		}

		return NULL;
	}

	function calcularCapEquipos()
	{
		$temporadaActual = obtenerTemporadaActual();
		$capMaximo = obtenerValorParametro("CAP_SPACE");

		$sql = "select pk_equipo from equipo";
		$result = consultarSql($sql);

		if ($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()){
				$pkEquipo = $row["pk_equipo"];

				$sql = "select ROUND(COALESCE(sum(contrato_salario),0),1) as salarios from contrato where contrato_lld = 0 and contrato_covid = 0 and fk_contrato_equipo=".$pkEquipo;
				$result2 = consultarSql($sql);

				$row2 = $result2->fetch_assoc();
				$totalSalarios = $row2["salarios"];

				$sql = "select ROUND(COALESCE(sum(sancion_cantidad),0),1) as sanciones from sancion where fk_sancion_equipo=".$pkEquipo." and fk_sancion_temporada=".$temporadaActual->pkTemporada;
				$result2 = consultarSql($sql);

				$row2 = $result2->fetch_assoc();
				$totalSanciones = $row2["sanciones"];

				$sql = "select ROUND(COALESCE(sum(bonus_cantidad),0),1) as bonuses from bonus where fk_bonus_equipo=".$pkEquipo." and fk_bonus_temporada=".$temporadaActual->pkTemporada;
				$result2 = consultarSql($sql);

				$row2 = $result2->fetch_assoc();
				$totalBonus = $row2["bonuses"];

				$capLibre = $capMaximo - $totalSalarios - $totalSanciones + $totalBonus;
				$sql = "update equipo set equipo_cap_libre = ROUND(".$capLibre.",1) where pk_equipo = ".$pkEquipo;
				ejecutarSql($sql);
			}
		}
	}

	function comprobarExcesoJugadoresSeason()
	{
		$sql = "select count(*) as numjugadores, fk_contrato_equipo from contrato where contrato_lld=0 group by fk_contrato_equipo having numjugadores > 13";
		$result = consultarSql($sql);

		if ($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()){
				$pkEquipo = $row["fk_contrato_equipo"];
				enviarEmailComi($pkEquipo, "Tu equipo ".obtenerNombreEquipo($pkEquipo)." ha sobrepasado el límite de jugadores. Debes regularizar el equipo de inmediato o te arriesgas a ser sancionado.");
			}
		}
	}

	function activarCorteGratis()
	{
		$sql = "update equipo set equipo_corte_gratis = 1";
		ejecutarSql($sql);
	}
?>