<?php
	session_start();
	$q=$_GET["q"];
	$dbserver="localhost";
	$dbuser="root";
	$dbpass="password";
	$dbname=$_SESSION['eR_cliente'];
	
	$conexion = new PDO("mysql:host=$dbserver;dbname=$dbname",$dbuser,$dbpass);
	$conexion->query("SET NAMES 'utf8'"); 
	
	$sql = 'CALL tablaTipoMaterial("'. $q .'")';
	$query = $conexion->query($sql);
	$query->setFetchMode(PDO::FETCH_ASSOC);
	
	echo "{ \"cols\": [ 
					{\"id\":\"\",\"label\":\"Usuario\",\"pattern\":\"\",\"type\":\"string\"},
					{\"id\":\"\",\"label\":\"Departamento\",\"pattern\":\"\",\"type\":\"string\"}, 
					{\"id\":\"\",\"label\":\"Tipo\",\"pattern\":\"\",\"type\":\"string\"}, 
					{\"id\":\"\",\"label\":\"Marca\",\"pattern\":\"\",\"type\":\"string\"},
					{\"id\":\"\",\"label\":\"Coste\",\"pattern\":\"\",\"type\":\"number\"},
					{\"id\":\"\",\"label\":\"Estado\",\"pattern\":\"\",\"type\":\"string\"},
					{\"id\":\"\",\"label\":\"Fecha_Compra\",\"pattern\":\"\",\"type\":\"string\"} 
					], \"rows\": [ ";
					
	$total_rows = $query->rowCount();
	$row_num = 0;
	while($row = $query->fetch()){
		$row_num++;
		if($row_num == $total_rows){
			echo "{\"c\":[{\"v\":\"" . $row['Usuario'] . "\",\"f\":null},
				  {\"v\":\"" . $row['Departamento'] . "\",\"f\":null},
				  {\"v\":\"" . $row['Tipo'] . "\",\"f\":null},
				  {\"v\":\"" . $row['Marca'] . "\",\"f\":null},
				  {\"v\":\"" . $row['Coste'] . "\",\"f\":null},
				  {\"v\":\"" . $row['Estado'] . "\",\"f\":null},
				  {\"v\":\"" . $row['Fecha_Compra'] . "\",\"f\":null}
				 ]}";
		}else{
			echo "{\"c\":[{\"v\":\"" . $row['Usuario'] . "\",\"f\":null},
				  {\"v\":\"" . $row['Departamento'] . "\",\"f\":null},
				  {\"v\":\"" . $row['Tipo'] . "\",\"f\":null},
				  {\"v\":\"" . $row['Marca'] . "\",\"f\":null},
				  {\"v\":\"" . $row['Coste'] . "\",\"f\":null},
				  {\"v\":\"" . $row['Estado'] . "\",\"f\":null},
				  {\"v\":\"" . $row['Fecha_Compra'] . "\",\"f\":null}
				 ]}, ";
		}
	}
	echo " ] }";
?>