<?php
	require_once __DIR__ . '/../conexionbd.php';
	require_once __DIR__ . '/gestoroferta.php';
	// require_once __DIR__ . '/../objetos/puja.php';
	// require_once("/home/montesinyy/www/gestores/gestortemporada.php");
	// require_once("/home/montesinyy/www/gestores/gestoremail.php");
	// require_once("/home/montesinyy/www/gestores/gestorsuceso.php");

	function cancelarTradesConContrato($pkContrato)
	{
		$sql = "select pk_trade from trade where pk_trade in (select fk_trade_contrato_trade from trade_contrato where fk_trade_contrato_contrato = ".$pkContrato.")";
		$result = consultarSql($sql);

		if ($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()){
				$pkTrade = $row["pk_trade"];

				cancelarTrade($pkTrade);
			}
		}
	}

	function cancelarTrade($pkTrade)
	{
		$sql = "delete from trade_contrato where fk_trade_contrato_trade=".$pkTrade;
		ejecutarSql($sql);

		$sql = "delete from trade_derecho where fk_trade_derecho_trade=".$pkTrade;
		ejecutarSql($sql);

		$sql = "delete from trade_draftpick where fk_trade_draftpick_trade=".$pkTrade;
		ejecutarSql($sql);

		$sql = "delete from trade where pk_trade=".$pkTrade;
		ejecutarSql($sql);
	}
	function crearTrade($pkManager, $pkEquipo1,$listaJugadoresEquipo1,$listaDerechosEquipo1,$listaDraftpicksEquipo1,$pkEquipo2,$listaJugadoresEquipo2,$listaDerechosEquipo2,$listaDraftpicksEquipo2,$pkLiga)
	{
		// para noticia
		$nombresJugadoresEquipo1 = "";
		$nombresDerechosEquipo1 = "";
		$nombresDraftpicks1 = "";
		$nombresJugadoresEquipo2 = "";
		$nombresDerechosEquipo2 = "";
		$nombresDraftpicks2 = "";
		//

		$sql = "insert into trade (fk_trade_equipo1,fk_trade_equipo2,trade_fecha,trade_estado) values (".$pkEquipo1.",".$pkEquipo2.",'".date('Ymd')."', 'PENDIENTE')";

		ejecutarSql($sql);

		$sql = "select max(pk_trade) as max_pk from trade";
		$result = consultarSql($sql);

		if ($result->num_rows > 0) {
			$row = $result->fetch_assoc();
			$pkTrade = $row["max_pk"];

			foreach ((array)$listaJugadoresEquipo1 as $jugador)
			{
				$sql = "select pk_contrato, contrato_salario, fk_contrato_temporada_fin from contrato where fk_contrato_jugadorliga=".$jugador->pkJugadorliga;
				$result = consultarSql($sql);

				if ($result->num_rows > 0) {
					$row = $result->fetch_assoc();
					$pkContrato = $row["pk_contrato"];
					$salario = $row["contrato_salario"];
					$pkTemporada = $row["fk_contrato_temporada_fin"];

					$sql = "insert into trade_contrato (fk_trade_contrato_trade, fk_trade_contrato_contrato) values (".$pkTrade.", ".$pkContrato.")";
					ejecutarSql($sql);

					cancelarOfertasConContrato($pkContrato);
					quitarJugadorTradingBlock($pkManager, $pkEquipo1, $jugador->pkJugadorliga);

					if (!empty($nombresJugadoresEquipo1)) { $nombresJugadoresEquipo1 .= ", "; }
					$nombresJugadoresEquipo1 .= $jugador->jugador->nombreAbreviado." (".$salario."M hasta ".obtenerNombreTemporada($pkTemporada).")";
				}
			}
			foreach ((array)$listaDerechosEquipo1 as $jugador)
			{
				$sql = "select pk_derecho from derecho where fk_derecho_jugadorliga=".$jugador->pkJugadorliga;
				$result = consultarSql($sql);

				if ($result->num_rows > 0) {
					$row = $result->fetch_assoc();
					$pkDerecho = $row["pk_derecho"];

					$sql = "insert into trade_derecho (fk_trade_derecho_trade, fk_trade_derecho_derecho) values (".$pkTrade.", ".$pkDerecho.")";
					ejecutarSql($sql);

					cancelarOfertasConDerecho($pkDerecho);
					quitarDerechoTradingBlock($pkManager, $pkEquipo1, $pkDerecho);

					if (!empty($nombresDerechosEquipo1)) { $nombresDerechosEquipo1 .= ", "; }
					$nombresDerechosEquipo1 .= $jugador->jugador->nombreAbreviado;
				}
			}
			foreach ((array)$listaDraftpicksEquipo1 as $draftpick)
			{
				$sql = "insert into trade_draftpick (fk_trade_draftpick_trade, fk_trade_draftpick_draftpick) values (".$pkTrade.", ".$draftpick->pkDraftpick.")";
				ejecutarSql($sql);

				cancelarOfertasConDraftpick($draftpick->pkDraftpick);
				quitarDraftpickTradingBlock($pkManager, $pkEquipo1, $draftpick->pkDraftpick);

				if (!empty($nombresDraftpicks1)) { $nombresDraftpicks1 .= ", "; }
				$nombresDraftpicks1 .= $draftpick->numRonda."a ronda ".obtenerNombreTemporada($draftpick->fkTemporada)." de ".obtenerNombreEquipo($draftpick->fkEquipoOri);
			}
			foreach ((array)$listaJugadoresEquipo2 as $jugador)
			{
				$sql = "select pk_contrato, contrato_salario, fk_contrato_temporada_fin from contrato where fk_contrato_jugadorliga=".$jugador->pkJugadorliga;
				$result = consultarSql($sql);

				if ($result->num_rows > 0) {
					$row = $result->fetch_assoc();
					$pkContrato = $row["pk_contrato"];
					$salario = $row["contrato_salario"];
					$pkTemporada = $row["fk_contrato_temporada_fin"];

					$sql = "insert into trade_contrato (fk_trade_contrato_trade, fk_trade_contrato_contrato) values (".$pkTrade.", ".$pkContrato.")";
					ejecutarSql($sql);

					cancelarOfertasConContrato($pkContrato);
					quitarJugadorTradingBlock($pkManager, $pkEquipo2, $jugador->pkJugadorliga);

					if (!empty($nombresJugadoresEquipo2)) { $nombresJugadoresEquipo2 .= ", "; }
					$nombresJugadoresEquipo2 .= $jugador->jugador->nombreAbreviado." (".$salario."M hasta ".obtenerNombreTemporada($pkTemporada).")";
				}
			}
			foreach ((array)$listaDerechosEquipo2 as $jugador)
			{
				$sql = "select pk_derecho from derecho where fk_derecho_jugadorliga=".$jugador->pkJugadorliga;
				$result = consultarSql($sql);

				if ($result->num_rows > 0) {
					$row = $result->fetch_assoc();
					$pkDerecho = $row["pk_derecho"];

					$sql = "insert into trade_derecho (fk_trade_derecho_trade, fk_trade_derecho_derecho) values (".$pkTrade.", ".$pkDerecho.")";
					ejecutarSql($sql);

					cancelarOfertasConDerecho($pkDerecho);
					quitarDerechoTradingBlock($pkManager, $pkEquipo2, $pkDerecho);

					if (!empty($nombresDerechosEquipo2)) { $nombresDerechosEquipo2 .= ", "; }
					$nombresDerechosEquipo2 .= $jugador->jugador->nombreAbreviado;
				}
			}
			foreach ((array)$listaDraftpicksEquipo2 as $draftpick)
			{
				$sql = "insert into trade_draftpick (fk_trade_draftpick_trade, fk_trade_draftpick_draftpick) values (".$pkTrade.", ".$draftpick->pkDraftpick.")";
				ejecutarSql($sql);

				cancelarOfertasConDraftpick($draftpick->pkDraftpick);
				quitarDraftpickTradingBlock($pkManager, $pkEquipo2, $draftpick->pkDraftpick);

				if (!empty($nombresDraftpicks2)) { $nombresDraftpicks2 .= ", "; }
				$nombresDraftpicks2 .= $draftpick->numRonda."a ronda ".obtenerNombreTemporada($draftpick->fkTemporada)." de ".obtenerNombreEquipo($draftpick->fkEquipoOri);
			}

			altaNoticia("Trade entre <b>".obtenerNombreEquipo($pkEquipo1)."</b> y <b>".obtenerNombreEquipo($pkEquipo2)."</b>: </br></br><b>Jugadores: </b>".$nombresJugadoresEquipo1."</br><b>Derechos: </b>".$nombresDerechosEquipo1."</br><b>Picks: </b>".$nombresDraftpicks1."</br></br>Por</br></br><b>Jugadores: </b>".$nombresJugadoresEquipo2."</br><b>Derechos: </b>".$nombresDerechosEquipo2."</br><b>Picks: </b>".$nombresDraftpicks2, 3, $pkLiga);
		}
	}
?>