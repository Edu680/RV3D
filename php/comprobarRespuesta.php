<?php
	//Se crea sesión.
	session_start();

	//Obtenemos el valor de las variables, el tipo de operación realizada para obtener el resultado y el número de vectores del problema.
	$vectores = $_SESSION['vectores'];
	$tipoOperacion = $_SESSION['operacion'];
	$iteraciones = $_SESSION['iteraciones'];

	//Si el tipo de operación es un producto mixto o producto escalar, la solución será dicho producto o un ángulo, dependiendo de lo que pida
	//el problema. En definitiva, será una respuesta única mediante un único campo del formulario.
	if($tipoOperacion == "productoMixto" || $tipoOperacion == "productoEscalar" || substr($tipoOperacion, 0, 14) == "anguloRadianes" || substr($tipoOperacion, 0, 12) == 'anguloGrados' || $tipoOperacion == "area" || $tipoOperacion == "volumen") {
		$respuesta = $_POST['respuesta'];
	}
	//En cambio, si se trata de una suma, resta o producto vectorial, el resultado será un vector, un formulario múltiple.
	else {
		$x = $_POST['respuestaX'];
		$y = $_POST['respuestaY'];
		$z = $_POST['respuestaZ'];
	}
	
	//Algoritmo suma vectorial
	if($tipoOperacion == 'suma') {
		for($i=0;$i<3;$i++) {
			for($j=0;$j<$iteraciones;$j++) {
				if($j==0) {
					$resultado[$i] = $vectores[$j][$i];	
				}
				else {
					$resultado[$i] += $vectores[$j][$i];
				}
			}
		}
	}
	//Algoritmo resta vectorial
	else if($tipoOperacion == 'resta') {
		for($i=0;$i<3;$i++) {
			for($j=0;$j<$iteraciones;$j++) {
				if($j == 0) {
					$resultado[$i] = $vectores[$j][$i];
				}
				else {
					$resultado[$i] -= $vectores[$j][$i];
				}
			}
		}
	}
	//Algoritmo producto vectorial
	else if($tipoOperacion == 'productoVectorial') {
		$resultado[0] = $vectores[0][0];
		$resultado[1] = $vectores[0][1];
		$resultado[2] = $vectores[0][2];
		
		for($j=1;$j<$iteraciones;$j++) {
			$auxiliar[0] = $resultado[1] * $vectores[$j][2] - $vectores[$j][1] * $resultado[2];
			$auxiliar[1] = -1 * ($resultado[0] * $vectores[$j][2] - $vectores[$j][0] * $resultado[2]);
			$auxiliar[2] = $resultado[0] * $vectores[$j][1] - $vectores[$j][0] * $resultado[1];
			
			$resultado[0] = $auxiliar[0];
			$resultado[1] = $auxiliar[1];
			$resultado[2] = $auxiliar[2];
		}
	}
	else if($tipoOperacion == 'area') {
		$resultado[0] = $vectores[0][0];
		$resultado[1] = $vectores[0][1];
		$resultado[2] = $vectores[0][2];
		
		for($j=1;$j<$iteraciones;$j++) {
			$auxiliar[0] = $resultado[1] * $vectores[$j][2] - $vectores[$j][1] * $resultado[2];
			$auxiliar[1] = -1 * ($resultado[0] * $vectores[$j][2] - $vectores[$j][0] * $resultado[2]);
			$auxiliar[2] = $resultado[0] * $vectores[$j][1] - $vectores[$j][0] * $resultado[1];
			
			$resultado[0] = $auxiliar[0];
			$resultado[1] = $auxiliar[1];
			$resultado[2] = $auxiliar[2];
		}
		//Área del paralelogramo es igual al módulo del producto vectorial de los 2 vectores que serán los lados del paralelogramo.
		$modulo = sqrt(pow($resultado[0], 2) + pow($resultado[1], 2) + pow($resultado[2], 2));
		$solucion = round($modulo, 2);
	}
	//Algoritmo producto escalar
	else if($tipoOperacion == 'productoEscalar') {
		$resultado[0] = $vectores[0][0];
		$resultado[1] = $vectores[0][1];
		$resultado[2] = $vectores[0][2];

		$escalar = $resultado[0] * $vectores[1][0] + $resultado[1] * $vectores[1][1] + $resultado[2] * $vectores[1][2]; 
		$solucion = $escalar;
	}
	//Algoritmo producto mixto
	else if($tipoOperacion == 'productoMixto') {
		$resultado[0] = $vectores[1][0];
		$resultado[1] = $vectores[1][1];
		$resultado[2] = $vectores[1][2];

		//Producto Cruz
		$auxiliar[0] = $resultado[1] * $vectores[2][2] - $vectores[2][1] * $resultado[2];
		$auxiliar[1] = -1 * ($resultado[0] * $vectores[2][2] - $vectores[2][0] * $resultado[2]);
		$auxiliar[2] = $resultado[0] * $vectores[2][1] - $vectores[2][0] * $resultado[1];
			
		$resultado[0] = $auxiliar[0];
		$resultado[1] = $auxiliar[1];
		$resultado[2] = $auxiliar[2];

		//Producto Escalar
		$escalar = $resultado[0] * $vectores[0][0] + $resultado[1] * $vectores[0][1] + $resultado[2] * $vectores[0][2]; 
		$solucion = $escalar;
	}
	else if($tipoOperacion == 'volumen') {
		$resultado[0] = $vectores[1][0];
		$resultado[1] = $vectores[1][1];
		$resultado[2] = $vectores[1][2];

		//Producto Cruz
		$auxiliar[0] = $resultado[1] * $vectores[2][2] - $vectores[2][1] * $resultado[2];
		$auxiliar[1] = -1 * ($resultado[0] * $vectores[2][2] - $vectores[2][0] * $resultado[2]);
		$auxiliar[2] = $resultado[0] * $vectores[2][1] - $vectores[2][0] * $resultado[1];
			
		$resultado[0] = $auxiliar[0];
		$resultado[1] = $auxiliar[1];
		$resultado[2] = $auxiliar[2];

		//Volumen paralelepípedo es igual al valor absoluto del producto mixto
		$escalar = $resultado[0] * $vectores[0][0] + $resultado[1] * $vectores[0][1] + $resultado[2] * $vectores[0][2]; 
		$solucion = abs($escalar);
	}
	else if(substr($tipoOperacion, 0, 14) == 'anguloRadianes') {
		$resultado[0] = $vectores[0][0];
		$resultado[1] = $vectores[0][1];
		$resultado[2] = $vectores[0][2];
		
		if(substr($tipoOperacion, -1) == '+') {
			if(substr($tipoOperacion, -2, 1) == 'X') {
				// Producto escalar = x * 1 + y * 0 + z * 0
				$escalar = $resultado[0] * 1; 
			}
			if(substr($tipoOperacion, -2, 1) == 'Y') {
				// Producto escalar = x * 0 + y * 1 + z * 0
				$escalar = $resultado[1] * 1; 
			}
			if(substr($tipoOperacion, -2, 1) == 'Z') {
				// Producto escalar = x * 0 + y * 0 + z * 1
				$escalar = $resultado[2] * 1; 
			}
			$moduloV = sqrt(pow($resultado[0], 2) + pow($resultado[1], 2) + pow($resultado[2], 2));
			$moduloW = sqrt(1);
		}
		else if(substr($tipoOperacion, -1) == '-') {
			if(substr($tipoOperacion, -2, 1) == 'X') {
				// Producto escalar = x * -1 + y * 0 + z * 0
				$escalar = $resultado[0] * -1; 
			}
			if(substr($tipoOperacion, -2, 1) == 'Y') {
				// Producto escalar = x * 0 + y * -1 + z * 0
				$escalar = $resultado[1] * -1; 
			}
			if(substr($tipoOperacion, -2, 1) == 'Z') {
				// Producto escalar = x * 0 + y * 0 + z * -1
				$escalar = $resultado[2] * -1; 
			}

			$moduloV = sqrt(pow($resultado[0], 2) + pow($resultado[1], 2) + pow($resultado[2], 2));
			$moduloW = sqrt(1);
		
		}
		else {

			$escalar = $resultado[0] * $vectores[1][0] + $resultado[1] * $vectores[1][1] + $resultado[2] * $vectores[1][2]; 
			$moduloV = sqrt(pow($resultado[0], 2) + pow($resultado[1], 2) + pow($resultado[2], 2));
			$moduloW = sqrt(pow($vectores[1][0], 2) + pow($vectores[1][1], 2) + pow($vectores[1][2], 2));
			
		}

		// AnguloRad = Producto Escalar (v * w) / módulo vector v * módulo vector w. Lo redondeamos a 2 decimales.
		$anguloRad = round(acos($escalar/($moduloV * $moduloW)), 2);
		$solucion = $anguloRad;
	}
	else if(substr($tipoOperacion, 0, 12) == 'anguloGrados') {
		$resultado[0] = $vectores[0][0];
		$resultado[1] = $vectores[0][1];
		$resultado[2] = $vectores[0][2];
		
		if(substr($tipoOperacion, -1) == '+') {
			if(substr($tipoOperacion, -2, 1) == 'X') {
				// Producto escalar = x * 1 + y * 0 + z * 0
				$escalar = $resultado[0] * 1; 
			}
			if(substr($tipoOperacion, -2, 1) == 'Y') {
				// Producto escalar = x * 0 + y * 1 + z * 0
				$escalar = $resultado[1] * 1; 
			}
			if(substr($tipoOperacion, -2, 1) == 'Z') {
				// Producto escalar = x * 0 + y * 0 + z * 1
				$escalar = $resultado[2] * 1; 
			}
			$moduloV = sqrt(pow($resultado[0], 2) + pow($resultado[1], 2) + pow($resultado[2], 2));
			$moduloW = sqrt(1);
		}
		else if(substr($tipoOperacion, -1) == '-') {
			if(substr($tipoOperacion, -2, 1) == 'X') {
				// Producto escalar = x * -1 + y * 0 + z * 0
				$escalar = $resultado[0] * -1; 
			}
			if(substr($tipoOperacion, -2, 1) == 'Y') {
				// Producto escalar = x * 0 + y * -1 + z * 0
				$escalar = $resultado[1] * -1; 
			}
			if(substr($tipoOperacion, -2, 1) == 'Z') {
				// Producto escalar = x * 0 + y * 0 + z * -1
				$escalar = $resultado[2] * -1; 
			}

			$moduloV = sqrt(pow($resultado[0], 2) + pow($resultado[1], 2) + pow($resultado[2], 2));
			$moduloW = sqrt(1);
		
		}
		else {

			$escalar = $resultado[0] * $vectores[1][0] + $resultado[1] * $vectores[1][1] + $resultado[2] * $vectores[1][2]; 
			$moduloV = sqrt(pow($resultado[0], 2) + pow($resultado[1], 2) + pow($resultado[2], 2));
			$moduloW = sqrt(pow($vectores[1][0], 2) + pow($vectores[1][1], 2) + pow($vectores[1][2], 2));
			
		}

		// AnguloRad = Producto Escalar (v * w) / módulo vector v * módulo vector w. Lo redondeamos a 2 decimales.
		$anguloRad = acos($escalar/($moduloV * $moduloW));
		$anguloGrad = $anguloRad * 180 / M_PI;
		$solucion = round($anguloGrad, 2);
	}


	//Comparamos el resultado introducido por el usuario con el resultado calculado por el sistema y se devuelve una salida al respecto.
	if($_SESSION['contadorPreguntas'] == 10) {
		if($tipoOperacion == 'productoMixto' || $tipoOperacion == 'productoEscalar' || substr($tipoOperacion, 0, 14) == 'anguloRadianes' || substr($tipoOperacion, 0, 12) == 'anguloGrados' || $tipoOperacion == 'area' || $tipoOperacion == 'volumen') {
			if($respuesta == $solucion){
				$_SESSION['respuestasCorrectas'] ++;
				echo('2');
			}
			else {
				echo('3');
			}
		}
		else {
			if($x == $resultado[0] & $y == $resultado[1] & $z == $resultado[2]) {
			
				$_SESSION['respuestasCorrectas'] ++;
				echo('2');
			}
			else {
				echo('3');
			}
		}
		
	}
	else {
		if($tipoOperacion == 'productoMixto' || $tipoOperacion == 'productoEscalar' || substr($tipoOperacion, 0, 14) == 'anguloRadianes' || substr($tipoOperacion, 0, 12) == 'anguloGrados' || $tipoOperacion == 'area' || $tipoOperacion == 'volumen') {
			if($respuesta == $solucion){
				$_SESSION['respuestasCorrectas'] ++;
				echo('1');
			}
			else {
				echo('0');
			}
		}
		else {
			if($x == $resultado[0] & $y == $resultado[1] & $z == $resultado[2]) {
			
				$_SESSION['respuestasCorrectas'] ++;
				echo('1');
			}
			else {
				echo('0');
			}
		}
	}
?>