<?php 
	session_start();
	require_once('datosConexion.php');

	$usuario_nombre = $_SESSION['usuario'];
   
    //Realizamos la conexión a la base de datos.
	$conexion = mysql_connect($servidor, $usuario_bbdd, $contrasena_bbdd);
	if(!$conexion){

		die('Ha sido imposible realizar la conexion: '.mysql_error());
	}
	mysql_select_db($base_de_datos,$conexion);

	//Se actualiza el campo tiempo de la tabla usuarios que determinará si el usuario está conectado o no a la aplicación.
	$consulta = "SELECT * FROM usuarios WHERE usuario='$usuario_nombre';";
	//mysql_query("set names utf8");
	$resultado = mysql_query($consulta,$conexion);
	$tiempo = time();

	if ($fila = mysql_num_rows($resultado) == 1){
		$datos = mysql_fetch_array($resultado);
		$email = $datos['email'];
		$tiempo2 = $datos['tiempo'];
		$consulta = "UPDATE usuarios SET tiempo='$tiempo' WHERE usuario='$usuario_nombre';";
		mysql_query("set names utf8");
		mysql_query($consulta,$conexion);

		/*$headers = 'MIME-Version: 1.0' ."\r\n";
			$headers .= 'Content-type: text/html; charset=utf-8'."\r\n";
			mail($email, "funcion online", "tiempo : $tiempo, tiempo2: $tiempo2", $headers); 
		*/
	}
		
	//Cerramos la conexión con la base de datos
	mysql_close($conexion);

	
?>