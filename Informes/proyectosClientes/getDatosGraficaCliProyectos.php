<?php
	session_start();
	$q=$_GET["q"];
	$dbserver="localhost";
	$dbuser="root";
	$dbpass="password";
	$dbname=$_SESSION['eR_cliente'];
	
	$conexion = new PDO("mysql:host=$dbserver;dbname=$dbname",$dbuser,$dbpass);
	$conexion->query("SET NAMES 'utf8'");

	$sql = 'CALL graficaCliProyectos("'. $q .'")';
	$query = $conexion->query($sql);
	$query->setFetchMode(PDO::FETCH_ASSOC);

	echo "{ \"cols\": [ 
					{\"id\":\"\",\"label\":\"Proyectos\",\"pattern\":\"\",\"type\":\"string\"},
					{\"id\":\"\",\"label\":\"Clientes\",\"pattern\":\"\",\"type\":\"number\"}
					], \"rows\": [ ";
	$total_rows = $query->rowCount();
	$row_num = 0;
	while($row = $query->fetch()){
		$row_num++;
		if($row_num == $total_rows){
			echo "{\"c\":[{\"v\":\"" . $row['Proyectos'] . "\",\"f\":null},
				  {\"v\":\"" . $row['Clientes'] . "\",\"f\":null}
				 ]}";
		}else{
			echo "{\"c\":[{\"v\":\"" . $row['Proyectos'] . "\",\"f\":null},
				  {\"v\":\"" . $row['Clientes'] . "\",\"f\":null}
				 ]}, ";
		}
	}
	echo " ] }";
?>