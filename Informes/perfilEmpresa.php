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
<!-- === INFORME PERFIL DE EMPRESA === -->
<html>
<head>
	<title>Easy Reports | Perfil de Empresa</title>
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
	<!-- Grafica Empleados -->
	<script type="text/javascript">
		function pintaGraficaEmpleados(opt){
			if(opt != ''){
				var datosGraficaEmpleados = $.ajax({
					url: "./perfilEmpresa/getDatosGraficaEmpleados.php",
					data: "q="+opt,
					dataType: "json",
					async: false
				}).responseText;
				var graficaEmpleados = new google.visualization.DataTable(datosGraficaEmpleados);
				var chart2 = new google.visualization.BarChart(document.getElementById('chart_empleados'));
				chart2.draw(graficaEmpleados, {
					width: 400,
					height: 251,
					pieSliceText: 'value',
					orientation: 'vertical',
					filterColumnLabel: 'Empleados',
					hAxis: {'minValue': 0, 'maxValue': 100},
					chartArea: { left:"15%",top:"5%",width:"80%",height:"85%" }
				});
			}
		}
	</script>
	<!-- Grafica Sexos -->
	<script type="text/javascript">
		function pintaGraficaSexos(opt){
			if(opt != ''){
				var datosGraficaSexos = $.ajax({
					url: "./perfilEmpresa/getDatosGraficaSexos.php",
					data: "q="+opt,
					dataType: "json",
					async: false
				}).responseText;
				var graficaSexos = new google.visualization.DataTable(datosGraficaSexos);
				var chart1 = new google.visualization.PieChart(document.getElementById('chart_sexos'));
				chart1.draw(graficaSexos, {
					width: 400,
					height: 251,
					pieSliceText: 'percentage',
					is3D: true,
					chartArea: { left:"5%",top:"5%",width:"85%",height:"85%" }
				});
			}
		}
	</script>
	<!-- Estructura corporativa -->
	<script type="text/javascript">
		function pintaTablaCorporativa(){
			var datosTablaCorporativa = $.ajax({
				url: "./perfilEmpresa/getDatosTablaCorporativa.php",
				dataType: "json",
				async: false
			}).responseText;
			var tablaCorporativa = new google.visualization.DataTable(datosTablaCorporativa);
			var tabla = new google.visualization.Table(document.getElementById('tabla_corporativa'));
			tabla.draw(tablaCorporativa, {
				showRowNumber: false,
				alternatingRowStyle: true,
				width: "100%"
			});
		}
	</script>
	<!-- Grafica Ventas -->
	<script type="text/javascript">
		function pintaGraficaVentas(){
			var datosGraficaVentas = $.ajax({
				url: "./perfilEmpresa/getDatosGraficaVentas.php",
				dataType: "json",
				async: false
			}).responseText;
			var graficaVentas = new google.visualization.DataTable(datosGraficaVentas);
			var chart3 = new google.visualization.ImageLineChart(document.getElementById('chart_ventas'));
			chart3.draw(graficaVentas, {
				width: 400,
				height: 251,
				chartArea: { left:"5%",top:"5%",width:"85%",height:"85%" }
			});
		}
	</script>
	<!-- Grafica Ganancias-->
	<script type="text/javascript">
		function pintaGraficaGanancias(){
			var datosGraficaGanancias = $.ajax({
				url: "./perfilEmpresa/getDatosGraficaGanancias.php",
				dataType: "json",
				async: false
			}).responseText;
			var graficaGanancias = new google.visualization.DataTable(datosGraficaGanancias);
			var chart4 = new google.visualization.ImageLineChart(document.getElementById('chart_ganancias'));
			chart4.draw(graficaGanancias, {
				width: 400,
				height: 251,
				vAxis: {'minValue': 0},
				chartArea: { left:"15%",top:"5%",width:"85%",height:"85%" }
			});
		}
	</script>
</head>
<body>
	<div id="oscuridad" style="display:block;">
		<div id="cargando" style="text-align:center;">
			<img class="loading" src="../Imagenes/Informes/loading.gif" width="250"/>
			<h1>Perfil de Empresa</h1>
		</div>
	</div>
	<div id="general">
		<div id="header">
			<!--<img class="logo_empresa" src="../Imagenes/Informes/callcenter.png"/>-->
			<h1 class="titulo">Perfil de Empresa</h1>
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
			mysqli_select_db($con,"easylogin");
			mysqli_query($con,"SET NAMES 'utf8'");
			//Query 
			$sql = 'CALL datosEmpresa("' . $userlabel . '")';
			$result = mysqli_query($con,$sql);
			if($row = mysqli_fetch_array($result)){
				$empresa 	= $row['Empresa'];
				$objSocial 	= $row['ObjetoSocial'];
				$nif 		= $row['NIF'];
				$fecha 		= $row['Fecha_Alta'];
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
				<h3>Objeto social: &nbsp;&nbsp; <?php echo $objSocial; ?></h3>
				<h3>Número Identificación Fiscal (NIF): &nbsp;&nbsp; <?php echo $nif; ?></h3>
				<h3>Con actividad desde: &nbsp;&nbsp; <?php echo $fecha; ?></h3>
			</div>
			<div id="box">
				<h3>Domicilio: &nbsp;&nbsp; <?php echo $domicilio; ?></h3>
				<h3>Teléfono: &nbsp;&nbsp; <?php echo $tlf; ?></h3>
				<h3>Em@il: &nbsp;&nbsp; <?php echo $email; ?></h3>
				<h3>URL: &nbsp;&nbsp; <?php echo $url; ?></h3>
			</div>
			<div id="box">
				<form>
					<select name="Sexos" onchange="pintaGraficaSexos(this.value),pintaGraficaEmpleados(this.value)">
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
						$sql = 'CALL controlAnioEmpleados()';
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
				<h3 style="float: left; width: 50%">Evolución de plantilla</h3>
				<h3 style="float: right; width: 50%">Distribución de sexos</h3>
				<div id="chart_empleados">
					<script type="text/javascript">pintaGraficaEmpleados('<?php echo $first; ?>')</script>
				</div>
				<div id="chart_sexos">
					<script type="text/javascript">pintaGraficaSexos('<?php echo $first; ?>')</script>
				</div>
			</div>
			<div id="clear"></div>
			<div id="box">
				<h3>Estructura corporativa</h3></BR>
				<div id="tabla_corporativa">
					<script type="text/javascript">pintaTablaCorporativa()</script>
				</div>
			</div>
			<div id="box">		
				<h3 style="float: left; width: 50%">Ventas Último Año</h3>
				<h3 style="float: right; width: 50%">Ganancias Último Año</h3>
				<div id="chart_ventas">
					<script type="text/javascript">pintaGraficaVentas()</script>
				</div>
				<div id="chart_ganancias">
					<script type="text/javascript">pintaGraficaGanancias()</script>
				</div>
			</div>
			
			<div id="pie">
				<img class="logo_easyReports" src="../Imagenes/logo/logoInformes.png" height="30px"; width="122px"; />
				<a href="../informes.php"><div class="volver"></div></a>
			</div>
			<div id="clear"></div>
		</div>
	</div>
	
</body>
</html>