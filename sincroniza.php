<?php
/* HACER COMPROBACION DE CONEXION A BBDD DEL SERVIDOR */
error_reporting(0);
include 'connect_local.php';
if(!mysqli_ping($con)){
	$_SESSION['error'] = "02";//Fallo Servidor
	header("Location:" . getenv('HTTP_REFERER'));
	die();
}
mysqli_query($con,"SET NAMES 'utf8'");

/* Inicia sesion y usa vble de sesion: usuario */
session_start();
$usuario = $_SESSION['usuario'];
/* Recibir datos de SINCRONIZA */
$departamentos 	= $_POST["tbl_dep"];
$empleados 		= $_POST["tbl_emp"];
$material 		= $_POST["tbl_mat"];
$mantenimiento 	= $_POST["tbl_man"];
$proyectos 		= $_POST["tbl_pro"];
$clientes 		= $_POST["tbl_cli"];

/* Consulta a la base de datos */
$sql = "SELECT * FROM `easyLogin`.`login` WHERE username='$usuario'";
$result = mysqli_query($con,$sql);

if(mysqli_num_rows($result) == 0){
	$_SESSION['error'] = "01";//Usuario No Encontrado
	header("Location:" . getenv('HTTP_REFERER'));
} else {
	$sql = "UPDATE `easyLogin`.`login` SET `Tbl_Departamentos`='$departamentos', `Tbl_Empleados`='$empleados', `Tbl_Material`='$material', `Tbl_Mantenimiento`='$mantenimiento', `Tbl_Proyectos`='$proyectos', `Tbl_Clientes`='$clientes' WHERE `username`='$usuario';";
	mysqli_query($con,$sql);
	
	/* VARIABLES DE SESION (TABLAS) */
	$_SESSION['departamentostb'] = $departamentos;
	$_SESSION['empleadostb']	 = $empleados;
	$_SESSION['materialtb']		 = $material;
	$_SESSION['mantenimientotb'] = $mantenimiento;
	$_SESSION['proyectostb'] 	 = $proyectos;
	$_SESSION['clientestb'] 	 = $clientes;
	
	/* Llamar a ManejadorDB siempre despues de sincronizar y desde Configuracion */
	mysqli_close($con);	
	header("Location:manejadorBD.php");
}
?>