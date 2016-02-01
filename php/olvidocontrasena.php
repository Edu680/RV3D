<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//ES" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>RV3D - Representación de vectores en 3 dimensiones</title>
		<link href="../css/global.css" rel="stylesheet" type="text/css" />
    	<link href="../css/start/jquery-ui-1.10.1.custom.min.css" rel="stylesheet" type="text/css" />
		<script type="text/javascript" src="../js/jquery.js"></script>
    	<script type="text/javascript" src="../js/jquery-ui.js"></script>
		<style>
		div#resultados, div#error {
			color: #f00
		}
		div#exito {
			color: green;
		}
		</style>
    	<script type="text/javascript">
			$(document).ready(inicio);
			function inicio(){
				//Abrimos ventana para la recuperación de contraseña olvidada.
				$("#formularioRecuperaContrasena").dialog();
				//Aplicamos al campo de texto efectos para cuando tenga el foco o el ratón pase por encima de él.
				var x = $("input:text");
				x.focus(enfocar);
				x.hover(entraRaton, saleRaton);
				//Asociamos una función al botón "Enviar".
				var b = $("#boton");
				b.button();
				b.click(enviarRegistro);
				//Asociamos una función al botón "Cerrar" de la ventana.
				var c = $("button");
				c.button();
				c.click(volverAtras);
			
			}
			/*Función que elimina el valor predeterminado del campo de texto cuando se hace foco en él. 
			También oculta los mensajes de error que pudieran haber aparecido como resultado de una validación de datos negativa.*/
			function enfocar(){
				$(this).attr("value", "");	
				$("#correoIncorrecto").text("");
				$("#resultados").text("");
				$("#error").text("");
			}
			//Función que dibuja el borde del campo de texto de color verde cuando el ratón pasa por encima de dicho campo.
			function entraRaton() {
				$(this).css("border-color", "#6eac2c");	
			}
			//Función que devuelve el color del borde del campo de texto a su estilo original al dejar el ratón de estar encima de él.
			function saleRaton() {
				$(this).css("border-color", "");	
			}
			/*Función que envía los datos para su validación mediante método post. Antes de enviarlos comprueba que 
			el correo electrónico sea de la Universidad de Córdoba.*/
			function enviarRegistro() {
					
				if($("#email").val().indexOf('@uco.es', 0) == -1) {
					var y = $("#correoIncorrecto").text("La dirección de correo parece incorrecta");
					y.css("color", "red");  
					return false;
				}

				$.post("../mysql/recupera.php",$("#formdata").serialize(),recibir);
				
			}
			//Función que lleva al usuario a la pantalla de inicio de sesión cuando se pulsa el botón "Cerrar" de la ventana.
			function volverAtras() {
				window.location = "../index.php";
			}
			/*Función que recibe la salida originada por la validación de datos. Si la validación ha sido correcta, se mostrará un mensaje
			indicando que los datos de inicio de sesión han sido enviados al correo del usuario e inmediatamente después, se devuelve al usuario
			a la pantalla de inicio de sesión.
			Si la validación no ha sido correcta, se muestra el mensaje de error indicando el motivo.*/
			function recibir(datos){
				if(datos == 1){
					$("#exito").fadeIn(2000, function(){
						window.location = "../index.php";	
					});
				}
				else if(datos == 0){
					$('#resultados').fadeIn('slow');
				}
				else{
					$('#error').fadeIn('slow');
				}
			}

			
		</script>
	</head>
	<body>
		<!-- Formulario para recuperar contraseña. Consta de una etiqueta "Email", campo de texto y botón "Enviar" -->
		<div id="formularioRecuperaContrasena" title="Recuperando contraseña">
		<!-- Mensajes que se mostrarán según la salida que envíe la validación de datos -->
		<div id="resultados" style="display:none">No existe usuario registrado para ese email</div>
		<div id="error" style="display:none">Problemas con el servidor</div>
		<div id="exito" style="display:none">Se han enviado los datos de inicio de sesión a su correo</div>
		<form method="post" id="formdata">
			<ul>
			<li><h2>Email</h2><input type="text" name="email" id="email" value="" size=40 /></li>
			<li class="botones"><input type="button" id="boton" value="Enviar" /></li>
			</ul>
		</form>
		<div id="camposVacios" style="padding-top:10px"></div>
		<div id="correoIncorrecto" style="padding-bottom:10px"></div>
		</div>
	</body>
</html>