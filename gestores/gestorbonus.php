<?php
	require_once __DIR__ . '/../conexionbd.php';
	require_once __DIR__ . '/../objetos/bonus.php';
	// require_once("/home/montesinyy/www/gestores/gestornoticia.php");
	// require_once("/home/montesinyy/www/gestores/gestorequipo.php");
	// require_once("/home/montesinyy/www/gestores/gestortemporada.php");
	// require_once("/home/montesinyy/www/gestores/gestorsuceso.php");

	function obtenerBonusEquipo($pkEquipo)
	{
		$sql = "select * from bonus where fk_bonus_equipo =".$pkEquipo;

		$result = consultarSql($sql);

		if ($result->num_rows > 0) {

			$listaBonus = array();

			while($row = $result->fetch_assoc()){
				$bonus = new Bonus();
				$bonus->pkBonus = $row["pk_bonus"];
				$bonus->fkEquipo = $row["fk_bonus_equipo"];
				$bonus->fkTemporada = $row["fk_bonus_temporada"];

				$bonus->fecha = $row["bonus_fecha"];
				$bonus->motivo = $row["bonus_motivo"];
				$bonus->cantidad = $row["bonus_cantidad"];

				array_push($listaBonus, $bonus);
			}

			return $listaBonus;
		}

		return NULL;
	}

?>