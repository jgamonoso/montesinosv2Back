<?php
	require_once __DIR__ . '/../conexionbd.php';
	require_once __DIR__ . '/../objetos/trade.php';
	require_once __DIR__ . '/gestoroferta.php';
	require_once __DIR__ . '/gestorjugadorliga.php';
	require_once __DIR__ . '/gestordraftpick.php';
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

			$lineaJugadores1 = !empty($nombresJugadoresEquipo1) ? "<b>Jugadores: </b>".$nombresJugadoresEquipo1."</br>" : "";
			$lineaDerechos1 = !empty($nombresDerechosEquipo1) ? "<b>Derechos: </b>".$nombresDerechosEquipo1."</br>" : "";
			$lineaPicks1 = !empty($nombresDraftpicks1) ? "<b>Picks: </b>".$nombresDraftpicks1."</br>" : "";

			$lineaJugadores2 = !empty($nombresJugadoresEquipo2) ? "<b>Jugadores: </b>".$nombresJugadoresEquipo2."</br>" : "";
			$lineaDerechos2 = !empty($nombresDerechosEquipo2) ? "<b>Derechos: </b>".$nombresDerechosEquipo2."</br>" : "";
			$lineaPicks2 = !empty($nombresDraftpicks2) ? "<b>Picks: </b>".$nombresDraftpicks2 : "";

			$texto = "Trade entre <b>".obtenerNombreEquipo($pkEquipo1)."</b> y <b>".obtenerNombreEquipo($pkEquipo2)."</b>: </br></br>" .
					"<b>".obtenerNombreEquipo($pkEquipo2)."</b> recibe:</br>" .
					$lineaJugadores1 .
					$lineaDerechos1 .
					$lineaPicks1 .
					"</br><b>".obtenerNombreEquipo($pkEquipo1)."</b> recibe:</br>" .
					$lineaJugadores2 .
					$lineaDerechos2 .
					$lineaPicks2;

			// altaNoticia("Trade entre <b>".obtenerNombreEquipo($pkEquipo1)."</b> y <b>".obtenerNombreEquipo($pkEquipo2)."</b>: </br></br><b>Jugadores: </b>".$nombresJugadoresEquipo1."</br><b>Derechos: </b>".$nombresDerechosEquipo1."</br><b>Picks: </b>".$nombresDraftpicks1."</br></br>Por</br></br><b>Jugadores: </b>".$nombresJugadoresEquipo2."</br><b>Derechos: </b>".$nombresDerechosEquipo2."</br><b>Picks: </b>".$nombresDraftpicks2, 3, $pkLiga);
			altaNoticia($texto, 3, $pkLiga);
		}
	}

	function obtenerListaTradesPendientes()
	{
		$sql = "select * from trade where trade_estado='PENDIENTE'";

		$result = consultarSql($sql);

		if ($result->num_rows > 0) {

			$listaTrades = array();

			while ($row = $result->fetch_assoc()){

				$trade = new Trade();
				$trade->pkTrade = $row["pk_trade"];
				$trade->fkEquipo1 = $row["fk_trade_equipo1"];
				$trade->fkEquipo2 = $row["fk_trade_equipo2"];
				$trade->fecha = $row["trade_fecha"];
				$trade->estado = $row["trade_estado"];

				$trade->jugadoresConContrato1 = obtenerJugadoresTradeEquipo($trade->pkTrade, $trade->fkEquipo1);
				$trade->jugadoresConDerecho1 = obtenerDerechosTradeEquipo($trade->pkTrade, $trade->fkEquipo1);
				$trade->draftpicks1 = obtenerDraftpicksTradeEquipo($trade->pkTrade, $trade->fkEquipo1);

				$trade->jugadoresConContrato2 = obtenerJugadoresTradeEquipo($trade->pkTrade, $trade->fkEquipo2);
				$trade->jugadoresConDerecho2 = obtenerDerechosTradeEquipo($trade->pkTrade, $trade->fkEquipo2);
				$trade->draftpicks2 = obtenerDraftpicksTradeEquipo($trade->pkTrade, $trade->fkEquipo2);

				array_push($listaTrades, $trade);
			}

			return $listaTrades;
		}

		return NULL;
	}

	function obtenerTrade($pkTrade)
	{
		$sql = "select * from trade where pk_trade=".$pkTrade;

		$result = consultarSql($sql);

		if ($result->num_rows > 0) {
			$row = $result->fetch_assoc();

			$trade = new Trade();
			$trade->pkTrade = $row["pk_trade"];
			$trade->fkEquipo1 = $row["fk_trade_equipo1"];
			$trade->fkEquipo2 = $row["fk_trade_equipo2"];
			$trade->fecha = $row["trade_fecha"];
			$trade->estado = $row["trade_estado"];

			$trade->jugadoresConContrato1 = obtenerJugadoresTradeEquipo($trade->pkTrade, $trade->fkEquipo1);
			$trade->jugadoresConDerecho1 = obtenerDerechosTradeEquipo($trade->pkTrade, $trade->fkEquipo1);
			$trade->draftpicks1 = obtenerDraftpicksTradeEquipo($trade->pkTrade, $trade->fkEquipo1);

			$trade->jugadoresConContrato2 = obtenerJugadoresTradeEquipo($trade->pkTrade, $trade->fkEquipo2);
			$trade->jugadoresConDerecho2 = obtenerDerechosTradeEquipo($trade->pkTrade, $trade->fkEquipo2);
			$trade->draftpicks2 = obtenerDraftpicksTradeEquipo($trade->pkTrade, $trade->fkEquipo2);

			return $trade;
		}

		return NULL;
	}

	function vetarTrade($pkManager, $pkTrade)
	{
		$trade = obtenerTrade($pkTrade);
		$equipo1 = obtenerEquipoPorPk($trade->fkEquipo1);
		$pkLiga = $equipo1->fkLiga;
		altaNoticia("Vetado el trade entre <b>".obtenerNombreEquipo($trade->fkEquipo1)."</b> y <b>".obtenerNombreEquipo($trade->fkEquipo2)."</b>", 2, $pkLiga);

		cancelarTrade($pkTrade);

		crearSuceso($pkManager, "NULL", "VETAR_TRADE", "Equipo1: ".$trade->fkEquipo1." Equipo2: ".$trade->fkEquipo2);
	}

	function validarTrade($pkManager, $pkTrade)
	{
		$trade = obtenerTrade($pkTrade);
		$equipo1 = obtenerEquipoPorPk($trade->fkEquipo1);
		$pkLiga = $equipo1->fkLiga;

		$sql = "select ROUND(COALESCE(sum(contrato_salario),0),1) as salarios1 from contrato where fk_contrato_equipo in (select fk_trade_equipo1 from trade where pk_trade=".$pkTrade.") and pk_contrato in (select fk_trade_contrato_contrato from trade_contrato where fk_trade_contrato_trade=".$pkTrade.")";
		$result = consultarSql($sql);
		$row = $result->fetch_assoc();
		$salariosEquipo1 = $row["salarios1"];

		$sql = "select ROUND(COALESCE(sum(contrato_salario),0),1) as salarios2 from contrato where fk_contrato_equipo in (select fk_trade_equipo2 from trade where pk_trade=".$pkTrade.") and pk_contrato in (select fk_trade_contrato_contrato from trade_contrato where fk_trade_contrato_trade=".$pkTrade.")";
		$result = consultarSql($sql);
		$row = $result->fetch_assoc();
		$salariosEquipo2 = $row["salarios2"];

		foreach ((array)$trade->jugadoresConContrato1 as $jugador)
		{
			$sql = "update contrato set fk_contrato_equipo=".$trade->fkEquipo2." where fk_contrato_jugadorliga=".$jugador->pkJugadorliga;
			ejecutarSql($sql);
		}
		foreach ((array)$trade->jugadoresConDerecho1 as $jugador)
		{
			$sql = "update derecho set fk_derecho_equipo=".$trade->fkEquipo2." where fk_derecho_jugadorliga=".$jugador->pkJugadorliga;
			ejecutarSql($sql);
		}
		foreach ((array)$trade->draftpicks1 as $draftpick)
		{
			$sql = "update draftpick set fk_draftpick_equipo_dest=".$trade->fkEquipo2." where pk_draftpick=".$draftpick->pkDraftpick;
			ejecutarSql($sql);
		}
		foreach ((array)$trade->jugadoresConContrato2 as $jugador)
		{
			$sql = "update contrato set fk_contrato_equipo=".$trade->fkEquipo1." where fk_contrato_jugadorliga=".$jugador->pkJugadorliga;
			ejecutarSql($sql);
		}
		foreach ((array)$trade->jugadoresConDerecho2 as $jugador)
		{
			$sql = "update derecho set fk_derecho_equipo=".$trade->fkEquipo1." where fk_derecho_jugadorliga=".$jugador->pkJugadorliga;
			ejecutarSql($sql);
		}
		foreach ((array)$trade->draftpicks2 as $draftpick)
		{
			$sql = "update draftpick set fk_draftpick_equipo_dest=".$trade->fkEquipo1." where pk_draftpick=".$draftpick->pkDraftpick;
			ejecutarSql($sql);
		}

		$sql = "update equipo set equipo_cap_libre=equipo_cap_libre + ".($salariosEquipo1 - $salariosEquipo2)." where pk_equipo=".$trade->fkEquipo1;
		ejecutarSql($sql);

		$sql = "update equipo set equipo_cap_libre=equipo_cap_libre + ".($salariosEquipo2 - $salariosEquipo1)." where pk_equipo=".$trade->fkEquipo2;
		ejecutarSql($sql);

		cancelarTrade($pkTrade);

		enviarEmail($trade->fkEquipo1, "Tu trade con ".obtenerNombreEquipo($trade->fkEquipo2)." ha sido validado.");
		enviarEmail($trade->fkEquipo2, "Tu trade con ".obtenerNombreEquipo($trade->fkEquipo1)." ha sido validado.");

		$sql = "select equipo_cap_libre from equipo where pk_equipo=".$trade->fkEquipo1;
		$result = consultarSql($sql);
		$row = $result->fetch_assoc();
		$capLibre = $row["equipo_cap_libre"];

		if ($capLibre < 0)
		{
			// enviar email
			enviarEmailComi($trade->fkEquipo1, "Tu equipo ".obtenerNombreEquipo($trade->fkEquipo1)." ha sobrepasado el límite salarial. Dispones de 48h para regularizar el equipo.");
		}

		$sql = "select equipo_cap_libre from equipo where pk_equipo=".$trade->fkEquipo2;
		$result = consultarSql($sql);
		$row = $result->fetch_assoc();
		$capLibre = $row["equipo_cap_libre"];

		if ($capLibre < 0)
		{
			// enviar email
			enviarEmailComi($trade->fkEquipo2, "Tu equipo ".obtenerNombreEquipo($trade->fkEquipo2)." ha sobrepasado el límite salarial. Dispones de 48h para regularizar el equipo.");
		}

		altaNoticia("Validado el trade entre <b>".obtenerNombreEquipo($trade->fkEquipo1)."</b> y <b>".obtenerNombreEquipo($trade->fkEquipo2)."</b>", 2, $pkLiga);

		crearSuceso($pkManager, "NULL", "VALIDAR_TRADE", "Equipo1: ".$trade->fkEquipo1." Equipo2: ".$trade->fkEquipo2);
	}

	function expirarTrades($temporadaActual)
	{
		$sql = "select pk_trade from trade where pk_trade in (select fk_trade_contrato_trade from trade_contrato where fk_trade_contrato_contrato in (select pk_contrato from contrato where fk_contrato_temporada_fin = ".$temporadaActual->pkTemporada.")) or pk_trade in (select fk_trade_derecho_trade from trade_derecho where fk_trade_derecho_derecho in (select pk_derecho from derecho where fk_derecho_temporada_fin = ".$temporadaActual->pkTemporada."))";
		$result = consultarSql($sql);

		if ($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()){
				$pkTrade = $row["pk_trade"];

				cancelarTrade($pkTrade);
			}
		}
	}
?>