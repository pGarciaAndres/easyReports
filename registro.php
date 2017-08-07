<?php
//Inicio de variables de sesion
if(!isset($_SESSION)){
	session_start();
}
//Recibe los datos del formulario de REGISTRO
$usuario 	= $_POST["newuser"];
$no_encrypt = $_POST["newpass"];
$password 	= sha1($no_encrypt);
/* ---------------------------- */
$hostdb 	= $_POST["Hostdb"];
$userdb 	= $_POST["Userdb"];
$no_encrypt_db = $_POST["Passdb"];
$passdb 	= $no_encrypt_db;
$namedb 	= $_POST["Namedb"];
/* ---------------------------- */
$empresa 	= $_POST["Empresa"];
$objSocial 	= $_POST["ObjSocial"];
$nif 		= $_POST["NIF"];
$fecha 		= $_POST["Fecha_Alta"];
$domicilio 	= $_POST["Domicilio"];
$telefono 	= $_POST["Telefono"];
$email 		= $_POST["Email"];
$url 		= $_POST["URL"];

/* VARIABLES DE SESION (DATOS DE CONEXION) */
$_SESSION['hostdb']		= $hostdb;
$_SESSION['userdb']		= $userdb;
$_SESSION['passdb']		= $no_encrypt_db;
$_SESSION['namedb']		= $namedb;

/* HACER COMPROBACION DE CONEXION A BBDD DEL CLIENTE */
error_reporting(0);
include 'connect_cliente.php';
if(!mysqli_ping($con)){
	$_SESSION['error'] = "03";//Fallo Cliente
	header("Location:" . getenv('HTTP_REFERER'));
	die();
}
mysqli_close($con);

/* HACER COMPROBACION DE CONEXION A BBDD DEL SERVIDOR */
error_reporting(0);
include 'connect_local.php';
if(!mysqli_ping($con)){
	$_SESSION['error'] = "02";//Fallo Servidor
	header("Location:" . getenv('HTTP_REFERER'));
	die();
}
mysqli_query($con,"SET NAMES 'utf8'");
//Consulta a la base de datos
$sql = "SELECT * FROM `easyLogin`.`login` WHERE username='$usuario'";
$result = mysqli_query($con,$sql);
//Usuario ya existe
if(mysqli_num_rows($result) != 0){
	$_SESSION['error'] = "02";//Fallo Servidor
	header("Location:" . getenv('HTTP_REFERER'));
} else {
	$sql = "INSERT INTO `easyLogin`.`login` (`username`, `password`, `hostdb`, `userdb`, `passdb`, `namedb`, `Empresa`, `ObjetoSocial`, `NIF`, `Fecha_Alta`, `Domicilio`, `Telefono`, `Email`, `URL`) VALUES ('$usuario', '$password', '$hostdb', '$userdb', '$passdb', '$namedb', '$empresa', '$objSocial', '$nif', '$fecha', '$domicilio', '$telefono', '$email', '$url')";
	mysqli_query($con,$sql);
	mysqli_close($con);
	$_SESSION['usuario']	= $usuario;
	$_SESSION['siguiente']	= "Sincronizar";
	header("Location:" . getenv('HTTP_REFERER'));
}
?>