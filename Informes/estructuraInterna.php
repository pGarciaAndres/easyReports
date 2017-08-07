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
<!-- === INFORME ESTRUCTURA INTERNA === -->
<html>
<head>
	<title>Easy Reports | Estructura Interna</title>
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
	<!-- Grafica Integrantes -->
	<script type="text/javascript">
		function pintaGraficaIntegrantes(){
				var datosGraficaIntegrantes = $.ajax({
					url: "./estructuraInterna/getDatosGraficaIntegrantes.php",
					dataType: "json",
					async: false
				}).responseText;
				var graficaIntegrantes = new google.visualization.DataTable(datosGraficaIntegrantes);
				var chart1 = new google.visualization.PieChart(document.getElementById('chart_integrantes'));
				chart1.draw(graficaIntegrantes, {
					width: 400,
					height: 251,
					pieSliceText: 'value',
					legend: 'labeled',
					is3D: false,
					chartArea: { left:"5%",top:"5%",width:"85%",height:"85%" }
				});
		}
	</script>
	<!-- Grafica Sueldos -->
	<script type="text/javascript">
		function pintaGraficaSueldos(){
				var datosGraficaSueldos = $.ajax({
					url: "./estructuraInterna/getDatosGraficaSueldos.php",
					dataType: "json",
					async: false
				}).responseText;
				var graficaSueldos = new google.visualization.DataTable(datosGraficaSueldos);
				var chart2 = new google.visualization.PieChart(document.getElementById('chart_sueldos'));
				var formatter = new google.visualization.NumberFormat({
					suffix: '€',
					fractionDigits: 0
				});
				formatter.format(graficaSueldos, 1);
				chart2.draw(graficaSueldos, {
					width: 400,
					height: 251,
					pieSliceText: 'value',
					legend: 'labeled',
					is3D: false,
					chartArea: { left:"5%",top:"5%",width:"85%",height:"85%" }
				});
		}
	</script>
	<!-- Tabla empleados -->
	<script type="text/javascript">
		function pintaTablaEmpleados(opt){
			var datosTablaEmpleados = $.ajax({
				url: "./estructuraInterna/getDatosTablaEmpleados.php",
				data: "q="+opt,
				dataType: "json",
				async: false
			}).responseText;
			var tablaEmpleados = new google.visualization.DataTable(datosTablaEmpleados);
			var tabla = new google.visualization.Table(document.getElementById('tabla_empleados'));
			tabla.draw(tablaEmpleados, {
				showRowNumber: false,
				alternatingRowStyle: true,
				width: "100%"
			});
		}
	</script>
	<!-- Info Empleados -->
	<script type="text/javascript">
		function pintaInfoEmpleado(opt){
			$.ajax({
				url: "./estructuraInterna/getDatosInfoEmpleado.php",
				data: "q="+opt,
				success: function(data){
					$("#info_empleado").html(data);
				}
			});
		}
	</script>
	<!-- Actualizar Combo -->
	<script type="text/javascript">
		function updateCombo(opt){
			$.ajax({
				url: "./estructuraInterna/updateCombo.php",
				data: "q="+opt,
				success: function(data){
					$("#comboEmpleados").html(data);
				}
			});
		}
	</script>
</head>
<body>
	<div id="oscuridad" style="display:block;">
		<div id="cargando" style="text-align:center;">
			<img class="loading" src="../Imagenes/Informes/loading.gif" width="250"/>
			<h1>Estructura Interna</h1>
		</div>
	</div>
	<div id="general">
		<div id="header">
			<!--<img class="logo_empresa" src="../Imagenes/Informes/callcenter.png"/>-->
			<h1 class="titulo">Estructura Interna</h1>
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
			}
			mysqli_close($con);
		?>
		<div id="contenido">
			<div id="box">
				<h3>Denominación:&nbsp;&nbsp; <BIG><?php echo $empresa; ?></BIG></h3>
			</div>
			<div id="box">
				<h3>Tabla de Departamentos</h3></BR>
				<div id="tabla_corporativa">
					<script type="text/javascript">pintaTablaCorporativa()</script>
				</div>
			</div>
			<div id="box">
				<h3 style="float: left; width: 50%">Integrantes</h3>
				<h3 style="float: right; width: 50%">Sueldos</h3>
				<div id="chart_integrantes">
					<script type="text/javascript">pintaGraficaIntegrantes()</script>
				</div>
				<div id="chart_sueldos">
					<script type="text/javascript">pintaGraficaSueldos()</script>
				</div>
			</div>
			<div id="clear"></div>
			<div id="box">
				<form>
					<select name="Departamento" onchange="pintaTablaEmpleados(this.value)">
					<option value="">Seleccione Departamento:</option>
					<?php
						//Datos de registro
						$dbserver="localhost";
						$dbuser="root";
						$dbpass="password";
						//Conexion MySQL
						$conexion = new PDO("mysql:host=$dbserver;dbname=$dbname",$dbuser,$dbpass);
						$conexion->query("SET NAMES 'utf8'"); 
						//Query 
						$sql = 'CALL controlDepartamentos()';
						$q = $conexion->query($sql);
						$q->setFetchMode(PDO::FETCH_ASSOC);
						if($row = $q->fetch()){
							$first = $row['Departamentos'];
							echo '<option value="'. $row['Departamentos'] . '"">'. $row['Departamentos'] . '</option>';
						}
						while($row = $q->fetch()){
							echo '<option value="'. $row['Departamentos'] . '"">'. $row['Departamentos'] . '</option>';
						}
					?>
					</select>
				</form>
				<h3>Miembros del Departamento</h3>
				<div id="tabla_empleados">
					<script type="text/javascript">pintaTablaEmpleados('<?php echo $first; ?>')</script>
				</div>
			</div>
			<div id="box">
				<form id="comboDepartamentos" style="float: left; padding-right: 8px;">
					<select name="Departamento" onchange="updateCombo(this.value)">
					<option value="">Seleccione Departamento:</option>
					<?php
						//Datos de registro
						$dbserver="localhost";
						$dbuser="root";
						$dbpass="password";
						//Conexion MySQL
						$conexion = new PDO("mysql:host=$dbserver;dbname=$dbname",$dbuser,$dbpass);
						$conexion->query("SET NAMES 'utf8'"); 
						//Query 
						$sql = 'CALL controlDepartamentos()';
						$q = $conexion->query($sql);
						$q->setFetchMode(PDO::FETCH_ASSOC);
						if($row = $q->fetch()){
							$first = $row['Departamentos'];
							echo '<option value="'. $row['Departamentos'] . '"">'. $row['Departamentos'] . '</option>';
						}
						while($row = $q->fetch()){
							echo '<option value="'. $row['Departamentos'] . '"">'. $row['Departamentos'] . '</option>';
						}
					?>
					</select>
				</form>
				<div id="comboEmpleados"></div>
				<div id="clear"></div>
				<h3>Información de Empleado</h3>
				<script type="text/javascript">pintaInfoEmpleado('')</script>
				<div id="info_empleado"></div>
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