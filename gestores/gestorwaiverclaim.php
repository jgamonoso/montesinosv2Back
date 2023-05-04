<?php
	require_once __DIR__ . '/../conexionbd.php';
	require_once __DIR__ . '/../objetos/waiverclaim.php';

	function obtenerClaimsJugador($pkJugador)
	{
		$sql = "select * from waiverclaim where fk_waiverclaim_waiver in (select pk_waiver from waiver where fk_waiver_jugadorliga=".$pkJugador.")";

		$result = consultarSql($sql);

		if ($result->num_rows > 0) {

			$listaClaims = array();

			while ($row = $result->fetch_assoc()){
				$waiverclaim = new Waiverclaim();
				$waiverclaim->pkWaiverclaim = $row["pk_waiverclaim"];
				$waiverclaim->fkEquipo = $row["fk_waiverclaim_equipo"];
				$waiverclaim->fkWaiver = $row["fk_waiverclaim_waiver"];
				$waiverclaim->prioridad = $row["waiverclaim_prioridad"];
				$waiverclaim->cantidad = $row["waiverclaim_cantidad"];
				$waiverclaim->anyos = $row["waiverclaim_anyos"];
				$waiverclaim->cr = $row["waiverclaim_cr"];

				array_push($listaClaims, $waiverclaim);
			}

			return $listaClaims;
		}

		return NULL;
	}
?>