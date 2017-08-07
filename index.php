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
<!-- === PAGINA PRINCIPAL === -->
<html>
<head>
	<title>Easy Reports</title>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta name="Description" content="Web Application of Dynamics Reports" />
	<meta name="Keywords" content="web, aplication, informes, reports, business, empresa" />
	<meta http-equiv="Content-Language" content="es"/> 
	<meta http-equiv="Pragma" content="no-cache" />
	<!-- Stylesheet CSS -->
	<link href="css/style.css" rel="stylesheet" type="text/css" />
	<!-- Google API -->
	<link href='http://fonts.googleapis.com/css?family=Kite+One' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Convergence' rel='stylesheet' type='text/css'>
	<!-- Jump scrolling -->
	<script src="./js/jquery.min.js" type="text/javascript"></script>
	<script src="./js/jquery.localscroll.js" type="text/javascript"></script>
	<script src="./js/jquery.scrollTo.js" type="text/javascript"></script> 
	<script type="text/javascript">
		$(document).ready(function() {
			$('#slide1').localScroll({duration:800});
		});
	</script>
	<script type="text/javascript">
		$(document).ready(function() {
			$('#slide2').localScroll({duration:800});
		});
	</script>
	<script type="text/javascript">
		$(document).ready(function() {
			$('#slide3').localScroll({duration:800});
		});
	</script>
	<script type="text/javascript">
		$(document).ready(function() {
			$('#slide4').localScroll({duration:800});
		});
	</script>
	<!-- Mostrar Escritorio, Login, Registro, Sincronizacion, Error -->
	<script type="text/javascript">
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
				$("#resultado").delay(2000).queue(function(n) {
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
	<script type="text/javascript">
	$(document).ready(function(){
		$sig = "<?php echo $siguiente; ?>";
		var errcode = "<?php echo $error; ?>";
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
		
		<div id="texto_bienvenida">
			<h1><center><BIG>Bienvenido a easy Reports</BIG></BR>
			Inteligencia de Negocios online para crear informes de empresas en sólo 3 pasos</center></h1>
		</div>
		
		<div id="steps">
			<div class="demoIMG1">
				<img class="trasera" src="./Imagenes/1b.png" />
				<img class="delantera" src="./Imagenes/1a.png" />
			</div>
			<div class="demoIMG2">
				<img class="trasera" src="./Imagenes/2b.png" />
				<img class="delantera" src="./Imagenes/2a.png" />
			</div>
			<div class="demoIMG3">
				<img class="trasera" src="./Imagenes/3b.png" />
				<img class="delantera" src="./Imagenes/3a.png" />
			</div>
		</div>
			
		<div id="slide1" class="clearfix">
			<a href="#up1"><div id="down1"></div></a>
		</div>
	</div>
	
	<div id="body_medium">
		<div id="slide2" class="clearfix">
			<a href="#bar"><div id="up1"></div></a>
		</div>
		<div id="box">
			<div class="row">
				<img class="imagen" src="./Imagenes/informesDinamicos.png" align="right"/>
				<div class="texto" align="left">
					<h2>Informes dinámicos</h2>
					<h3>Crea estudios detallados compuestos de informes dinámicos y gráficos interactivos con filtros de selección en tiempo real para conseguir un trabajo brillante para el análisis y toma de decisiones de tu negocio</h3>
				</div>
			</div>
			<div class="row">
				<img class="imagen" src="./Imagenes/sinInstalaciones.png" align="left" />
				<div class="texto">
					<h2>Sin instalaciones</h2>
					<h3>Para trabajar con Easy Reports no necesitas instalar ningún programa en tu equipo, simplemente conectar tu base de datos, elegir el tipo de informe que más te interese y empezar a sacarle partido</h3>
				</div>
			</div>
			
			<div id="slide3" class="clearfix">
				<a href="#up2"><div id="down2"></div></a>
			</div>
		</div>
	</div>
	
	<div id="body_down">
		<div id="box">	
			<div id="slide4" class="clearfix">
				<a href="#up1"><div id="up2"></div></a>
			</div>
			<div class="row">
				<img class="imagen" src="./Imagenes/dosMinutos.png" align="right" />
				<div class="texto" align="left">
					<h2>En 2 minutos</h2>
					<h3>Es el tiempo que te llevará realizar el trabajo que tardarías al menos 2 días haciéndolo a mano, con un resultado brillante y ahorrándote horas de esfuerzo y quebraderos de cabeza</h3>
				</div>
			</div>
			<div class="row">
				<img class="imagen" src="./Imagenes/util.png" align="left" />
				<div class="texto">
					<h2>Útil herramienta</h2>
					<h3>La generación de informes de mercado puede suponer una potente herramienta en una empresa para optimizar el proceso de toma de decisiones basándose en el análisis de los resultados obtenidos</h3>
				</div>
			</div>
		</div>
		
		<div id="footer">
			<p>Proyecto Fin de Carrera </br>
			Desarrollado por: Pablo García Andrés</p>
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