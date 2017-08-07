<?php
	session_start();
	$dbserver="localhost";
	$dbuser="root";
	$dbpass="password";
	$dbname=$_SESSION['eR_cliente'];
	$q=$_GET["q"];

	if ($q == ''){
		echo '<h3>Seleccione un departamento y un empleado para ver información sobre el material del que dispone.</h3>';
	} else {
		$conexion = new PDO("mysql:host=$dbserver;dbname=$dbname",$dbuser,$dbpass);
		$conexion->query("SET NAMES 'utf8'");
		
		$sql = 'CALL datosMaterial("'. $q .'")';
		$q = $conexion->query($sql);
		$q->setFetchMode(PDO::FETCH_ASSOC);

		if($row = $q->fetch()){
			echo '<h3>'.$row['Nombre'].'</br>';
			echo '<h3>'.$row['Cargo'].' del departamento de '.$row['Departamento'].'</br>';
			if($row['Tipo']==NULL){
				echo 'No consta ningún material a su cargo.</h3></br>';
			} else {
				echo 'Dispone del siguiente material:</h3></br>';
				do{
					echo '<h3>'.$row['Tipo'].', de la marca '.$row['Marca'].'</br>';
					echo 'Coste: '.$row['Coste'].' Euros, estado: '.$row['Estado'].'</h3></br>';
				} while($row = $q->fetch());
			}
			
		}

	}
?>