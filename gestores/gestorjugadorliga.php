<?php
	require_once __DIR__ . '/../conexionbd.php';
	require_once __DIR__ . '/../objetos/jugadorliga.php';
	require_once __DIR__ . '/gestorjugador.php';
	require_once __DIR__ . '/gestorcontrato.php';
	require_once __DIR__ . '/gestorderecho.php';
	require_once __DIR__ . '/gestornoticia.php';
	require_once __DIR__ . '/gestorsuceso.php';
	require_once __DIR__ . '/gestorequipo.php';
	require_once __DIR__ . '/gestorwaiver.php';
	require_once __DIR__ . '/gestorsubasta.php';
	// require_once("/home/montesinyy/www/gestores/gestortemporada.php");

	function obtenerJugadorliga($pkJugadorliga)
	{
		$sql = "select * from jugadorliga where pk_jugadorliga=".$pkJugadorliga;

		$result = consultarSql($sql);

		if ($result->num_rows > 0) {
			$row = $result->fetch_assoc();

			$jugadorliga = new Jugadorliga();
			$jugadorliga->pkJugadorliga = $row["pk_jugadorliga"];
			$jugadorliga->jugador = obtenerJugador($row["fk_jugadorliga_jugador"]);

			$jugadorliga->fkLiga = $row["fk_jugadorliga_liga"];
			$jugadorliga->fkEquipoQueloDropo = $row["fk_jugadorliga_equipo_drop"];
			$jugadorliga->fkEquipoRestringido = $row["fk_jugadorliga_equipo_restringido"];
			$jugadorliga->exequipoSalario = $row["jugadorliga_exequipo_salario"];

			$jugadorliga->contrato = obtenerContratoJugador($pkJugadorliga);
			$jugadorliga->derecho = obtenerDerechoJugador($pkJugadorliga);

			$jugadorliga->enTradingBlock = ($row["jugadorliga_tradingblock"] != "0");
			$jugadorliga->drafteable = ($row["jugadorliga_drafteable"] != "0");

			return $jugadorliga;
		}

		return NULL;
	}


	function obtenerJugadoresConContratoEquipo($pkEquipo)
	{
		$sql = "select *, (select min(fk_posicion) from jugador_posicion where fk_jugador=fk_jugadorliga_jugador) as posicion from jugadorliga where pk_jugadorliga in (select distinct(fk_contrato_jugadorliga) from contrato where fk_contrato_equipo=".$pkEquipo." and contrato_lld=0 and contrato_covid=0 and contrato_activo=1) order by posicion";

		$result = consultarSql($sql);

		if ($result->num_rows > 0) {
			$listaJugadores = array();

			while ($row = $result->fetch_assoc())
			{
				$jugadorliga = new Jugadorliga();
				$jugadorliga->pkJugadorliga = $row["pk_jugadorliga"];
				$jugadorliga->jugador = obtenerJugador($row["fk_jugadorliga_jugador"]);

				$jugadorliga->fkLiga = $row["fk_jugadorliga_liga"];
				$jugadorliga->fkEquipoQueloDropo = $row["fk_jugadorliga_equipo_drop"];
				$jugadorliga->fkEquipoRestringido = $row["fk_jugadorliga_equipo_restringido"];
				$jugadorliga->exequipoSalario = $row["jugadorliga_exequipo_salario"];

				$jugadorliga->contrato = obtenerContratoJugador($jugadorliga->pkJugadorliga);
				$jugadorliga->derecho = obtenerDerechoJugador($jugadorliga->pkJugadorliga);

				$jugadorliga->enTradingBlock = ($row["jugadorliga_tradingblock"] != "0");
				$jugadorliga->drafteable = ($row["jugadorliga_drafteable"] != "0");

				array_push($listaJugadores, $jugadorliga);
			}
			return $listaJugadores;
		}

		return NULL;
	}

	function obtenerNumJugadoresConContratoEquipo($pkEquipo)
	{
		$sql = "SELECT COUNT(DISTINCT c.fk_contrato_jugadorliga) AS num_jugadores FROM equipo e INNER JOIN contrato c ON e.pk_equipo = c.fk_contrato_equipo WHERE e.pk_equipo =" . $pkEquipo . " AND c.contrato_lld = 0 AND c.contrato_covid = 0 AND c.contrato_activo = 1";

		$result = consultarSql($sql);

		if ($result->num_rows > 0) {
			$row = $result->fetch_assoc();
			return $row["num_jugadores"];
		}

		return 0;
	}

	function obtenerJugadoresConDerechoEquipo($pkEquipo)
	{
		$sql = "select * from jugadorliga where pk_jugadorliga in (select distinct(fk_derecho_jugadorliga) from derecho where fk_derecho_equipo=".$pkEquipo.")";

		$result = consultarSql($sql);

		if ($result->num_rows > 0) {
			$listaJugadores = array();

			while ($row = $result->fetch_assoc())
			{
				$jugadorliga = new Jugadorliga();
				$jugadorliga->pkJugadorliga = $row["pk_jugadorliga"];
				$jugadorliga->jugador = obtenerJugador($row["fk_jugadorliga_jugador"]);

				$jugadorliga->fkLiga = $row["fk_jugadorliga_liga"];
				$jugadorliga->fkEquipoQueloDropo = $row["fk_jugadorliga_equipo_drop"];
				$jugadorliga->fkEquipoRestringido = $row["fk_jugadorliga_equipo_restringido"];
				$jugadorliga->exequipoSalario = $row["jugadorliga_exequipo_salario"];

				$jugadorliga->contrato = obtenerContratoJugador($jugadorliga->pkJugadorliga);
				$jugadorliga->derecho = obtenerDerechoJugador($jugadorliga->pkJugadorliga);

				$jugadorliga->enTradingBlock = ($row["jugadorliga_tradingblock"] != "0");
				$jugadorliga->drafteable = ($row["jugadorliga_drafteable"] != "0");

				array_push($listaJugadores, $jugadorliga);
			}
			return $listaJugadores;
		}

		return NULL;
	}

	function obtenerJugadoresLesionadosEquipo($pkEquipo)
	{
		$sql = "select jugadorliga.*, jugador_apellido from jugadorliga,jugador where fk_jugadorliga_jugador=pk_jugador and pk_jugadorliga in (select fk_contrato_jugadorliga from contrato where fk_contrato_equipo=".$pkEquipo." and contrato_lld=1) order by jugador_apellido";

		$result = consultarSql($sql);

		if ($result->num_rows > 0) {
			$listaJugadores = array();

			while ($row = $result->fetch_assoc())
			{
				$jugadorliga = new Jugadorliga();
				$jugadorliga->pkJugadorliga = $row["pk_jugadorliga"];
				$jugadorliga->jugador = obtenerJugador($row["fk_jugadorliga_jugador"]);

				$jugadorliga->fkLiga = $row["fk_jugadorliga_liga"];
				$jugadorliga->fkEquipoQueloDropo = $row["fk_jugadorliga_equipo_drop"];
				$jugadorliga->fkEquipoRestringido = $row["fk_jugadorliga_equipo_restringido"];
				$jugadorliga->exequipoSalario = $row["jugadorliga_exequipo_salario"];

				$jugadorliga->contrato = obtenerContratoJugador($jugadorliga->pkJugadorliga);
				$jugadorliga->derecho = obtenerDerechoJugador($jugadorliga->pkJugadorliga);

				$jugadorliga->enTradingBlock = ($row["jugadorliga_tradingblock"] != "0");
				$jugadorliga->drafteable = ($row["jugadorliga_drafteable"] != "0");

				array_push($listaJugadores, $jugadorliga);
			}
			return $listaJugadores;
		}

		return NULL;
	}

	function obtenerJugadoresCOVID($pkEquipo)
	{
		$sql = "select jugadorliga.*, jugador_apellido from jugadorliga,jugador where fk_jugadorliga_jugador=pk_jugador and pk_jugadorliga in (select fk_contrato_jugadorliga from contrato where fk_contrato_equipo=".$pkEquipo." and contrato_covid=1) order by jugador_apellido";

		$result = consultarSql($sql);

		if ($result->num_rows > 0) {
			$listaJugadores = array();

			while ($row = $result->fetch_assoc())
			{
				$jugadorliga = new Jugadorliga();
				$jugadorliga->pkJugadorliga = $row["pk_jugadorliga"];
				$jugadorliga->jugador = obtenerJugador($row["fk_jugadorliga_jugador"]);

				$jugadorliga->fkLiga = $row["fk_jugadorliga_liga"];
				$jugadorliga->fkEquipoQueloDropo = $row["fk_jugadorliga_equipo_drop"];
				$jugadorliga->fkEquipoRestringido = $row["fk_jugadorliga_equipo_restringido"];
				$jugadorliga->exequipoSalario = $row["jugadorliga_exequipo_salario"];

				$jugadorliga->contrato = obtenerContratoJugador($jugadorliga->pkJugadorliga);
				$jugadorliga->derecho = obtenerDerechoJugador($jugadorliga->pkJugadorliga);

				$jugadorliga->enTradingBlock = ($row["jugadorliga_tradingblock"] != "0");
				$jugadorliga->drafteable = ($row["jugadorliga_drafteable"] != "0");

				array_push($listaJugadores, $jugadorliga);
			}
			return $listaJugadores;
		}

		return NULL;
	}

	function obtenerJugadoresIL($pkEquipo)
	{
		$sql = "select jugadorliga.*, jugador_apellido from jugadorliga,jugador where fk_jugadorliga_jugador=pk_jugador and pk_jugadorliga in (select fk_contrato_jugadorliga from contrato where fk_contrato_equipo=".$pkEquipo." and contrato_activo=0 and contrato_covid=0) order by jugador_apellido";

		$result = consultarSql($sql);

		if ($result->num_rows > 0) {
			$listaJugadores = array();

			while ($row = $result->fetch_assoc())
			{
				$jugadorliga = new Jugadorliga();
				$jugadorliga->pkJugadorliga = $row["pk_jugadorliga"];
				$jugadorliga->jugador = obtenerJugador($row["fk_jugadorliga_jugador"]);

				$jugadorliga->fkLiga = $row["fk_jugadorliga_liga"];
				$jugadorliga->fkEquipoQueloDropo = $row["fk_jugadorliga_equipo_drop"];
				$jugadorliga->fkEquipoRestringido = $row["fk_jugadorliga_equipo_restringido"];
				$jugadorliga->exequipoSalario = $row["jugadorliga_exequipo_salario"];

				$jugadorliga->contrato = obtenerContratoJugador($jugadorliga->pkJugadorliga);
				$jugadorliga->derecho = obtenerDerechoJugador($jugadorliga->pkJugadorliga);

				$jugadorliga->enTradingBlock = ($row["jugadorliga_tradingblock"] != "0");
				$jugadorliga->drafteable = ($row["jugadorliga_drafteable"] != "0");

				array_push($listaJugadores, $jugadorliga);
			}
			return $listaJugadores;
		}

		return NULL;
	}

	function activarILDeJugador($pkManager, $pkJugadorliga, $pkLiga, $pkEquipo)
	{
		$sql = "update contrato set contrato_activo=0 where fk_contrato_jugadorliga=".$pkJugadorliga;
		ejecutarSql($sql);

		$sql = "update jugadorliga set jugadorliga_tradingblock=0 where pk_jugadorliga=".$pkJugadorliga;
		ejecutarSql($sql);

		altaNoticia("<b>".obtenerNombreEquipo($pkEquipo)."</b>: IL activada para <b>".obtenerJugadorliga($pkJugadorliga)->jugador->nombre." ".obtenerJugadorliga($pkJugadorliga)->jugador->apellido."</b>", 3, $pkLiga);			
		crearSuceso($pkManager, "NULL", "ACTIVAR_IL_JUGADOR", $pkJugadorliga);
	}

	function recuperarJugadordeIL($pkManager, $pkJugadorliga, $pkLiga, $pkEquipo)
	{
		$sql = "update contrato set contrato_activo=1 where fk_contrato_jugadorliga=".$pkJugadorliga;
		ejecutarSql($sql);

		altaNoticia("<b>".obtenerJugadorliga($pkJugadorliga)->jugador->nombre." ".obtenerJugadorliga($pkJugadorliga)->jugador->apellido."</b> recuperado de IL por <b>".obtenerNombreEquipo($pkEquipo)."</b>.", 3, $pkLiga);			
		crearSuceso($pkManager, "NULL", "RECUPERAR_IL_JUGADOR", $pkJugadorliga);
	}

	function obtenerListaJugadoresBuscadosFUSION($pkLiga, $filtro)
	{
		$sql = "SELECT jugadorliga.*, jugador_apellido,
				(CASE
					WHEN pk_jugadorliga NOT IN (SELECT DISTINCT(fk_derecho_jugadorliga) FROM derecho)
					AND pk_jugadorliga NOT IN (SELECT DISTINCT(fk_contrato_jugadorliga) FROM contrato)
					AND jugadorliga_drafteable = 0 THEN 'AL'
					WHEN pk_jugadorliga IN (SELECT DISTINCT(fk_contrato_jugadorliga) FROM contrato WHERE contrato_lld = 0 AND contrato_covid = 0) THEN 'CONTRATO'
					WHEN pk_jugadorliga IN (SELECT fk_derecho_jugadorliga FROM derecho) THEN 'DERECHO'
					WHEN pk_jugadorliga IN (SELECT DISTINCT(fk_contrato_jugadorliga) FROM contrato WHERE contrato_lld = 1) THEN 'LLD'
					WHEN pk_jugadorliga IN (SELECT DISTINCT(fk_contrato_jugadorliga) FROM contrato WHERE contrato_covid = 1) THEN 'COVID'
					ELSE 'OTHER'
				END) as tipo
				FROM jugadorliga, jugador
				WHERE fk_jugadorliga_liga = $pkLiga
				AND fk_jugadorliga_jugador = pk_jugador
				AND (jugador_nombre LIKE '%$filtro%' OR jugador_apellido LIKE '%$filtro%')
				ORDER BY jugador_apellido";

		$result = consultarSql($sql);

		$listas = array(
			'AL' => array(),
			'CONTRATO' => array(),
			'DERECHO' => array(),
			'LLD' => array(),
			'COVID' => array()
		);

		if ($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()) {
				$jugadorliga = new Jugadorliga();
				$jugadorliga->pkJugadorliga = $row["pk_jugadorliga"];
					$jugadorliga->jugador = obtenerJugador($row["fk_jugadorliga_jugador"]);

					$jugadorliga->fkLiga = $row["fk_jugadorliga_liga"];
					$jugadorliga->fkEquipoQueloDropo = $row["fk_jugadorliga_equipo_drop"];
					$jugadorliga->fkEquipoRestringido = $row["fk_jugadorliga_equipo_restringido"];
					$jugadorliga->exequipoSalario = $row["jugadorliga_exequipo_salario"];

					$jugadorliga->contrato = obtenerContratoJugador($jugadorliga->pkJugadorliga);
					$jugadorliga->derecho = obtenerDerechoJugador($jugadorliga->pkJugadorliga);

					$jugadorliga->enTradingBlock = ($row["jugadorliga_tradingblock"] != "0");
					$jugadorliga->drafteable = ($row["jugadorliga_drafteable"] != "0");
					$jugadorliga->waiver = obtenerWaiver($jugadorliga->pkJugadorliga);

				$listas[$row['tipo']][] = $jugadorliga;
			}
		}

		return array(
			'listaALJugadores' => $listas['AL'],
			'listaJugadores' => $listas['CONTRATO'],
			'listaDerechos' => $listas['DERECHO'],
			'listaOFS' => $listas['LLD'],
			'listaCOVID' => $listas['COVID']
		);
	}

	function obtenerJugadoresLLDConContrato($pkLiga)
	{
		// if ($ordenacion == "nombre"){
		// 	$sql = "select jugadorliga.*,jugador_apellido,jugador_nombre from jugadorliga,jugador where fk_jugadorliga_liga=".$pkLiga." and fk_jugadorliga_jugador=pk_jugador and pk_jugadorliga in (select distinct(fk_contrato_jugadorliga) from contrato where contrato_lld=1) order by jugador_nombre, jugador_apellido";
		// } else if ($ordenacion == "equipoNba"){
		// 	$sql = "select jugadorliga.*,jugador_apellido,fk_jugador_equiponba from jugadorliga,jugador where fk_jugadorliga_liga=".$pkLiga." and fk_jugadorliga_jugador=pk_jugador and pk_jugadorliga in (select distinct(fk_contrato_jugadorliga) from contrato where contrato_lld=1) order by fk_jugador_equiponba, jugador_apellido";
		// } else if ($ordenacion == "posicion"){
		// 	$sql = "select distinct jugadorliga.*,jugador_apellido from jugadorliga,jugador,posicion where fk_jugadorliga_liga=".$pkLiga." and fk_jugadorliga_jugador=pk_jugador and pk_jugadorliga in (select distinct(fk_contrato_jugadorliga) from contrato where contrato_lld=1) AND (pk_posicion IN(SELECT fk_posicion FROM jugador_posicion WHERE fk_jugador = fk_jugadorliga_jugador)) order by posicion.pk_posicion, jugador_apellido";
		// } else {
			$sql = "select jugadorliga.*,jugador_apellido from jugadorliga,jugador where fk_jugadorliga_liga=".$pkLiga." and fk_jugadorliga_jugador=pk_jugador and pk_jugadorliga in (select distinct(fk_contrato_jugadorliga) from contrato where contrato_lld=1) order by jugador_apellido";
		// }

		$result = consultarSql($sql);

		if ($result->num_rows > 0) {
			$listaJugadores = array();

			while ($row = $result->fetch_assoc())
			{
				$jugadorliga = new Jugadorliga();
				$jugadorliga->pkJugadorliga = $row["pk_jugadorliga"];
				$jugadorliga->jugador = obtenerJugador($row["fk_jugadorliga_jugador"]);

				$jugadorliga->fkLiga = $row["fk_jugadorliga_liga"];
				$jugadorliga->fkEquipoQueloDropo = $row["fk_jugadorliga_equipo_drop"];
				$jugadorliga->fkEquipoRestringido = $row["fk_jugadorliga_equipo_restringido"];
				$jugadorliga->exequipoSalario = $row["jugadorliga_exequipo_salario"];

				$jugadorliga->contrato = obtenerContratoJugador($jugadorliga->pkJugadorliga);
				$jugadorliga->derecho = obtenerDerechoJugador($jugadorliga->pkJugadorliga);

				$jugadorliga->enTradingBlock = ($row["jugadorliga_tradingblock"] != "0");
				$jugadorliga->drafteable = ($row["jugadorliga_drafteable"] != "0");
				// $jugadorliga->waiver = obtenerWaiver($jugadorliga->pkJugadorliga);

				array_push($listaJugadores, $jugadorliga);
			}
			return $listaJugadores;
		}

		return NULL;
	}

	function obtenerJugadoresCOVIDConContrato($pkLiga)
	{
		// if ($ordenacion == "nombre"){
		// 	$sql = "select jugadorliga.*,jugador_apellido,jugador_nombre from jugadorliga,jugador where fk_jugadorliga_liga=".$pkLiga." and fk_jugadorliga_jugador=pk_jugador and pk_jugadorliga in (select distinct(fk_contrato_jugadorliga) from contrato where contrato_covid=1) order by jugador_nombre, jugador_apellido";
		// } else if ($ordenacion == "equipoNba"){
		// 	$sql = "select jugadorliga.*,jugador_apellido,fk_jugador_equiponba from jugadorliga,jugador where fk_jugadorliga_liga=".$pkLiga." and fk_jugadorliga_jugador=pk_jugador and pk_jugadorliga in (select distinct(fk_contrato_jugadorliga) from contrato where contrato_covid=1) order by fk_jugador_equiponba, jugador_apellido";
		// } else if ($ordenacion == "posicion"){
		// 	$sql = "select distinct jugadorliga.*,jugador_apellido from jugadorliga,jugador,posicion where fk_jugadorliga_liga=".$pkLiga." and fk_jugadorliga_jugador=pk_jugador and pk_jugadorliga in (select distinct(fk_contrato_jugadorliga) from contrato where contrato_covid=1) AND (pk_posicion IN(SELECT fk_posicion FROM jugador_posicion WHERE fk_jugador = fk_jugadorliga_jugador)) order by posicion.pk_posicion, jugador_apellido";
		// } else {
			$sql = "select jugadorliga.*,jugador_apellido from jugadorliga,jugador where fk_jugadorliga_liga=".$pkLiga." and fk_jugadorliga_jugador=pk_jugador and pk_jugadorliga in (select distinct(fk_contrato_jugadorliga) from contrato where contrato_covid=1) order by jugador_apellido";
		// }

		$result = consultarSql($sql);

		if ($result->num_rows > 0) {
			$listaJugadores = array();

			while ($row = $result->fetch_assoc())
			{
				$jugadorliga = new Jugadorliga();
				$jugadorliga->pkJugadorliga = $row["pk_jugadorliga"];
				$jugadorliga->jugador = obtenerJugador($row["fk_jugadorliga_jugador"]);

				$jugadorliga->fkLiga = $row["fk_jugadorliga_liga"];
				$jugadorliga->fkEquipoQueloDropo = $row["fk_jugadorliga_equipo_drop"];
				$jugadorliga->fkEquipoRestringido = $row["fk_jugadorliga_equipo_restringido"];
				$jugadorliga->exequipoSalario = $row["jugadorliga_exequipo_salario"];

				$jugadorliga->contrato = obtenerContratoJugador($jugadorliga->pkJugadorliga);
				$jugadorliga->derecho = obtenerDerechoJugador($jugadorliga->pkJugadorliga);

				$jugadorliga->enTradingBlock = ($row["jugadorliga_tradingblock"] != "0");
				$jugadorliga->drafteable = ($row["jugadorliga_drafteable"] != "0");
				// $jugadorliga->waiver = obtenerWaiver($jugadorliga->pkJugadorliga);

				array_push($listaJugadores, $jugadorliga);
			}
			return $listaJugadores;
		}

		return NULL;
	}

	function obtenerJugadoresILLiga($pkLiga)
	{
		$sql = "    select jugadorliga.*, jugador_apellido from jugadorliga,jugador where fk_jugadorliga_jugador=pk_jugador and pk_jugadorliga in (select fk_contrato_jugadorliga from contrato where fk_jugadorliga_liga=".$pkLiga." and contrato_activo=0) order by jugador_apellido";

		// if ($ordenacion == "nombre"){
		// 	$sql = "select jugadorliga.*, jugador_apellido,jugador_nombre from jugadorliga,jugador where fk_jugadorliga_jugador=pk_jugador and pk_jugadorliga in (select fk_contrato_jugadorliga from contrato where fk_jugadorliga_liga=".$pkLiga." and contrato_activo=0 AND contrato_covid = 0) order by jugador_nombre, jugador_apellido";
		// } else if ($ordenacion == "equipoNba"){
		// 	$sql = "select jugadorliga.*, jugador_apellido,fk_jugador_equiponba from jugadorliga,jugador where fk_jugadorliga_jugador=pk_jugador and pk_jugadorliga in (select fk_contrato_jugadorliga from contrato where fk_jugadorliga_liga=".$pkLiga." and contrato_activo=0 AND contrato_covid = 0) order by fk_jugador_equiponba, jugador_apellido";
		// } else if ($ordenacion == "posicion"){
		// 	$sql = "select distinct jugadorliga.*, jugador_apellido from jugadorliga,jugador,posicion where fk_jugadorliga_jugador=pk_jugador and pk_jugadorliga in (select fk_contrato_jugadorliga from contrato where fk_jugadorliga_liga=".$pkLiga." and contrato_activo=0 AND contrato_covid = 0) AND (pk_posicion IN(SELECT fk_posicion FROM jugador_posicion WHERE fk_jugador = fk_jugadorliga_jugador))order by posicion.pk_posicion, jugador_apellido";
		// } else {
		// 	$sql = "select jugadorliga.*, jugador_apellido from jugadorliga,jugador where fk_jugadorliga_jugador=pk_jugador and pk_jugadorliga in (select fk_contrato_jugadorliga from contrato where fk_jugadorliga_liga=".$pkLiga." and contrato_activo=0 AND contrato_covid = 0) order by jugador_apellido";
		// }

		$result = consultarSql($sql);

		if ($result->num_rows > 0) {
			$listaJugadores = array();

			while ($row = $result->fetch_assoc())
			{
				$jugadorliga = new Jugadorliga();
				$jugadorliga->pkJugadorliga = $row["pk_jugadorliga"];
				$jugadorliga->jugador = obtenerJugador($row["fk_jugadorliga_jugador"]);

				$jugadorliga->fkLiga = $row["fk_jugadorliga_liga"];
				$jugadorliga->fkEquipoQueloDropo = $row["fk_jugadorliga_equipo_drop"];
				$jugadorliga->fkEquipoRestringido = $row["fk_jugadorliga_equipo_restringido"];
				$jugadorliga->exequipoSalario = $row["jugadorliga_exequipo_salario"];

				$jugadorliga->contrato = obtenerContratoJugador($jugadorliga->pkJugadorliga);
				$jugadorliga->derecho = obtenerDerechoJugador($jugadorliga->pkJugadorliga);

				$jugadorliga->enTradingBlock = ($row["jugadorliga_tradingblock"] != "0");
				$jugadorliga->drafteable = ($row["jugadorliga_drafteable"] != "0");
				// $jugadorliga->waiver = obtenerWaiver($jugadorliga->pkJugadorliga);

				array_push($listaJugadores, $jugadorliga);
			}
			return $listaJugadores;
		}

		return NULL;
	}

	function obtenerJugadoresConContrato($pkLiga)
	{
		// if ($ordenacion == "nombre"){
		// 	$sql = "select jugadorliga.*,jugador_apellido,jugador_nombre from jugadorliga,jugador where fk_jugadorliga_liga=".$pkLiga." and fk_jugadorliga_jugador=pk_jugador and pk_jugadorliga in (select distinct(fk_contrato_jugadorliga) from contrato where contrato_lld=0 and contrato_covid=0) order by jugador_nombre, jugador_apellido";
		// } else if ($ordenacion == "equipoNba"){
		// 	$sql = "select jugadorliga.*,jugador_apellido,fk_jugador_equiponba from jugadorliga,jugador where fk_jugadorliga_liga=".$pkLiga." and fk_jugadorliga_jugador=pk_jugador and pk_jugadorliga in (select distinct(fk_contrato_jugadorliga) from contrato where contrato_lld=0 and contrato_covid=0) order by fk_jugador_equiponba, jugador_apellido";
		// } else if ($ordenacion == "posicion"){
		// 	$sql = "select distinct jugadorliga.*,jugador_apellido from jugadorliga,jugador,posicion where fk_jugadorliga_liga=".$pkLiga." and fk_jugadorliga_jugador=pk_jugador and pk_jugadorliga in (select distinct(fk_contrato_jugadorliga) from contrato where contrato_lld=0 and contrato_covid=0) AND (pk_posicion IN(SELECT fk_posicion FROM jugador_posicion WHERE fk_jugador = fk_jugadorliga_jugador)) order by posicion.pk_posicion, jugador_apellido";
		// } else {
			$sql = "select jugadorliga.*,jugador_apellido from jugadorliga,jugador where fk_jugadorliga_liga=".$pkLiga." and fk_jugadorliga_jugador=pk_jugador and pk_jugadorliga in (select distinct(fk_contrato_jugadorliga) from contrato where contrato_lld=0 and contrato_covid=0) order by jugador_apellido";
		// }

		$result = consultarSql($sql);

		if ($result->num_rows > 0) {
			$listaJugadores = array();

			while ($row = $result->fetch_assoc())
			{
				$jugadorliga = new Jugadorliga();
				$jugadorliga->pkJugadorliga = $row["pk_jugadorliga"];
				$jugadorliga->jugador = obtenerJugador($row["fk_jugadorliga_jugador"]);

				$jugadorliga->fkLiga = $row["fk_jugadorliga_liga"];
				$jugadorliga->fkEquipoQueloDropo = $row["fk_jugadorliga_equipo_drop"];
				$jugadorliga->fkEquipoRestringido = $row["fk_jugadorliga_equipo_restringido"];
				$jugadorliga->exequipoSalario = $row["jugadorliga_exequipo_salario"];

				$jugadorliga->contrato = obtenerContratoJugador($jugadorliga->pkJugadorliga);
				// $jugadorliga->derecho = obtenerDerechoJugador($jugadorliga->pkJugadorliga);

				$jugadorliga->enTradingBlock = ($row["jugadorliga_tradingblock"] != "0");
				$jugadorliga->drafteable = ($row["jugadorliga_drafteable"] != "0");
				// $jugadorliga->waiver = obtenerWaiver($jugadorliga->pkJugadorliga);

				array_push($listaJugadores, $jugadorliga);
			}
			return $listaJugadores;
		}

		return NULL;
	}

	function obtenerListaJugadoresConDerecho($pkLiga)
	{
		// if ($ordenacion == "nombre"){
		// 	$sql = "select jugadorliga.*,jugador_apellido, jugador_nombre from jugadorliga,jugador where fk_jugadorliga_liga=".$pkLiga." and fk_jugadorliga_jugador=pk_jugador and pk_jugadorliga in (select fk_derecho_jugadorliga from derecho) order by jugador_nombre, jugador_apellido";
		// } else if ($ordenacion == "equipoNba"){
		// 	$sql = "select jugadorliga.*,jugador_apellido, fk_jugador_equiponba from jugadorliga,jugador where fk_jugadorliga_liga=".$pkLiga." and fk_jugadorliga_jugador=pk_jugador and pk_jugadorliga in (select fk_derecho_jugadorliga from derecho) order by fk_jugador_equiponba, jugador_apellido";
		// } else if ($ordenacion == "posicion"){
		// 	$sql = "select distinct jugadorliga.*,jugador_apellido from jugadorliga,jugador,posicion where fk_jugadorliga_liga=".$pkLiga." and fk_jugadorliga_jugador=pk_jugador and pk_jugadorliga in (select fk_derecho_jugadorliga from derecho) AND ( pk_posicion IN(SELECT fk_posicion FROM jugador_posicion WHERE  fk_jugador = fk_jugadorliga_jugador)) order by posicion.pk_posicion, jugador_apellido";
		// } else {
			$sql = "select jugadorliga.*,jugador_apellido from jugadorliga,jugador where fk_jugadorliga_liga=".$pkLiga." and fk_jugadorliga_jugador=pk_jugador and pk_jugadorliga in (select fk_derecho_jugadorliga from derecho) order by jugador_apellido";
		// }

		$result = consultarSql($sql);

		if ($result->num_rows > 0) {
			$listaJugadores = array();

			while ($row = $result->fetch_assoc())
			{
				$jugadorliga = new Jugadorliga();
				$jugadorliga->pkJugadorliga = $row["pk_jugadorliga"];
				$jugadorliga->jugador = obtenerJugador($row["fk_jugadorliga_jugador"]);

				$jugadorliga->fkLiga = $row["fk_jugadorliga_liga"];
				$jugadorliga->fkEquipoQueloDropo = $row["fk_jugadorliga_equipo_drop"];
				$jugadorliga->fkEquipoRestringido = $row["fk_jugadorliga_equipo_restringido"];
				$jugadorliga->exequipoSalario = $row["jugadorliga_exequipo_salario"];

				$jugadorliga->contrato = obtenerContratoJugador($jugadorliga->pkJugadorliga);
				$jugadorliga->derecho = obtenerDerechoJugador($jugadorliga->pkJugadorliga);

				$jugadorliga->enTradingBlock = ($row["jugadorliga_tradingblock"] != "0");
				$jugadorliga->drafteable = ($row["jugadorliga_drafteable"] != "0");

				array_push($listaJugadores, $jugadorliga);
			}
			return $listaJugadores;
		}

		return NULL;
	}

	function obtenerListaJugadoresConDerechoTradingBlock($pkLiga)
	{
		// if ($ordenacion == "nombre"){
		// 	$sql = "select jugadorliga.*,jugador_apellido, jugador_nombre from jugadorliga,jugador where fk_jugadorliga_liga=".$pkLiga." and fk_jugadorliga_jugador=pk_jugador and pk_jugadorliga in (select fk_derecho_jugadorliga from derecho) and jugadorliga_tradingblock=1 order by jugador_nombre, jugador_apellido";
		// } else if ($ordenacion == "equipoNba"){
		// 	$sql = "select jugadorliga.*,jugador_apellido, fk_jugador_equiponba from jugadorliga,jugador where fk_jugadorliga_liga=".$pkLiga." and fk_jugadorliga_jugador=pk_jugador and pk_jugadorliga in (select fk_derecho_jugadorliga from derecho) and jugadorliga_tradingblock=1 order by fk_jugador_equiponba, jugador_apellido";
		// } else if ($ordenacion == "posicion"){
		// 	$sql = "select distinct jugadorliga.*,jugador_apellido from jugadorliga,jugador, posicion where fk_jugadorliga_liga=".$pkLiga." and fk_jugadorliga_jugador=pk_jugador and pk_jugadorliga in (select fk_derecho_jugadorliga from derecho) and jugadorliga_tradingblock=1 AND ( pk_posicion IN(SELECT fk_posicion FROM jugador_posicion WHERE fk_jugador = fk_jugadorliga_jugador) ) order by posicion.pk_posicion, jugador_apellido";
		// } else {
			$sql = "select jugadorliga.*,jugador_apellido from jugadorliga,jugador where fk_jugadorliga_liga=".$pkLiga." and fk_jugadorliga_jugador=pk_jugador and pk_jugadorliga in (select fk_derecho_jugadorliga from derecho) and jugadorliga_tradingblock=1 order by jugador_apellido";
		// }

		$result = consultarSql($sql);

		if ($result->num_rows > 0) {
			$listaJugadores = array();

			while ($row = $result->fetch_assoc())
			{
				$jugadorliga = new Jugadorliga();
				$jugadorliga->pkJugadorliga = $row["pk_jugadorliga"];
				$jugadorliga->jugador = obtenerJugador($row["fk_jugadorliga_jugador"]);

				$jugadorliga->fkLiga = $row["fk_jugadorliga_liga"];
				$jugadorliga->fkEquipoQueloDropo = $row["fk_jugadorliga_equipo_drop"];
				$jugadorliga->fkEquipoRestringido = $row["fk_jugadorliga_equipo_restringido"];
				$jugadorliga->exequipoSalario = $row["jugadorliga_exequipo_salario"];

				$jugadorliga->contrato = obtenerContratoJugador($jugadorliga->pkJugadorliga);
				$jugadorliga->derecho = obtenerDerechoJugador($jugadorliga->pkJugadorliga);

				$jugadorliga->enTradingBlock = ($row["jugadorliga_tradingblock"] != "0");
				$jugadorliga->drafteable = ($row["jugadorliga_drafteable"] != "0");

				array_push($listaJugadores, $jugadorliga);
			}
			return $listaJugadores;
		}

		return NULL;
	}

	function obtenerListaJugadoresConContratoTradingBlock($pkLiga)
	{
		// if ($ordenacion == "nombre"){
		// 	$sql = "select jugadorliga.*,jugador_apellido, jugador_nombre from jugadorliga,jugador where fk_jugadorliga_liga=".$pkLiga." and fk_jugadorliga_jugador=pk_jugador and pk_jugadorliga in (select distinct(fk_contrato_jugadorliga) from contrato where contrato_lld=0) and jugadorliga_tradingblock=1 order by jugador_nombre, jugador_apellido";
		// } else if ($ordenacion == "equipoNba"){
		// 	$sql = "select jugadorliga.*,jugador_apellido, fk_jugador_equiponba from jugadorliga,jugador where fk_jugadorliga_liga=".$pkLiga." and fk_jugadorliga_jugador=pk_jugador and pk_jugadorliga in (select distinct(fk_contrato_jugadorliga) from contrato where contrato_lld=0) and jugadorliga_tradingblock=1 order by fk_jugador_equiponba, jugador_apellido";
		// } else if ($ordenacion == "posicion"){
		// 	$sql = "select distinct jugadorliga.*,jugador_apellido from jugadorliga,jugador, posicion where fk_jugadorliga_liga=".$pkLiga." and fk_jugadorliga_jugador=pk_jugador and pk_jugadorliga in (select distinct(fk_contrato_jugadorliga) from contrato where contrato_lld=0) and jugadorliga_tradingblock=1 AND ( pk_posicion IN(SELECT fk_posicion FROM jugador_posicion WHERE  fk_jugador = fk_jugadorliga_jugador) ) order by posicion.pk_posicion, jugador_apellido";
		// } else {
			$sql = "select jugadorliga.*,jugador_apellido from jugadorliga,jugador where fk_jugadorliga_liga=".$pkLiga." and fk_jugadorliga_jugador=pk_jugador and pk_jugadorliga in (select distinct(fk_contrato_jugadorliga) from contrato where contrato_lld=0) and jugadorliga_tradingblock=1 order by jugador_apellido";
		// }

		$result = consultarSql($sql);

		if ($result->num_rows > 0) {
			$listaJugadores = array();

			while ($row = $result->fetch_assoc())
			{
				$jugadorliga = new Jugadorliga();
				$jugadorliga->pkJugadorliga = $row["pk_jugadorliga"];
				$jugadorliga->jugador = obtenerJugador($row["fk_jugadorliga_jugador"]);

				$jugadorliga->fkLiga = $row["fk_jugadorliga_liga"];
				$jugadorliga->fkEquipoQueloDropo = $row["fk_jugadorliga_equipo_drop"];
				$jugadorliga->fkEquipoRestringido = $row["fk_jugadorliga_equipo_restringido"];
				$jugadorliga->exequipoSalario = $row["jugadorliga_exequipo_salario"];

				$jugadorliga->contrato = obtenerContratoJugador($jugadorliga->pkJugadorliga);
				$jugadorliga->derecho = obtenerDerechoJugador($jugadorliga->pkJugadorliga);

				$jugadorliga->enTradingBlock = ($row["jugadorliga_tradingblock"] != "0");
				$jugadorliga->drafteable = ($row["jugadorliga_drafteable"] != "0");

				array_push($listaJugadores, $jugadorliga);
			}
			return $listaJugadores;
		}

		return NULL;
	}

	function obtenerListaJugadoresLibres($pkLiga)
	{
		// if ($ordenacion == "nombre"){
		// 	$sql = "select jugadorliga.*,jugador_apellido,jugador_nombre from jugadorliga,jugador where fk_jugadorliga_liga=".$pkLiga." and fk_jugadorliga_jugador=pk_jugador and pk_jugadorliga not in (select distinct(fk_derecho_jugadorliga) from derecho) and pk_jugadorliga not in (select distinct(fk_contrato_jugadorliga) from contrato) and jugadorliga_drafteable=0 order by jugador_nombre, jugador_apellido";
		// } else if ($ordenacion == "equipoNba"){
		// 	$sql = "select jugadorliga.*,jugador_apellido,fk_jugador_equiponba from jugadorliga,jugador where fk_jugadorliga_liga=".$pkLiga." and fk_jugadorliga_jugador=pk_jugador and pk_jugadorliga not in (select distinct(fk_derecho_jugadorliga) from derecho) and pk_jugadorliga not in (select distinct(fk_contrato_jugadorliga) from contrato) and jugadorliga_drafteable=0 order by fk_jugador_equiponba, jugador_apellido";
		// } else if ($ordenacion == "posicion"){
		// 	$sql = "select distinct jugadorliga.*,jugador_apellido from jugadorliga,jugador,posicion where fk_jugadorliga_liga=".$pkLiga." and fk_jugadorliga_jugador=pk_jugador and pk_jugadorliga not in (select distinct(fk_derecho_jugadorliga) from derecho) and pk_jugadorliga not in (select distinct(fk_contrato_jugadorliga) from contrato) and jugadorliga_drafteable=0 AND (pk_posicion IN(SELECT fk_posicion FROM jugador_posicion WHERE fk_jugador = fk_jugadorliga_jugador)) order by posicion.pk_posicion, jugador_apellido";
		// } else {
			$sql = "select jugadorliga.*,jugador_apellido from jugadorliga,jugador where fk_jugadorliga_liga=".$pkLiga." and fk_jugadorliga_jugador=pk_jugador and pk_jugadorliga not in (select distinct(fk_derecho_jugadorliga) from derecho) and pk_jugadorliga not in (select distinct(fk_contrato_jugadorliga) from contrato) and jugadorliga_drafteable=0 order by jugador_apellido";
		// }

		$result = consultarSql($sql);

		if ($result->num_rows > 0) {
			$listaJugadores = array();

			while ($row = $result->fetch_assoc())
			{
				$jugadorliga = new Jugadorliga();
				$jugadorliga->pkJugadorliga = $row["pk_jugadorliga"];
				$jugadorliga->jugador = obtenerJugador($row["fk_jugadorliga_jugador"]);

				$jugadorliga->fkLiga = $row["fk_jugadorliga_liga"];
				$jugadorliga->fkEquipoQueloDropo = $row["fk_jugadorliga_equipo_drop"];
				$jugadorliga->fkEquipoRestringido = $row["fk_jugadorliga_equipo_restringido"];
				$jugadorliga->exequipoSalario = $row["jugadorliga_exequipo_salario"];

				// $jugadorliga->contrato = obtenerContratoJugador($jugadorliga->pkJugadorliga);
				// $jugadorliga->derecho = obtenerDerechoJugador($jugadorliga->pkJugadorliga);

				$jugadorliga->enTradingBlock = ($row["jugadorliga_tradingblock"] != "0");
				$jugadorliga->drafteable = ($row["jugadorliga_drafteable"] != "0");

				array_push($listaJugadores, $jugadorliga);
			}
			return $listaJugadores;
		}

		return NULL;
	}

	function obtenerListaJugadoresLibresOffseason($pkLiga, $pkEquipo)
	{
		// if ($ordenacion == "nombre"){
		// 	$sql = "select jugadorliga.*,jugador_apellido,jugador_nombre from jugadorliga,jugador where fk_jugadorliga_liga=".$pkLiga." and fk_jugadorliga_jugador=pk_jugador and pk_jugadorliga not in (select distinct(fk_derecho_jugadorliga) from derecho) and pk_jugadorliga not in (select distinct(fk_contrato_jugadorliga) from contrato) and jugadorliga_drafteable=0 order by jugador_nombre, jugador_apellido";
		// } else if ($ordenacion == "equipoNba"){
		// 	$sql = "select jugadorliga.*,jugador_apellido,fk_jugador_equiponba from jugadorliga,jugador where fk_jugadorliga_liga=".$pkLiga." and fk_jugadorliga_jugador=pk_jugador and pk_jugadorliga not in (select distinct(fk_derecho_jugadorliga) from derecho) and pk_jugadorliga not in (select distinct(fk_contrato_jugadorliga) from contrato) and jugadorliga_drafteable=0 order by fk_jugador_equiponba, jugador_apellido";
		// } else if ($ordenacion == "posicion"){
		// 	$sql = "select distinct jugadorliga.*,jugador_apellido from jugadorliga,jugador,posicion where fk_jugadorliga_liga=".$pkLiga." and fk_jugadorliga_jugador=pk_jugador and pk_jugadorliga not in (select distinct(fk_derecho_jugadorliga) from derecho) and pk_jugadorliga not in (select distinct(fk_contrato_jugadorliga) from contrato) and jugadorliga_drafteable=0 AND (pk_posicion IN(SELECT fk_posicion FROM jugador_posicion WHERE fk_jugador = fk_jugadorliga_jugador)) order by posicion.pk_posicion, jugador_apellido";
		// } else {
			$sql = "select jugadorliga.*,jugador_apellido from jugadorliga,jugador where fk_jugadorliga_liga=".$pkLiga." and fk_jugadorliga_jugador=pk_jugador and pk_jugadorliga not in (select distinct(fk_derecho_jugadorliga) from derecho) and pk_jugadorliga not in (select distinct(fk_contrato_jugadorliga) from contrato) and jugadorliga_drafteable=0 order by jugador_apellido";
		// }

		$result = consultarSql($sql);

		if ($result->num_rows > 0) {
			$listaJugadores = array();

			while ($row = $result->fetch_assoc())
			{
				$jugadorliga = new Jugadorliga();
				$jugadorliga->pkJugadorliga = $row["pk_jugadorliga"];
				$jugadorliga->jugador = obtenerJugador($row["fk_jugadorliga_jugador"]);

				$jugadorliga->fkLiga = $row["fk_jugadorliga_liga"];
				$jugadorliga->fkEquipoQueloDropo = $row["fk_jugadorliga_equipo_drop"];
				$jugadorliga->fkEquipoRestringido = $row["fk_jugadorliga_equipo_restringido"];
				$jugadorliga->exequipoSalario = $row["jugadorliga_exequipo_salario"];

				// $jugadorliga->contrato = obtenerContratoJugador($jugadorliga->pkJugadorliga);
				// $jugadorliga->derecho = obtenerDerechoJugador($jugadorliga->pkJugadorliga);

				$jugadorliga->enTradingBlock = ($row["jugadorliga_tradingblock"] != "0");
				$jugadorliga->drafteable = ($row["jugadorliga_drafteable"] != "0");

				$jugadorliga->waiver = obtenerWaiver($jugadorliga->pkJugadorliga);

				$jugadorliga->subasta = obtenerSubasta($jugadorliga->pkJugadorliga, $pkEquipo);


				array_push($listaJugadores, $jugadorliga);
			}
			return $listaJugadores;
		}

		return NULL;
	}

	function obtenerListaJugadoresRenovables($pkLiga)
	{
		// if ($ordenacion == "nombre"){
		// 	$sql = "select jugadorliga.*,jugador_apellido,jugador_nombre from jugadorliga,jugador where fk_jugadorliga_liga=".$pkLiga." and fk_jugadorliga_jugador=pk_jugador and fk_jugadorliga_equipo_restringido is not NULL order by jugador_nombre,jugador_apellido";
		// } else if ($ordenacion == "equipoNba"){
		// 	$sql = "select jugadorliga.*,jugador_apellido,fk_jugador_equiponba from jugadorliga,jugador where fk_jugadorliga_liga=".$pkLiga." and fk_jugadorliga_jugador=pk_jugador and fk_jugadorliga_equipo_restringido is not NULL order by fk_jugador_equiponba,jugador_apellido";
		// } else if ($ordenacion == "posicion"){
		// 	$sql = "select jugadorliga.*,jugador_apellido from jugadorliga,jugador where fk_jugadorliga_liga=".$pkLiga." and fk_jugadorliga_jugador=pk_jugador and fk_jugadorliga_equipo_restringido is not NULL order by jugador_apellido";
		// } else {
			$sql = "select jugadorliga.*,jugador_apellido from jugadorliga,jugador where fk_jugadorliga_liga=".$pkLiga." and fk_jugadorliga_jugador=pk_jugador and fk_jugadorliga_equipo_restringido is not NULL order by jugador_apellido";
		// }

		$result = consultarSql($sql);

		if ($result->num_rows > 0) {
			$listaJugadores = array();

			while ($row = $result->fetch_assoc())
			{
				$jugadorliga = new Jugadorliga();
				$jugadorliga->pkJugadorliga = $row["pk_jugadorliga"];
				$jugadorliga->jugador = obtenerJugador($row["fk_jugadorliga_jugador"]);

				$jugadorliga->fkLiga = $row["fk_jugadorliga_liga"];
				$jugadorliga->fkEquipoQueloDropo = $row["fk_jugadorliga_equipo_drop"];
				$jugadorliga->fkEquipoRestringido = $row["fk_jugadorliga_equipo_restringido"];
				$jugadorliga->exequipoSalario = $row["jugadorliga_exequipo_salario"];

				$jugadorliga->contrato = obtenerContratoJugador($jugadorliga->pkJugadorliga);
				$jugadorliga->derecho = obtenerDerechoJugador($jugadorliga->pkJugadorliga);

				$jugadorliga->enTradingBlock = ($row["jugadorliga_tradingblock"] != "0");
				$jugadorliga->drafteable = ($row["jugadorliga_drafteable"] != "0");

				array_push($listaJugadores, $jugadorliga);
			}
			return $listaJugadores;
		}

		return NULL;
	}
?>