<?php
	function calcularLiquidez($anio){
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
	$sql = 'CALL liquidezEmpresa(' . $anio . ')';
	$result = mysqli_query($con,$sql);
	if($row = mysqli_fetch_array($result)){
		$liquidez = (INT)(100-83.3*$row['Liquidez']);
		switch($liquidez){
			case ($liquidez >=80):
				$liquidezRet="Baja";
				break;
			case ($liquidez <= 79 && $liquidez >= 60):
				$liquidezRet="Media-Baja";
				break;
			case ($liquidez <= 59 && $liquidez >= 40):
				$liquidezRet="Media";
				break;
			case ($liquidez <= 39 && $liquidez >= 20):
				$liquidezRet="Media-Alta";
				break;
			case ($liquidez <= 19):
				$liquidezRet="Alta";
				break;
			default:
				$liquidezRet="No existen datos.";
		}
		if($liquidez < 0){
			$liquidez = 0;
		}
		$solvencia = array($anio,$liquidez,$liquidezRet);
	}
	mysqli_close($con);
	return $solvencia;
	}
?>