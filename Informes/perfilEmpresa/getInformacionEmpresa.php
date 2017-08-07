<?php
	session_start();
	//Datos de registro
	$dbserver="localhost";
	$dbuser="root";
	$dbpass="password";
	$dbname="easyLogin";
	$myusername = $_SESSION['usuario'];
	//Conexion MySQL
	$conexion = new PDO("mysql:host=$dbserver;dbname=$dbname",$dbuser,$dbpass);
	$conexion->query("SET NAMES 'utf8'"); 
	//Query 
	$sql = 'CALL datosEmpresa("' . $myusername . '")';
	$q = $conexion->query($sql);
	$q->setFetchMode(PDO::FETCH_ASSOC);
	if($row = $q->fetch()){
	
	echo "{ \"cols\": [ 
					{\"id\":\"\",\"label\":\"Empresa\",\"pattern\":\"\",\"type\":\"string\"}, {\"id\":\"\",\"label\":\"NIF\",\"pattern\":\"\",\"type\":\"string\"} 
					], \"rows\": [ ";
					
	
	$total_rows = $query->rowCount();
	$row_num = 0;
	while($row = $query->fetch()){
		$row_num++;
		if ($row_num == $total_rows){
		  echo "{\"c\":[{\"v\":\"" . $row['Empresa'] . "\",\"f\":null},{\"v\":" . $row['NIF'] . ",\"f\":null}]}";
		} else {
		  echo "{\"c\":[{\"v\":\"" . $row['Empresa'] . "\",\"f\":null},{\"v\":" . $row['NIF'] . ",\"f\":null}]}, ";
		}
	}
	echo " ] }";
	}
?>