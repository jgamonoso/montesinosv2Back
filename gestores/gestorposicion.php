<?php
	require_once __DIR__ . '/../conexionbd.php';
	require_once __DIR__ . '/../objetos/posicion.php';


	function obtenerPosicionesJugador($pkJugador)
	{
		$sql = "select * from posicion where pk_posicion in (select fk_posicion from jugador_posicion where fk_jugador=".$pkJugador.") order by pk_posicion";

		$result = consultarSql($sql);

		if ($result->num_rows > 0) {

			$listaPos = array();

			while($row = $result->fetch_assoc()){
				$pos = new Posicion();
				$pos->pkPosicion = $row["pk_posicion"];
				$pos->nombre = $row["posicion_nombre"];

				array_push($listaPos, $pos);
			}

			return $listaPos;
		}

		return NULL;
	}
?>