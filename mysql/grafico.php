<?php

	require_once('datosConexion.php');
	//Se crea una sesión
	session_start();

	//Realizamos un if-else para saber si la llamada a grafico.php la hace un usuario cualquiera cuando finaliza su test en la pantalla final 
	//del cuestionario o si se trata de un profesor que ha pinchado en el enlace "Mostrar gráfica" de uno de sus alumnos.
	if($_POST['user'] === " ") { 
		$usuario = $_SESSION['usuario'];
	}

	else {
		$usuario = $_POST['user'];
	}

	//Crearemos un array donde guardaremos todos los datos
	$arrayNotas = array();
	$i = 0;

	//Crearemos conexion

	$conexion = mysql_connect($servidor, $usuario_bbdd, $contrasena_bbdd);
	if(!$conexion){

		die('Ha sido imposible realizar la conexion: '.mysql_error());
	}
	mysql_select_db($base_de_datos,$conexion);
	
	//Consulta que muestra todos los cuestionarios realizados por el usuario ordenados de más reciente a más antiguo hasta un límite de 20.

	$consulta = "SELECT * FROM notas WHERE usuario = '$usuario' ORDER BY fechaTest DESC LIMIT 20;";  		
	//$consulta = "SELECT * FROM notas;";

	//Lanzamos la consulta
	$resultado = mysql_query($consulta,$conexion);

	//Guardamos los datos en el array
	while($fila=mysql_fetch_object($resultado)) {
		$arrayNotas[] = array("fechaTest"=>$fila->fechaTest, "nota"=>(float)$fila->nota);

	} 
/*	foreach($arrayNotas AS &$text)
		{
		array_map(utf8_encode,$text);
		}
		//unset($text);
		$arrayNotas=json_encode($arrayNotas);
		echo $arrayNotas;
*/
	/*$fila = mysql_num_rows($resultado);
	if($fila == 0) {
		echo 'noo';
	}
	else if($fila >= 1) {
	
	
	while($fila2=mysql_fetch_array($resultado)) {
		$arrayNotas[] = $fila2['nota'];
		$i++;
		//$nota = $fila['fechaTest'];
		//echo $fila['fechaTest'];
		/*echo '<br>';
		echo utf8_encode($fila['fechaTest']);
		echo '<br>';
		echo utf8_decode($fila['fechaTest']);
		echo '<br>';
		echo $fila['nota'];
		echo '<br>';
		echo (float)$fila['nota'];
		echo '<br>';
		echo utf8_encode($fila['nota']);
		echo '<br>';
		echo utf8_decode($fila['nota']); 
		
	}

	}*/


	//Cerramos base de datos
	mysql_close($conexion);

	//Enviamos objeto JSON con los datos
	echo json_encode($arrayNotas);
	
?>
