<?php
	require_once __DIR__ . '/../conexionbd.php';
	require_once __DIR__ . '/../objetos/categoria.php';

	function obtenerCategoria($pkCategoria)
	{
		$sql = "select * from categoria where pk_categoria=".$pkCategoria;

		$result = consultarSql($sql);

		if ($result->num_rows > 0) {
			$row = $result->fetch_assoc();

			$cat = new Categoria();
			$cat->pkCategoria = $row["pk_categoria"];
			$cat->nombre = $row["categoria_nombre"];

			return $cat;
		}

		return NULL;
	}
?>