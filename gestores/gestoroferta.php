<?php
	require_once __DIR__ . '/../conexionbd.php';
	require_once __DIR__ . '/../objetos/oferta.php';
	require_once __DIR__ . '/gestorjugadorliga.php';
	require_once __DIR__ . '/gestordraftpick.php';
	// require_once __DIR__ . '/../objetos/puja.php';
	// require_once("/home/montesinyy/www/gestores/gestortemporada.php");
	// require_once("/home/montesinyy/www/gestores/gestoremail.php");
	// require_once("/home/montesinyy/www/gestores/gestorsuceso.php");

	function obtenerOferta($pkOferta)
	{
		$sql = "select * from oferta where pk_oferta=".$pkOferta;

		$result = consultarSql($sql);

		if ($result->num_rows > 0) {

			$row = $result->fetch_assoc();

			$oferta = new Oferta();
			$oferta->pkOferta = $row["pk_oferta"];
			$oferta->fkEquipo1 = $row["fk_oferta_equipo1"];
			$oferta->fkEquipo2 = $row["fk_oferta_equipo2"];
			$oferta->fecha = $row["oferta_fecha"];

			$oferta->jugadoresConContrato1 = obtenerJugadoresOfertaEquipo($oferta->pkOferta, $oferta->fkEquipo1);
			$oferta->jugadoresConDerecho1 = obtenerDerechosOfertaEquipo($oferta->pkOferta, $oferta->fkEquipo1);
			$oferta->draftpicks1 = obtenerDraftpicksOfertaEquipo($oferta->pkOferta, $oferta->fkEquipo1);

			$oferta->jugadoresConContrato2 = obtenerJugadoresOfertaEquipo($oferta->pkOferta, $oferta->fkEquipo2);
			$oferta->jugadoresConDerecho2 = obtenerDerechosOfertaEquipo($oferta->pkOferta, $oferta->fkEquipo2);
			$oferta->draftpicks2 = obtenerDraftpicksOfertaEquipo($oferta->pkOferta, $oferta->fkEquipo2);

			return $oferta;
		}

		return NULL;
	}

	function cancelarOfertasConContrato($pkContrato)
	{
		$sql = "select pk_oferta from oferta where pk_oferta in (select fk_oferta_contrato_oferta from oferta_contrato where fk_oferta_contrato_contrato = ".$pkContrato.")";
		$result = consultarSql($sql);

		if ($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()){
				$pkOferta = $row["pk_oferta"];

				cancelarOferta($pkOferta);
			}
		}
	}

	function cancelarOferta($pkOferta)
	{
		$sql = "delete from oferta_contrato where fk_oferta_contrato_oferta=".$pkOferta;
		ejecutarSql($sql);

		$sql = "delete from oferta_derecho where fk_oferta_derecho_oferta=".$pkOferta;
		ejecutarSql($sql);

		$sql = "delete from oferta_draftpick where fk_oferta_draftpick_oferta=".$pkOferta;
		ejecutarSql($sql);

		$sql = "delete from oferta where pk_oferta=".$pkOferta;
		ejecutarSql($sql);
	}

	function crearOferta($pkManager, $pkEquipo1, $listaJugadoresEquipo1, $listaDerechosEquipo1, $listaDraftpicksEquipo1, $pkEquipo2, $listaJugadoresEquipo2, $listaDerechosEquipo2, $listaDraftpicksEquipo2)
	{
		$sql = "insert into oferta (fk_oferta_equipo1,fk_oferta_equipo2,oferta_fecha) values (".$pkEquipo1.",".$pkEquipo2.",'".date('Ymd')."')";

		ejecutarSql($sql);

		$sql = "select max(pk_oferta) as max_pk from oferta";

		$result = consultarSql($sql);

		if ($result->num_rows > 0) {
			$row = $result->fetch_assoc();

			$pkOferta = $row["max_pk"];

			foreach ((array)$listaJugadoresEquipo1 as $pkJugador)
			{
				$sql = "select pk_contrato from contrato where fk_contrato_jugadorliga=".$pkJugador;
				$result = consultarSql($sql);

				if ($result->num_rows > 0) {
					$row = $result->fetch_assoc();
					$pkContrato = $row["pk_contrato"];

					$sql = "insert into oferta_contrato (fk_oferta_contrato_oferta, fk_oferta_contrato_contrato) values (".$pkOferta.", ".$pkContrato.")";
					ejecutarSql($sql);
				}
			}
			foreach ((array)$listaDerechosEquipo1 as $pkJugador)
			{
				$sql = "select pk_derecho from derecho where fk_derecho_jugadorliga=".$pkJugador;
				$result = consultarSql($sql);

				if ($result->num_rows > 0) {
					$row = $result->fetch_assoc();
					$pkDerecho = $row["pk_derecho"];

					$sql = "insert into oferta_derecho (fk_oferta_derecho_oferta, fk_oferta_derecho_derecho) values (".$pkOferta.", ".$pkDerecho.")";
					ejecutarSql($sql);
				}
			}
			foreach ((array)$listaDraftpicksEquipo1 as $pkDraftpick)
			{
				$sql = "insert into oferta_draftpick (fk_oferta_draftpick_oferta, fk_oferta_draftpick_draftpick) values (".$pkOferta.", ".$pkDraftpick.")";
				ejecutarSql($sql);
			}
			foreach ((array)$listaJugadoresEquipo2 as $pkJugador)
			{
				$sql = "select pk_contrato from contrato where fk_contrato_jugadorliga=".$pkJugador;
				$result = consultarSql($sql);

				if ($result->num_rows > 0) {
					$row = $result->fetch_assoc();
					$pkContrato = $row["pk_contrato"];

					$sql = "insert into oferta_contrato (fk_oferta_contrato_oferta, fk_oferta_contrato_contrato) values (".$pkOferta.", ".$pkContrato.")";
					ejecutarSql($sql);
				}
			}
			foreach ((array)$listaDerechosEquipo2 as $pkJugador)
			{
				$sql = "select pk_derecho from derecho where fk_derecho_jugadorliga=".$pkJugador;
				$result = consultarSql($sql);

				if ($result->num_rows > 0) {
					$row = $result->fetch_assoc();
					$pkDerecho = $row["pk_derecho"];

					$sql = "insert into oferta_derecho (fk_oferta_derecho_oferta, fk_oferta_derecho_derecho) values (".$pkOferta.", ".$pkDerecho.")";
					ejecutarSql($sql);
				}
			}
			foreach ((array)$listaDraftpicksEquipo2 as $pkDraftpick)
			{
				$sql = "insert into oferta_draftpick (fk_oferta_draftpick_oferta, fk_oferta_draftpick_draftpick) values (".$pkOferta.", ".$pkDraftpick.")";
				ejecutarSql($sql);
			}

			enviarEmail($pkEquipo2, "Tu equipo ha recibido una propuesta de trade de ".obtenerNombreEquipo($pkEquipo1).". Accede a la web para más detalles.");

			crearSuceso($pkManager, $pkEquipo1, "RECHAZAR_OFERTA", "Equipo1: ".$pkEquipo1." Equipo2: ".$pkEquipo2);
		}
	}

	function obtenerListaOfertasRealizadas($pkEquipo1)
	{
		$sql = "select * from oferta where fk_oferta_equipo1=".$pkEquipo1;

		$result = consultarSql($sql);

		if ($result->num_rows > 0) {

			$listaOfertas = array();

			while ($row = $result->fetch_assoc()){

				$oferta = new Oferta();
				$oferta->pkOferta = $row["pk_oferta"];
				$oferta->fkEquipo1 = $row["fk_oferta_equipo1"];
				$oferta->fkEquipo2 = $row["fk_oferta_equipo2"];
				$oferta->fecha = $row["oferta_fecha"];

				$oferta->jugadoresConContrato1 = obtenerJugadoresOfertaEquipo($oferta->pkOferta, $oferta->fkEquipo1);
				$oferta->jugadoresConDerecho1 = obtenerDerechosOfertaEquipo($oferta->pkOferta, $oferta->fkEquipo1);
				$oferta->draftpicks1 = obtenerDraftpicksOfertaEquipo($oferta->pkOferta, $oferta->fkEquipo1);

				$oferta->jugadoresConContrato2 = obtenerJugadoresOfertaEquipo($oferta->pkOferta, $oferta->fkEquipo2);
				$oferta->jugadoresConDerecho2 = obtenerDerechosOfertaEquipo($oferta->pkOferta, $oferta->fkEquipo2);
				$oferta->draftpicks2 = obtenerDraftpicksOfertaEquipo($oferta->pkOferta, $oferta->fkEquipo2);

				array_push($listaOfertas, $oferta);
			}

			return $listaOfertas;
		}

		return NULL;
	}

	function obtenerListaOfertasRecibidas($pkEquipo2)
	{
		$sql = "select * from oferta where fk_oferta_equipo2=".$pkEquipo2;

		$result = consultarSql($sql);

		if ($result->num_rows > 0) {

			$listaOfertas = array();

			while ($row = $result->fetch_assoc()){

				$oferta = new Oferta();
				$oferta->pkOferta = $row["pk_oferta"];
				$oferta->fkEquipo1 = $row["fk_oferta_equipo1"];
				$oferta->fkEquipo2 = $row["fk_oferta_equipo2"];
				$oferta->fecha = $row["oferta_fecha"];

				$oferta->jugadoresConContrato1 = obtenerJugadoresOfertaEquipo($oferta->pkOferta, $oferta->fkEquipo1);
				$oferta->jugadoresConDerecho1 = obtenerDerechosOfertaEquipo($oferta->pkOferta, $oferta->fkEquipo1);
				$oferta->draftpicks1 = obtenerDraftpicksOfertaEquipo($oferta->pkOferta, $oferta->fkEquipo1);

				$oferta->jugadoresConContrato2 = obtenerJugadoresOfertaEquipo($oferta->pkOferta, $oferta->fkEquipo2);
				$oferta->jugadoresConDerecho2 = obtenerDerechosOfertaEquipo($oferta->pkOferta, $oferta->fkEquipo2);
				$oferta->draftpicks2 = obtenerDraftpicksOfertaEquipo($oferta->pkOferta, $oferta->fkEquipo2);

				array_push($listaOfertas, $oferta);
			}

			return $listaOfertas;
		}

		return NULL;
	}

	function obtenerNumOfertasRealizadasEquipo($pkEquipo1)
	{
		$sql = "select count(*) as numofertas from oferta where fk_oferta_equipo1=".$pkEquipo1;

		$result = consultarSql($sql);

		if ($result->num_rows > 0) {

			$row = $result->fetch_assoc();

			return $row["numofertas"];
		}

		return 0;
	}

	function obtenerNumOfertasRecibidasEquipo($pkEquipo2)
	{
		$sql = "select count(*) as numofertas from oferta where fk_oferta_equipo2=".$pkEquipo2;

		$result = consultarSql($sql);

		if ($result->num_rows > 0) {

			$row = $result->fetch_assoc();

			return $row["numofertas"];
		}

		return 0;
	}

	function rechazarOferta($pkManager, $pkEquipo, $pkOferta)
	{
		$oferta = obtenerOferta($pkOferta);

		cancelarOferta($pkOferta);

		enviarEmail($oferta->fkEquipo1, "Tu propuesta de trade a ".obtenerNombreEquipo($oferta->fkEquipo2)." ha sido rechazada.");

		crearSuceso($pkManager, $pkEquipo, "RECHAZAR_OFERTA", "Equipo1: ".$oferta->fkEquipo1." Equipo2: ".$oferta->fkEquipo2);
	}

	function aceptarOferta($pkManager, $pkEquipo, $pkOferta, $pkLiga)
	{
		$oferta = obtenerOferta($pkOferta);

		crearTrade($pkManager, $oferta->fkEquipo1,$oferta->jugadoresConContrato1,$oferta->jugadoresConDerecho1,$oferta->draftpicks1,$oferta->fkEquipo2,$oferta->jugadoresConContrato2,$oferta->jugadoresConDerecho2,$oferta->draftpicks2,$pkLiga);

		cancelarOferta($pkOferta);

		enviarEmailComi($oferta->fkEquipo1, "Tu propuesta de trade a ".obtenerNombreEquipo($oferta->fkEquipo2)." ha sido aceptada. El trade será evaluado por el comisionado.");

		crearSuceso($pkManager, $pkEquipo, "ACEPTAR_OFERTA", "Equipo1: ".$oferta->fkEquipo1." Equipo2: ".$oferta->fkEquipo2);
	}

	function cancelarOfertasConDerecho($pkDerecho)
	{
		$sql = "select pk_oferta from oferta where pk_oferta in (select fk_oferta_derecho_oferta from oferta_derecho where fk_oferta_derecho_derecho = ".$pkDerecho.")";
		$result = consultarSql($sql);

		if ($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()){
				$pkOferta = $row["pk_oferta"];

				cancelarOferta($pkOferta);
			}
		}
	}

	function cancelarOfertasConDraftpick($pkDraftpick)
	{
		$sql = "select pk_oferta from oferta where pk_oferta in (select fk_oferta_draftpick_oferta from oferta_draftpick where fk_oferta_draftpick_draftpick = ".$pkDraftpick.")";
		$result = consultarSql($sql);

		if ($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()){
				$pkOferta = $row["pk_oferta"];

				cancelarOferta($pkOferta);
			}
		}
	}

	function anularOferta($pkManager, $pkEquipo, $pkOferta)
	{
		$oferta = obtenerOferta($pkOferta);

		cancelarOferta($pkOferta);

		crearSuceso($pkManager, $pkEquipo, "CANCELAR_OFERTA", "Equipo1: ".$oferta->fkEquipo1." Equipo2: ".$oferta->fkEquipo2);
	}
?>