<?php
	header('Content-Type: text/html; charset=UTF-8');
	require_once('datosConexion.php');

	//Se crea una sesión
	session_start();

	//Recogemos el email enviado por el formulario.
	$email = $_POST['email'];


	//Realizamos la conexión a la base de datos.
	$conexion = mysql_connect($servidor, $usuario_bbdd, $contrasena_bbdd);
	if(!$conexion){

		die('Ha sido imposible realizar la conexion: '.mysql_error());
	}
	mysql_select_db($base_de_datos,$conexion);

	//Creamos una consulta que consistirá en mostrar la fila cuyo campo email sea el enviado por el usuario.
	$consulta = "SELECT * FROM usuarios WHERE email = '$email';";

	//Ejecutamos la consulta
	mysql_query("set names utf8");
	$resultado = mysql_query($consulta,$conexion);
		$fila = mysql_num_rows($resultado);
		//Si no existe ninguna fila con ese email, es porque no existe usuario asociado a ese correo.
		if($fila == 0) {
			echo 0;
		}
		//Si existe, se envía un correo electrónico con los datos de inicio de sesión al correo introducido por el usuario.
		else if($fila == 1) {
			$datos = mysql_fetch_array($resultado);
			$usuario = $datos['usuario'];
			$contrasena = $datos['contrasena'];
			
			$headers = 'MIME-Version: 1.0' ."\r\n";
			$headers .= 'Content-type: text/html; charset=utf-8'."\r\n";
			mail($email, "Recuperacion de datos", "Sus datos en nuestra web son usuario : $usuario y su contraseña es: $contrasena", $headers); 
			echo 1;
		}
		
	//Cerramos la conexión con la base de datos.
	mysql_close($conexion);
?>