<?php
	require_once __DIR__ . '/../conexionbd.php';
	require_once __DIR__ . '/../objetos/temporada.php';

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

?>