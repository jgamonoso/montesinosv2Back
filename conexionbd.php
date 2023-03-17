<?php

function consultarSql($sql)
{
    error_log("¡La base de datos de Oracle no está disponible!", 0);
	// $servername = "montesinyymnba.mysql.db";
	$servername = "127.0.0.1";
	// $username = "montesinyymnba"; 
	$username = "root"; 
	// $password = "ntolc10DB";
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

