<?php
	require_once __DIR__ . '/../conexionbd.php';
	require_once __DIR__ . '/../objetos/sancion.php';
	require_once __DIR__ . '/gestortemporada.php';
	require_once __DIR__ . '/gestoremail.php';
	require_once __DIR__ . '/gestorsuceso.php';
	require_once __DIR__ . '/gestorequipo.php';
	require_once __DIR__ . '/gestornoticia.php';

	function obtenerSancionesEquipo($pkEquipo)
	{
		$sql = "select * from sancion where fk_sancion_equipo =".$pkEquipo;

		$result = consultarSql($sql);

		if ($result->num_rows > 0) {

			$listaSanciones = array();

			while($row = $result->fetch_assoc()){
				$sancion = new Sancion();
				$sancion->pkSancion = $row["pk_sancion"];
				$sancion->fkEquipo = $row["fk_sancion_equipo"];
				$sancion->fkTemporada = $row["fk_sancion_temporada"];

				$sancion->fecha = $row["sancion_fecha"];
				$sancion->motivo = $row["sancion_motivo"];
				$sancion->cantidad = $row["sancion_cantidad"];

				array_push($listaSanciones, $sancion);
			}

			return $listaSanciones;
		}

		return NULL;
	}

	function altaSancion($equipo, $cantidad, $temporada, $motivo, $pkLiga)
	{
		$sql = "insert into sancion (fk_sancion_equipo, fk_sancion_temporada, sancion_fecha, sancion_motivo, sancion_cantidad) values (".$equipo.", ".$temporada.", '".date('Ymd')."', '".$motivo."', ".$cantidad.")";

		ejecutarSql($sql);

		$temporadaActual = obtenerTemporadaActual();

		if ($temporada == $temporadaActual->pkTemporada)
		{
			$sql = "update equipo set equipo_cap_libre=ROUND(equipo_cap_libre - ".$cantidad.",1) where pk_equipo=".$equipo;
			ejecutarSql($sql);
		}

		enviarEmail($equipo, "Tu equipo ".obtenerNombreEquipo($equipo)." ha sido sancionado con ".$cantidad."M por ".$motivo.".");

		$sql = "select equipo_cap_libre from equipo where pk_equipo=".$equipo;
		$result = consultarSql($sql);
		$row = $result->fetch_assoc();
		$capLibre = $row["equipo_cap_libre"];

		if ($capLibre < 0)
		{
			// enviar email
			enviarEmailComi($equipo, "Tu equipo ".obtenerNombreEquipo($equipo)." ha sobrepasado el límite salarial. Dispones de 48h para regularizar el equipo.");
		}

		altaNoticia("Sanci&oacute;n de <b>".$cantidad."M</b> para <b>".obtenerNombreEquipo($equipo)."</b> durante la temporada <b>".obtenerNombreTemporada($temporada)."</b> por: <i>".$motivo."</i>", 2, $pkLiga);
	}

	function altaSancionComi($pkManager, $equipo, $cantidad, $temporada, $motivo, $pkLiga)
	{
		altaSancion($equipo, $cantidad, $temporada, $motivo, $pkLiga);
		crearSuceso($pkManager, $equipo, "ALTA_SANCION", "Cantidad: ".$cantidad." Temporada fin: ".$temporada);

		$temporadaSiguiente = $temporada + 1;

		altaSancion($equipo, $cantidad, $temporadaSiguiente, $motivo, $pkLiga);
		crearSuceso($pkManager, $equipo, "ALTA_SANCION", "Cantidad: ".$cantidad." Temporada fin: ".$temporadaSiguiente);
	}

	function expirarSanciones($temporadaActual)
	{
		$sql = "delete from sancion where fk_sancion_temporada = ".$temporadaActual->pkTemporada;
		ejecutarSql($sql);
	}
?>