<?php
	require_once __DIR__ . '/../conexionbd.php';
	require_once __DIR__ . '/../objetos/bonus.php';
	require_once __DIR__ . '/gestortemporada.php';
	require_once __DIR__ . '/gestornoticia.php';
	require_once __DIR__ . '/gestorsuceso.php';
	require_once __DIR__ . '/gestorequipo.php';

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


	function altaBonusComi($pkManager, $equipo, $cantidad, $temporada, $motivo, $pkLiga)
	{
		$sql = "insert into bonus (fk_bonus_equipo, fk_bonus_temporada, bonus_fecha, bonus_motivo, bonus_cantidad) values (".$equipo.", ".$temporada.", '".date('Ymd')."', '".$motivo."', ".$cantidad.")";

		ejecutarSql($sql);

		$temporadaActual = obtenerTemporadaActual();

		if ($temporada == $temporadaActual->pkTemporada)
		{
			$sql = "update equipo set equipo_cap_libre=ROUND(equipo_cap_libre + ".$cantidad.",1) where pk_equipo=".$equipo;
			ejecutarSql($sql);
		}

		altaNoticia("Bonus de <b>".$cantidad."M</b> para <b>".obtenerNombreEquipo($equipo)."</b> durante la temporada <b>".obtenerNombreTemporada($temporada)."</b> por: <i>".$motivo."</i>", 2,$pkLiga);

		crearSuceso($pkManager, $equipo, "ALTA_BONUS", "Cantidad: ".$cantidad." Temporada fin: ".$temporada);
	}

	function expirarBonus($temporadaActual)
	{
		$sql = "delete from bonus where fk_bonus_temporada = ".$temporadaActual->pkTemporada;
		ejecutarSql($sql);
	}
?>