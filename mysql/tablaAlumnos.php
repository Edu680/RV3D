<?php
	
	require_once('datosConexion.php');
	//Se crea una sesión
	session_start();

	//Guardamos permisos del usuario.
	$codigo = $_SESSION['permisos'];
	//Creamos array para guardar los datos
	$arrayAlumnos = array();
	$i=0;

	//Si el usuario es profesor
	if($codigo == 1){
		
		//Creamos conexión

		$conexion = mysql_connect($servidor, $usuario_bbdd, $contrasena_bbdd);
		if(!$conexion){

			die('Ha sido imposible realizar la conexion: '.mysql_error());
		}
		mysql_select_db($base_de_datos,$conexion);

		//Establecemos una consulta que muestra todos los usuarios del sistema

		$consulta = "SELECT * FROM usuarios ;";

		//Ejecutamos la consulta
		//mysql_query("set names utf8");
		$resultado = mysql_query($consulta,$conexion);
	

		while ($fila = mysql_fetch_array($resultado)){
			//Hacer if donde comparamos si el usuario está conectado o no fijándonos en el tiempo límite
			$limite = time()-600;//600 segundos. Cambiar por el plazo que se quiera dar al usuario para realizar alguna acción (recargar por ejemplo).
			if($fila['tiempo'] < $limite) {
				$estado = '<font color = "red"> Desconectado</font>';
			}
			else {
				$estado = '<font color = "green"> Conectado</font>';
			}
			$arrayAlumnos[] = array(utf8_encode($fila['usuario']), utf8_encode($fila['contrasena']), utf8_encode($fila['nombre']), utf8_encode($fila['primerApellido']), utf8_encode($fila['segundoApellido']), utf8_encode($fila['email']), $estado, utf8_encode($fila['notaMasAlta']), utf8_encode($fila['notaMasBaja']), utf8_encode($fila['rol']), $i);
			$i++;
			
		}

		//Cerramos la conexion

		mysql_close($conexion);

		//Enviamos objeto JSON con los datos de los alumnos registrados en el sistema
		echo json_encode($arrayAlumnos);
	}
	//Si no es profesor, no tiene permisos para acceder aquí. Esto ya lo habíamos validado previamente, de manera que para un alumno no se muestra 
	//en el menú principal la opción 'Gestión Alumnos', pero por si por alguna razón un alumno consiguiese acceder aquí, se encontraría con 
	//esta nueva restricción.
	else{echo "Tu no eres administrador";}
?>