<?php
	session_start();
	session_destroy();
	
	unset($_SESSION['usuario']);
	unset($_SESSION['siguiente']);
	unset($_SESSION['error']);
	unset($_SESSION['hostdb']);
	unset($_SESSION['userdb']);
	unset($_SESSION['passdb']);
	unset($_SESSION['namedb']);
	unset($_SESSION['departamentostb']);
	unset($_SESSION['empleadostb']);
	unset($_SESSION['materialtb']);
	unset($_SESSION['mantenimientotb']);
	unset($_SESSION['proyectostb']);
	unset($_SESSION['clientestb']);
	unset($_SESSION['eR_cliente']);
	unset($_SESSION['departamentostb']);
	unset($_SESSION['empleadostb']);
	unset($_SESSION['materialtb']);
	unset($_SESSION['mantenimientotb']);
	unset($_SESSION['proyectostb']);
	unset($_SESSION['clientestb']);
	
	
	session_unset();
	header("Location:" . getenv('HTTP_REFERER'));
	?>