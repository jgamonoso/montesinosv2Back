<?php
	require_once __DIR__ . '/../conexionbd.php';
	require_once __DIR__ . '/../objetos/sancion.php';
	// require_once("/home/montesinyy/www/gestores/gestortemporada.php");
	// require_once("/home/montesinyy/www/gestores/gestoremail.php");
	// require_once("/home/montesinyy/www/gestores/gestorsuceso.php");

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
?>