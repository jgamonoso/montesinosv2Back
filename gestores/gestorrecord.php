<?php
	require_once __DIR__ . '/../conexionbd.php';
	require_once __DIR__ . '/../objetos/record.php';
	require_once __DIR__ . '/gestorcategoria.php';
	require_once __DIR__ . '/gestortemporada.php';

	function obtenerRecordsEquipo($pkEquipo)
	{
		$sql = "select * from record where fk_record_equipo=".$pkEquipo." order by fk_record_categoria";

		$result = consultarSql($sql);

		if ($result->num_rows > 0) {

			$listaRecord = array();

			while($row = $result->fetch_assoc()){
				$record = new Record();
				$record->pkRecord = $row["pk_record"];
				$record->temporada = obtenerTemporada($row["fk_record_temporada"]);
				$record->fkLiga = $row["fk_record_liga"];
				$record->fkEquipo = $row["fk_record_equipo"];

				$record->categoria = obtenerCategoria($row["fk_record_categoria"]);
				$record->valor = $row["record_valor"];

				array_push($listaRecord, $record);
			}

			return $listaRecord;
		}

		return NULL;
	}

	function obtenerRecordsLiga($pkLiga)
	{
		$sql = "select * from record where fk_record_liga=".$pkLiga." order by fk_record_categoria";

		$result = consultarSql($sql);

		if ($result->num_rows > 0) {

			$listaRecord = array();

			while($row = $result->fetch_assoc()){
				$record = new Record();
				$record->pkRecord = $row["pk_record"];
				$record->temporada = obtenerTemporada($row["fk_record_temporada"]);
				$record->fkLiga = $row["fk_record_liga"];
				$record->fkEquipo = $row["fk_record_equipo"];

				$record->categoria = obtenerCategoria($row["fk_record_categoria"]);
				$record->valor = $row["record_valor"];

				array_push($listaRecord, $record);
			}

			return $listaRecord;
		}

		return NULL;
	}

?>