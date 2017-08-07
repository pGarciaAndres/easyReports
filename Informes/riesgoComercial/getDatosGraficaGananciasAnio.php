<?php
	session_start();
	$dbserver="localhost";
	$dbuser="root";
	$dbpass="password";	
	$dbname=$_SESSION['eR_cliente'];
	$q=$_GET["q"];
	
	$conexion = new PDO("mysql:host=$dbserver;dbname=$dbname",$dbuser,$dbpass);
	$conexion->query("SET NAMES 'utf8'");
	
	$sql = 'CALL graficaGananciasAnio(' . $q . ')';
	$query = $conexion->query($sql);
	$query->setFetchMode(PDO::FETCH_ASSOC);
	
	echo "{ \"cols\": [ 
					{\"id\":\"\",\"label\":\"Mes\",\"pattern\":\"\",\"type\":\"string\"}, {\"id\":\"\",\"label\":\"Ganancias\",\"pattern\":\"\",\"type\":\"number\"} 
					], \"rows\": [ ";
					
	$total_rows = $query->rowCount();
	$row_num = 0;
	while($row = $query->fetch()){
		$row_num++;
		if ($row_num == $total_rows){
		  echo "{\"c\":[{\"v\":\"" . $row['Mes'] . "\",\"f\":null},{\"v\":" . $row['Ganancias'] . ",\"f\":null}]}";
		} else {
		  echo "{\"c\":[{\"v\":\"" . $row['Mes'] . "\",\"f\":null},{\"v\":" . $row['Ganancias'] . ",\"f\":null}]}, ";
		}
	}
	echo " ] }";
?>