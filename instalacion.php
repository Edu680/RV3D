<?php
	require_once('mysql/datosConexion.php');

	//Creamos conexion
	$conexion = mysql_connect($servidor, $usuario_bbdd, $contrasena_bbdd);
	if(!$conexion){
		die('Ha sido imposible realizar la conexion: '.mysql_error());
	}
	mysql_query("DROP DATABASE $base_de_datos", $conexion) or die ("ERROR: ".mysql_error());
	if(mysql_query("CREATE DATABASE $base_de_datos",$conexion))
	{
		echo "Se ha creado la base de datos";
	}
	else {
		echo "No se ha podido crear la base de datos por el siguiente error: ".mysql_error();
	}

	//Preparo la peticion
	mysql_select_db($base_de_datos,$conexion);
	mysql_query("set names utf8");
	$peticion = "TRUNCATE TABLE	usuarios";
	mysql_query($peticion,$conexion);
	$peticion = "CREATE TABLE usuarios
	(
		personaID int(11) NOT NULL AUTO_INCREMENT UNIQUE,
		nombre varchar(30) NOT NULL,
		primerApellido varchar(30) NOT NULL,
		segundoApellido varchar(30),
		email varchar(50) NOT NULL,
		usuario varchar(15) NOT NULL,
		PRIMARY KEY(usuario),
		contrasena varchar(15) NOT NULL,
		rol int(11) NOT NULL,
		notaMasAlta decimal(10,0) NULL,
		notaMasBaja decimal(10,0) NULL,
		tiempo int(11) NOT NULL DEFAULT '0'
			
	)
	ENGINE = InnoDB
	DEFAULT CHARACTER SET = utf8
	COLLATE = utf8_spanish_ci ";
	
	$control = mysql_query($peticion,$conexion);
	if(!$control) {
		echo "<p>Error al crear la tabla usuarios</p>";
	}

	mysql_query($peticion,$conexion);
	$peticion = "TRUNCATE TABLE	problemas";
	mysql_query($peticion,$conexion);
	$peticion = "CREATE TABLE problemas
	(
		problemaID int(11) NOT NULL AUTO_INCREMENT,
		PRIMARY KEY(problemaID),
		enunciado varchar(500) NOT NULL,
		tipoOperacion varchar(20) NOT NULL,
		numeroVectores int(11) NOT NULL
	)
	ENGINE = InnoDB
	DEFAULT CHARACTER SET = utf8
	COLLATE = utf8_spanish_ci ";
	
	$control = mysql_query($peticion,$conexion);
	if(!$control) {
		echo "<p>Error al crear la tabla problemas</p>";
	}

	$peticion = "TRUNCATE TABLE	notas";
	mysql_query($peticion,$conexion);
	$peticion = "CREATE TABLE notas
	(
		notaID int(11) NOT NULL AUTO_INCREMENT,
		PRIMARY KEY(notaID),
		usuario varchar(15) NOT NULL,
		fechaTest DateTime NOT NULL,
		nota float NOT NULL,
		FOREIGN KEY (usuario) REFERENCES usuarios (usuario) ON DELETE CASCADE ON UPDATE CASCADE
	)
	ENGINE = InnoDB
	DEFAULT CHARACTER SET = utf8
	COLLATE = utf8_spanish_ci ";
	
	$control = mysql_query($peticion,$conexion);
	if(!$control) {
		echo "<p>Error al crear la tabla notas</p>";
	}

	$peticion = "INSERT INTO usuarios (usuario, contrasena, nombre, primerApellido, segundoApellido, email, rol) 
	VALUES (
		'pilar',
		'profesor',
		'Pilar',
		'Martínez',
		'Jiménez',
		'fa1majip@uco.es',
		1
	)";
	$peticion2 = "INSERT INTO usuarios (usuario, contrasena, nombre, primerApellido, segundoApellido, email, rol) 
	VALUES (
		'mcarmen',
		'profesor',
		'Mª Carmen',
		'García',
		'Martínez',
		'fa1gamam@uco.es',
		1
	)";
	//Ejecuto la petición
	mysql_query("set names utf8");
	$control = mysql_query($peticion,$conexion);
	if(!$control) {
		echo "<p>Error al crear registro de profesor1</p>";
	}
	$control = mysql_query($peticion2,$conexion);
	if(!$control) {
		echo "<p>Error al crear registro de profesor2</p>";
	}

	$peticion = "INSERT INTO problemas (enunciado, tipoOperacion, numeroVectores) 
	VALUES 
	(
		'Sea vector v = (x1,y1,z1) y vector w = (x2,y2,z2). Calcular el vector suma resultante.',
		'suma',
		'2'
	),
	(
		'Sea vector v = (x1,y1,z1) y vector w = (x2,y2,z2). Calcular el vector resta resultante.',
		'resta',
		'2'
	),
	(
		'Sea vector v = (x1,y1,z1) y vector w = (x2,y2,z2). Calcular el producto vectorial de ambos.',
		'productoVectorial',
		'2'
	),
	(
		'Sea vector u = (x1,y1,z1), vector v = (x2,y2,z2) y vector w = (x3,y3,z3). Calcular su producto vectorial.',
		'productoVectorial',
		'3'
	),
	(
		'Sea vector u = (x1,y1,z1), vector v = (x2,y2,z2) y vector w = (x3,y3,z3). Calcular el vector suma resultante.',
		'suma',
		'3'
	),
	(
		'Sea vector u = (x1,y1,z1), vector v = (x2,y2,z2) y vector w = (x3,y3,z3). Calcular el vector resta resultante.',
		'resta',
		'3'
	),
	(
		'Sea vector u = (x1,y1,z1), vector v = (x2,y2,z2) y vector w = (x3,y3,z3). Calcular su producto mixto.',
		'productMixto',
		'3'
	),
	(
		'Sea vector v = (x1,y1,z1) y vector w = (x2,y2,z2). Calcular su producto escalar.',
		'productoEscalar',
		'2'
	),
	(
		'Sea vector v = (x1,y1,z1) y vector w = (x2,y2,z2). Calcular el ángulo, en radianes, que forman (Redondea la solución a 2 decimales).',
		'anguloRadianes',
		'2'
	),
	(
		'Sea vector v = (x1,y1,z1) y vector w = (x2,y2,z2). Calcular el ángulo, en grados, que forman (Redondea la solución a 2 decimales).',
		'anguloGrados',
		'2'
	),
	(
		'Sea vector v = (x1,y1,z1). Calcular el ángulo, en grados, que forma con el eje x positivo (Redondea la solución a 2 decimales).',
		'anguloGradosX+',
		'2'
	),
	(
		'Sea vector v = (x1,y1,z1). Calcular el ángulo, en grados, que forma con el eje x negativo (Redondea la solución a 2 decimales).',
		'anguloGradosX-',
		'2'
	),
	(
		'Sea vector v = (x1,y1,z1). Calcular el ángulo, en grados, que forma con el eje y positivo (Redondea la solución a 2 decimales).',
		'anguloGradosY+',
		'2'
	),
	(
		'Sea vector v = (x1,y1,z1). Calcular el ángulo, en grados, que forma con el eje y negativo (Redondea la solución a 2 decimales).',
		'anguloGradosY-',
		'2'
	),
	(
		'Sea vector v = (x1,y1,z1). Calcular el ángulo, en grados, que forma con el eje z positivo (Redondea la solución a 2 decimales).',
		'anguloGradosZ+',
		'2'
	),
	(
		'Sea vector v = (x1,y1,z1). Calcular el ángulo, en grados, que forma con el eje z negativo (Redondea la solución a 2 decimales).',
		'anguloGradosZ-',
		'2'
	),
	(
		'Sea vector v = (x1,y1,z1). Calcular el ángulo, en radianes, que forma con el eje x positivo (Redondea la solución a 2 decimales).',
		'anguloRadianesX+',
		'2'
	),
	(
		'Sea vector v = (x1,y1,z1). Calcular el ángulo, en radianes, que forma con el eje x negativo (Redondea la solución a 2 decimales).',
		'anguloRadianesX-',
		'2'
	),
	(
		'Sea vector v = (x1,y1,z1). Calcular el ángulo, en radianes, que forma con el eje y positivo (Redondea la solución a 2 decimales).',
		'anguloRadianesY+',
		'2'
	),
	(
		'Sea vector v = (x1,y1,z1). Calcular el ángulo, en radianes, que forma con el eje y negativo (Redondea la solución a 2 decimales).',
		'anguloRadianesY-',
		'2'
	),
	(
		'Sea vector v = (x1,y1,z1). Calcular el ángulo, en radianes, que forma con el eje z positivo (Redondea la solución a 2 decimales).',
		'anguloRadianesZ+',
		'2'
	),
	(
		'Sea vector v = (x1,y1,z1). Calcular el ángulo, en radianes, que forma con el eje z negativo (Redondea la solución a 2 decimales).',
		'anguloRadianesZ-',
		'2'
	),
	(
		'Sea vector v = (x1,y1,z1) y vector w = (x2,y2,z2), calcular el área del paralelogramo que forman (Redondea la solución a 2 decimales).',
		'area',
		'2'
	),
	(
		'Sea vector u = (x1,y1,z1), vector v = (x2,y2,z2) y vector w = (x3,y3,z3), calcular el volumen del paralelepípedo que forman (Redondea la solución a 2 decimales).',
		'volumen',
		'3'
	)";

	//Ejecuto la petición
	mysql_query("set names utf8");
	$control = mysql_query($peticion,$conexion);
	if(!$control) {
		echo "<p>Error al crear enunciados de problemas</p>";
	}

	//Cierro la conexion
	mysql_close($conexion);
	

?>