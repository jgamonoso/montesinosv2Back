<?php
	require_once "conexionbd.php";

	function validarManager($user, $pass)
	{
		$sql = "select * from manager where manager_login='".$user."' and manager_password='".md5($pass)."'";
		
		$resultado = consultarSql($sql);
		
		return ($resultado->num_rows > 0);
	}
?>
