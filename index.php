<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//ES" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>RV3D - Representaci�n de vectores en 3 dimensiones</title>
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
				//Abrimos la ventana de inicio de sesi�n con el bot�n cerrar deshabilitado.
				$("#login").dialog({dialogClass: "no-close"});		
				var x = $("input:text");							
				var y = $("input:password");
				/*Aplicamos a los campos usuario y contrase�a efectos para cuando tengan el foco o
				el rat�n pase por encima de ellos.*/
				x.focus(enfocar);
				y.focus(enfocar);
				x.hover(entraRaton, saleRaton);
				y.hover(entraRaton, saleRaton);
				/*Llamamos a la funci�n "verificarLogin" que ser� la encargada de validar los datos de
				inicio de sesi�n al hacer click sobre el bot�n "Enviar".*/
				$("#boton").button().click(verificarLogin);		
			}
			/*Funci�n que elimina el valor predeterminado del campo de texto cuando se hace foco en �l. 
			Tambi�n oculta los mensajes de error que pudieran haber aparecido como resultado de un intento fallido de inicio de sesi�n anterior.*/
			function enfocar(){
				$(this).attr("value", "");
				$("#resultados").hide();
				$("#error").hide();
			}
			//Funci�n que dibuja el borde del campo de texto de color verde cuando el rat�n pasa por encima de dicho campo.
			function entraRaton() {
				$(this).css("border-color", "#6eac2c");	
			}
			//Funci�n que devuelve el color del borde del campo de texto a su estilo original al dejar el rat�n de estar encima de �l.
			function saleRaton() {
				$(this).css("border-color", "");	
			}
			/*Funci�n que aplica un efecto de movimiento horizontal al formulario de inicio de sesi�n cuando los datos introducidos son incorrectos.*/
			function errorLogin(){
				$("#login").effect("shake");		
			}
			/*Funci�n que env�a los datos introducidos por el usuario (usuario y contrase�a) mediante m�todo post para su validaci�n*/
			function verificarLogin(){
				$.post("mysql/login.php",$("#formdata").serialize(),recibir);
			}
			/*Funci�n que recoge la salida enviada por el script que realiza la validaci�n de datos.
			Si los datos son correctos, el usuario entra en la aplicaci�n. Si son incorrectos, aparece un mensaje de error indic�ndolo.
			Si no se ha podido realizar la conexi�n con la base de datos para validarlos, se muestra otro mensaje de error notific�ndolo.*/
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
		<!-- Mensajes de error que se mostrar�n seg�n la salida enviada por login.php, que es el encargado
		de la validaci�n de los datos de inicio de sesi�n.-->
			<div id="resultados" style="display:none">El usuario y/o contrase�a son incorrectos</div>
			<div id="error" style="display:none">Problemas con el servidor</div>
			<!-- Formulario para introducir los datos de inicio de sesi�n. -->
			<form method="post" id="formdata">
			<br>
  			<input type="text" name="usuario" id="usuario" value="Usuario" /><br />
            <input type="password" name="contrasena" id="contrasena" value="Contrase�a" /><br />
			<br>
            <input type="button" id="boton" value="Enviar" />
			</form>
			<br>
			<!-- Enlaces a los m�dulos "Olvido de contrase�a" y "Registrar un nuevo usuario" -->
			<a href="php/olvidocontrasena.php">�Olvidaste tu contrase�a?</a><br>
			<a href="php/formularioregistro.php">�A�n no est�s registrado?</a>
		</div>
	</body>
</html>

