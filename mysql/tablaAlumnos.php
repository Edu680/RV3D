<?php
	
	require_once('datosConexion.php');
	//Se crea una sesi�n
	session_start();

	//Guardamos permisos del usuario.
	$codigo = $_SESSION['permisos'];
	//Creamos array para guardar los datos
	$arrayAlumnos = array();
	$i=0;

	//Si el usuario es profesor
	if($codigo == 1){
		
		//Creamos conexi�n

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
			//Hacer if donde comparamos si el usuario est� conectado o no fij�ndonos en el tiempo l�mite
			$limite = time()-600;//600 segundos. Cambiar por el plazo que se quiera dar al usuario para realizar alguna acci�n (recargar por ejemplo).
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
	//Si no es profesor, no tiene permisos para acceder aqu�. Esto ya lo hab�amos validado previamente, de manera que para un alumno no se muestra 
	//en el men� principal la opci�n 'Gesti�n Alumnos', pero por si por alguna raz�n un alumno consiguiese acceder aqu�, se encontrar�a con 
	//esta nueva restricci�n.
	else{echo "Tu no eres administrador";}
?>