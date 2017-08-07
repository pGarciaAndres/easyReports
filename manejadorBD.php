<?php
session_start();
/* Tablas de la BBDD del cliente (Parametro que viene de SINCRONIZA) */
$departamentos	= $_SESSION['departamentostb'];
$empleados		= $_SESSION['empleadostb'];
$material		= $_SESSION['materialtb'];
$mantenimiento	= $_SESSION['mantenimientotb'];
$proyectos		= $_SESSION['proyectostb'];
$clientes		= $_SESSION['clientestb'];
/* HACER COMPROBACION DE CONEXION A BBDD DEL CLIENTE */
error_reporting(0);
include 'connect_cliente.php';
if(!mysqli_ping($con)){
	$_SESSION['error'] = "03";//Fallo Cliente
	header("Location:" . getenv('HTTP_REFERER'));
	die();
}
/* Conexion Estable */
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

/* Modelo de Estructura Base */
$eR_modelo = "easyDatabase";
/* Base de Datos sin depurar del cliente */
$cp_cliente = "cp_".$cliente_namedb;
/* Base de Datos depurada del cliente */
$eR_cliente = "eR_".$cliente_namedb;
$_SESSION['eR_cliente'] = $eR_cliente;

/* 0 - Borra BBDD eR_namedb si existe (Resincronizacion) */
$sql="DROP SCHEMA $eR_cliente";
mysqli_query($con,$sql);

/* 1 - Crea BBDD nueva vacía eR_namedb */
$exe1="Funciones\mysqladmin.exe -h $mi_host -u $mi_user --password=$mi_pass CREATE $eR_cliente";
system($exe1,$res1);

/* 2 - Hace una copia de easyDatabase en eR_cliente */
$exe2="Funciones\mysqldump.exe -h $mi_host -u $mi_user --password=$mi_pass $eR_modelo | Funciones\mysql.exe -h $mi_host -u $mi_user --password=$mi_pass $eR_cliente" ;
system($exe2,$res2);

/* 3 - Ejecuta el script MAN.SQL para incluir las rutinas en eR_cliente */
$exe3="Funciones\mysql.exe -h $mi_host -u $mi_user --password=$mi_pass $eR_cliente < MAN.sql";
system($exe3,$res3);

/* 4 - Crea BBDD nueva vacía cp_namedb */
$exe4="Funciones\mysqladmin.exe -h $mi_host -u $mi_user --password=$mi_pass CREATE $cp_cliente";
system($exe4,$res4);

/* 5 - Copia BBDD original en la creada nueva */
$exe5="Funciones\mysqldump.exe -h $cliente_host -u $cliente_user --password=$cliente_pass $cliente_namedb | Funciones\mysql.exe -h $mi_host -u $mi_user --password=$mi_pass $cp_cliente";
system($exe5,$res5);

/* 6 - Migrar tablas de cp_cliente a eR_cliente */
$sql="INSERT INTO $eR_cliente.departamentos SELECT * FROM $cp_cliente.$departamentos";
mysqli_query($con,$sql);
$sql="INSERT INTO $eR_cliente.empleados SELECT * FROM $cp_cliente.$empleados";
mysqli_query($con,$sql);
$sql="INSERT INTO $eR_cliente.material SELECT * FROM $cp_cliente.$material";
mysqli_query($con,$sql);
$sql="INSERT INTO $eR_cliente.mantenimiento SELECT * FROM $cp_cliente.$mantenimiento";
mysqli_query($con,$sql);
$sql="INSERT INTO $eR_cliente.proyectos SELECT * FROM $cp_cliente.$proyectos";
mysqli_query($con,$sql);
$sql="INSERT INTO $eR_cliente.clientes SELECT * FROM $cp_cliente.$clientes";
mysqli_query($con,$sql);

/* 7 - Borra la copia de la BBDD cliente */
$sql="DROP SCHEMA $cp_cliente";
mysqli_query($con,$sql);

/* Cierra conexion */
mysqli_close($con);
/* Redirecciona a página anterior */
header("Location:" . getenv('HTTP_REFERER'));
?>