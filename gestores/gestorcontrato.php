<?php
	require_once __DIR__ . '/../conexionbd.php';
	require_once __DIR__ . '/../objetos/contrato.php';
	require_once __DIR__ . '/gestortemporada.php';
	require_once __DIR__ . '/gestorequipo.php';
	require_once __DIR__ . '/gestorjugadorliga.php';
	require_once __DIR__ . '/gestoremail.php';

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

	function expirarContratos($temporadaActual)
	{
		$sql = "update jugadorliga set jugadorliga_tradingblock = 0 where pk_jugadorliga in (select fk_contrato_jugadorliga from contrato where fk_contrato_temporada_fin = ".$temporadaActual->pkTemporada.")";
		ejecutarSql($sql);

		$sql = "delete from contrato where fk_contrato_temporada_fin = ".$temporadaActual->pkTemporada;
		ejecutarSql($sql);
	}

	function crearContrato($pkJugadorliga,$pkEquipo,$cantidad,$duracion,$contratoRookie)
	{
		$temporadaActual = obtenerTemporadaActual();

		$jugadorliga = obtenerJugadorliga($pkJugadorliga);

		$sql="insert into contrato (fk_contrato_jugadorliga, fk_contrato_equipo, fk_contrato_temporada_fin, contrato_salario, contrato_rookie, contrato_lld, contrato_covid, contrato_activo, contrato_fecha) values (".$pkJugadorliga.",".$pkEquipo.",".($temporadaActual->pkTemporada + $duracion - 1).",".$cantidad.",".$contratoRookie.",0,0,1,'".date('Ymd')."')";

		ejecutarSql($sql);

		// actualizar cap libre equipo
		$sql = "update equipo set equipo_cap_libre=ROUND(equipo_cap_libre - ".$cantidad.",1) where pk_equipo=".$pkEquipo;
		ejecutarSql($sql);

		if ($temporadaActual->estado == "SEASON")
		{
			$sql = "update equipo set equipo_moves_semanales=equipo_moves_semanales-1 where pk_equipo=".$pkEquipo;
			ejecutarSql($sql);
		}

		$sql = "update jugadorliga set fk_jugadorliga_equipo_drop = NULL, fk_jugadorliga_equipo_restringido = NULL, jugadorliga_exequipo_salario = NULL where pk_jugadorliga=".$pkJugadorliga;
		ejecutarSql($sql);

		$sql = "select equipo_cap_libre from equipo where pk_equipo=".$pkEquipo;
		$result = consultarSql($sql);
		$row = $result->fetch_assoc();
		$capLibre = $row["equipo_cap_libre"];

		if ($capLibre < 0 && $temporadaActual->estado != "SEASON")
		{
			// enviar email
			enviarEmailComi($pkEquipo, "Tu equipo ".obtenerNombreEquipo($pkEquipo)." ha sobrepasado el límite salarial. Dispones de 48h para regularizar el equipo.");
		}
		else if ($capLibre < 0)
		{
			// enviar email
			enviarEmailComi($pkEquipo, "Tu equipo ".obtenerNombreEquipo($pkEquipo)." ha sobrepasado el límite salarial. Debes regularizar el equipo de inmediato o te arriesgas a ser sancionado.");
		}

		$sql = "select count(*) as numjugadores from contrato where contrato_lld=0 and contrato_covid=0 and contrato_activo=1 and fk_contrato_equipo=".$pkEquipo;
		$result = consultarSql($sql);
		$row = $result->fetch_assoc();
		$numJugadores = $row["numjugadores"];

		if ($numJugadores > 13 && $temporadaActual->estado == "SEASON")
		{
			// enviar email
			enviarEmailComi($pkEquipo, "Tu equipo ".obtenerNombreEquipo($pkEquipo)." ha sobrepasado el límite de jugadores. Debes regularizar el equipo de inmediato o te arriesgas a ser sancionado.");
		}
		else if ($numJugadores > 14)
		{
			// enviar email
			enviarEmailComi($pkEquipo, "Tu equipo ".obtenerNombreEquipo($pkEquipo)." ha sobrepasado el límite de jugadores. Dispones de 48h para regularizar el equipo.");
		}
	}

	function liberarContratosLLD()
	{
		$sql = "delete from contrato where contrato_lld=1";
		ejecutarSql($sql);
	}
?>