<?php
	require_once __DIR__ . '/../conexionbd.php';
	require_once __DIR__ . '/../objetos/temporada.php';
	require_once __DIR__ . '/gestoroferta.php';
	require_once __DIR__ . '/gestortrade.php';
	require_once __DIR__ . '/gestorjugadorliga.php';
	require_once __DIR__ . '/gestorcontrato.php';
	require_once __DIR__ . '/gestorderecho.php';
	require_once __DIR__ . '/gestorsancion.php';
	require_once __DIR__ . '/gestorbonus.php';
	require_once __DIR__ . '/gestordraftpick.php';
	require_once __DIR__ . '/gestornoticia.php';
	require_once __DIR__ . '/gestorsuceso.php';

	function obtenerTemporada($pkTemporada)
	{
		$sql = "select * from temporada where pk_temporada=".$pkTemporada;

		$result = consultarSql($sql);

		if ($result->num_rows > 0) {
			$row = $result->fetch_assoc();

			$temp = new Temporada();
			$temp->pkTemporada = $row["pk_temporada"];
			$temp->nombre = $row["temporada_nombre"];
			$temp->estado = $row["temporada_estado"];

			return $temp;
		}

		return NULL;
	}

	function obtenerTemporadaActual()
	{
		$sql = "select * from temporada where temporada_actual=1";

		$result = consultarSql($sql);

		if ($result->num_rows > 0) {
			$row = $result->fetch_assoc();

			$temp = new Temporada();
			$temp->pkTemporada = $row["pk_temporada"];
			$temp->nombre = $row["temporada_nombre"];
			$temp->estado = $row["temporada_estado"];

			return $temp;
		}
		return NULL;
	}

	function obtenerProximasTemporadas()
	{
		$sql = "select * from temporada where temporada_nombre >= (select temporada_nombre from temporada where temporada_actual=1) limit 4";

		$result = consultarSql($sql);

		if ($result->num_rows > 3) {
			$listaTemp = array();
			while ($row = $result->fetch_assoc())
			{
				$temp = new Temporada();
				$temp->pkTemporada = $row["pk_temporada"];
				$temp->nombre = $row["temporada_nombre"];
				$temp->estado = $row["temporada_estado"];

				array_push($listaTemp, $temp);
			}
			return $listaTemp;
		}

		return NULL;
	}

	function obtenerNombreTemporada($pkTemporada)
	{
		$nombre = "";
		$sql = "select temporada_nombre from temporada where pk_temporada=".$pkTemporada;

		$result = consultarSql($sql);

		if ($result->num_rows > 0) {
			$row = $result->fetch_assoc();

			$nombre = $row["temporada_nombre"];
		}

		return $nombre;
	}

	function cambiarTemporada()
	{
		$temporadaActual = obtenerTemporadaActual();

		// ofertas con contratos o derechos expirados
		expirarOfertas($temporadaActual);

		// trades con contratos o derechos expirados
		expirarTrades($temporadaActual);

		// renovaciones
		prepararJugadoresRestringidos($temporadaActual);

		// contratos
		expirarContratos($temporadaActual);

		// derechos
		expirarDerechos($temporadaActual);

		// sanciones
		expirarSanciones($temporadaActual);

		// bonus
		expirarBonus($temporadaActual);

		// crear draft picks
		crearDraftpicksTemporada($temporadaActual->pkTemporada + 3);

		$sql = "update temporada set temporada_actual = 0, temporada_estado = NULL";
		ejecutarSql($sql);

		$sql = "update temporada set temporada_actual = 1 where pk_temporada = ".($temporadaActual->pkTemporada+1);
		ejecutarSql($sql);

		// actualizar caps
		calcularCapEquipos();
	}

	function cambiarEstadoTemporadaActual($pkManager, $estado)
	{
		$sql = "update temporada set temporada_estado='".$estado."' where temporada_actual=1;";
		ejecutarSql($sql);

		altaNoticia("Cambio de estado de la temporada a ".str_replace("_", ' ', $estado), 1, 1);
		altaNoticia("Cambio de estado de la temporada a ".str_replace("_", ' ', $estado), 1, 2);
		if ($estado === 'RENOVACIONES') {
			altaNoticia("---<b>COMIENZA UNA NUEVA TEMPORADA</b>---", 1, 1);
			altaNoticia("---<b>COMIENZA UNA NUEVA TEMPORADA</b>---", 1, 2);
		}

		crearSuceso($pkManager, "NULL", "CAMBIAR_ESTADO_TEMPORADA", $estado);
	}

?>