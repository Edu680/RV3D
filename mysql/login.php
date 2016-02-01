<?php

	require_once('datosConexion.php');

	//Se crea una sesión
	session_start();

	//Obtenemos variables del formulario.

	$usuario = $_POST['usuario'];
	$contrasena = $_POST['contrasena'];

	//Creamos conexion

	$conexion = mysql_connect($servidor, $usuario_bbdd, $contrasena_bbdd);
	if(!$conexion){

		die('Ha sido imposible realizar la conexion: '.mysql_error());
	}

	mysql_select_db($base_de_datos,$conexion);

	//Creamos consulta que busque en la tabla usuarios un usuario con el nombre de usuario y contraseña recogidos del formulario.

	$consulta = "SELECT * FROM usuarios WHERE usuario='$usuario' AND contrasena='$contrasena';";

	//Lanzamos la consulta
	//mysql_query("set names utf8");
	$resultado = mysql_query($consulta,$conexion);

	//Repasamos los resultados
	while($fila=mysql_fetch_array($resultado)){

		$usuariobasedatos = $fila['usuario'];
		$contrasenabasedatos = $fila['contrasena'];

		//Si el resultado es positivo, entonces asignamos a variables de sesión para que los datos sean accesibles desde cualquier parte de la aplicación.
		if($usuario == $usuariobasedatos & $contrasena == $contrasenabasedatos){
			
			$_SESSION['usuario'] = $usuario;
			$_SESSION['contrasena'] = $contrasena;
			$_SESSION['nombre'] = $fila['nombre'];
			$_SESSION['apellido_1'] = $fila['primerApellido'];
			$_SESSION['apellido_2'] = $fila['segundoApellido'];
			$_SESSION['permisos'] = $fila['rol'];

			//Actualizamos la variable tiempo para indicar que el usuario está ahora conectado.
			$tiempo = time();
			$consulta2 = "UPDATE usuarios SET tiempo='$tiempo' WHERE usuario='$usuario';";
			mysql_query($consulta2,$conexion);

			//Devolvemos 1 indicando que la validación ha sido positiva.
			echo('1');
		}

		//Si el resultado es negativo, enviamos 0.
		else
		{
			echo('0');
		}

	}

	//Cerramos conexión con la base de datos
	mysql_close($conexion);

?>
