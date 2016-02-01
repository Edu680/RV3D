<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//ES" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>RV3D - Representación de vectores en 3 dimensiones</title>
		<link href="css/global.css" rel="stylesheet" type="text/css" />
    	<link href="css/start/jquery-ui-1.10.1.custom.min.css" rel="stylesheet" type="text/css" />
		<script type="text/javascript" src="js/jquery.js"></script>
    	<script type="text/javascript" src="js/jquery-ui.js"></script>
		<style>
			.no-close .ui-dialog-titlebar-close {
				display: none;
			}
			div#resultados, div#error {
				color: #f00
			}
		</style>
    	<script type="text/javascript">
			$(document).ready(inicio);
			function inicio(){
				//Abrimos la ventana de inicio de sesión con el botón cerrar deshabilitado.
				$("#login").dialog({dialogClass: "no-close"});		
				var x = $("input:text");							
				var y = $("input:password");
				/*Aplicamos a los campos usuario y contraseña efectos para cuando tengan el foco o
				el ratón pase por encima de ellos.*/
				x.focus(enfocar);
				y.focus(enfocar);
				x.hover(entraRaton, saleRaton);
				y.hover(entraRaton, saleRaton);
				/*Llamamos a la función "verificarLogin" que será la encargada de validar los datos de
				inicio de sesión al hacer click sobre el botón "Enviar".*/
				$("#boton").button().click(verificarLogin);		
			}
			/*Función que elimina el valor predeterminado del campo de texto cuando se hace foco en él. 
			También oculta los mensajes de error que pudieran haber aparecido como resultado de un intento fallido de inicio de sesión anterior.*/
			function enfocar(){
				$(this).attr("value", "");
				$("#resultados").hide();
				$("#error").hide();
			}
			//Función que dibuja el borde del campo de texto de color verde cuando el ratón pasa por encima de dicho campo.
			function entraRaton() {
				$(this).css("border-color", "#6eac2c");	
			}
			//Función que devuelve el color del borde del campo de texto a su estilo original al dejar el ratón de estar encima de él.
			function saleRaton() {
				$(this).css("border-color", "");	
			}
			/*Función que aplica un efecto de movimiento horizontal al formulario de inicio de sesión cuando los datos introducidos son incorrectos.*/
			function errorLogin(){
				$("#login").effect("shake");		
			}
			/*Función que envía los datos introducidos por el usuario (usuario y contraseña) mediante método post para su validación*/
			function verificarLogin(){
				$.post("mysql/login.php",$("#formdata").serialize(),recibir);
			}
			/*Función que recoge la salida enviada por el script que realiza la validación de datos.
			Si los datos son correctos, el usuario entra en la aplicación. Si son incorrectos, aparece un mensaje de error indicándolo.
			Si no se ha podido realizar la conexión con la base de datos para validarlos, se muestra otro mensaje de error notificándolo.*/
			function recibir(datos){
				if(datos == 1){
					window.location = "php/vectoresPrincipal.php";
				}
				else if(datos == 0){
					$('#resultados').fadeIn('slow');
					errorLogin();
				}
				else{
					$('#error').fadeIn('slow');
				}
			}
			

		</script>
	</head>
	<body>
		<div id="login" title="Login">
		<!-- Mensajes de error que se mostrarán según la salida enviada por login.php, que es el encargado
		de la validación de los datos de inicio de sesión.-->
			<div id="resultados" style="display:none">El usuario y/o contraseña son incorrectos</div>
			<div id="error" style="display:none">Problemas con el servidor</div>
			<!-- Formulario para introducir los datos de inicio de sesión. -->
			<form method="post" id="formdata">
			<br>
  			<input type="text" name="usuario" id="usuario" value="Usuario" /><br />
            <input type="password" name="contrasena" id="contrasena" value="Contraseña" /><br />
			<br>
            <input type="button" id="boton" value="Enviar" />
			</form>
			<br>
			<!-- Enlaces a los módulos "Olvido de contraseña" y "Registrar un nuevo usuario" -->
			<a href="php/olvidocontrasena.php">¿Olvidaste tu contraseña?</a><br>
			<a href="php/formularioregistro.php">¿Aún no estás registrado?</a>
		</div>
	</body>
</html>

