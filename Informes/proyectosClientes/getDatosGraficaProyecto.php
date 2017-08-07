<?php
	session_start();
	$q=$_GET["q"];
	$dbserver="localhost";
	$dbuser="root";
	$dbpass="password";
	$dbname=$_SESSION['eR_cliente'];
	
	$conexion = new PDO("mysql:host=$dbserver;dbname=$dbname",$dbuser,$dbpass);
	$conexion->query("SET NAMES 'utf8'");

	$sql = 'CALL graficaArticulosProyecto("'. $q .'")';
	$query = $conexion->query($sql);
	$query->setFetchMode(PDO::FETCH_ASSOC);

	echo "{ \"cols\": [ 
					{\"id\":\"\",\"label\":\"Articulo\",\"pattern\":\"\",\"type\":\"string\"},
					{\"id\":\"\",\"label\":\"Ventas\",\"pattern\":\"\",\"type\":\"number\"},
					{\"id\":\"\",\"label\":\"Recaudacion\",\"pattern\":\"\",\"type\":\"number\"}
					], \"rows\": [ ";
	$total_rows = $query->rowCount();
	$row_num = 0;
	while($row = $query->fetch()){
		$row_num++;
		if($row_num == $total_rows){
			echo "{\"c\":[{\"v\":\"" . $row['Articulo'] . "\",\"f\":null},
				  		  {\"v\":\"" . $row['Ventas'] . "\",\"f\":null},
				  		  {\"v\":\"" . $row['Recaudacion'] . "\",\"f\":null}
				 ]}";
		}else{
			echo "{\"c\":[{\"v\":\"" . $row['Articulo'] . "\",\"f\":null},
				  		  {\"v\":\"" . $row['Ventas'] . "\",\"f\":null},
				   		  {\"v\":\"" . $row['Recaudacion'] . "\",\"f\":null}
				 ]}, ";
		}
	}
	echo " ] }";
?>