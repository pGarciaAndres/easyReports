<?php
	function calcularRating($anio){
	/* HACER COMPROBACION DE CONEXION A BBDD DEL SERVIDOR */
	error_reporting(0);
	include '../connect_local.php';
	if(!mysqli_ping($con)){
		$_SESSION['error'] = "02";//Fallo Servidor
		header("Location:" . getenv('HTTP_REFERER'));
		die();
	}
	$dbname = $_SESSION['eR_cliente'];
	mysqli_select_db($con,$dbname);
	mysqli_query($con,"SET NAMES 'utf8'");
	//Query 
	$sql = 'CALL ratingEmpresa(' . $anio . ')';
	$result = mysqli_query($con,$sql);
	if($row = mysqli_fetch_array($result)){
		$rating	= (INT)(20-16.6*$row['Rating']);
		switch($rating){
			case ($rating >=18):
				$ratingVal="Excelente";
				$ratingRisk="Inexistente";
				$ratingView="Se ha conseguido alcanzar un balance muy favorable, esto es debido a un 
							nivel de ventas muy elevado con respecto a las pérdidas de la empresa, enorabuena.";
				break;
			case ($rating <= 17 && $rating >= 13):
				$ratingVal="Bueno";
				$ratingRisk="Bajo";
				$ratingView="Mantiene un nivel de rating favorable, el número de ventas es alto con
							respecto a los gastos de la empresa, buen trabajo.";
				break;
			case ($rating <= 12 && $rating >= 8):
				$ratingVal="Aceptable";
				$ratingRisk="Medio";
				$ratingView="El balance entre ganancias y gastos aún es favorable pero no es muy elevado,
							se recomienda prudencia e intentar incrementar las ventas.";
				break;
			case ($rating <= 7 && $rating >= 4):
				$ratingVal="Débil";
				$ratingRisk="Alto";
				$ratingView="El nivel de rating es excesivamente bajo y podría ocasionar problemas en un
							futuro próximo, se recomienda estudiar una nueva estrategia de mercado.";
				break;
			case ($rating <= 3):
				$ratingVal="Deficiente";
				$ratingRisk="Muy Alto";
				$ratingView="Nivel alarmante de pérdidas y escasos beneficios durante el año, se
							recomienda acción inmediata para evitar la quiebra de la empresa .";
				break;
			default:
				$ratingVal="No existen datos.";
				$ratingRisk="No existen datos.";
				$ratingView="";
		}
		if($rating < 0){
			$rating = 0;
		}
		$credito = array($anio,$rating,$ratingVal,$ratingRisk,$ratingView);
	}
	mysqli_close($con);
	return $credito;
	}
?>