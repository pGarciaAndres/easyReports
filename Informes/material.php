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
<!-- === INFORME MATERIAL === -->
<html>
<head>
	<title>Easy Reports | Materiales</title>
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
	<!-- Tabla Material -->
	<script type="text/javascript">
		function pintaTablaMaterial(){
			var datosTablaMaterial = $.ajax({
				url: "./material/getDatosTablaMaterial.php",
				dataType: "json",
				async: false
			}).responseText;
			var tablaMaterial = new google.visualization.DataTable(datosTablaMaterial);
			var tabla1 = new google.visualization.Table(document.getElementById('tabla_material'));
			tabla1.draw(tablaMaterial, {
				showRowNumber: false,
				alternatingRowStyle: true,
				width: "85%"
			});
		}
	</script>
	<!-- Grafica Material -->
	<script type="text/javascript">
		function pintaGraficaMaterial(){
				var datosGraficaMaterial = $.ajax({
					url: "./material/getDatosGraficaMaterial.php",
					dataType: "json",
					async: false
				}).responseText;
				var graficaMaterial = new google.visualization.DataTable(datosGraficaMaterial);
				var chart1 = new google.visualization.PieChart(document.getElementById('chart_material'));
				var formatter = new google.visualization.NumberFormat({
					suffix: '€',
					fractionDigits: 0
				});
				formatter.format(graficaMaterial, 1);
				chart1.draw(graficaMaterial, {
					width: 400,
					height: 251,
					pieSliceText: 'value',
					is3D: true,
					chartArea: { left:"15%",top:"5%",width:"85%",height:"85%" }
				});
		}
	</script>
	<!-- Info Material Empleado -->
	<script type="text/javascript">
		function pintaInfoMaterialEmp(opt){
			$.ajax({
				url: "./material/getDatosInfoMaterialEmp.php",
				data: "q="+opt,
				success: function(data){
					$("#info_material_emp").html(data);
				}
			});
		}
	</script>
	<!-- Actualizar Combo -->
	<script type="text/javascript">
		function updateCombo(opt){
			$.ajax({
				url: "./material/updateCombo.php",
				data: "q="+opt,
				success: function(data){
					$("#comboMaterial").html(data);
				}
			});
		}
	</script>
	<!-- Tabla Tipo Material -->
	<script type="text/javascript">
		function pintaTablaTipoMaterial(opt){
			var datosTablaTipoMaterial = $.ajax({
				url: "./material/getDatosTablaTipoMaterial.php",
				data: "q="+opt,
				dataType: "json",
				async: false
			}).responseText;
			var tablaTipoMaterial = new google.visualization.DataTable(datosTablaTipoMaterial);
			var tabla2 = new google.visualization.Table(document.getElementById('tabla_tipoMaterial'));
			tabla2.draw(tablaTipoMaterial, {
				showRowNumber: false,
				alternatingRowStyle: true,
				width: "100%"
			});
		}
	</script>
	<!-- Info Material Referencia -->
	<script type="text/javascript">
		function pintaInfoMaterialRef(){
			var opt = document.getElementById("numserie").value;
			$.ajax({
				url: "./material/getDatosInfoMaterialRef.php",
				data: "q="+opt,
				success: function(data){
					$("#info_material_ref").html(data);
				}
			});
		}
	</script>
	<!-- Grafica Mantenimiento -->
	<script type="text/javascript">
		function pintaGraficaMantenimiento(){
				var opt = document.getElementById("numserie").value;
				var datosGraficaMantenimiento = $.ajax({
					url: "./material/getDatosGraficaMantenimiento.php",
					data: "q="+opt,
					dataType: "json",
					async: false
				}).responseText;
				var graficaMantenimiento = new google.visualization.DataTable(datosGraficaMantenimiento);
				var chart2 = new google.visualization.SteppedAreaChart(document.getElementById('chart_mantenimiento'));
				chart2.draw(graficaMantenimiento, {
					width: 400,
					height: 351,
					vAxis: {title: 'Coste Reparación'},
					isStacked: true
				});
		}
	</script>
</head>
<body>
	<div id="oscuridad" style="display:block;">
		<div id="cargando" style="text-align:center;">
			<img class="loading" src="../Imagenes/Informes/loading.gif" width="250"/>
			<h1>Material</h1>
		</div>
	</div>
	<div id="general">
		<div id="header">
			<!--<img class="logo_empresa" src="../Imagenes/Informes/callcenter.png"/>-->
			<h1 class="titulo">Material</h1>
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
				<h3 style="float: left; width: 50%">Departamentos</h3>
				<h3 style="float: right; width: 50%">Distribución del Material</h3>
				<div id="tabla_material">
					<script type="text/javascript">pintaTablaMaterial()</script>
				</div>
				<div id="chart_material">
					<script type="text/javascript">pintaGraficaMaterial()</script>
				</div>
			</div>
			<div id="clear"></div>
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
				<div id="comboMaterial"></div>
				<div id="clear"></div>
				<h3>Información de Empleado</h3>
				<script type="text/javascript">pintaInfoMaterialEmp('')</script>
				<div id="info_material_emp"></div>
			</div>
			<div id="box">
				<form>
					<select name="TipoMaterial" onchange="pintaTablaTipoMaterial(this.value)">
					<option value="">Seleccione Tipo:</option>
					<?php
						//Datos de registro
						$dbserver="localhost";
						$dbuser="root";
						$dbpass="password";
						//Conexion MySQL
						$conexion = new PDO("mysql:host=$dbserver;dbname=$dbname",$dbuser,$dbpass);
						$conexion->query("SET NAMES 'utf8'"); 
						//Query 
						$sql = 'CALL controlTipoMaterial()';
						$q = $conexion->query($sql);
						$q->setFetchMode(PDO::FETCH_ASSOC);
						if($row = $q->fetch()){
							$first = $row['Departamentos'];
							echo '<option value="'. $row['Tipo'] . '"">'. $row['Tipo'] . '</option>';
						}
						while($row = $q->fetch()){
							echo '<option value="'. $row['Tipo'] . '"">'. $row['Tipo'] . '</option>';
						}
					?>
					</select>
				</form>
				<h3>Inventario de Material por Tipo</h3>
				<div id="tabla_tipoMaterial">
					<script type="text/javascript">pintaTablaTipoMaterial('<?php echo $first; ?>')</script>
				</div>
			</div>
			<div id="box">
				<h3>Buscador de Material</h3></BR>
				<form name="input" action="javascript:pintaInfoMaterialRef();javascript:pintaGraficaMantenimiento();">
					Serial No. <input type="text" id="numserie">
							   <input type="submit" value="Buscar">
				</form> 
				<script type="text/javascript">pintaInfoMaterialRef()</script>
				<div style="float: left; width: 50%" id="info_material_ref"></div>
				<div style="float: right; width: 50%" id="chart_mantenimiento"></div>
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