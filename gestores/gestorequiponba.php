<?php
	require_once __DIR__ . '/../conexionbd.php';
	require_once __DIR__ . '/../objetos/equiponba.php';
	// require_once __DIR__ . '/gestorjugadorliga.php';

	function obtenerListaEquiposNba()
	{
		$sql = "select * from equiponba";

		$result = consultarSql($sql);

		if ($result->num_rows > 0) {
			$listaEquiposNba = array();

			while ($row = $result->fetch_assoc())
			{
				$eq = new EquipoNba();
				$eq->pkEquipoNba = $row["pk_equiponba"];
				$eq->nombre = $row["equiponba_nombre"];
				$eq->abrev = $row["equiponba_abreviatura"];

				array_push($listaEquiposNba, $eq);
			}

			return $listaEquiposNba;
		}

		return NULL;
	}
?>