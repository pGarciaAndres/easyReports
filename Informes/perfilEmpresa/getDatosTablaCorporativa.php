<?php
	session_start();
	$dbserver="localhost";
	$dbuser="root";
	$dbpass="password";
	$dbname=$_SESSION['eR_cliente'];
	
	$conexion = new PDO("mysql:host=$dbserver;dbname=$dbname",$dbuser,$dbpass);
	$conexion->query("SET NAMES 'utf8'"); 
	
	$sql = 'CALL tablaCorporativa()';
	$query = $conexion->query($sql);
	$query->setFetchMode(PDO::FETCH_ASSOC);
	
	echo "{ \"cols\": [ 
					{\"id\":\"\",\"label\":\"Departamento\",\"pattern\":\"\",\"type\":\"string\"},
					{\"id\":\"\",\"label\":\"Descripcion\",\"pattern\":\"\",\"type\":\"string\"}, 
					{\"id\":\"\",\"label\":\"Trabajadores\",\"pattern\":\"\",\"type\":\"number\"}, {\"id\":\"\",\"label\":\"Director\",\"pattern\":\"\",\"type\":\"string\"},
					{\"id\":\"\",\"label\":\"Cargo\",\"pattern\":\"\",\"type\":\"string\"},
					{\"id\":\"\",\"label\":\"Antiguedad\",\"pattern\":\"\",\"type\":\"number\"} 
					], \"rows\": [ ";
					
	$total_rows = $query->rowCount();
	$row_num = 0;
	while($row = $query->fetch()){
		$row_num++;
		if($row_num == $total_rows){
			echo "{\"c\":[{\"v\":\"" . $row['Departamento'] . "\",\"f\":null},
				  {\"v\":\"" . $row['Descripcion'] . "\",\"f\":null},
				  {\"v\":\"" . $row['Trabajadores'] . "\",\"f\":null},
				  {\"v\":\"" . $row['Director'] . "\",\"f\":null},
				  {\"v\":\"" . $row['Cargo'] . "\",\"f\":null},
				  {\"v\":\"" . $row['Antiguedad'] . "\",\"f\":null}
				 ]}";
		}else{
			echo "{\"c\":[{\"v\":\"" . $row['Departamento'] . "\",\"f\":null},
				  {\"v\":\"" . $row['Descripcion'] . "\",\"f\":null},
				  {\"v\":\"" . $row['Trabajadores'] . "\",\"f\":null},
				  {\"v\":\"" . $row['Director'] . "\",\"f\":null},
				  {\"v\":\"" . $row['Cargo'] . "\",\"f\":null},
				  {\"v\":\"" . $row['Antiguedad'] . "\",\"f\":null}
				 ]}, ";
		}
	}
	echo " ] }";
?>