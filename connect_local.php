<?php
$mi_host	= "localhost";  //Nombre del Host
$mi_user	= "root"; 		//Usuario Mysql
$mi_pass	= "password"; 	//Contraseña Mysql
$mi_login = "easyLogin";	//BBDD de autenticación

/* Conexión normal sin MySQL Proxy */
$con = mysqli_connect("$mi_host","$mi_user","$mi_pass");

/* Conexión con MySQL Proxy */
/* $con = mysqli_connect("$mi_host","$mi_user","$mi_pass","$mi_login",4040); */
?>