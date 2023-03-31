<?php
	require_once __DIR__ . '/../conexionbd.php';
	require_once __DIR__ . '/../objetos/grupo.php';

	function obtenerGrupo($pkGrupo)
	{
		$sql = "select * from grupo where pk_grupo=".$pkGrupo;

		$result = consultarSql($sql);

		if ($result->num_rows > 0) {
			$row = $result->fetch_assoc();

			$grupo = new Grupo();
			$grupo->pkGrupo = $row["pk_grupo"];
			$grupo->nombre = $row["grupo_nombre"];

			return $grupo;
		}

		return NULL;
	}
?>