<?php
	require_once __DIR__ . '/../conexionbd.php';
	require_once __DIR__ . '/../objetos/apuesta.php';
	// require_once("/home/montesinyy/www/gestores/gestorequipo.php");
	// require_once("/home/montesinyy/www/gestores/gestorequiponba.php");
	// require_once("/home/montesinyy/www/gestores/gestorjugador.php");
	// require_once("/home/montesinyy/www/gestores/gestorentrenador.php");
	// require_once("/home/montesinyy/www/gestores/gestorsuceso.php");

	function obtenerApuestaEquipo($pkEquipo)
	{
		$sql = "select * from apuesta where fk_apuesta_equipo=".$pkEquipo." and fk_apuesta_temporada in (select (pk_temporada) from temporada where temporada_actual=1)";

		$result = consultarSql($sql);

		if ($result->num_rows > 0) {
			$row = $result->fetch_assoc();

			$ap = new Apuesta();
			$ap->pkApuesta = $row["pk_apuesta"];
			$ap->fkEquipo = $row["fk_apuesta_equipo"];
			$ap->fkTemporada = $row["fk_apuesta_temporada"];
			$ap->fkJugadorMVP = $row["fk_apuesta_mvp"];
			$ap->fkJugadorROY = $row["fk_apuesta_rookie"];
			$ap->fkJugadorSexto = $row["fk_apuesta_sexto"];
			$ap->fkJugadorDefensor = $row["fk_apuesta_defensivo"];
			$ap->fkJugadorMIP = $row["fk_apuesta_mip"];
			$ap->fkEntrenador = $row["fk_apuesta_entrenador"];
			$ap->fkEquiponbaCampeon = $row["fk_apuesta_campeonnba"];
			$ap->fkEquiponbaPeor = $row["fk_apuesta_peornba"];
			$ap->fkEquipoCampeon = $row["fk_apuesta_campeon"];
			$ap->fkEquipoFinalista = $row["fk_apuesta_finalista"];
			$ap->fkEquipoRegular = $row["fk_apuesta_regular"];
			$ap->fkEquipoPeor = $row["fk_apuesta_peor"];
			$ap->fkEquipoCopa = $row["fk_apuesta_copa"];
			$ap->fkEquipoSupercopa = $row["fk_apuesta_supercopa"];

			return $ap;
		}

		return NULL;
	}

?>