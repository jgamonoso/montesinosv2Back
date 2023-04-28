<?php
	require_once __DIR__ . '/../conexionbd.php';

	function crearSuceso($pkManager, $pkEquipo, $accion, $parametros)
	{
		$sql = "insert into suceso (fk_suceso_manager, fk_suceso_equipo, suceso_fecha, suceso_hora, suceso_accion, suceso_parametros) values (".$pkManager.", ".$pkEquipo.", '".date("Ymd")."', '".date('H:i')."', '".$accion."', '".$parametros."')";
		ejecutarSql($sql);
	}
?>