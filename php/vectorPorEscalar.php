<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//ES" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>Vector por un escalar</title>	
		<script type="text/javascript">
			$("#contenedor").css("height", "100%");
		</script>
		<script type="text/javascript" src="../js/escenaInicial.js"></script>
	</head>

	<body>
        	<div id="grafico" class="ui-corner-all">
            	<div id="inset" class="ui-corner-all"></div>
				<div id="stats"></div>
           	</div>
            <div id="interfaz" class="ui-corner-all">
            </div>
	</body>
</html>


<script>
	
	//Declaración de variables.
	var puntosActivos = false;
	var lineasActivas = false;
	var separadoresActivos = false;
	var numeracionActiva = false;
	var origenVector = new THREE.Vector3(0,0,0);
	var extremoVector = new THREE.Vector3(4,3,2);
	var extremoResultado = new THREE.Vector3();
	var vectorABColor = 0x0000ff;
	var vectorResultadoColor = 0xff0000;
	var ayudaVisualColor = 0xff0000;
	var vectorAB = new THREE.Object3D();
	var vectorResultado = new THREE.Object3D();
	var escalar;
	var ayudaV1End = new THREE.Object3D();
	var ayudaVREnd = new THREE.Object3D();
	var lineasAuxEje = new THREE.Object3D(); 
	var numAuxEje = new THREE.Object3D();
				
	//Se crea y añade al escenario un vector
	vectorAB = crearVector(origenVector,extremoVector,vectorABColor);
	scene.add(vectorAB);
			
				
				
	//Interfaz
	var anchuraGUI = $("#interfaz").width();
	var gui = new dat.GUI({
		autoPlace: false,
		width: anchuraGUI 
	});
	
	$("#interfaz").append(gui.domElement);
	//Parámetros a añadir a la interfaz
	var parametros = 
	{
		vABx: 4, vABy: 3, vABz: 2,
		vrx: "", vry: "", vrz: "",
		escalar: 0,
		moduloV: " ",
		moduloResultado: " ",
		v1color: "#0000ff", // color (change "#" to "0x")
		tamannoEscenario: "10",
		puntosAuxiliares: false,
		lineasAuxiliares: false,
		auxColor: "#ff0000",
		lineasAuxEjes: false,
		numAuxEjes: false,
		botonOperacion: function(){ 
			calcular();
		}
	};
	
	//Campos de la interfaz para Vector v
	var folder1 = gui.addFolder('Vector v');
	var folder3 = folder1.addFolder('Coordenadas');
	var vABx = folder3.add( parametros, 'vABx').name('x');
	var vABy = folder3.add( parametros, 'vABy').name('y');
	var vABz = folder3.add( parametros, 'vABz').name('z');
	var moduloV = folder1.add(parametros, 'moduloV').name('Módulo').listen();
	folder3.open();
	folder1.open();
				
	//Se calcula el módulo del vector V y se asigna el valor a su campo 
	parametros.moduloV = extremoVector.length();

	/*Si cambia el valor de la coordenada x, hay que repintar el vector y las ayudas visuales. */
	vABx.onChange(function(value) 
	{	extremoVector.setX(value); 
		scene.remove(vectorAB);
		scene.remove(vectorResultado);
		scene.remove(ayudaV1End);
		scene.remove(ayudaVREnd);
		vectorAB = crearVector(origenVector,extremoVector,vectorABColor);
		ayudaV1End = ayudaVisual(extremoVector,vectorABColor,puntosActivos,lineasActivas);
		scene.add(vectorAB);
		scene.add(ayudaV1End);
	});
	//Igual para coordenada y
	vABy.onChange(function(value) 
	{   extremoVector.setY(value); 
		scene.remove(vectorAB);
		scene.remove(vectorResultado);
		scene.remove(ayudaV1End);
		scene.remove(ayudaVREnd);
		vectorAB = crearVector(origenVector,extremoVector,vectorABColor);
		ayudaV1End = ayudaVisual(extremoVector,vectorABColor,puntosActivos,lineasActivas);
		scene.add(vectorAB);
		scene.add(ayudaV1End);
	});
	//Igual para coordenada z
	vABz.onChange(function(value) 
	{  	extremoVector.setZ(value); 
		scene.remove(vectorAB);
		scene.remove(vectorResultado);
		scene.remove(ayudaV1End);
		scene.remove(ayudaVREnd);
		vectorAB = crearVector(origenVector,extremoVector,vectorABColor);
		ayudaV1End = ayudaVisual(extremoVector,vectorABColor,puntosActivos,lineasActivas);
		scene.add(vectorAB);
		scene.add(ayudaV1End);
	});
			
	//Color del vector V. Si cambia el color del vector a través de la paleta de colores, se actualiza el color en el escenario.
	var v1Color = folder1.addColor( parametros, 'v1color' ).name('Color Vector v').listen();
	v1Color.onChange(function(value) // onFinishChange
	{	vectorABColor = parseInt(value.replace("#", "0x"), 16);	
		scene.remove(vectorAB);
		scene.remove(ayudaV1End);
		vectorAB = crearVector(origenVector,extremoVector,vectorABColor);
		ayudaV1End = ayudaVisual(extremoVector,vectorABColor,puntosActivos,lineasActivas);
		scene.add(vectorAB);
		scene.add(ayudaV1End);		
	}); 

	//Campo de la interfaz para el escalar
	var variableEscalar = gui.add(parametros, 'escalar').name('Escalar');
	variableEscalar.onChange(function(value)
	{	escalar = value;
		
	});
	//Botón para calcular el vector resultado del producto del vector del escenario por el escalar introducido mediante la interfaz.
	var botonOperacion = gui.add(parametros, 'botonOperacion').name('C A L C U L A R');
	
	//Campos de la interfaz para el vector resultado.
	var folder7 = gui.addFolder('Vector resultado');
	var folder9 = folder7.addFolder('Coordenadas');
	var vrx = folder9.add( parametros, 'vrx').name('x').listen();
	var vry = folder9.add( parametros, 'vry').name('y').listen();
	var vrz = folder9.add( parametros, 'vrz').name('z').listen();
	var moduloResultado = folder7.add(parametros, 'moduloResultado').name('Módulo').listen();
	folder9.open();
	folder7.open();
		
	//Color del vector resultado.
	var auxColor = folder7.addColor( parametros, 'auxColor' ).name('Color Vector Resultado').listen();
	auxColor.onChange(function(value) // onFinishChange
	{	vectorResultadoColor = parseInt(value.replace("#", "0x"), 16);
		scene.remove(vectorResultado);
		scene.remove(ayudaVREnd);
		vectorResultado = vectorPorEscalar(origenVector, extremoVector, escalar, extremoResultado, vectorResultadoColor, parametros);
		ayudaVREnd = ayudaVisual(extremoResultado,vectorResultadoColor,puntosActivos,lineasActivas);
		scene.add(vectorResultado);
		scene.add(ayudaVREnd);
	}); 

	//Campos de la interfaz para las Ayudas Visuales.
	var folder11 = gui.addFolder('Ayudas Visuales');
	var cambiarTamannoEscenario = folder11.add(parametros, 'tamannoEscenario', ["10", "20", "40", "60"]).name('Tamaño escenario');
	cambiarTamannoEscenario.onChange(function(value)
	{	size = parseInt(value);
		scene.remove(escenario);
		scene.remove(lineasAuxEje);
		scene.remove(numAuxEje);
		escenario = escalaEscenario();
		lineasAuxEje = lineasEjes(separadoresActivos);
		numAuxEje = numEjes(numeracionActiva);
		scene.add(escenario);
		scene.add(lineasAuxEje);
		scene.add(numAuxEje);
		//Se le asigna una nueva posición a la cámara para que se pueda observar en buena perspectiva todo el escenario una vez se ha cambiado su tamaño.
		camera.position.set(-0.3*size,size/2,2*size);
			
	});

	//Opción mostrar puntos auxiliares.
	var mostrarPuntosAuxiliares = folder11.add(parametros, 'puntosAuxiliares').name('Mostrar puntos auxiliares').listen();
	mostrarPuntosAuxiliares.onChange(function(value) 
	{ 	puntosActivos = value;
		scene.remove(ayudaV1End);
		scene.remove(ayudaVREnd);
		ayudaV1End = ayudaVisual(extremoVector,vectorABColor,puntosActivos,lineasActivas);
		ayudaVREnd = ayudaVisual(extremoResultado,vectorResultadoColor,puntosActivos,lineasActivas);
		scene.add(ayudaV1End);
		scene.add(ayudaVREnd);
	}); 
	//Opción mostrar líneas auxiliares.
	var mostrarlineasAuxiliares = folder11.add(parametros, 'lineasAuxiliares').name('Mostrar lineas auxiliares').listen();
	mostrarlineasAuxiliares.onChange(function(value) 
	{ 	lineasActivas = value;
		scene.remove(ayudaV1End);
		scene.remove(ayudaVREnd);
		ayudaV1End = ayudaVisual(extremoVector,vectorABColor,puntosActivos,lineasActivas);
		ayudaVREnd = ayudaVisual(extremoResultado,vectorResultadoColor,puntosActivos,lineasActivas);
		scene.add(ayudaV1End);
		scene.add(ayudaVREnd);
	}); 
	//Opción mostrar separadores ejes de coordenadas.
	var mostrarSeparadorEjes = folder11.add(parametros, 'lineasAuxEjes').name('Separación ejes').listen();
	mostrarSeparadorEjes.onChange(function(value)
	{	separadoresActivos = value;
		scene.remove(lineasAuxEje);
		lineasAuxEje = lineasEjes(separadoresActivos);
		scene.add(lineasAuxEje);
	});
	//Opción mostrar numeración ejes de coordenadas
	var mostrarNumeracionEjes = folder11.add(parametros, 'numAuxEjes').name('Numeración ejes').listen();
	mostrarNumeracionEjes.onChange(function(value)
	{	numeracionActiva = value;
		scene.remove(numAuxEje);
		numAuxEje = numEjes(numeracionActiva);
		scene.add(numAuxEje);
	});
	
	gui.open();
		
	//Función que calcula el vector resultado y el módulo de cada vector.
	function calcular(){
		scene.remove(vectorResultado);
		scene.remove(ayudaVREnd);
		vectorResultado = vectorPorEscalar(origenVector, extremoVector, escalar, extremoResultado, vectorResultadoColor, parametros);
		ayudaVREnd = ayudaVisual(extremoResultado,vectorResultadoColor,puntosActivos,lineasActivas);
		parametros.moduloV = extremoVector.length();
		parametros.moduloResultado = extremoResultado.length();
		scene.add(vectorResultado);
		scene.add(ayudaVREnd);
	}
</script>