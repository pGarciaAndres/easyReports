<?php
	session_start();
	$q=$_GET["q"];
	$dbserver="localhost";
	$dbuser="root";
	$dbpass="password";
	$dbname=$_SESSION['eR_cliente'];
	
	$conexion = new PDO("mysql:host=$dbserver;dbname=$dbname",$dbuser,$dbpass);
	$conexion->query("SET NAMES 'utf8'"); 
	
	$sql = 'CALL graficaSexos(' . $q . ')';
	$query = $conexion->query($sql);
	$query->setFetchMode(PDO::FETCH_ASSOC);
	
	echo "{ \"cols\": [ 
					{\"id\":\"\",\"label\":\"Sexo\",\"pattern\":\"\",\"type\":\"string\"}, 
					{\"id\":\"\",\"label\":\"Cantidad\",\"pattern\":\"\",\"type\":\"number\"} 
					], \"rows\": [ ";
					
	$total_rows = $query->rowCount();
	$row_num = 0;
	while($row = $query->fetch()){
		$row_num++;
		if ($row_num == $total_rows){
		  echo "{\"c\":[{\"v\":\"" . $row['Sexo'] . "\",\"f\":null},
		  				{\"v\":" . $row['Cantidad'] . ",\"f\":null}]}";
		} else {
		  echo "{\"c\":[{\"v\":\"" . $row['Sexo'] . "\",\"f\":null},
		  				{\"v\":" . $row['Cantidad'] . ",\"f\":null}]}, ";
		}
	}
	echo " ] }";
?>