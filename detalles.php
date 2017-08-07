<?php
session_start();
if($_SESSION['usuario']){
	$userlabel = $_SESSION['usuario'];
	$estado = "online";
	$siguiente = $_SESSION['siguiente'];
}else{
	$userlabel = "Login";
	$estado = "offline";
	$siguiente = "";
	$error = $_SESSION['error'];
}
?>
<!-- PROYECTO FIN DE CARRERA - PABLO GARCIA ANDRES -->
<!-- === PAGINA DETALLES === -->
<html>
<head>
	<title>Easy Reports | Detalles</title>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta http-equiv="Content-Language" content="es"/> 
	<meta http-equiv="Pragma" content="no-cache" />
	<!-- JQuery -->
	<script src="./js/jquery-1.10.2.js"></script>
	<script src="./js/jquery-ui-1.10.4.custom.js"></script>
	<script src="./js/jquery.min.js"></script>
    <script src="./js/jquery.slidorion.min.js"></script>
	<!-- Stylesheet CSS -->
	<link href="css/style.css" rel="stylesheet" type="text/css" />
	<!-- Slidorion Plugin -->
	<link rel="stylesheet" href="css/slidorion/slidorion.css" />
	<link href='http://fonts.googleapis.com/css?family=Yanone+Kaffeesatz:400,200,700' rel='stylesheet' type='text/css'>
	<!-- Google API -->
	<!--<link href='http://fonts.googleapis.com/css?family=Kite+One' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Convergence' rel='stylesheet' type='text/css'>-->
	<!-- Mostrar Escritorio, Login, Registro, Sincronizacion, Error -->
	<script>
	function escritorio() {
		visible=document.getElementById("loginId");
		visible.style.display="none";
		visible=document.getElementById("closeId");
		visible.style.display="none";
		visible=document.getElementById("registroId");
		visible.style.display="none";
		visible=document.getElementById("sincBdId");
		visible.style.display="none";
		visible=document.getElementById("errorId");
		visible.style.display="none";
		fondo=document.getElementById("oscuridad");
		fondo.style.display="none";
		<?php unset($_SESSION['siguiente']);?>
		<?php unset($_SESSION['error']);?>
    }
    function abrirLogin() {
		$estado = "<?php echo $estado; ?>";
		if ($estado == "offline"){
			visible=document.getElementById("loginId");
		} 
		if ($estado == "online"){
			visible=document.getElementById("closeId");
		}
		visible.style.display="block";
		fondo=document.getElementById("oscuridad");
		fondo.style.display="block";
	}
	function abrirRegistro() {
		escritorio();
		visible=document.getElementById("registroId");
		visible.style.display="block";
		fondo=document.getElementById("oscuridad");
		fondo.style.display="block";
		
		$(document).ready(function(){
			var consulta;
			$("#newuser").focus();
			$("#newuser").keyup(function(e){
			consulta = $("#newuser").val();
				$("#resultado").delay(5000).queue(function(n) {
					$.ajax({
						type: "POST",
						url: "comprobar.php",
						data: "b="+consulta,
						dataType: "html",
						error: function(){
							alert("Error del servicio. Pruebe mas tarde");
						},
						success: function(data){
							$("#resultado").html(data);
							n();
						}
					});                   
				 });                
			});                
		});
	}
	function abrirSincBD(){
		visible=document.getElementById("sincBdId");
		visible.style.display="block";
		fondo=document.getElementById("oscuridad");
		fondo.style.display="block";
	}
	function abrirError(){
		visible=document.getElementById("errorId");
		visible.style.display="block";
		fondo=document.getElementById("oscuridad");
		fondo.style.display="block";
	}
	</script>
	<script>
	$(document).ready(function(){
		$sig = "<?php echo $siguiente; ?>";
		var errcode = "<?php echo $error; ?>";
		console.log(errcode);
		var msj = "";
		if($sig == "Sincronizar"){
			abrirSincBD();
		}
		switch(errcode) {
			case "01":
				msj= "El usuario no se encuentra dado de alta en el sistema, o el login y/o contraseña son erróneos.";
				abrirError();
				break;
			case "02":
				msj = "Se ha producido un error en la base de datos del sistema\n. Inténtelo nuévamente.";
				abrirError();
				break;
			case "03":
				msj = "Su base de datos parece no estar disponible, o los datos de conexión son erróneos, la operación no se completó.";
				abrirError();
				break;
			case "04":
				abrirLogin();
				break;
		}
		document.getElementById('mensajeId').innerHTML = msj;
	});
	</script>
	<script>
	$(function() {
		$('#slidorion').slidorion({
			interval: 5000000,
			effect: 'slideRandom'
		});
	});
	</script>
	<style>
	#slider {
		background:url(../img/cool-bg.jpg) center center no-repeat !important;
	}
	.slide {
		background:transparent !important;
		color:#fff;
	}
	.slide .content {
		padding:15px 20px;
		font-family: 'Yanone Kaffeesatz', sans-serif; font-weight:bold;
	}
	.slide .content h1 {
		text-align:left;
		font-size:60px;
	}
	.slide .sub {
		text-align:right;
	}
	</style>
