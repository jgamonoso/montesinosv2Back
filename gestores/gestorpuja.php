<?php
	require_once __DIR__ . '/../conexionbd.php';
	require_once __DIR__ . '/../objetos/puja.php';
	// require_once("/home/montesinyy/www/gestores/gestortemporada.php");
	// require_once("/home/montesinyy/www/gestores/gestoremail.php");
	// require_once("/home/montesinyy/www/gestores/gestorsuceso.php");

	function obtenerPujas($pkSubasta)
	{
		$sql = "select * from puja where fk_puja_subasta=".$pkSubasta." order by puja_fecha, puja_hora";

		$result = consultarSql($sql);

		if ($result->num_rows > 0) {
			$listaPujas = array();

			while ($row = $result->fetch_assoc())
			{
				$puja = new Puja();
				$puja->pkPuja = $row["pk_puja"];
				$puja->fkSubasta = $row["fk_puja_subasta"];
				$puja->fkEquipo = $row["fk_puja_equipo"];
				$puja->fecha = $row["puja_fecha"];
				$puja->hora = $row["puja_hora"];
				$puja->valor = $row["puja_valor"];
				$puja->anyos = $row["puja_anyos"];
				$puja->cr = $row["puja_cr"];

				array_push($listaPujas, $puja);
			}
			return $listaPujas;
		}

		return NULL;
	}

	function obtenerNumPujasRestantes($subasta, $pkEquipo)
	{
		if ($subasta->pujas == NULL) return 4;
		else if (count($subasta->pujas) == 1) return 3;

		$numPujasRestantes = 3;

		for ($i=1; $i<count($subasta->pujas);$i++)
		{
			if ($subasta->pujas[$i]->fkEquipo == $pkEquipo) $numPujasRestantes--;
		}

		return $numPujasRestantes;
	}
?>