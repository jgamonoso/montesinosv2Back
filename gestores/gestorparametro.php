<?php
	require_once __DIR__ . '/../conexionbd.php';

	function obtenerValorParametro($campo)
	{
		$sql = "select valor from parametros where campo='".$campo."'";

		$result = consultarSql($sql);

		if ($result->num_rows > 0) {
			$row = $result->fetch_assoc();
			return $row["valor"];
		}

		return null;
	}
?>