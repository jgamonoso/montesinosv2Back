<?php
	require_once "../conexionbd.php";

	function validarManager($user, $pass)
	{
		$sql = "select * from manager where manager_login='".$user."' and manager_password='".md5($pass)."'";

		$resultado = consultarSql($sql);

		return ($resultado->num_rows > 0);
	}

	function validarLigaManager($user, $pass)
	{
		$sql = "select fk_equipo_liga from manager ma, equipo eq where ma.manager_login='".$user."' and ma.manager_password='".md5($pass)."' and ma.pk_manager=eq.fk_equipo_manager";

		$result = consultarSql($sql);

		if ($result->num_rows > 0) {
			$row = $result->fetch_assoc();

			$liga = $row["fk_equipo_liga"];

			return $liga;
		}
	}
?>
