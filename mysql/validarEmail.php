<?php

	require_once('datosConexion.php');

	//Se crea una sesión.
	session_start();

	//Recogemos el email enviado.
	$email_registrado = $_POST['email'];

	//Realizamos la conexión a la base de datos.
	$conexion = mysql_connect($servidor, $usuario_bbdd, $contrasena_bbdd);
	if(!$conexion){

		die('Ha sido imposible realizar la conexion: '.mysql_error());
	}
	mysql_select_db($base_de_datos,$conexion);

	//Creamos una consulta que buscará en la base de datos el email enviado por el formulario.
	$consulta = "SELECT usuario FROM usuarios WHERE email='$email_registrado';";
	$resultado = mysql_query($consulta,$conexion);
	$fila = mysql_num_rows($resultado);
	//Si no existe, se envía un 0 indicando que el email está disponible para el registro.
	if($fila == 0) {
		echo('0');
	}
	//Si existe, se envía un 1 indicando que ya está en uso.
	else if($fila == 1) {
		echo('1');	
	}
	
	//Cerramos la conexión con la base de datos.
	mysql_close($conexion);
?>