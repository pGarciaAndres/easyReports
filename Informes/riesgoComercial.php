<?php
session_start();
if($_SESSION['usuario']){
	if(($_SESSION['departamentostb']=="")and($_SESSION['empleadostb']=="")and($_SESSION['materialtb']=="")and($_SESSION['mantenimientotb']=="")and($_SESSION['proyectostb']=="")and($_SESSION['clientestb']=="")){
		$_SESSION['siguiente']	= "Sincronizar";
		header("Location:" . getenv('HTTP_REFERER'));
		die();
	}else{
		$userlabel = $_SESSION['usuario'];
		$dbname = $_SESSION['eR_cliente'];
	}
}else{
	$_SESSION['error'] = "04";//Zona Restringida
	header("Location:" . getenv('HTTP_REFERER'));
	die();
}
?>
<!-- PROYECTO FIN DE CARRERA - PABLO GARCIA ANDRES -->
<!-- === INFORME RIESGO COMERCIAL === -->
<html>
<head>
	<title>Easy Reports | Riesgo Comercial</title>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta http-equiv="Content-Language" content="es"/> 
	<meta http-equiv="Pragma" content="no-cache" />
	<!-- Stylesheet CSS -->
	<link href="../css/style2.css" rel="stylesheet" type="text/css" />
	<!-- JS-Docs API Google Chart -->
	<script type="text/javascript" src="../js/jsapi.min.js"></script>
	<script type="text/javascript" src="../js/jquery-1.9.1.min.js"></script>
    <script type="text/javascript">google.load('visualization', '1.1', {packages: ['corechart','table','imagelinechart']});</script>
	<!-- Informe cargando -->
	<script type='text/javascript'>
		window.onload = detectarCarga;
		function detectarCarga() {
			setTimeout("cerrarOscuridad()", 2000);
		}
	</script>
	<script>
	    function cerrarOscuridad() {
			fondo=document.getElementById("oscuridad");
			fondo.style.display="none";
	    }
	</script>
	<!-- Grafica Ventas Año -->
	<script type="text/javascript">
		function pintaGraficaVentasAnio(opt){
			if(opt != ''){
				var datosGraficaVentas = $.ajax({
					url: "./riesgoComercial/getDatosGraficaVentasAnio.php",
					data: "q="+opt,
					dataType: "json",
					async: false
				}).responseText;
				var graficaVentas = new google.visualization.DataTable(datosGraficaVentas);
				var chart1 = new google.visualization.ImageLineChart(document.getElementById('chart_ventas'));
				chart1.draw(graficaVentas, {
					width: 400,
					height: 251,
					chartArea: { left:"5%",top:"5%",width:"85%",height:"85%" }
				});
			}
		}
	</script>
	<!-- Grafica Ganancias Año -->
	<script type="text/javascript">
		function pintaGraficaGananciasAnio(opt){
			if(opt != ''){
				var datosGraficaGanancias = $.ajax({
					url: "./riesgoComercial/getDatosGraficaGananciasAnio.php",
					data: "q="+opt,
					dataType: "json",
					async: false
				}).responseText;
				var graficaGanancias = new google.visualization.DataTable(datosGraficaGanancias);
				var chart2 = new google.visualization.ImageLineChart(document.getElementById('chart_ganancias'));
				chart2.draw(graficaGanancias, {
					width: 400,
					height: 251,
					chartArea: { left:"15%",top:"5%",width:"85%",height:"85%" }
				});
			}
		}
	</script>
	<!-- Grafica Financiera -->
	<script type="text/javascript">
		function pintaGraficaFinanciera(){
				var datosGraficaFinanciera = $.ajax({
					url: "./riesgoComercial/getDatosGraficaFinanciera.php",
					dataType: "json",
					async: false
				}).responseText;
				var graficaFinanciera = new google.visualization.DataTable(datosGraficaFinanciera);
				var chart3 = new google.visualization.BarChart(document.getElementById('chart_financiera'));
				chart3.draw(graficaFinanciera, {
					//colors: ['blue', 'red'],
					width: 700,
					height: 251,
					pieSliceText: 'value',
					orientation: 'vertical',
					filterColumnLabel: 'Valor',
					hAxis: {'minValue': 0, 'maxValue': 100},
					chartArea: { left:"20%", top:"5%",width:"65%",height:"85%" }
				});
		}
	</script>
