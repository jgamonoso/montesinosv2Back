<?php
	require_once __DIR__ . '/../conexionbd.php';
	// require_once __DIR__ . '/../objetos/puja.php';
	// require_once("/home/montesinyy/www/gestores/gestortemporada.php");
	// require_once("/home/montesinyy/www/gestores/gestoremail.php");
	// require_once("/home/montesinyy/www/gestores/gestorsuceso.php");

	function cancelarOfertasConContrato($pkContrato)
	{
		$sql = "select pk_oferta from oferta where pk_oferta in (select fk_oferta_contrato_oferta from oferta_contrato where fk_oferta_contrato_contrato = ".$pkContrato.")";
		$result = consultarSql($sql);

		if ($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()){
				$pkOferta = $row["pk_oferta"];

				cancelarOferta($pkOferta);
			}
		}
	}

	function cancelarOferta($pkOferta)
	{
		$sql = "delete from oferta_contrato where fk_oferta_contrato_oferta=".$pkOferta;
		ejecutarSql($sql);

		$sql = "delete from oferta_derecho where fk_oferta_derecho_oferta=".$pkOferta;
		ejecutarSql($sql);

		$sql = "delete from oferta_draftpick where fk_oferta_draftpick_oferta=".$pkOferta;
		ejecutarSql($sql);

		$sql = "delete from oferta where pk_oferta=".$pkOferta;
		ejecutarSql($sql);
	}
?>