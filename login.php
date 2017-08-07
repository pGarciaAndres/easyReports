<?php
/* Inicio de variables de sesion */
if(!isset($_SESSION)){
	session_start();
}
/* HACER COMPROBACION DE CONEXION A BBDD DEL SERVIDOR */
error_reporting(0);
include 'connect_local.php';
if(!mysqli_ping($con)){
	$_SESSION['error'] = "02";//Fallo Servidor
	header("Location:" . getenv('HTTP_REFERER'));
	die();
}
mysqli_query($con,"SET NAMES 'utf8'");
/* Recibir datos de formulario */
$usuario = $_POST["username"];
$no_encrypt = $_POST["password"];
$password = sha1($no_encrypt);

/* Consulta a la base de datos */
$sql = "SELECT * FROM `easyLogin`.`login` WHERE username='$usuario' AND password='$password'";
$result = mysqli_query($con,$sql);

/* Usuario no logueado */
if(mysqli_num_rows($result) == 0){
	mysqli_close($con);
	$_SESSION['error'] = "01";//Usuario No Encontrado
	header("Location:" . getenv('HTTP_REFERER'));
/* Usuario logueado */
} else {
	$fila=mysqli_fetch_array($result);
	$_SESSION['usuario'] = $fila['username'];
	$_SESSION['eR_cliente'] = "eR_".$fila['namedb'];
/* Para cuando se loguea desde Configuracion para sincronizar */
	$_SESSION['hostdb']	= $fila['hostdb'];
	$_SESSION['userdb']	= $fila['userdb'];
	$_SESSION['passdb']	= $fila['passdb'];
	$_SESSION['namedb']	= $fila['namedb'];
/* Para controlar sincronizacion antes de informes */
	$_SESSION['departamentostb'] = $fila['Tbl_Departamentos'];
	$_SESSION['empleadostb']	 = $fila['Tbl_Empleados'];
	$_SESSION['materialtb']		 = $fila['Tbl_Material'];
	$_SESSION['mantenimientotb'] = $fila['Tbl_Mantenimientos'];
	$_SESSION['proyectostb'] 	 = $fila['Tbl_Proyectos'];
	$_SESSION['clientestb'] 	 = $fila['Tbl_Clientes'];
	
	mysqli_close($con);
	header('Location:' . getenv('HTTP_REFERER'));
}
?>