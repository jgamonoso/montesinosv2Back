<?php
	require_once __DIR__ . '/../conexionbd.php';
	require_once __DIR__ . '/../objetos/manager.php';
	require_once __DIR__ . '/gestorgrupo.php';
	require_once __DIR__ . '/gestorequipo.php';

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

	function obtenerManager($pkManager)
	{
		$sql = "select * from manager where pk_manager=".$pkManager;

		$result = consultarSql($sql);

		if ($result->num_rows > 0) {
			$row = $result->fetch_assoc();

			$man = new Manager();
			$man->pkManager = $row["pk_manager"];
			$man->nombre = $row["manager_nombre"];
			$man->mail = $row["manager_email"];
			$man->login = $row["manager_login"];

			$man->grupo = obtenerGrupo($row["fk_manager_grupo"]);

			$man->equipo = obtenerEquipo($man->pkManager);

			return $man;
		}

		return NULL;
	}

	function obtenerManagerPorLogin($login)
	{
		$sql = "select * from manager where manager_login='".$login."'";

		$result = consultarSql($sql);

		if ($result->num_rows > 0) {
			$row = $result->fetch_assoc();

			$man = new Manager();
			$man->pkManager = $row["pk_manager"];
			$man->nombre = $row["manager_nombre"];
			$man->mail = $row["manager_email"];
			$man->login = $row["manager_login"];

			$man->grupo = obtenerGrupo($row["fk_manager_grupo"]);

			$man->equipo = obtenerEquipo($man->pkManager);

			return $man;
		}

		return NULL;
	}

	// function obtenerListaManagersConEquipo($pkLiga)
	// {
	// 	$sql = "select * from manager where pk_manager in (select fk_equipo_manager from equipo where fk_equipo_liga = ".$pkLiga.") order by manager_nombre";

	// 	$result = consultarSql($sql);

	// 	if ($result->num_rows > 0)
	// 	{
	// 		$listaManagers = array();

	// 		while ($row = $result->fetch_assoc())
	// 		{
	// 			$man = new Manager();
	// 			$man->pkManager = $row["pk_manager"];
	// 			$man->nombre = $row["manager_nombre"];
	// 			$man->mail = $row["manager_email"];
	// 			$man->login = $row["manager_login"];

	// 			$man->grupo = obtenerGrupo($row["fk_manager_grupo"]);

	// 			$man->equipo = obtenerEquipo($man->pkManager);

	// 			array_push($listaManagers, $man);
	// 		}

	// 		return $listaManagers;
	// 	}

	// 	return NULL;
	// }

	function obtenerListadoManagersConEquipo($pkLiga)
	{
		$sql = "select * from manager where pk_manager in (select fk_equipo_manager from equipo where fk_equipo_liga = ".$pkLiga.") order by manager_nombre";

		$result = consultarSql($sql);

		if ($result->num_rows > 0)
		{
			$listaManagers = array();

			while ($row = $result->fetch_assoc())
			{
				$man = new Manager();
				$man->pkManager = $row["pk_manager"];
				$man->nombre = $row["manager_nombre"];
				$man->equipo = obtenerDatosListadoEquipos($man->pkManager);

				array_push($listaManagers, $man);
			}

			return $listaManagers;
		}

		return NULL;
	}
?>
