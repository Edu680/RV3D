<?php
	//header('Content-Type: text/html; charset=UTF-8');
	require_once('datosConexion.php');

	//Se crea una sesi�n.
	session_start();

	//Se obtienen los datos enviados por el formulario.
	$nombre = $_POST['nombre'];
	$apellido_1 = $_POST['apellido_1'];
	$apellido_2 = $_POST['apellido_2'];
	$email = $_POST['email'];
	$usuario = $_POST['usuario'];
	$contrasena = $_POST['contrasena'];

	//Realizamos la conexi�n a la base de datos.
	$conexion = mysql_connect($servidor, $usuario_bbdd, $contrasena_bbdd);
	if(!$conexion){

		die('Ha sido imposible realizar la conexion: '.mysql_error());
	}
	mysql_select_db($base_de_datos,$conexion);


	//Creamos la consulta que consistir� en almacenar en cada campo de la tabla el valor correspondiente.
	$consulta = "INSERT INTO usuarios (nombre, primerApellido, segundoApellido, email, usuario, contrasena) 
	VALUES (
		'".$nombre."',
		'".$apellido_1."',
		'".$apellido_2."',
		'".$email."',
		'".$usuario."',
		'".$contrasena."'
	)";

	//Ejecutamos la consulta
	mysql_query("set names utf8");
	mysql_query($consulta,$conexion);


	/*Enviamos un correo electr�nico a la direcci�n de email aportada por el usuario d�ndole la bienvenida a la aplicaci�n
	y record�ndole sus datos de inicio de sesi�n */
	$headers = 'MIME-Version: 1.0' ."\r\n";
	$headers .= 'Content-type: text/html; charset=utf-8'."\r\n";
	mail($email, "Bienvenido a RV3D", "Sus datos de acceso a la aplicaci�n RV3D son usuario : $usuario y su contrase�a es: $contrasena", $headers); 	


	//Cerramos la conexion con la base de datos.
	mysql_close($conexion);


	//Devolvemos un 1 indicando que el registro se ha realizado correctamente.
	echo 1;
?>