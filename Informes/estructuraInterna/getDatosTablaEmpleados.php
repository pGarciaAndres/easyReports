<?php
	session_start();
	$q=$_GET["q"];
	$dbserver="localhost";
	$dbuser="root";
	$dbpass="password";
	$dbname=$_SESSION['eR_cliente'];
	
	$conexion = new PDO("mysql:host=$dbserver;dbname=$dbname",$dbuser,$dbpass);
	$conexion->query("SET NAMES 'utf8'");
	
	$sql = 'CALL tablaEmpleados("'. $q .'")';
	$query = $conexion->query($sql);
	$query->setFetchMode(PDO::FETCH_ASSOC);
	
	echo "{ \"cols\": [ 
					{\"id\":\"\",\"label\":\"Nombre\",\"pattern\":\"\",\"type\":\"string\"},
					{\"id\":\"\",\"label\":\"DNI\",\"pattern\":\"\",\"type\":\"string\"}, 
					{\"id\":\"\",\"label\":\"Pais\",\"pattern\":\"\",\"type\":\"string\"}, 
					{\"id\":\"\",\"label\":\"Edad\",\"pattern\":\"\",\"type\":\"number\"},
					{\"id\":\"\",\"label\":\"Cargo\",\"pattern\":\"\",\"type\":\"string\"},
					{\"id\":\"\",\"label\":\"Sueldo\",\"pattern\":\"\",\"type\":\"number\"},
					{\"id\":\"\",\"label\":\"Materiales\",\"pattern\":\"\",\"type\":\"string\"} 
					], \"rows\": [ ";
				
	$total_rows = $query->rowCount();
	$row_num = 0;
	while($row = $query->fetch()){
		$row_num++;
		if($row_num == $total_rows){
			echo "{\"c\":[{\"v\":\"" . $row['Nombre'] . "\",\"f\":null},
				  {\"v\":\"" . $row['DNI'] . "\",\"f\":null},
				  {\"v\":\"" . $row['Pais'] . "\",\"f\":null},
				  {\"v\":\"" . $row['Edad'] . "\",\"f\":null},
				  {\"v\":\"" . $row['Cargo'] . "\",\"f\":null},
				  {\"v\":\"" . $row['Sueldo'] . "\",\"f\":null},
				  {\"v\":\"" . $row['Materiales'] . "\",\"f\":null}
				 ]}";
		}else{
			echo "{\"c\":[{\"v\":\"" . $row['Nombre'] . "\",\"f\":null},
				  {\"v\":\"" . $row['DNI'] . "\",\"f\":null},
				  {\"v\":\"" . $row['Pais'] . "\",\"f\":null},
				  {\"v\":\"" . $row['Edad'] . "\",\"f\":null},
				  {\"v\":\"" . $row['Cargo'] . "\",\"f\":null},
				  {\"v\":\"" . $row['Sueldo'] . "\",\"f\":null},
				  {\"v\":\"" . $row['Materiales'] . "\",\"f\":null}
				 ]}, ";
		}
	}
	echo " ] }";
?>