<?php
	require_once __DIR__ . '/../conexionbd.php';
	require_once __DIR__ . '/gestormanager.php';

	function enviarEmail($pkEquipo, $mensaje)
	{
		$destino = obtenerEmailManagerPorEquipo($pkEquipo);

		if ($destino != NULL)
		{
			$subject = "Notificación montesina";
			$header = "From:notificaciones@montesinosnba.ovh \r\n";
			mail ($destino,$subject,$mensaje,$header);
		}
	}

	function enviarEmailComi($pkEquipo, $mensaje)
	{
		$destino = obtenerEmailManagerPorEquipo($pkEquipo);
		$correosComis = obtenerEmailComis();

		if ($destino != NULL)
		{
			$subject = "Notificación montesina";
			$header = "From:notificaciones@montesinosnba.ovh \r\n";
			if ($correosComis != NULL) { $header .= "Cc: ".$correosComis." \r\n"; }
			mail ($destino,$subject,$mensaje,$header);
		}
	}

	function enviarEmailLiga($mensaje, $pkLiga)
	{
		$destino = obtenerEmailsLiga($pkLiga);

		if ($destino != NULL)
		{
			$subject = "Notificación montesina";
			$header = "From:notificaciones@montesinosnba.ovh \r\n";
			mail($destino, $subject, $mensaje, $header);
		}
	}
?>