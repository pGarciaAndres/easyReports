<?php
	session_start();
	$dbserver="localhost";
	$dbuser="root";
	$dbpass="password";
	$dbname=$_SESSION['eR_cliente'];
	
	$conexion = new PDO("mysql:host=$dbserver;dbname=$dbname",$dbuser,$dbpass);
	$conexion->query("SET NAMES 'utf8'"); 
	
	$sql = 'CALL graficaSueldos()';
	$query = $conexion->query($sql);
	$query->setFetchMode(PDO::FETCH_ASSOC);
	
	echo "{ \"cols\": [ 
					{\"id\":\"\",\"label\":\"Departamentos\",\"pattern\":\"\",\"type\":\"string\"}, 
					{\"id\":\"\",\"label\":\"Sueldos\",\"pattern\":\"\",\"type\":\"number\"} 
					], \"rows\": [ ";
					
	$total_rows = $query->rowCount();
	$row_num = 0;
	while($row = $query->fetch()){
		$row_num++;
		if ($row_num == $total_rows){
		  echo "{\"c\":[{\"v\":\"" . $row['Departamentos'] . "\",\"f\":null},
		  				{\"v\":" . $row['Sueldos'] . ",\"f\":null}]}";
		} else {
		  echo "{\"c\":[{\"v\":\"" . $row['Departamentos'] . "\",\"f\":null},
		  				{\"v\":" . $row['Sueldos'] . ",\"f\":null}]}, ";
		}
	}
	echo " ] }";
?>