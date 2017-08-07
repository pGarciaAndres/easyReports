<?php
	session_start();
	$dbserver="localhost";
	$dbuser="root";
	$dbpass="password";
	$dbname=$_SESSION['eR_cliente'];
	
	$conexion = new PDO("mysql:host=$dbserver;dbname=$dbname",$dbuser,$dbpass);
	$conexion->query("SET NAMES 'utf8'"); 
	
	$sql = 'CALL tablaMaterial()';
	$query = $conexion->query($sql);
	$query->setFetchMode(PDO::FETCH_ASSOC);
	
	echo "{ \"cols\": [ 
					{\"id\":\"\",\"label\":\"Departamento\",\"pattern\":\"\",\"type\":\"string\"}, 
					{\"id\":\"\",\"label\":\"Material\",\"pattern\":\"\",\"type\":\"number\"} 
					], \"rows\": [ ";
					
	$total_rows = $query->rowCount();
	$row_num = 0;
	while($row = $query->fetch()){
		$row_num++;
		if ($row_num == $total_rows){
		  echo "{\"c\":[{\"v\":\"" . $row['Deartamento'] . "\",\"f\":null},
		  				{\"v\":" . $row['Material'] . ",\"f\":null}]}";
		} else {
		  echo "{\"c\":[{\"v\":\"" . $row['Departamento'] . "\",\"f\":null},
		  				{\"v\":" . $row['Material'] . ",\"f\":null}]}, ";
		}
	}
	echo " ] }";
?>