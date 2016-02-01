<?php

	require_once('datosConexion.php');
	//Se crea sesión
	session_start();

	//Se decodifica el objeto JSON recibido con los datos del usuario. Se puede hacer mucho más sencillo ya que mediante la validación de nombre de usuario realizada al realizar el registro de nuevo usuario, nos aseguramos que solo existirá un usuario en la aplicación con un nombre de usuario concreto, por lo que pasando mediante post el nombre de usuario y haciendo la consulta para que borre el registro con el usuario = nombre de usuario que pasamos por post, sería suficiente.
	
	$data = json_decode($_POST['jObject'], true);

	//Creamos la conexión a la base de datos
	$conexion = mysql_connect($servidor, $usuario_bbdd, $contrasena_bbdd);
	if(!$conexion){

		die('Ha sido imposible realizar la conexion: '.mysql_error());
	}
	mysql_select_db($base_de_datos,$conexion);


	$usuario = utf8_decode($data[0]);
	$contrasena = utf8_decode($data[1]);
	$nombre = utf8_decode($data[2]);
	$apellido_1 = utf8_decode($data[3]);
	$apellido_2 = utf8_decode($data[4]);

	//Creamos consulta en la que borramos de la tabla usuarios al usuario con los datos que le indicamos
	$consulta = "DELETE FROM usuarios WHERE  usuario='".$usuario."' AND contrasena='".$contrasena."' AND nombre='".$nombre."' AND primerApellido='".$apellido_1."' AND segundoApellido='".$apellido_2."'";

	//Ejecutamos la consulta.
	$resultado = mysql_query($consulta,$conexion);

	//Cerramos la conexión con la base de datos
	mysql_close($conexion);

	//Devolvemos salida 1 indicando que se ha eliminado correctamente
	echo('1');

?>