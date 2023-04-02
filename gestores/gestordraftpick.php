<?php
	require_once __DIR__ . '/../conexionbd.php';
	require_once __DIR__ . '/../objetos/draftpick.php';
	// require_once("/home/montesinyy/www/gestores/gestortemporada.php");
	// require_once("/home/montesinyy/www/gestores/gestorderecho.php");
	// require_once("/home/montesinyy/www/gestores/gestorequipo.php");
	// require_once("/home/montesinyy/www/gestores/gestorjugadorliga.php");
	// require_once("/home/montesinyy/www/gestores/gestoroferta.php");
	// require_once("/home/montesinyy/www/gestores/gestortrade.php");
	// require_once("/home/montesinyy/www/gestores/gestorsuceso.php");

	function obtenerDraftpicksEquipo($pkEquipo)
	{
		$sql = "select * from draftpick where fk_draftpick_equipo_dest=".$pkEquipo." and fk_draftpick_jugadorliga_elegido IS NULL";

		$result = consultarSql($sql);

		if ($result->num_rows > 0)
		{
			$listaDraftpicks = array();

			while ($row = $result->fetch_assoc())
			{
				$pick = new DraftPick();
				$pick->pkDraftpick = $row["pk_draftpick"];
				$pick->fkTemporada = $row["fk_draftpick_temporada"];
				$pick->fkEquipoOri = $row["fk_draftpick_equipo_ori"];
				$pick->fkEquipoDest = $row["fk_draftpick_equipo_dest"];
				$pick->fkLiga = $row["fk_draftpick_liga"];

				$pick->numRonda = $row["draftpick_numronda"];
				$pick->numPick = $row["draftpick_numpick"];

				$pick->enTradingBlock = ($row["draftpick_tradingblock"] != "0");

				$pick->fkJugadorligaPreferido = $row["fk_draftpick_jugadorliga_preferido"];
				$pick->fkJugadorligaElegido = $row["fk_draftpick_jugadorliga_elegido"];

				array_push($listaDraftpicks, $pick);
			}

			return $listaDraftpicks;
		}

		return NULL;
	}
?>