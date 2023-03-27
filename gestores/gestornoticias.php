<?php
	require_once "../conexionbd.php";
	require_once("../objetos/noticia.php");

	function obtenerListaFechas($pagina)
	{
		$listaFechas = array();

		$fechaIni = strtotime(date('Y-m-d H:i:s') . " -".($pagina - 1)." month");

		for ($i = 0; $i < 7; $i++)
		{
			$fecha = strtotime(date('Y-m-d H:i:s') . " -".($pagina - 1)." month". " -".($i)." day");

			array_push($listaFechas, $fecha);
		}

		return $listaFechas;
	}

function obtenerNoticiasDia($dia, $pkLiga)
	{
		$sql = "select * from noticia where fk_noticia_liga = ".$pkLiga." and noticia_fecha='".date('Ymd', $dia)."' order by noticia_prioridad asc, pk_noticia desc";

		$result = consultarSql($sql);


		if ($result->num_rows > 0) {

			$listaNoticias = array();

			while ($row = $result->fetch_assoc()){
				$noticia = new Noticia();
				$noticia->pkNoticia = $row["pk_noticia"];
				$noticia->fkLiga = $row["fk_noticia_liga"];
				$noticia->texto = $row["noticia_texto"];
				$noticia->fecha = $row["noticia_fecha"];
				$noticia->hora = $row["noticia_hora"];
				$noticia->prioridad = $row["noticia_prioridad"];

				array_push($listaNoticias, $noticia);
			}

			return $listaNoticias;
		}

		return NULL;
	}
?>