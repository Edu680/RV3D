<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//ES" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>RV3D - Representación de vectores en 3 dimensiones</title>
		<link href="../css/global.css" rel="stylesheet" type="text/css" />
    	<link href="../css/start/jquery-ui-1.10.1.custom.min.css" rel="stylesheet" type="text/css" />
		<script type="text/javascript" src="../js/jquery.js"></script>
    	<script type="text/javascript" src="../js/jquery-ui.js"></script>
    	<script type="text/javascript">
			$(document).ready(inicio);
			function inicio(){
				//Abrimos ventana para el formulario de registro de un nuevo usuario.
				$("#formularioregistro").dialog({width:400});
				//Aplicamos efectos para cuando el campo de texto tenga el foco y cuando el ratón pase por encima de él.
				var x = $("input:text");
				x.focus(enfocar);
				x.hover(entraRaton, saleRaton);
				//Asociamos una función al botón "Registrar".
				var b = $("#boton");
				b.button();
				b.click(enviarRegistro);
				//Asociamos una función al botón "Cerrar" de la ventana.
				var c = $("button");
				c.button();
				c.click(volverAtras);
			
				/*Lanzamos una función que valida si el nombre de usuario introducido está disponible para su uso. Cada vez que el usuario
				pulsa una tecla, se envía el valor del campo de texto mediante el método ajax() de JQuery a validarUsuario.php. Si la 
				validación es satisfactoria (el nombre de usuario está disponible), se dibujará un borde verde de 5 píxeles en el campo 
				de texto. Si ya estuviera en uso, se dibuja un borde rojo de 5 píxeles.*/
				$("#usuario").keyup(function() {
					var username = $(this).val();
					$("#usuario").removeClass("disponible").removeClass("noDisponible")
					if(username.length > 0) {
						$.ajax({
							url: '../mysql/validarUsuario.php',
							type: 'POST',
							data: 'usuario='+username,
							success: function(data) {
								if(data==0) {
									$("#usuario").addClass("disponible");
								}
								else if(data==1) {
									$("#usuario").addClass("noDisponible");
								}
							}
						});
					}
				});

				//Igual que la anterior pero para comprobar la disponibilidad de un correo electrónico.
				$("#email").keyup(function() {
					var correo = $(this).val();
					$("#email").removeClass("disponible").removeClass("noDisponible")
					if(correo.length > 0) {
						$.ajax({
							url: '../mysql/validarEmail.php',
							type: 'POST',
							data: 'email='+correo,
							success: function(data) {
								if(data==0) {
									$("#email").addClass("disponible");
								}
								else if(data==1) {
									$("#email").addClass("noDisponible");
								}
							}
						});
					}
				});
				
			}
			/*Función que elimina el valor predeterminado del campo de texto cuando se hace foco en él. 
			También oculta los mensajes de error que pudieran haber aparecido como resultado de una validación de datos negativa.*/
			function enfocar(){
				$(this).attr("value", "");	
				$("#camposVacios").text("");
				$("#correoIncorrecto").text("");
			}
			//Función que dibuja el borde del campo de texto de color verde cuando el ratón pasa por encima de dicho campo.
			function entraRaton() {
				$(this).css("border-color", "#6eac2c");	
			}
			//Función que devuelve el color del borde del campo de texto a su estilo original al dejar el ratón de estar encima de él.
			function saleRaton() {
				$(this).css("border-color", "");	
			}
			/*Función que envía los datos para su validación mediante método post. Antes de enviarlos comprueba que no 
			exista ningún campo obligatorio vacío y que el correo electrónico sea de la Universidad de Córdoba.
			Si el campo usuario o el campo email indican que usuario y/o email ya están en uso, no envía los datos para validar.*/
			function enviarRegistro() {
				
				if($("#nombre").val().length == " " || $("#apellido_1").val().length == " " || $("#usuario").val().length == " " || $("#contrasena").val().length == " "){
					var x = $("#camposVacios").text("No puede haber ningún campo obligatorio vacío");
					x.css("color", "red");
					return false;
				}
				/*if($("#email").val().indexOf('@uco.es', 0) == -1 ) {
					var y = $("#correoIncorrecto").text("La dirección de correo parece incorrecta");
					y.css("color", "red");  
					return false;
				}*/
			    if($("#usuario").hasClass("noDisponible")) {
					return false;
				}

				$.post("../mysql/registro.php",$("#formdata").serialize(),recibir);
				
			}
			//Función que lleva al usuario a la pantalla de inicio de sesión cuando se pulsa el botón "Cerrar" de la ventana.
			function volverAtras() {
				window.location = "../index.php";
			}
			/*Función que recibe la salida originada por la validación de datos. Si la validación ha sido correcta (usuario se ha registrado),
			se muestra una ventana de notificación. Al pulsar sobre "Aceptar", se cierra la ventana y se devuelve al usuario a la pantalla de
			inicio de sesión.*/
			function recibir(datos) {
				if(datos) {
					
					$("#registroRealizado").dialog({
					title: 'Usuario registrado ',
					 modal: true,
					  buttons: {
						"Aceptar": function() {
							$( this ).dialog( "close" );
							window.location = "../index.php";
						},
						
					  }

					});
				}
			}

			
		</script>
	</head>
	<body>
		<!-- Formulario de registro de nuevo usuario. -->
		<div id="formularioregistro" title="Formulario de registro">
		<form method="post" id="formdata">
			<ul>
			<li><h2>Nombre (*)</h2><input type="text" name="nombre" id="nombre" value="" size=40></li>
			<li><h2>Primer Apellido (*)</h2><input type="text" name="apellido_1" id="apellido_1" value="" size=40></li>
			<li><h2>Segundo Apellido</h2><input type="text" name="apellido_2" id="apellido_2" value="" size=40></li>
			<li><h2>Email (*)</h2><input type="text" name="email" id="email" value="" size=40 /></li>
			<li><h2>Usuario (*)</h2><input type="text" name="usuario" id="usuario" value="" size=40></li>
			<li><h2>Contraseña (*)</h2><input type="text" name="contrasena" id="contrasena" value="" size=40></li>
			<li class="botones"><input type="button" id="boton" value="Registrar" /></li>
			</ul>
		</form>
		<!-- Divs donde se muestran los mensajes arrojados por la validación de datos -->
		<div id="camposVacios" style="padding-top:10px"></div>
		<div id="correoIncorrecto" style="padding-bottom:10px"></div>
		<div id="registroRealizado" style="display:none"><p><span class="ui-icon ui-icon-disk" style="float: left; margin: 0 7px 20px 0;"></span>El usuario ha sido registrado satisfactoriamente</p></div>
		</div>
	</body>
</html>