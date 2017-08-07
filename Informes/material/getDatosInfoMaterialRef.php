<?php
	session_start();
	$dbserver="localhost";
	$dbuser="root";
	$dbpass="password";
	$dbname=$_SESSION['eR_cliente'];
	$q=$_GET["q"];

	if ($q == ''){
		echo '<h3>Para consultar un material, introduzca su número de serie.</h3>';
	} else {
		$conexion = new PDO("mysql:host=$dbserver;dbname=$dbname",$dbuser,$dbpass);
		$conexion->query("SET NAMES 'utf8'");
		
		$sql = 'CALL datosMaterialRef("'. $q .'")';
		$q = $conexion->query($sql);
		$q->setFetchMode(PDO::FETCH_ASSOC);

		if($row = $q->fetch()){
			echo '<h3>Material: '.$row['Tipo'].' marca '.$row['Marca'].'</br>';
			echo '<h3>Usuario: '.$row['Usuario'].' del departamento de '.$row['Departamento'].'</br>';
			echo '<h3>Fecha de compra: '.$row['Fecha_Compra'].'</br>';
			echo '<h3>Estado: '.$row['Estado'].'</br>';
		} else {
			echo '<h3>Material no encontrado</h3></br>';
		}

	}
?>