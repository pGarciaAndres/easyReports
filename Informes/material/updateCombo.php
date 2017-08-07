<?php
	session_start();
	$q=$_GET["q"];
	$dbserver="localhost";
	$dbuser="root";
	$dbpass="password";
	$dbname=$_SESSION['eR_cliente'];

	$conexion = new PDO("mysql:host=$dbserver;dbname=$dbname",$dbuser,$dbpass);
	$conexion->query("SET NAMES 'utf8'");

	$sql = 'CALL controlEmpleados("'. $q .'")';
	$q = $conexion->query($sql);
	$q->setFetchMode(PDO::FETCH_ASSOC);
	
	echo '<form><select name="Empleado" onchange="pintaInfoMaterialEmp(this.value)"><option value="">Seleccione Empleado:</option>';

	while($row = $q->fetch()){
		echo '<option value="'. $row['Empleados'] . '"">'. $row['Empleados'] . '</option>';
	}

	echo '</select></form>';
?>