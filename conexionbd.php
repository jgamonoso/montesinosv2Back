<?php

function consultarSql($sql)
{
    error_log("¡La base de datos de Oracle no está disponible!", 0);
	// Nube
	// $servername = "montesinyymnba.mysql.db";
	// $username = "montesinyymnba";
	// $password = "ntolc10DB";

	//localhost
	$servername = "127.0.0.1";
	$username = "root";
	$password = "";
	$dbname = "montesinyymnba";

	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);

	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	$result = $conn->query($sql);

	$conn->close();

	return $result;
}
?>

