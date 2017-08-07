<?php
	session_start();
	$dbserver="localhost";
	$dbuser="root";
	$dbpass="password";
	$dbname=$_SESSION['eR_cliente'];
	$q=$_GET["q"];

	$conexion = new PDO("mysql:host=$dbserver;dbname=$dbname",$dbuser,$dbpass);
	$conexion->query("SET NAMES 'utf8'");

	$sql = 'CALL controlEmpleados("'. $q .'")';
	$q = $conexion->query($sql);
	$q->setFetchMode(PDO::FETCH_ASSOC);
	
	echo '<form><select name="Empleado" onchange="pintaInfoEmpleado(this.value)"><option value="">Seleccione Empleado:</option>';

	while($row = $q->fetch()){
		echo '<option value="'. $row['Empleados'] . '"">'. $row['Empleados'] . '</option>';
	}

	echo '</select></form>';
?>