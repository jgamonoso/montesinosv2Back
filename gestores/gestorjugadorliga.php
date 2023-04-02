<?php
	require_once __DIR__ . '/../conexionbd.php';
	require_once __DIR__ . '/../objetos/jugadorliga.php';
	require_once __DIR__ . '/gestorjugador.php';
	require_once __DIR__ . '/gestorcontrato.php';
	require_once __DIR__ . '/gestorderecho.php';
	// require_once("/home/montesinyy/www/gestores/gestorposicion.php");
	// require_once("/home/montesinyy/www/gestores/gestortemporada.php");
	// require_once("/home/montesinyy/www/gestores/gestorsuceso.php");

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

	function obtenerJugadoresLesionados($pkEquipo)
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
?>