</head>
<body>
	<div id="oscuridad" style="display:block;">
		<div id="cargando" style="text-align:center;">
			<img class="loading" src="../Imagenes/Informes/loading.gif" width="250"/>
			<h1>Riesgo Comercial</h1>
		</div>
	</div>
	<div id="general">
		<div id="header">
			<!--<img class="logo_empresa" src="../Imagenes/Informes/callcenter.png"/>-->
			<h1 class="titulo">Riesgo Comercial</h1>
		</div>
		<?php
			/* HACER COMPROBACION DE CONEXION A BBDD DEL SERVIDOR */
			error_reporting(0);
			include '../connect_local.php';
			if(!mysqli_ping($con)){
				$_SESSION['error'] = "02";//Fallo Servidor
				header("Location:" . getenv('HTTP_REFERER'));
				die();
			}
			mysqli_select_db($con,"easyLogin");
			mysqli_query($con,"SET NAMES 'utf8'");
			//Query 
			$sql = 'CALL datosEmpresa("' . $userlabel . '")';
			$result = mysqli_query($con,$sql);
			if($row = mysqli_fetch_array($result)){
				$empresa 	= $row['Empresa'];
				$nif 		= $row['NIF'];
				$domicilio 	= $row['Domicilio'];
				$tlf 		= $row['Telefono'];
				$email 		= $row['Email'];
				$url 		= $row['URL'];
			}
			mysqli_close($con);
		?>
		
		<div id="contenido">
			<div id="box">
				<h3>Denominación:&nbsp;&nbsp; <BIG><?php echo $empresa; ?></BIG></h3>
				<h3>Número Identificación Fiscal (NIF): &nbsp;&nbsp; <?php echo $nif; ?></h3>
				<h3>Domicilio: &nbsp;&nbsp; <?php echo $domicilio; ?></h3>
				<h3>Teléfono: &nbsp;&nbsp; <?php echo $tlf; ?></h3>
				<h3>Em@il: &nbsp;&nbsp; <?php echo $email; ?></h3>
				<h3>URL: &nbsp;&nbsp; <?php echo $url; ?></h3>
			</div>

			<?php
				include './riesgoComercial/getDatosRating.php';
				include './riesgoComercial/getDatosLiquidez.php';
				//Datos de registro
				$dbserver="localhost";
				$dbuser="root";
				$dbpass="password";
				//Conexion MySQL
				$conexion = new PDO("mysql:host=$dbserver;dbname=$dbname",$dbuser,$dbpass);
				$conexion->query("SET NAMES 'utf8'"); 
				//Query 
				$sql = 'CALL controlAnioClientes()';
				$q = $conexion->query($sql);
				$q->setFetchMode(PDO::FETCH_ASSOC);
				if($row = $q->fetch()){
					$first = $row['YEAR(Fecha_Alta)'];
					$credito = calcularRating($first);
					$solvencia = calcularLiquidez($first);
				}
			?>
			<div id="box">
				<h3>Crédito <?php echo $credito[0];?></h3></BR>
				<h3>Rating: &nbsp;&nbsp;<?php echo $credito[1];?> / 20&nbsp;&nbsp;<?php echo $credito[2]; ?></h3>
				<h3>Riesgo: &nbsp;&nbsp;<?php echo $credito[3];?></h3>
				<h3>Opinión de Crédito: &nbsp;<?php echo $credito[4];?></h3>
			</div>
			<div id="clear"></div>
			<div id="box">
				<h3>Solvencia <?php echo $solvencia[0];?></h3></BR>
				<h3>Score de Liquidez: &nbsp;&nbsp;<?php echo $solvencia[1];?> / 100</h3>
				<h3>Probabilidad retraso de pagos: &nbsp;&nbsp;<?php echo $solvencia[2];?></h3>
			</div>
			<div id="box">	
				<form>
					<select name="Ventas" onchange="pintaGraficaVentasAnio(this.value),pintaGraficaGananciasAnio(this.value)">
					<option value="">Seleccione año:</option>
					<?php
						//Datos de registro
						$dbserver="localhost";
						$dbuser="root";
						$dbpass="password";
						//Conexion MySQL
						$conexion = new PDO("mysql:host=$dbserver;dbname=$dbname",$dbuser,$dbpass);
						$conexion->query("SET NAMES 'utf8'"); 
						//Query 
						$sql = 'CALL controlAnioClientes()';
						$q = $conexion->query($sql);
						$q->setFetchMode(PDO::FETCH_ASSOC);
						if($row = $q->fetch()){
							$first = $row['YEAR(Fecha_Alta)'];
							echo '<option value='. $row['YEAR(Fecha_Alta)'] . '>'. $row['YEAR(Fecha_Alta)'] . '</option>';
						}
						while($row = $q->fetch()){
							echo '<option value='. $row['YEAR(Fecha_Alta)'] . '>'. $row['YEAR(Fecha_Alta)'] . '</option>';
						}
					?>
					</select>
				</form>	
				<h3 style="float: left; width: 50%">Ventas Últimos año</h3>
				<h3 style="float: right; width: 50%">Ganancias Últimos año</h3>
				<div id="chart_ventas">
					<script type="text/javascript">pintaGraficaVentasAnio('<?php echo $first; ?>')</script>
				</div>
				<div id="chart_ganancias">
					<script type="text/javascript">pintaGraficaGananciasAnio('<?php echo $first; ?>')</script>
				</div>
			</div>

			<div id="clear"></div>

			<div id="box">
				<h3>Situación Financiera</h3>
				<div id="chart_financiera">
					<script type="text/javascript">pintaGraficaFinanciera()</script>
				</div>
			</div>

			<div id="clear"></div>
			
			<div id="pie">
				<img class="logo_easyReports" src="../Imagenes/logo/logoInformes.png" height="30px"; width="122px"; />
				<a href="../informes.php"><div class="volver"></div></a>
			</div>
			<div id="clear"></div>
		</div>
	</div>
</body>
</html>