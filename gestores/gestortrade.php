<?php
	require_once __DIR__ . '/../conexionbd.php';
	// require_once __DIR__ . '/../objetos/puja.php';
	// require_once("/home/montesinyy/www/gestores/gestortemporada.php");
	// require_once("/home/montesinyy/www/gestores/gestoremail.php");
	// require_once("/home/montesinyy/www/gestores/gestorsuceso.php");

	function cancelarTradesConContrato($pkContrato)
	{
		$sql = "select pk_trade from trade where pk_trade in (select fk_trade_contrato_trade from trade_contrato where fk_trade_contrato_contrato = ".$pkContrato.")";
		$result = consultarSql($sql);

		if ($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()){
				$pkTrade = $row["pk_trade"];

				cancelarTrade($pkTrade);
			}
		}
	}

	function cancelarTrade($pkTrade)
	{
		$sql = "delete from trade_contrato where fk_trade_contrato_trade=".$pkTrade;
		ejecutarSql($sql);

		$sql = "delete from trade_derecho where fk_trade_derecho_trade=".$pkTrade;
		ejecutarSql($sql);

		$sql = "delete from trade_draftpick where fk_trade_draftpick_trade=".$pkTrade;
		ejecutarSql($sql);

		$sql = "delete from trade where pk_trade=".$pkTrade;
		ejecutarSql($sql);
	}
?>