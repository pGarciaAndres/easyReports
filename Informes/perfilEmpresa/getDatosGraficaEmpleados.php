<?php
	session_start();
	$q=$_GET["q"];
	$dbserver="localhost";
	$dbuser="root";
	$dbpass="password";
	$dbname = $_SESSION['eR_cliente'];
	
	$conexion = new PDO("mysql:host=$dbserver;dbname=$dbname",$dbuser,$dbpass);
	$conexion->query("SET NAMES 'utf8'"); 
	
	$sql = 'CALL graficaEmpleados(' . $q . ')';
	$query = $conexion->query($sql);
	$query->setFetchMode(PDO::FETCH_ASSOC);
	
	echo "{ \"cols\": [ 
					{\"id\":\"\",\"label\":\"Mes\",\"pattern\":\"\",\"type\":\"string\"}, {\"id\":\"\",\"label\":\"Empleados\",\"pattern\":\"\",\"type\":\"number\"} 
					], \"rows\": [ ";
					
	
	$total_rows = $query->rowCount();
	$row_num = 0;
	while($row = $query->fetch()){
		$row_num++;
		if ($row_num == $total_rows){
		  echo "{\"c\":[{\"v\":\"" . $row['Mes'] . "\",\"f\":null},{\"v\":" . $row['Empleados'] . ",\"f\":null}]}";
		} else {
		  echo "{\"c\":[{\"v\":\"" . $row['Mes'] . "\",\"f\":null},{\"v\":" . $row['Empleados'] . ",\"f\":null}]}, ";
		}
	}
	echo " ] }";
?>