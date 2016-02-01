<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//ES" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>Suma, resta y producto vectorial de 2 vectores</title>	
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
	
	//Declaración de variables
	var puntosActivos = false;
	var lineasActivas = false;
	var separadoresActivos = false;
	var numeracionActiva = false;
	var puntoOrigen = new THREE.Vector3(0,0,0);
	var puntoV = new THREE.Vector3(4,2,6);
	var puntoW = new THREE.Vector3(1,4,2);
	var extremoResultado = new THREE.Vector3();
	var vectorVColor = 0xff0000;
	var vectorWColor = 0xff0000;
	var resultadoColor = 0x0000ff;
	var ayudaVisualColor = 0xff0000;
	var indicadorOperacion = "Suma";
	var vectorV = new THREE.Object3D();
	var vectorW = new THREE.Object3D();
	var vectorResultado = new THREE.Object3D();
	var ayudaV1Start = new THREE.Object3D();
	var ayudaV1End = new THREE.Object3D();
	var ayudaV2Start = new THREE.Object3D();
	var ayudaV2End = new THREE.Object3D();
	var ayudaVSStart = new THREE.Object3D();
	var ayudaVSEnd = new THREE.Object3D();
	var lineasAuxEje = new THREE.Object3D(); 
	var numAuxEje = new THREE.Object3D();

	//Se crean y añaden 2 vectores iniciales al escenario.
	vectorV = crearVector(puntoOrigen,puntoV,vectorVColor);
	vectorW = crearVector(puntoOrigen,puntoW,vectorWColor);
	scene.add(vectorV);
	scene.add(vectorW);
	
	//Se crea y añade el vector suma de los 2 vectores iniciales, ya que "Suma" es la operación por defecto. 
	vectorResultado = operacionVectores(puntoV, puntoW, extremoResultado, resultadoColor, parametros, indicadorOperacion);
	scene.add(vectorResultado);
			
	
				
	//Interfaz
	var anchuraGUI = $("#interfaz").width();
	var gui = new dat.GUI({
		autoPlace: false,
		width: anchuraGUI 
	});
	
	$("#interfaz").append(gui.domElement);
	//Parámetros a añadir a la interfaz
	var parametros = new function() {
		this.pVx = 4;
		this.pVy = 2;
		this.pVz = 6;
		this.pWx = 1;
		this.pWy = 4;
		this.pWz = 2;
		this.vResultadoX = 5;
		this.vResultadoY = 6;
		this.vResultadoZ = 8;
		this.moduloV = " ";
		this.moduloW = " ";
		this.moduloResultado = " ";
		this.vVcolor = "#ff0000";
		this.vWcolor = "#ff0000";
		this.operacion = "Suma";
		this.tamannoEscenario = "10";
		this.puntosAuxiliares = false;
		this.lineasAuxiliares = false;
		this.auxColor = "#ff0000";
		this.lineasAuxEjes = false;
		this.numAuxEjes = false;
		this.mostrarPlano = false;
	}
		
	//Campos de la interfaz para vector v
	var folder1 = gui.addFolder('Vector v');					
	var pVx = folder1.add( parametros, 'pVx').name('x');
	var pVy = folder1.add( parametros, 'pVy').name('y');
	var pVz = folder1.add( parametros, 'pVz').name('z');
	var moduloV = folder1.add(parametros, 'moduloV').name('Módulo').listen();
	folder1.open();

	//Se calcula el módulo del vector v y se añade a su campo
	parametros.moduloV = puntoV.length();

	/*Si cambia el valor de la coordenada x del vector v, hay que modificar todo en lo que influya esta coordenada: posición del vector v, posición del
	vector resultado, módulo de V, módulo del vector resultado, puntos y líneas auxiliares para vector V y vector resultado*/
	pVx.onChange(function(value) 
	{	puntoV.setX(value); 
		scene.remove(vectorV);
		scene.remove(vectorResultado);
		scene.remove(ayudaV1End);
		scene.remove(ayudaV2End);
		scene.remove(ayudaVSEnd);
		vectorV = crearVector(puntoOrigen,puntoV,vectorVColor);
		parametros.moduloV = puntoV.length();
		vectorResultado = operacionVectores(puntoV, puntoW, extremoResultado, resultadoColor, parametros, indicadorOperacion);
		parametros.moduloResultado = extremoResultado.length();
		ayudaV1End = ayudaVisual(puntoV,vectorVColor,puntosActivos,lineasActivas);
		ayudaV2End = ayudaVisual(puntoW,vectorWColor,puntosActivos,lineasActivas);
		ayudaVSEnd = ayudaVisual(extremoResultado,resultadoColor,puntosActivos,lineasActivas);
		scene.add(vectorV);
		scene.add(vectorResultado);
		scene.add(ayudaV1End);
		scene.add(ayudaV2End);
		scene.add(ayudaVSEnd);
	});
	//Igual que coordenada x
	pVy.onChange(function(value) 
	{   puntoV.setY(value); 
		scene.remove(vectorV);
		scene.remove(vectorResultado);
		scene.remove(ayudaV1End);
		scene.remove(ayudaV2End);
		scene.remove(ayudaVSEnd);
		vectorV = crearVector(puntoOrigen,puntoV,vectorVColor);
		parametros.moduloV = puntoV.length();
		vectorResultado = operacionVectores(puntoV, puntoW, extremoResultado, resultadoColor, parametros, indicadorOperacion);
		parametros.moduloResultado = extremoResultado.length();
		ayudaV1End = ayudaVisual(puntoV,vectorVColor,puntosActivos,lineasActivas);
		ayudaV2End = ayudaVisual(puntoW,vectorWColor,puntosActivos,lineasActivas);
		ayudaVSEnd = ayudaVisual(extremoResultado,resultadoColor,puntosActivos,lineasActivas);
		scene.add(vectorV);
		scene.add(vectorResultado);
		scene.add(ayudaV1End);
		scene.add(ayudaV2End);
		scene.add(ayudaVSEnd);
	});
	//Igual que coordenada x
	pVz.onChange(function(value) 
	{  	puntoV.setZ(value); 
		scene.remove(vectorV);
		scene.remove(vectorResultado);
		scene.remove(ayudaV1End);
		scene.remove(ayudaV2End);
		scene.remove(ayudaVSEnd);
		vectorV = crearVector(puntoOrigen,puntoV,vectorVColor);
		parametros.moduloV = puntoV.length();
		vectorResultado = operacionVectores(puntoV, puntoW, extremoResultado, resultadoColor, parametros, indicadorOperacion);
		parametros.moduloResultado = extremoResultado.length();
		ayudaV1End = ayudaVisual(puntoV,vectorVColor,puntosActivos,lineasActivas);
		ayudaV2End = ayudaVisual(puntoW,vectorWColor,puntosActivos,lineasActivas);
		ayudaVSEnd = ayudaVisual(extremoResultado,resultadoColor,puntosActivos,lineasActivas);
		scene.add(vectorV);
		scene.add(vectorResultado);
		scene.add(ayudaV1End);
		scene.add(ayudaV2End);
		scene.add(ayudaVSEnd);
	});
	
	//Color del vector V. Si cambia el color del vector a través de la paleta de colores, se actualiza el color en el escenario.
	var vVColor = folder1.addColor( parametros, 'vVcolor' ).name('Color').listen();
	vVColor.onChange(function(value) // onFinishChange
	{	vectorVColor = parseInt(value.replace("#", "0x"), 16);	
		scene.remove(vectorV);
		scene.remove(ayudaV1End);
		vectorV = crearVector(puntoOrigen,puntoV,vectorVColor);
		ayudaV1End = ayudaVisual(puntoV,vectorVColor,puntosActivos,lineasActivas);
		scene.add(vectorV);
		scene.add(ayudaV1End);		
	}); 

	//Campos de la interfaz para vector w
	var folder2 = gui.addFolder('Vector w');					
	var pWx = folder2.add( parametros, 'pWx').name('x');
	var pWy = folder2.add( parametros, 'pWy').name('y');
	var pWz = folder2.add( parametros, 'pWz').name('z');
	var moduloW = folder2.add(parametros, 'moduloW').name('Módulo').listen();
	folder2.open();

	//Se calcula el módulo del vector w y se añade a su campo
	parametros.moduloW = puntoW.length();
			
	/*Si cambia el valor de la coordenada x del vector w, hay que modificar todo en lo que influya esta coordenada: posición del vector w, posición del
	vector resultado, módulo de W, módulo del vector resultado, puntos y líneas auxiliares para vector W y vector resultado*/
	pWx.onChange(function(value) 
	{	puntoW.setX(value); 
		scene.remove(vectorW);
		scene.remove(vectorResultado);
		scene.remove(ayudaV1End);
		scene.remove(ayudaV2End);
		scene.remove(ayudaVSEnd);
		vectorW = crearVector(puntoOrigen,puntoW,vectorWColor);
		parametros.moduloW = puntoW.length();
		vectorResultado = operacionVectores(puntoV, puntoW, extremoResultado, resultadoColor, parametros, indicadorOperacion);
		parametros.moduloResultado = extremoResultado.length();
		ayudaV1End = ayudaVisual(puntoV,vectorVColor,puntosActivos,lineasActivas);
		ayudaV2End = ayudaVisual(puntoW,vectorWColor,puntosActivos,lineasActivas);
		ayudaVSEnd = ayudaVisual(extremoResultado,resultadoColor,puntosActivos,lineasActivas);
		scene.add(vectorW);
		scene.add(vectorResultado);
		scene.add(ayudaV1End);
		scene.add(ayudaV2End);
		scene.add(ayudaVSEnd);
	});
	//Igual que coordenada x
	pWy.onChange(function(value) 
	{   puntoW.setY(value); 
		scene.remove(vectorW);
		scene.remove(vectorResultado);
		scene.remove(ayudaV1End);
		scene.remove(ayudaV2End);
		scene.remove(ayudaVSEnd);
		vectorW = crearVector(puntoOrigen,puntoW,vectorWColor);
		parametros.moduloW = puntoW.length();
		vectorResultado = operacionVectores(puntoV, puntoW, extremoResultado, resultadoColor, parametros, indicadorOperacion);
		parametros.moduloResultado = extremoResultado.length();
		ayudaV1End = ayudaVisual(puntoV,vectorVColor,puntosActivos,lineasActivas);
		ayudaV2End = ayudaVisual(puntoW,vectorWColor,puntosActivos,lineasActivas);
		ayudaVSEnd = ayudaVisual(extremoResultado,resultadoColor,puntosActivos,lineasActivas);
		scene.add(vectorW);
		scene.add(vectorResultado);
		scene.add(ayudaV1End);
		scene.add(ayudaV2End);
		scene.add(ayudaVSEnd);
	});
	//Igual que coordenada x
	pWz.onChange(function(value) 
	{  	puntoW.setZ(value); 
		scene.remove(vectorW);
		scene.remove(vectorResultado);
		scene.remove(ayudaV1End);
		scene.remove(ayudaV2End);
		scene.remove(ayudaVSEnd);
		vectorW = crearVector(puntoOrigen,puntoW,vectorWColor);
		parametros.moduloW = puntoW.length();
		vectorResultado = operacionVectores(puntoV, puntoW, extremoResultado, resultadoColor, parametros, indicadorOperacion);
		parametros.moduloResultado = extremoResultado.length();
		ayudaV1End = ayudaVisual(puntoV,vectorVColor,puntosActivos,lineasActivas);
		ayudaV2End = ayudaVisual(puntoW,vectorWColor,puntosActivos,lineasActivas);
		ayudaVSEnd = ayudaVisual(extremoResultado,resultadoColor,puntosActivos,lineasActivas);
		scene.add(vectorW);
		scene.add(vectorResultado);
		scene.add(ayudaV1End);
		scene.add(ayudaV2End);
		scene.add(ayudaVSEnd);
	});

	//Color del vector W. Si cambia el color del vector a través de la paleta de colores, se actualiza el color en el escenario.
	var vWColor = folder2.addColor( parametros, 'vWcolor' ).name('Color').listen();
	vWColor.onChange(function(value) // onFinishChange
	{	vectorWColor = parseInt(value.replace("#", "0x"), 16);	
		scene.remove(vectorW);
		scene.remove(ayudaV2End);
		vectorW = crearVector(puntoOrigen,puntoW,vectorWColor);
		ayudaV2End = ayudaVisual(puntoW,vectorWColor,puntosActivos,lineasActivas);
		scene.add(vectorW);
		scene.add(ayudaV2End);		
	}); 

	//Lista desplegable para elegir la operación a realizar. Disponibles: Suma, resta y producto vectorial.
	//Al elegir operación, se borra el vector resultado y se vuelve a dibujar como resultado de la nueva operación, al igual que sus ayudas visuales.
	var operacion = gui.add(parametros, 'operacion', ["Suma", "Resta", "Producto Cruz"]).name('Operación');
	operacion.onChange(function(value) 
	{   indicadorOperacion = value;
		scene.remove(vectorResultado);
		scene.remove(ayudaVSEnd);
		vectorResultado = operacionVectores(puntoV, puntoW, extremoResultado, resultadoColor, parametros, indicadorOperacion);
		parametros.moduloV = puntoV.length();
		parametros.moduloW = puntoW.length();
		parametros.moduloResultado = extremoResultado.length();
		ayudaVSEnd = ayudaVisual(extremoResultado,resultadoColor,puntosActivos,lineasActivas);
		scene.add(vectorResultado);
		scene.add(ayudaVSEnd);
	});

	//Campos de la interfaz para el vector resultado.
	var folder3 = gui.addFolder('Vector resultado');				
	var vResultadoX = folder3.add( parametros, 'vResultadoX').name('x').listen();
	var vResultadoY = folder3.add( parametros, 'vResultadoY').name('y').listen();
	var vResultadoZ = folder3.add( parametros, 'vResultadoZ').name('z').listen();
	var moduloResultado = folder3.add(parametros, 'moduloResultado').name('Módulo').listen();
	folder3.open();

	//Se calcula y se añade el módulo a su campo correspondiente.
	parametros.moduloResultado = extremoResultado.length();
		
	//Campos de la interfaz para Ayudas visuales.
	var folder4 = gui.addFolder('Ayudas Visuales');
	//Lista desplegable para cambiar el tamaño del escenario.
	var cambiarTamannoEscenario = folder4.add(parametros, 'tamannoEscenario', ["10", "20", "40", "60"]).name('Tamaño escenario');
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
		camera.position.set(-0.3*size,size/2,2*size);
			
	});
	//Opción mostrar puntos auxiliares.
	var mostrarPuntosAuxiliares = folder4.add(parametros, 'puntosAuxiliares').name('Mostrar puntos auxiliares').listen();
	mostrarPuntosAuxiliares.onChange(function(value) 
	{ 	puntosActivos = value;
		scene.remove(ayudaV1End);
		scene.remove(ayudaV2End);
		scene.remove(ayudaVSEnd);
		ayudaV1End = ayudaVisual(puntoV,vectorVColor,puntosActivos,lineasActivas);
		ayudaV2End = ayudaVisual(puntoW,vectorWColor,puntosActivos,lineasActivas);
		ayudaVSEnd = ayudaVisual(extremoResultado,resultadoColor,puntosActivos,lineasActivas);
		scene.add(ayudaV1End);
		scene.add(ayudaV2End);
		scene.add(ayudaVSEnd);
	}); 
	//Opción mostrar líneas auxiliares			
	var mostrarlineasAuxiliares = folder4.add(parametros, 'lineasAuxiliares').name('Mostrar lineas auxiliares').listen();
	mostrarlineasAuxiliares.onChange(function(value) 
	{ 	lineasActivas = value;
		scene.remove(ayudaV1End);
		scene.remove(ayudaV2End);
		scene.remove(ayudaVSEnd);
		ayudaV1End = ayudaVisual(puntoV,vectorVColor,puntosActivos,lineasActivas);
		ayudaV2End = ayudaVisual(puntoW,vectorWColor,puntosActivos,lineasActivas);
		ayudaVSEnd = ayudaVisual(extremoResultado,resultadoColor,puntosActivos,lineasActivas);
		scene.add(ayudaV1End);
		scene.add(ayudaV2End);
		scene.add(ayudaVSEnd);
	}); 
	//Opción mostrar separadores ejes de coordenadas.
	var mostrarSeparadorEjes = folder4.add(parametros, 'lineasAuxEjes').name('Separación ejes').listen();
	mostrarSeparadorEjes.onChange(function(value)
	{	separadoresActivos = value;
		scene.remove(lineasAuxEje);
		lineasAuxEje = lineasEjes(separadoresActivos);
		scene.add(lineasAuxEje);
	});
	//Opción mostrar numeración ejes de coordenadas
	var mostrarNumeracionEjes = folder4.add(parametros, 'numAuxEjes').name('Numeración ejes').listen();
	mostrarNumeracionEjes.onChange(function(value)
	{	numeracionActiva = value;
		scene.remove(numAuxEje);
		numAuxEje = numEjes(numeracionActiva);
		scene.add(numAuxEje);
	});
	
	gui.open();
				
</script>