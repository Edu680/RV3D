<?php

	require_once('datosConexion.php');

	//Se crea una sesi�n.
	session_start();

	//Recogemos el nombre de usuario enviado.
	$usuario_registrado = $_POST['usuario'];

	//Realizamos la conexi�n a la base de datos.
	$conexion = mysql_connect($servidor, $usuario_bbdd, $contrasena_bbdd);
	if(!$conexion){

		die('Ha sido imposible realizar la conexion: '.mysql_error());
	}
	mysql_select_db($base_de_datos,$conexion);

	//Creamos una consulta que buscar� en la base de datos el usuario enviado por el formulario.
	$consulta = "SELECT usuario FROM usuarios WHERE usuario='$usuario_registrado';";
	$resultado = mysql_query($consulta,$conexion);
	$fila = mysql_num_rows($resultado);
	//Si no existe, se env�a un 0 indicando que el nombre de usuario est� disponible para el registro.
	if($fila == 0) {
		echo('0');
	}
	//Si existe, se env�a un 1 indicando que no est� disponible para el registro.
	else if($fila == 1) {
		echo('1');	
	}
	
	//Cerramos la conexi�n con la base de datos.
	mysql_close($conexion);
?>