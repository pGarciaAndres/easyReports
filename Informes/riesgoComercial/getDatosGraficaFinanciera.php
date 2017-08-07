<?php
	session_start();
	$dbserver="localhost";
	$dbuser="root";
	$dbpass="password";
	$dbname=$_SESSION['eR_cliente'];
	
	$conexion = new PDO("mysql:host=$dbserver;dbname=$dbname",$dbuser,$dbpass);
	$conexion->query("SET NAMES 'utf8'");

	$sql = 'CALL controlAnioClientes()';
	$query = $conexion->query($sql);
	$query->setFetchMode(PDO::FETCH_ASSOC);

	if($row = $query->fetch()){
		$first = $row['YEAR(Fecha_Alta)'];

		$conexion2 = new PDO("mysql:host=$dbserver;dbname=$dbname",$dbuser,$dbpass);
		$conexion2->query("SET NAMES 'utf8'");

		$sql2 = 'CALL ratingEmpresa(' . $first . ')';
		$query2 = $conexion2->query($sql2);
		$query2->setFetchMode(PDO::FETCH_ASSOC);
		if($row = $query2->fetch()){
			$rating	= (INT)(20-16.6*$row['Rating']);
			if($rating < 0){
				$rating = 0;
			}
		}

		$conexion3 = new PDO("mysql:host=$dbserver;dbname=$dbname",$dbuser,$dbpass);
		$conexion3->query("SET NAMES 'utf8'");

		$sql3 = 'CALL liquidezEmpresa(' . $first . ')';
		$query3 = $conexion3->query($sql3);
		$query3->setFetchMode(PDO::FETCH_ASSOC);
		if($row = $query3->fetch()){
			$liquidez = (INT)(100-83.3*$row['Liquidez']);
			if($liquidez <0){
				$liquidez = 0;
			}
		}

		$status = array("Rentabilidad","Solidez","Liquidez Inmediata","Riesgo");
	}
	
	echo "{ \"cols\": [ 
					{\"id\":\"\",\"label\":\"Status\",\"pattern\":\"\",\"type\":\"string\"}, {\"id\":\"\",\"label\":\"Valor\",\"pattern\":\"\",\"type\":\"number\"} 
					], \"rows\": [ ";
					
	if($rating !== null){
		echo "{\"c\":[{\"v\":\"" . $status[0] . "\",\"f\":null},{\"v\":" . ($rating*5) . ",\"f\":null}]}, ";
		echo "{\"c\":[{\"v\":\"" . $status[1] . "\",\"f\":null},{\"v\":" . (($rating*5+$liquidez)/2) . ",\"f\":null}]}, ";
		echo "{\"c\":[{\"v\":\"" . $status[2] . "\",\"f\":null},{\"v\":" . $liquidez . ",\"f\":null}]}, ";
		echo "{\"c\":[{\"v\":\"" . $status[3] . "\",\"f\":null},{\"v\":" . (100-$rating*5) . ",\"f\":null}]}";
	}
	echo " ] }";
?>