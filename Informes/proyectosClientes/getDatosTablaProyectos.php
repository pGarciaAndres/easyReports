<?php
	session_start();
	$q=$_GET["q"];
	$dbserver="localhost";
	$dbuser="root";
	$dbpass="password";
	$dbname=$_SESSION['eR_cliente'];
	
	$conexion = new PDO("mysql:host=$dbserver;dbname=$dbname",$dbuser,$dbpass);
	$conexion->query("SET NAMES 'utf8'");
	
	$sql = 'CALL tablaProyectos("'. $q .'")';
	$query = $conexion->query($sql);
	$query->setFetchMode(PDO::FETCH_ASSOC);
	
	echo "{ \"cols\": [ 
					{\"id\":\"\",\"label\":\"Nombre\",\"pattern\":\"\",\"type\":\"string\"},
					{\"id\":\"\",\"label\":\"Descripcion\",\"pattern\":\"\",\"type\":\"string\"}, 
					{\"id\":\"\",\"label\":\"Estado\",\"pattern\":\"\",\"type\":\"string\"}
					], \"rows\": [ ";
				
	$total_rows = $query->rowCount();
	$row_num = 0;
	while($row = $query->fetch()){
		$row_num++;
		if($row_num == $total_rows){
			echo "{\"c\":[{\"v\":\"" . $row['Nombre'] . "\",\"f\":null},
				  {\"v\":\"" . $row['Descripcion'] . "\",\"f\":null},
				  {\"v\":\"" . $row['Estado'] . "\",\"f\":null}
				 ]}";
		}else{
			echo "{\"c\":[{\"v\":\"" . $row['Nombre'] . "\",\"f\":null},
				  {\"v\":\"" . $row['Descripcion'] . "\",\"f\":null},
				  {\"v\":\"" . $row['Estado'] . "\",\"f\":null}
				 ]}, ";
		}
	}
	echo " ] }";
?>