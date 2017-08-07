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
<!-- === PAGINA INFORMES === -->
<html>
<head>
	<title>Easy Reports | Informes</title>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta http-equiv="Content-Language" content="es"/> 
	<meta http-equiv="Pragma" content="no-cache" />
	<!-- Vertical Tab JQuery -->
	<link href="css/redmond/jquery.ui.all.css" rel="stylesheet" type="text/css" />
	<script src="./js/jquery-1.10.2.js"></script>
	<script src="./js/jquery-ui-1.10.4.custom.js"></script>
	<!-- Stylesheet CSS -->
	<link href="css/style.css" rel="stylesheet" type="text/css" />
	<!-- Vertical Tab JQuery -->
	<script>
	$(function() {
		$( "#tabs" ).tabs().addClass( "ui-tabs-vertical ui-helper-clearfix" );
		$( "#tabs li" ).removeClass( "ui-corner-top" ).addClass( "ui-corner-left" );
	});
	</script>
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
			<large>¿Qué tipo de <BIG>informe</BIG> necesitas<BIG>?</BIG></large>
		</div>
		
		<div id="tabs">
			<ul>
				<li><a href="#tabs-1">Perfil de Empresa</a></li>
				<li><a href="#tabs-2">Riesgo Comercial</a></li>
				<li><a href="#tabs-3">Estructura interna</a></li>
				<li><a href="#tabs-4">Proyectos y Clientes</a></li>
				<li><a href="#tabs-5">Materiales</a></li>
			</ul>
	
			<div id="tabs-1">
				<h2>Perfil de Empresa</h2>
				<p>Informe genérico que despliega las características principales 
				de la franquicia, localización geográfica y contacto. Recoge la 
				estructura corporativa, la evolución en la plantilla y resultados 
				más relevantes en los últimos años.</p>
				<a href="./detalles.php"><img class="detalles" src="./Imagenes/Icon/white_detail2.png" /></a>
				<a href="./Informes/perfilEmpresa.php"><img class="informes" src="./Imagenes/Icon/white_report2.png" /></a>
			</div>
			<div id="tabs-2">
				<h2>Riesgo Comercial</h2>
				<p>Despliega las características principales de la franquicia. 
				Descripción del rating y liquidez, y valoración sobre los mismos. 
				Ventas, resultados positivos obtenidos de los últimos años y 
				situación financiera actual.</p>
				<a href="./detalles.php"><img class="detalles" src="./Imagenes/Icon/white_detail2.png" /></a>
				<a href="./Informes/riesgoComercial.php"><img class="informes" src="./Imagenes/Icon/white_report2.png" /></a>
			</div>
			<div id="tabs-3">
				<h2>Departamentos y Empleados</h2>
				<p>Desglose de departamentos de la empresa y detalles de distribución 
				de empleados y salarios. Descripción detallada de cada departamento y 
				los empleados que lo componen. Descripción detallada de cada empleado, 
				su status y contribuciones.</p>
				<a href="./detalles.php"><img class="detalles" src="./Imagenes/Icon/white_detail2.png" /></a>
				<a href="./Informes/estructuraInterna.php"><img class="informes" src="./Imagenes/Icon/white_report2.png" /></a>
			</div>
			<div id="tabs-4">
				<h2>Proyectos y Clientes</h2>
				<p>Información sobre la labor final de la empresa y fecha de iniciación. 
				Denominación y descripción detallada de los proyectos en curso y los 
				llevados a cabo hasta la fecha actual, así como el volumen de clientes 
				y productividad de cada uno de ellos.</p>
				<a href="./detalles.php"><img class="detalles" src="./Imagenes/Icon/white_detail2.png" /></a>
				<a href="./Informes/proyectosClientes.php"><img class="informes" src="./Imagenes/Icon/white_report2.png" /></a>
			</div>
			<div id="tabs-5">
				<h2>Material y Mantenimiento</h2>
				<p>Informe en el que se recogen los materiales de los que dispone la 
				empresa, el valor económico y el departamento al que pertenece. 
				Detalles técnicos sobre cada material, el trabajador que hace uso 
				de él y los mantenimiento que han sido necesarios.</p>
				<a href="./detalles.php"><img class="detalles" src="./Imagenes/Icon/white_detail2.png" /></a>
				<a href="./Informes/material.php"><img class="informes" src="./Imagenes/Icon/white_report2.png" /></a>
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