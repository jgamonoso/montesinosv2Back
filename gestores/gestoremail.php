<?php
	require_once __DIR__ . '/../conexionbd.php';
	// require_once __DIR__ . '/../objetos/noticia.php';
	require_once __DIR__ . '/gestormanager.php';

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