<?php
	session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//ES" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	
	<head>
		<title>Test</title>	
	</head>
	<body>
		<div id="contenido" class="ui-corner-all">
			<h2 style="color: green">Preguntas acertadas: 
			<?php 
				require_once('datosConexion.php');

				$usuario = $_SESSION['usuario'];

				//Se calcula la nota final obtenida en el cuestionario.
				$nota = 10 / $_SESSION['contadorPreguntas'] * $_SESSION['respuestasCorrectas'];
				$nota = round($nota * 100) / 100;
				
				//Realizamos conexión con la base de datos
				$conexion = mysql_connect($servidor, $usuario_bbdd, $contrasena_bbdd);
				if(!$conexion){

					die('Ha sido imposible realizar la conexion: '.mysql_error());
				}

				mysql_select_db($base_de_datos,$conexion);

				//Guardamos usuario, fecha del cuestionario y nota 
				$consulta = "INSERT INTO notas (usuario, fechaTest, nota) 
				VALUES (
					'".$usuario."',
					NOW(),
					'".$nota."'
				)";

				//Ejecutamos la consulta
				mysql_query("set names utf8");
				mysql_query($consulta,$conexion);

				//Actualizamos el valor para notaMasAlta y/o notaMasBaja
				$consultaNota = "SELECT notaMasAlta, notaMasBaja FROM usuarios WHERE usuario='$usuario'";
				$resultado = mysql_query($consultaNota,$conexion);
				while($fila=mysql_fetch_array($resultado)){
					if($fila['notaMasAlta'] == NULL & $fila['notaMasBaja'] == NULL) {
						$consulta1 = "UPDATE usuarios SET notaMasAlta='$nota', notaMasBaja='$nota' WHERE usuario='$usuario'";
						mysql_query("set names utf8");
						mysql_query($consulta1,$conexion);
					}
					else if($nota > $fila['notaMasAlta']) {
						$consulta2 = "UPDATE usuarios SET notaMasAlta='$nota' WHERE usuario='$usuario'";
						mysql_query("set names utf8");
						mysql_query($consulta2,$conexion);
					}
					else if($nota < $fila['notaMasBaja']) {
						$consulta3 = "UPDATE usuarios SET notaMasBaja='$nota' WHERE usuario='$usuario'";
						mysql_query("set names utf8");
						mysql_query($consulta3,$conexion);
					}
				}
				
				//Cerramos la conexión con la base de datos
				mysql_close($conexion);

				//Mostramos el número de respuestas correctas.
				echo $_SESSION['respuestasCorrectas']; 
			?>
			</h2>
			<br><h2 style="color: red">Preguntas falladas:
			<?php 
				//Mostramos el número de respuestas incorrectas.
				echo $_SESSION['contadorPreguntas'] - $_SESSION['respuestasCorrectas'];
			?>
			</h2>
			<br><h2>Nota final: 
			<?php
				//Mostramos la nota final
				echo $nota;
			?>
			<br>
			</h2>
			<br><h2>Gráfico de notas: 
			</h2><br>
			<div id="graficaNotas">
				
			</div>
		</div>
	</body>
</html>

<script type="text/javascript">

$(document).ready(function() {
	//Se habilita el enlace de cerrar sesión para que el usuario pueda cerrar sesión.
	$("#pie").click("a", function() {
			window.location = "cerrarsesion.php";
	});
	//Mediante ajax(), le pasamos a grafico.php el nombre del usuario y nos devuelve un objeto JSON con todos los últimos 20 cuestionarios realizados
	//por ese usuario. A través de morris.js, mostramos la información en una gráfica lineal.
	var user = " ";
	$.ajax({
		url: '../mysql/grafico.php',
		type: 'POST',
		data: {user: user},
		success: function(data) {
			var $graph = data;
			var obj = $.parseJSON($graph);
			
			new Morris.Line({
			  // ID of the element in which to draw the chart.
			  element: 'graficaNotas',
			  // Chart data records -- each entry in this array corresponds to a point on
			  // the chart.
			  data: obj,
			  // The name of the data record attribute that contains x-values.
			  xkey: 'fechaTest',
			  // A list of names of data record attributes that contain y-values.
			  ykeys: ['nota'],
			  // Labels for the ykeys -- will be displayed when you hover over the
			  // chart.
			  labels: ['Nota'],
			  ymin: 0,
			  ymax: 10
			});
		}
	});
});

</script>