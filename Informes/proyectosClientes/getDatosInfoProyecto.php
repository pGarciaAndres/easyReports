<?php
	session_start();
	$dbserver="localhost";
	$dbuser="root";
	$dbpass="password";
	$dbname=$_SESSION['eR_cliente'];
	$q=$_GET["q"];

	if ($q == ''){
		echo '<h3>Seleccione un proyecto de la lista para ver información detallada.</h3>';
	} else {
		$conexion = new PDO("mysql:host=$dbserver;dbname=$dbname",$dbuser,$dbpass);
		$conexion->query("SET NAMES 'utf8'");
		
		$sql = 'CALL datosProyecto("'. $q .'")';
		$q = $conexion->query($sql);
		$q->setFetchMode(PDO::FETCH_ASSOC);

		if($row = $q->fetch()){
			echo '<h3>Proyecto: '.$row['Nombre'].'</br>';
			echo '<h3>Descripción: '.$row['Descripcion'].'</br>';
			echo '<h3>Iniciado el: '.$row['Fecha_Inicio'].'</br>';
			if ($row['Fecha_Fin'] == 0){
				echo '<h3>Estado del proyecto: ACTIVO</br>';
			} else {
				echo '<h3>Estado del proyecto: INACTIVO</br>';
			}
			echo '<h3>Media de clientes: '.$row['Clientes_Mes'].' al mes.</br>';
			echo '<h3>Artículos más vendidos:';

		}

	}
?>