<?php
	$user = $_POST['b'];
	if(!empty($user)) {
		comprobar($user);
	}
	function comprobar($b) {
		/* HACER COMPROBACION DE CONEXION A BBDD DEL SERVIDOR */
		error_reporting(0);
		include 'connect_local.php';
		if(!mysqli_ping($con)){
			header("Location:" . getenv('HTTP_REFERER') . "?error=02");
			die();
		}
		
		$sql = "SELECT * FROM `easyLogin`.`login` WHERE username = '".$b."'";
		$result = mysqli_query($con,$sql);
        $contar = mysqli_num_rows($result);
		
        if($contar == 0){
			echo "<span style='float: right;color:green;'>Usuario disponible.</span>";
		}else{
			echo "<span style='float: right;color:red;'>Usuario no disponible.</span>";
		}
		mysqli_close($con);
	}
?>