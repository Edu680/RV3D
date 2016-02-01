<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//ES" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>RV3D - Representaci�n de vectores en 3 dimensiones</title>
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
				//Abrimos ventana para la recuperaci�n de contrase�a olvidada.
				$("#formularioRecuperaContrasena").dialog();
				//Aplicamos al campo de texto efectos para cuando tenga el foco o el rat�n pase por encima de �l.
				var x = $("input:text");
				x.focus(enfocar);
				x.hover(entraRaton, saleRaton);
				//Asociamos una funci�n al bot�n "Enviar".
				var b = $("#boton");
				b.button();
				b.click(enviarRegistro);
				//Asociamos una funci�n al bot�n "Cerrar" de la ventana.
				var c = $("button");
				c.button();
				c.click(volverAtras);
			
			}
			/*Funci�n que elimina el valor predeterminado del campo de texto cuando se hace foco en �l. 
			Tambi�n oculta los mensajes de error que pudieran haber aparecido como resultado de una validaci�n de datos negativa.*/
			function enfocar(){
				$(this).attr("value", "");	
				$("#correoIncorrecto").text("");
				$("#resultados").text("");
				$("#error").text("");
			}
			//Funci�n que dibuja el borde del campo de texto de color verde cuando el rat�n pasa por encima de dicho campo.
			function entraRaton() {
				$(this).css("border-color", "#6eac2c");	
			}
			//Funci�n que devuelve el color del borde del campo de texto a su estilo original al dejar el rat�n de estar encima de �l.
			function saleRaton() {
				$(this).css("border-color", "");	
			}
			/*Funci�n que env�a los datos para su validaci�n mediante m�todo post. Antes de enviarlos comprueba que 
			el correo electr�nico sea de la Universidad de C�rdoba.*/
			function enviarRegistro() {
					
				if($("#email").val().indexOf('@uco.es', 0) == -1) {
					var y = $("#correoIncorrecto").text("La direcci�n de correo parece incorrecta");
					y.css("color", "red");  
					return false;
				}

				$.post("../mysql/recupera.php",$("#formdata").serialize(),recibir);
				
			}
			//Funci�n que lleva al usuario a la pantalla de inicio de sesi�n cuando se pulsa el bot�n "Cerrar" de la ventana.
			function volverAtras() {
				window.location = "../index.php";
			}
			/*Funci�n que recibe la salida originada por la validaci�n de datos. Si la validaci�n ha sido correcta, se mostrar� un mensaje
			indicando que los datos de inicio de sesi�n han sido enviados al correo del usuario e inmediatamente despu�s, se devuelve al usuario
			a la pantalla de inicio de sesi�n.
			Si la validaci�n no ha sido correcta, se muestra el mensaje de error indicando el motivo.*/
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
		<!-- Formulario para recuperar contrase�a. Consta de una etiqueta "Email", campo de texto y bot�n "Enviar" -->
		<div id="formularioRecuperaContrasena" title="Recuperando contrase�a">
		<!-- Mensajes que se mostrar�n seg�n la salida que env�e la validaci�n de datos -->
		<div id="resultados" style="display:none">No existe usuario registrado para ese email</div>
		<div id="error" style="display:none">Problemas con el servidor</div>
		<div id="exito" style="display:none">Se han enviado los datos de inicio de sesi�n a su correo</div>
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