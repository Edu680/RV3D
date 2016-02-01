<?php

	require_once('datosConexion.php');

	//Se crea sesión
	session_start();

	$pregunta = array();

	//Crearemos conexion

	$conexion = mysql_connect($servidor, $usuario_bbdd, $contrasena_bbdd);
	if(!$conexion){
		die('Ha sido imposible realizar la conexion: '.mysql_error());
	}
	mysql_select_db($base_de_datos,$conexion);
	
	//Generamos un número aleatorio para elegir la pregunta.
	$aleatorio = mt_rand(1,24);

	//Consulta que muestra un problema con problemaID = número aleatorio generado
	$consulta = "SELECT * FROM problemas WHERE problemaID = '$aleatorio';";

	//Lanzamos la consulta
	$resultado = mysql_query($consulta,$conexion);

	//Repasamos los resultados
	while($fila=mysql_fetch_array($resultado)){

		$preguntatest = $fila['enunciado'];
		$numeroVectores = $fila['numeroVectores'];
		$tipoOperacion = $fila['tipoOperacion'];
	}
	//Cerramos base de datos
	mysql_close($conexion);

	//Generamos valores aleatorias para cada variable inicial del problema
	for($i=1;$i<=$numeroVectores;$i++) {
			$variablealeatoria[$i-1][0] = mt_rand(-20,20);
			$preguntatest = preg_replace('/x'.$i.'/', $variablealeatoria[$i-1][0], $preguntatest);
			$variablealeatoria[$i-1][1] = mt_rand(-20,20);
			$preguntatest = preg_replace('/y'.$i.'/', $variablealeatoria[$i-1][1], $preguntatest);
			$variablealeatoria[$i-1][2] = mt_rand(-20,20);
			$preguntatest = preg_replace('/z'.$i.'/', $variablealeatoria[$i-1][2], $preguntatest);
	}
	
	//Creamos sesiones con los datos necesarios para el cuestionario
	$_SESSION['vectores'] = $variablealeatoria;
	$_SESSION['operacion'] = $tipoOperacion;
	$_SESSION['iteraciones'] = $numeroVectores; 
	$_SESSION['contadorPreguntas']+= 1;
	
	$pregunta[0] = $preguntatest;		//Posible fallo con json_encode(), ya que las sesiones si funcionan y la única que falla es esta que no es sesión.
	$pregunta[1] = $_SESSION['contadorPreguntas'];
	$pregunta[2] = $_SESSION['operacion'];
	
	//Enviamos un objeto JSON con el enunciado del problema, el contador de preguntas del cuestionario y el tipo de operación que se requiere para 
	//hallar la solución.
	//echo json_encode($pregunta);

	echo json_encode(array_map(utf8_encode,$pregunta));
	
?>