</head>

<body>
	<div id="body_up">
		<div id="header">
			<div id="logo"></div>
		</div>

		<div id="bar">
			<span class="<?php echo $estado; ?>"><a onclick="abrirLogin()"> <?php echo $userlabel; ?></a></span>
			<span class="config"><a href="./configuracion.php"> Configuración</a></span>
			<span class="detail"><a href="./detalles.php"> Detalles</a></span>
			<span class="report"><a href="./informes.php"> Informes</a></span>
			<span class="home"><a href="./index.php">Inicio</a></span>
		</div>
		
		<div id="texto_informe">
			<large>Detalles</large>
		</div>
		<div id="tabla_detalles">
			<div id="slidorion" class="slidorion">
				<div class="slider">
					<div class="slide"><img src="./Imagenes/detalles1.jpg" /></div>
					<div class="slide"><img src="./Imagenes/detalles2.jpg" /></div>
					<div class="slide"><img src="./Imagenes/detalles3.jpg" /></div>
					<div class="slide"><img src="./Imagenes/detalles4.jpg" /></div>
					<div class="slide"><img src="./Imagenes/detalles5.jpg" /></div>
				</div>

				<div class="accordion">
					<div class="header">Perfil de empresa</div>
					<div class="content">
						<p>Perfil de empresa es un informe general, que ofrece información básica sobre la empresa. Recoge la estructura corporativa, con los distintos departamentos e información sobre estos. Ofrece información sobre la plantilla desde los últimos años hasta el presente. Muestra el volumen de ventas acontecidas en los últimos años y el resultado obtenido como ganancias netas.</p>
						<p>Este informe es perfecto para obtener una visión general sobre la empresa y como se desarrolla de forma genérica en la actualidad y en los últimos años registrados.</p>
					</div>
					<div class="header">Riesgo comercial</div>
					<div class="content">
						<p>Riesgo comercial define un informe bastante técnico, donde encontrarás la evolución del rating en la empresa y la valoración técnica teniendo en cuenta estos resultados. Ofrece una visión del volumen de ventas logradas en la actualidad y años anteriores y las ganancias que han generado, y como resultado la situación financiera en la que se encuentra la franquicia.</p>
						<p>Este informe se centra fundamentalmente en el sector económico y estima una valoración aproximada sobre la rentabilidad del negocio en la actualidad y conocer si existe riesgo económico y poder actuar en consecuencia.</p> 
					</div>
					<div class="header">Estructura interna</div>
					<div class="content">
						<p>Estructura interna se centra en el estudio de la estructura interna de la empresa y ofrece información detallada sobre los empleados y su situación laboral. Se analizan los distintos departamentos en los que se divide la franquicia y los empleados que se encuentran en cada uno de ellos. Existe la posibilidad de seleccionar cualquier empleado de un departamento, para obtener su información personal y su estado dentro de la empresa.</p>
						<p>Es un informe muy útil para conocer la distribución interna de la empresa y conseguir una gestión de la plantilla adecuada y facilitar notablemente las tareas de recursos humanos.</p>
					</div>
					<div class="header">Proyectos y clientes</div>
					<div class="content">
						<p>Proyectos y clientes hace un repaso sobre los proyectos que se han creado en la empresa, tantos los activos en la actualidad como los inactivos, puedes ver en gráficas los clientes que ha conseguido cada proyecto y las ganancias que se han obtenido hasta el momento. Podrás analizar con más detalle un proyecto en concreto, ver sus estadísticas y conocer los artículos de este proyecto con más aceptación por el público.</p>
						<p>Un informe muy interesante para conocer con exactitud el grado de satisfacción de los clientes con los proyectos llevados a cabo en la empresa y tomar los más acertados como referencia para crear proyectos futuros.</p>
					</div>
					<div class="header">Materiales</div>
					<div class="content">
						<p>Materiales se centra en la gestión interna de la empresa, pero en este caso de los bienes materiales y cómo estan distribuidos en cada departamento. Podrás acceder a los materiales que existen en un departamento en concreto y conocer información sobre estos, como la marca, coste, su estado o el empleado a su cargo. Se puede sacar un listado de todos los materiales de un tipo en concreto o buscar un material en concreto por su número de serie.</p>
						<p>Este informe es interesante para conocer el estado del material del que dispone la empresa y evaluar la rentabilidad de por ejemplo una marca, viendo su historial de mantenimientos requeridos.</p>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<div id="oscuridad" style="display: none;" onclick="escritorio()"></div>
	
	<div id="errorId" style="display: none;">
		<div id="cabecera">
			<button class="close" type="button" onclick="escritorio()">x</button>
			<span id="txt_cabecera">Ocurrió un error!</span>
		</div>
		<div id="left_body" style="width:93%">
			<label class="mensaje"><div id="mensajeId"></div></label>
		</div>
	</div>
	
	<div id="loginId" style="display: none;">
		<div id="cabecera">
			<button class="close" type="button" onclick="escritorio()">x</button>
			<span id="txt_cabecera">Login</span>
		</div>
		<div id="left_body">
			<form id="form_login" method="POST" action="login.php" name="form_login">
				<label for="username">Usuario</label>
				<input id="username" type="text" placeholder="Nombre de usuario" name="username" required>
				<label for="password">Contraseña</label>
				<input id="password" type="password" placeholder="Contraseña" name="password" required>
				<button id="btn_body" style="margin-top:90px;" type="submit"> Login </button>
			</form>
		</div>
		<div id="right_body">
			<img class="img_reg" src="./Imagenes/registro.png" />
			<button id="btn_body" type="button" onClick="abrirRegistro()">Regístrate</button>
		</div>
	</div>
	
	<div id="closeId" style="display: none;">
		<div id="cabecera">
			<span id="txt_cabecera">Cerrar Sesion</span>
		</div>
		<div id="left_body" style="width:93%">
			<label class="mensaje">¿Desea cerrar la sesión?</label>
			<input id="btn_min" type="button" onClick="location.href='logout.php'" value='Aceptar'>
			<input id="btn_min" type="button" onClick="escritorio()" value='Cancelar'>
		</div>
	</div>
	
	<div id="registroId" style="display: none;">
		<div id="cabecera">
			<button class="close" type="button" onclick="escritorio()">x</button>
			<span id="txt_cabecera">Registro</span>
		</div>
		<a class="ayuda">*Campos Obligatorios</a>
		<div id="left_body" style="width:90%">
			<form id="form_registro" method="POST" action="registro.php" name="form_registro">
				<label for="newuser">Usuario*</label>
				<input class="reg" id="newuser" type="text" placeholder="Nombre de usuario" name="newuser" required>
				<div id="resultado"></div>
				<label for="newpass">Contraseña*</label>
				<input class="reg" id="newpass" type="password" placeholder="Contraseña" name="newpass" required>
				
				<label for="hostdb">Datos de Conexión*</label>
				<input class="reg" id="Hostdb" type="text" placeholder="Nombre o IP del servidor host" name="Hostdb" required>
				<input class="reg" id="Userdb" type="text" placeholder="Nombre usuario de la base de datos" name="Userdb" required>
				<input class="reg" id="Passdb" type="text" placeholder="Contraseña de la base de datos" name="Passdb" required>
				<input class="reg" id="Namedb" type="text" placeholder="Nombre de la base de datos" name="Namedb" required>
				
				<label for="Empresa">Datos de la Empresa</label>
				<input class="reg" id="Empresa" type="text" placeholder="Nombre de la empresa" name="Empresa">
				<input class="reg" id="ObjSocial" type="text" placeholder="Actividades que realiza" name="ObjSocial">
				<input class="reg" id="NIF" type="text" placeholder="Número Identificación Fiscal" name="NIF">
				<input class="reg" id="Fecha_Alta" type="text" placeholder="Fecha de alta, ejemplo: 2000-01-01" name="Fecha_Alta">
				<input class="reg" id="Domicilio" type="text" placeholder="Domicilio, ejemeplo: C/Nueva, 15 29010 Málaga" name="Domicilio">
				<input class="reg" id="Telefono" type="tel" placeholder="Teléfono de contacto" name="Telefono">
				<input class="reg" id="Email" type="text" placeholder="Email, ejemplo: contacto@tuempresa.com" name="Email">
				<input class="reg" id="URL" type="text" placeholder="URL, ejemplo: www.tuempresa.com" name="URL">
				<button id="btn_body" style="margin-top:20px;" type="submit"> Registrar </button>
			</form>
		</div>
	</div>
	
	<div id="sincBdId" style="display: none;">
		<div id="cabecera">
			<button class="close" type="button" onclick="escritorio()">x</button>
			<span id="txt_cabecera">¿Sincronizar ahora?</span>
		</div>
		<div id="left_body">
			<form id="form_sinc" method="POST" action="sincroniza.php" name="form_sinc">
				<label for="tbl_dep">Departamentos</label>
				<select name="tbl_dep">
					<option value="">Su tabla de Departamentos...</option>
					<?php include("showTables.php"); if($siguiente == "Sincronizar"){ show_tables(); } ?>
				</select>
				
				<label for="tbl_emp">Empleados</label>
				<select name="tbl_emp">
					<option value="">Su tabla de Empleados...</option>
					<?php if($siguiente == "Sincronizar"){ show_tables(); } ?>
				</select>
				
				<label for="tbl_mat">Material</label>
				<select name="tbl_mat">
					<option value="">Su tabla de Materiales...</option>
					<?php if($siguiente == "Sincronizar"){ show_tables(); } ?>
				</select>
				
				<label for="tbl_man">Mantenimiento</label>
				<select name="tbl_man">
					<option value="">Su tabla de Mantenimientos...</option>
					<?php if($siguiente == "Sincronizar"){ show_tables(); } ?>
				</select>
				
				<label for="tbl_pro">Proyectos</label>
				<select name="tbl_pro">
					<option value="">Su tabla de Proyectos...</option>
					<?php if($siguiente == "Sincronizar"){ show_tables(); } ?>
				</select>
				
				<label for="tbl_cli">Clientes</label>
				<select name="tbl_cli">
					<option value="">Su tabla de Clientes...</option>
					<?php if($siguiente == "Sincronizar"){ show_tables(); } ?>
				</select>
				
				<button id="btn_body" style="margin-top:20px;" type="submit"> Sincronizar </button>
			</form>
		</div>
		<div id="right_body">
			<a class="ayuda" href="./configuracion.php">¿Qué es esto?</a>
			<img class="img_reg" src="./Imagenes/sincroniza.png" />
			<button id="btn_body" style="margin-top:39px;" type="button" onClick="escritorio()">Después</button>
		</div>
	</div>
</body>
</html>