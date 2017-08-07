<?php
/* Datos del cliente (Parametro que viene de REGISTRO) */
$cliente_host 	= $_SESSION['hostdb'];
$cliente_user 	= $_SESSION['userdb'];
$cliente_pass	= $_SESSION['passdb'];
$cliente_namedb = $_SESSION['namedb'];

$con = mysqli_connect("$cliente_host","$cliente_user","$cliente_pass","$cliente_namedb");
?>