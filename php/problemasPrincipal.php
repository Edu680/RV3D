<!-- Se crea una sesi�n y un contador de preguntas y un contador de respuestas correctas-->
<?php
	session_start();
	$_SESSION['contadorPreguntas'] = 0;
	$_SESSION['respuestasCorrectas'] = 0;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//ES" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>Cuestionario</title>	
		<style>
			div#contenido {
				height: auto;
				
			}
		</style>
		<script type="text/javascript">
			$(document).ready(inicio);
			function inicio(){
				//Cuando se pulsa el bot�n, se redirige al usuario a problema1.php para comenzar el cuestionario
				$("#boton").button().click(comenzarTest);
			}
							
			function comenzarTest() {
				$("#contenido").load("problema1.php");	
			}	
			
		</script>
	</head>
	<body>
		<!-- Indicaciones sobre el cuestionario -->
		<div id="test" class="ui-corner-all">
		<p><h2>Para ayudarte a evaluar los conceptos aprendidos en clase y demostrar que los ejemplos de esta aplicaci�n te han servido de ayuda, te proponemos el siguiente test de autoevaluaci�n.</p>
		<br><p>El procedimiento es de lo m�s sencillo: Deber�s contestar 10 preguntas introduciendo el resultado en su correspondiente campo de texto
		y validar la respuesta pulsando sobre el bot�n "Enviar respuesta" para cada pregunta que se te formule.</p>
		<br><p>Para ayudarte a realizar los c�lculos y encontrar la respuesta correcta, podr�s usar un escenario 3D 
		y una interfaz de control como los mostrados en el m�dulo Practica.</p>
		<br><p>Si tienes alguna duda, puedes consultar la ayuda disponible pulsando en el icono de la bombilla que encontrar�s en la parte superior izquierda del escenario.</p>
		<br><p>Suerte y...</p></h2><br>
		<form method="post" id="formdata">
			<input type="button" id="boton" value="�A jugar!" />
		</form>
		</div>
	</body>
</html>