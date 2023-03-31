<?php
	require_once __DIR__ . '/../conexionbd.php';
	require_once __DIR__ . '/../objetos/equipo.php';
	require_once __DIR__ . '/gestorjugadorliga.php';
	// require_once("/home/montesinyy/www/objetos/equiposorteo.php");
	// require_once("/home/montesinyy/www/gestores/gestordraftpick.php");
	// require_once("/home/montesinyy/www/gestores/gestorsancion.php");
	// require_once("/home/montesinyy/www/gestores/gestorbonus.php");
	// require_once("/home/montesinyy/www/gestores/gestorcontrato.php");
	// require_once("/home/montesinyy/www/gestores/gestorparametro.php");
	// require_once("/home/montesinyy/www/gestores/gestortemporada.php");
	// require_once("/home/montesinyy/www/gestores/gestornoticia.php");
	// require_once("/home/montesinyy/www/gestores/gestoroferta.php");
	// require_once("/home/montesinyy/www/gestores/gestortrade.php");
	// require_once("/home/montesinyy/www/gestores/gestorsuceso.php");
	// require_once("/home/montesinyy/www/gestores/gestoremail.php");
	// require_once("/home/montesinyy/www/gestores/gestorapuesta.php");
	// require_once("/home/montesinyy/www/gestores/gestorpalmares.php");
	// require_once("/home/montesinyy/www/gestores/gestorrecord.php");

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
			// $eq->jugadoresIL = obtenerJugadoresIL($eq->pkEquipo);
			// $eq->jugadoresLesionados = obtenerJugadoresLesionados($eq->pkEquipo);
			// $eq->jugadoresCovid = obtenerJugadoresCOVID($eq->pkEquipo);
			// $eq->jugadoresConDerecho = obtenerJugadoresConDerechoEquipo($eq->pkEquipo);
			// $eq->draftpicks = obtenerDraftpicksEquipo($eq->pkEquipo);

			// $eq->sanciones = obtenerSancionesEquipo($eq->pkEquipo);
			// $eq->bonus = obtenerBonusEquipo($eq->pkEquipo);

			// $eq->apuesta = obtenerApuestaEquipo($eq->pkEquipo);

			// $eq->palmares = obtenerPalmaresEquipo($eq->pkEquipo);
			// $eq->records = obtenerRecordsEquipo($eq->pkEquipo);

			return $eq;
		}

		return NULL;
	}

?>