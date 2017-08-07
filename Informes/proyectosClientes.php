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
<!-- === INFORME PROYECTOS & CLIENTES === -->
<html>
<head>
	<title>Easy Reports | Proyectos y Clientes</title>
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
	<!-- Tabla proyectos -->
	<script type="text/javascript">
		function pintaTablaProyectos(opt){
			var datosTablaProyectos = $.ajax({
				url: "./proyectosClientes/getDatosTablaProyectos.php",
				data: "q="+opt,
				dataType: "json",
				async: false
			}).responseText;
			var tablaProyectos = new google.visualization.DataTable(datosTablaProyectos);
			var tabla = new google.visualization.Table(document.getElementById('tabla_proyectos'));
			tabla.draw(tablaProyectos, {
				showRowNumber: false,
				alternatingRowStyle: true,
				width: "100%"
			});
		}
	</script>
	<!-- Grafica Clientes Proyectos -->
	<script type="text/javascript">
		function pintaGraficaCliProyectos(opt){
				var datosGraficaCliProyectos = $.ajax({
					url: "./proyectosClientes/getDatosGraficaCliProyectos.php",
					data: "q="+opt,
					dataType: "json",
					async: false
				}).responseText;
				var graficaCliProyectos = new google.visualization.DataTable(datosGraficaCliProyectos);
				var chart1 = new google.visualization.BarChart(document.getElementById('chart_CliProyectos'));
				chart1.draw(graficaCliProyectos, {
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
	<!-- Grafica Ganancias Proyectos -->
	<script type="text/javascript">
		function pintaGraficaGanProyectos(opt){
				var datosGraficaGanProyectos = $.ajax({
					url: "./proyectosClientes/getDatosGraficaGanProyectos.php",
					data: "q="+opt,
					dataType: "json",
					async: false
				}).responseText;
				var graficaGanProyectos = new google.visualization.DataTable(datosGraficaGanProyectos);
				var chart2 = new google.visualization.BarChart(document.getElementById('chart_GanProyectos'));
				var formatter = new google.visualization.NumberFormat({
					suffix: '€',
					fractionDigits: 0
				});
				formatter.format(graficaGanProyectos, 1);
				chart2.draw(graficaGanProyectos, {
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
	<!-- Info Proyecto -->
	<script type="text/javascript">
		function pintaInfoProyecto(opt){
			$.ajax({
				url: "./proyectosClientes/getDatosInfoProyecto.php",
				data: "q="+opt,
				success: function(data){
					$("#info_proyecto").html(data);
				}
			});
		}
	</script>
	<!-- Grafica Proyectos -->
	<script type="text/javascript">
		function pintaGraficaProyecto(opt){
			if(opt != ''){
				var datosGraficaProyecto = $.ajax({
					url: "./proyectosClientes/getDatosGraficaProyecto.php",
					data: "q="+opt,
					dataType: "json",
					async: false
				}).responseText;
				var graficaProyecto = new google.visualization.DataTable(datosGraficaProyecto);
				var chart3 = new google.visualization.BubbleChart(document.getElementById('chart_proyecto'));
				chart3.draw(graficaProyecto, {
					width: 800,
					height: 500,
					hAxis: {title: 'Ventas'},
        			vAxis: {title: 'Recaudación'},
        			bubble: {textStyle: {fontSize: 11}}
				});
			}
		}
	</script>
</head>
<body>
	<div id="oscuridad" style="display:block;">
		<div id="cargando" style="text-align:center;">
			<img class="loading" src="../Imagenes/Informes/loading.gif" width="250"/>
			<h1>Proyectos y Clientes</h1>
		</div>
	</div>
	<div id="general">
		<div id="header">
			<!--<img class="logo_empresa" src="../Imagenes/Informes/callcenter.png"/>-->
			<h1 class="titulo">Proyectos y Clientes</h1>
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
				$objSocial 	= $row['ObjetoSocial'];
				$fecha 		= $row['Fecha_Alta'];
			}
			mysqli_close($con);
		?>
		
		<div id="contenido">
			<div id="box">
				<h3>Denominación:&nbsp;&nbsp; <BIG><?php echo $empresa; ?></BIG></h3>
				<h3>Objeto social: &nbsp;&nbsp; <?php echo $objSocial; ?></h3>
				<h3>Con actividad desde: &nbsp;&nbsp; <?php echo $fecha; ?></h3>
			</div>

			<div id="box">
				<form>
					<select name="Proyectos" onchange="pintaTablaProyectos(this.value),pintaGraficaCliProyectos(this.value),pintaGraficaGanProyectos(this.value)">
						<option value="activos">Activos</option>
						<option value="inactivos">Inactivos</option>
					</select>
				</form>
				<h3>Tabla de Proyectos</h3></BR>
				<div id="tabla_proyectos">
					<script type="text/javascript">pintaTablaProyectos("activos")</script>
				</div>
			</div>
			<div id="clear"></div>
			<div id="box">
				<h3>Número de Clientes</h3>
				<div id="chart_CliProyectos">
					<script type="text/javascript">pintaGraficaCliProyectos("activos")</script>
				</div>
				<h3>Volumen de Ganancias</h3>
				<div id="chart_GanProyectos">
					<script type="text/javascript">pintaGraficaGanProyectos("activos")</script>
				</div>
			</div>
			<div id="box">
				<form>
					<select name="Proyecto" onchange="pintaInfoProyecto(this.value),pintaGraficaProyecto(this.value)">
					<option value="">Seleccione Proyecto:</option>
					<?php
						//Datos de registro
						$dbserver="localhost";
						$dbuser="root";
						$dbpass="password";
						//Conexion MySQL
						$conexion = new PDO("mysql:host=$dbserver;dbname=$dbname",$dbuser,$dbpass);
						$conexion->query("SET NAMES 'utf8'"); 
						//Query 
						$sql = 'CALL controlProyectos()';
						$q = $conexion->query($sql);
						$q->setFetchMode(PDO::FETCH_ASSOC);
						if($row = $q->fetch()){
							echo '<option value="'. $row['Proyectos'] . '"">'. $row['Proyectos'] . '</option>';
						}
						while($row = $q->fetch()){
							echo '<option value="'. $row['Proyectos'] . '"">'. $row['Proyectos'] . '</option>';
						}
					?>
					</select>
				</form>
				<h3>Detalles del Proyecto</h3>
				<script type="text/javascript">pintaInfoProyecto('')</script>
				<div id="info_proyecto"></div>
				<div id="chart_proyecto"></div>
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