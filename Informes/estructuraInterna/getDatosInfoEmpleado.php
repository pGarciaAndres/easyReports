<?php
	session_start();
	$dbserver="localhost";
	$dbuser="root";
	$dbpass="password";
	$dbname=$_SESSION['eR_cliente'];
	$q=$_GET["q"];

	if ($q == ''){
		echo '<h3>Seleccione un departamento y un empleado para ver su información personal.</h3>';
	} else {
		$conexion = new PDO("mysql:host=$dbserver;dbname=$dbname",$dbuser,$dbpass);
		$conexion->query("SET NAMES 'utf8'");
		
		$sql = 'CALL datosEmpleado("'. $q .'")';
		$q = $conexion->query($sql);
		$q->setFetchMode(PDO::FETCH_ASSOC);

		while($row = $q->fetch()){
			echo '<h3>'.$row['Nombre'].', de '.$row['Ciudad'].' ciudad de '.$row['Pais'].'.</br>';
			echo 'Trabajador desde el '.substr($row['Fecha_Alta'],8,2).' del '.substr($row['Fecha_Alta'],5,2).' de '.substr($row['Fecha_Alta'],0,4).'.</br>';
			echo 'Perteneciente al departamento de '.$row['Departamento'].', desempeñando tareas de '.$row['Cargo'].' con un sueldo en la actualidad de '.$row['Sueldo'].' € mensuales.</h3>';
		}

	}
?>