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

	function obtenerListaDraftpicks($pkLiga)
	{
		$sql = "select * from draftpick where fk_draftpick_liga=".$pkLiga." and fk_draftpick_jugadorliga_elegido IS NULL order by fk_draftpick_temporada DESC, draftpick_numronda, draftpick_numpick, pk_draftpick";

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

	function obtenerListaDraftpicksTradingBlock($pkLiga)
	{
		$sql = "select * from draftpick where fk_draftpick_liga=".$pkLiga." and draftpick_tradingblock=1 order by fk_draftpick_temporada";

		$result = consultarSql($sql);

		if ($result->num_rows > 0)
		{
			$listaDraftpicks = array();

			while ($row = $result->fetch_assoc())
			{
				$pick = new DraftPick();
				$pick->pkDraftPick = $row["pk_draftpick"];
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

	function addDraftpickTradingBlock($pkManager, $pkEquipo, $pkDraftpick)
	{
		$sql = "update draftpick set draftpick_tradingblock=1 where pk_draftpick=".$pkDraftpick;
		ejecutarSql($sql);

		crearSuceso($pkManager, $pkEquipo, "ADD_TRADBLOCK_DRAFTPICK", $pkDraftpick);
	}

	function quitarDraftpickTradingBlock($pkManager, $pkEquipo, $pkDraftpick)
	{
		$sql = "update draftpick set draftpick_tradingblock=0 where pk_draftpick=".$pkDraftpick;
		ejecutarSql($sql);

		crearSuceso($pkManager, $pkEquipo, "QUITAR_TRADBLOCK_DRAFTPICK", $pkDraftpick);
	}

	function obtenerDraftpicksOfertaEquipo($pkOferta, $pkEquipo)
	{
		$sql = "select * from draftpick where fk_draftpick_equipo_dest=".$pkEquipo." and pk_draftpick in (select fk_oferta_draftpick_draftpick from oferta_draftpick where fk_oferta_draftpick_oferta = ".$pkOferta.")";

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

	function obtenerDraftpicksTradeEquipo($pkTrade, $pkEquipo)
	{
		$sql = "select * from draftpick where fk_draftpick_equipo_dest=".$pkEquipo." and pk_draftpick in (select fk_trade_draftpick_draftpick from trade_draftpick where fk_trade_draftpick_trade = ".$pkTrade.")";

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