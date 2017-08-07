<?php	
function show_tables(){
	/* HACER COMPROBACION DE CONEXION A BBDD DEL CLIENTE */
	error_reporting(0);
	include 'connect_cliente.php';
	if(!mysqli_ping($con)){
		$_SESSION['error'] = "03";//Fallo Cliente
		header("Location:" . getenv('HTTP_REFERER'));
		die();
	}
	mysqli_query($con,"SET NAMES 'utf8'");
	
	$sql = "SHOW TABLES";
	$result = mysqli_query($con,$sql);
	
	while($row = mysqli_fetch_array($result)){
		echo '<option value='. $row['Tables_in_'.$cliente_namedb] . '>'. $row['Tables_in_'.$cliente_namedb] . '</option>';
	}
	mysqli_close($con);
}
?>