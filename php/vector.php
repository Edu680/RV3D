<!--<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//ES" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">-->
	<head>
		<title>Vector</title>
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
	var opuestoActivo = false;
	var descomponerActivo = false;
	var puntoOrigen = new THREE.Vector3(0,0,0);
	var puntoV = new THREE.Vector3(6,4,6);
	var puntoX = new THREE.Vector3(6,0,0);
	var puntoY = new THREE.Vector3(0,4,0);
	var puntoZ = new THREE.Vector3(0,0,6);
	var puntoOpuesto = new THREE.Vector3(-6,-4,-6);
	var vectorColor = 0xff0000;
	var opuestoColor = 0x0000ff;
	var ayudaVisualColor = 0xff0000;
	var ayudaOpuestoColor = 0x0000ff;
	var vector = new THREE.Object3D();
	var opuesto = new THREE.Object3D();
	var componenteX = new THREE.Object3D();
	var componenteY = new THREE.Object3D();
	var componenteZ = new THREE.Object3D();
	var ayudaVector = new THREE.Object3D();
	var ayudaOpuesto = new THREE.Object3D();
	var lineasAuxEje = new THREE.Object3D(); 
	var numAuxEje = new THREE.Object3D();
				
	//Se crea un vector.
	vector = crearVector(puntoOrigen,puntoV,vectorColor);
	//Se crea un vector opuesto al vector anterior.
	opuesto = crearVector2(puntoOrigen,puntoOpuesto,opuestoColor,opuestoActivo);
	//Se crean las componentes cartesianas de la descomposición del vector creado.
	componenteX = crearVector2(puntoOrigen,puntoX,vectorColor,descomponerActivo);
	componenteY = crearVector2(puntoOrigen,puntoY,vectorColor,descomponerActivo);
	componenteZ = crearVector2(puntoOrigen,puntoZ,vectorColor,descomponerActivo);
	//Se añade el vector al escenario.
	scene.add(vector);
	//Se añade el vector opuesto al escenario.
	scene.add(opuesto);
	//Se añaden las componentes cartesianas del vector al escenario.
	scene.add(componenteX);
	scene.add(componenteY);
	scene.add(componenteZ);
		
				
	//Interfaz
	var anchuraGUI = $("#interfaz").width();
	var gui = new dat.GUI({
		autoPlace: false,
		width: anchuraGUI
	});
	
	$("#interfaz").append(gui.domElement);
	//Parámetros a añadir a la interfaz
	var parametros = new function() {
		this.x = 6;
		this.y = 4;
		this.z = 6;
		this.xOpuesto = -6;
		this.yOpuesto = -4;
		this.zOpuesto = -6;
		this.modulo = "";
		this.moduloOp = "";
		this.alpha = "";
		this.betha = "";
		this.gamma = "";
		this.alphaRad = "";
		this.bethaRad = "";
		this.gammaRad = "";
		this.color = "#ff0000";	// color (change "#" to "0x")
		this.auxColor = "#ff0000";
		this.mostrarOpuesto = false;
		this.descomponerVector = false;
		this.puntosAuxiliares = false;
		this.lineasAuxiliares = false;
		this.lineasAuxEjes = false;
		this.numAuxEjes = false;
		
	}
	
	//Pestañas y campos de la interfaz.
	var folder1 = gui.addFolder('Vector');
	var x = folder1.add( parametros, 'x');
	var y = folder1.add( parametros, 'y');
	var z = folder1.add( parametros, 'z');
	var modulo = folder1.add(parametros, 'modulo').name('Módulo').listen();
	var angDirectores = folder1.addFolder('Ángulos directores');
	var grados = angDirectores.addFolder('Grados');
	var alpha = grados.add(parametros, 'alpha').listen();
	var betha = grados.add(parametros, 'betha').listen();
	var gamma = grados.add(parametros, 'gamma').listen();
	var radianes = angDirectores.addFolder('Radianes');
	var alphaRad = radianes.add(parametros, 'alphaRad').name('alpha').listen();
	var bethaRad = radianes.add(parametros, 'bethaRad').name('betha').listen();
	var gammaRad = radianes.add(parametros, 'gammaRad').name('gamma').listen();
	angDirectores.open();
	grados.open();
	folder1.open();
			
	//Se calcula el módulo del vector V y se asigna al campo de la interfaz
	parametros.modulo = puntoV.length();
	//Se calculan los ángulos directores del vector V y se asigna el valor a sus correspondientes campos de la interfaz.
	parametros.alphaRad = Math.acos(puntoV.x / puntoV.length());
	parametros.bethaRad = Math.acos(puntoV.y / puntoV.length());
	parametros.gammaRad = Math.acos(puntoV.z / puntoV.length());
	parametros.alpha = parametros.alphaRad * 180 / Math.PI;
	parametros.betha = parametros.bethaRad * 180 / Math.PI;
	parametros.gamma = parametros.gammaRad * 180 / Math.PI;
	
	/*Cada vez que el usuario modifica la coordenada x del vector, se actualiza el escenario y la interfaz para la coordenada x, coordenada x del vector
	opuesto, componente X de la descomposición del vector en componentes cartesianas, el módulo del vector, módulo del vector opuesto, ángulos
	directores.*/
	x.onChange(function(value)
	{	puntoV.setX(value);
		puntoOpuesto.setX(-value);
		puntoX.setX(value);
		parametros.xOpuesto = puntoOpuesto.x;
		scene.remove(vector);
		scene.remove(opuesto);
		scene.remove(componenteX);
		scene.remove(componenteY);
		scene.remove(componenteZ);
		scene.remove(ayudaVector);
		scene.remove(ayudaOpuesto);
		vector = crearVector(puntoOrigen,puntoV,vectorColor);
		opuesto = crearVector2(puntoOrigen,puntoOpuesto,opuestoColor,opuestoActivo);
		componenteX = crearVector2(puntoOrigen,puntoX,vectorColor,descomponerActivo);
		componenteY = crearVector2(puntoOrigen,puntoY,vectorColor,descomponerActivo);
		componenteZ = crearVector2(puntoOrigen,puntoZ,vectorColor,descomponerActivo);
		parametros.modulo = puntoV.length();
		parametros.moduloOp = puntoOpuesto.length();
		parametros.alphaRad = Math.acos(puntoV.x / puntoV.length());
		parametros.bethaRad = Math.acos(puntoV.y / puntoV.length());
		parametros.gammaRad = Math.acos(puntoV.z / puntoV.length());
		parametros.alpha = parametros.alphaRad * 180 / Math.PI;
		parametros.betha = parametros.bethaRad * 180 / Math.PI;
		parametros.gamma = parametros.gammaRad * 180 / Math.PI;
		ayudaVector = ayudaVisual(puntoV,ayudaVisualColor,puntosActivos,lineasActivas);
		ayudaOpuesto = ayudaVisual2(puntoOpuesto,ayudaOpuestoColor,puntosActivos,lineasActivas,opuestoActivo);
		scene.add(vector);
		scene.add(opuesto);
		scene.add(ayudaVector);
		scene.add(ayudaOpuesto);
		scene.add(componenteX);
		scene.add(componenteY);
		scene.add(componenteZ);
	});
	//Mismo caso que para coordenada x
	y.onChange(function(value) 
	{   puntoV.setY(value);
		puntoOpuesto.setY(-value);
		puntoY.setY(value);
		parametros.yOpuesto = puntoOpuesto.y;
		scene.remove(vector);
		scene.remove(opuesto);
		scene.remove(componenteX);
		scene.remove(componenteY);
		scene.remove(componenteZ);
		scene.remove(ayudaVector);
		scene.remove(ayudaOpuesto);
		vector = crearVector(puntoOrigen,puntoV,vectorColor);
		opuesto = crearVector2(puntoOrigen,puntoOpuesto,opuestoColor,opuestoActivo);
		componenteX = crearVector2(puntoOrigen,puntoX,vectorColor,descomponerActivo);
		componenteY = crearVector2(puntoOrigen,puntoY,vectorColor,descomponerActivo);
		componenteZ = crearVector2(puntoOrigen,puntoZ,vectorColor,descomponerActivo);
		parametros.modulo = puntoV.length();
		parametros.moduloOp = puntoOpuesto.length();
		parametros.alphaRad = Math.acos(puntoV.x / puntoV.length());
		parametros.bethaRad = Math.acos(puntoV.y / puntoV.length());
		parametros.gammaRad = Math.acos(puntoV.z / puntoV.length());
		parametros.alpha = parametros.alphaRad * 180 / Math.PI;
		parametros.betha = parametros.bethaRad * 180 / Math.PI;
		parametros.gamma = parametros.gammaRad * 180 / Math.PI;
		ayudaVector = ayudaVisual(puntoV,ayudaVisualColor,puntosActivos,lineasActivas);
		ayudaOpuesto = ayudaVisual2(puntoOpuesto,ayudaOpuestoColor,puntosActivos,lineasActivas,opuestoActivo);
		scene.add(vector);
		scene.add(opuesto);
		scene.add(ayudaVector);
		scene.add(ayudaOpuesto);
		scene.add(componenteX);
		scene.add(componenteY);
		scene.add(componenteZ);
	});
	//Mismo caso que para coordenada x
	z.onChange(function(value) 
	{   puntoV.setZ(value);
		puntoOpuesto.setZ(-value);
		puntoZ.setZ(value);
		parametros.zOpuesto = puntoOpuesto.z;
		scene.remove(vector);
		scene.remove(opuesto);
		scene.remove(componenteX);
		scene.remove(componenteY);
		scene.remove(componenteZ);
		scene.remove(ayudaVector);
		scene.remove(ayudaOpuesto);
		vector = crearVector(puntoOrigen,puntoV,vectorColor);
		opuesto = crearVector2(puntoOrigen,puntoOpuesto,opuestoColor,opuestoActivo);
		componenteX = crearVector2(puntoOrigen,puntoX,vectorColor,descomponerActivo);
		componenteY = crearVector2(puntoOrigen,puntoY,vectorColor,descomponerActivo);
		componenteZ = crearVector2(puntoOrigen,puntoZ,vectorColor,descomponerActivo);
		parametros.modulo = puntoV.length();
		parametros.moduloOp = puntoOpuesto.length();
		parametros.alphaRad = Math.acos(puntoV.x / puntoV.length());
		parametros.bethaRad = Math.acos(puntoV.y / puntoV.length());
		parametros.gammaRad = Math.acos(puntoV.z / puntoV.length());
		parametros.alpha = parametros.alphaRad * 180 / Math.PI;
		parametros.betha = parametros.bethaRad * 180 / Math.PI;
		parametros.gamma = parametros.gammaRad * 180 / Math.PI;
		ayudaVector = ayudaVisual(puntoV,ayudaVisualColor,puntosActivos,lineasActivas);
		ayudaOpuesto = ayudaVisual2(puntoOpuesto,ayudaOpuestoColor,puntosActivos,lineasActivas,opuestoActivo);
		scene.add(vector);
		scene.add(opuesto);
		scene.add(ayudaVector);
		scene.add(ayudaOpuesto);
		scene.add(componenteX);
		scene.add(componenteY);
		scene.add(componenteZ);
	});
	
	//Color del vector. Si cambia el color del vector a través de la paleta de colores, se actualiza el color en el escenario.
	var vColor = gui.addColor( parametros, 'color' ).name('Color').listen();
	vColor.onChange(function(value) // onFinishChange
	{	vectorColor = parseInt(value.replace("#", "0x"), 16);	
		scene.remove(vector);
		vector = crearVector(puntoOrigen,puntoV,vectorColor);
		scene.add(vector);
	}); 
		
	//Campos de la interfaz para el vector opuesto.
	var folder2 = gui.addFolder('Vector opuesto');
	var xop = folder2.add( parametros, 'xOpuesto').name('x').listen();
	var yop = folder2.add( parametros, 'yOpuesto').name('y').listen();
	var zop = folder2.add( parametros, 'zOpuesto').name('z').listen();
	var moduloOp = folder2.add(parametros, 'moduloOp').name('Módulo').listen();
	folder2.open();

	//Se calcula el módulo del vector opuesto y se asigna al campo de la interfaz
	parametros.moduloOp = puntoOpuesto.length();

	//Campos de la interfaz para Ayudas Visuales
	var folder4 = gui.addFolder('Ayudas Visuales');
	//Opción descomponer vector en componentes cartesianas
	var descomponerVector = folder4.add(parametros, 'descomponerVector').name('Descomponer vector').listen();
	descomponerVector.onChange(function(value)
	{	descomponerActivo = value;
		scene.remove(componenteX);
		scene.remove(componenteY);
		scene.remove(componenteZ);
		componenteX = crearVector2(puntoOrigen,puntoX,vectorColor,descomponerActivo);
		componenteY = crearVector2(puntoOrigen,puntoY,vectorColor,descomponerActivo);
		componenteZ = crearVector2(puntoOrigen,puntoZ,vectorColor,descomponerActivo);
		scene.add(componenteX);
		scene.add(componenteY);
		scene.add(componenteZ);
	});
	//Opción mostrar vector opuesto.
	var mostrarOpuesto = folder4.add(parametros, 'mostrarOpuesto').name('Mostrar Opuesto').listen();
	mostrarOpuesto.onChange(function(value)
	{	opuestoActivo = value;
		scene.remove(opuesto);
		scene.remove(ayudaOpuesto);
		opuesto = crearVector2(puntoOrigen,puntoOpuesto,opuestoColor,opuestoActivo);
		ayudaOpuesto = ayudaVisual2(puntoOpuesto,ayudaOpuestoColor,puntosActivos,lineasActivas,opuestoActivo);
		scene.add(opuesto);
		scene.add(ayudaOpuesto);
	
	});
	//Opción mostrar puntos auxiliares.
	var mostrarPuntosAuxiliares = folder4.add(parametros, 'puntosAuxiliares').name('Mostrar puntos auxiliares').listen();
	mostrarPuntosAuxiliares.onChange(function(value) 
	{ 	puntosActivos = value;
		scene.remove(ayudaVector);
		scene.remove(ayudaOpuesto);
		ayudaVector = ayudaVisual(puntoV,ayudaVisualColor,puntosActivos,lineasActivas);
		ayudaOpuesto = ayudaVisual2(puntoOpuesto,ayudaOpuestoColor,puntosActivos,lineasActivas,opuestoActivo);
		scene.add(ayudaVector);
		scene.add(ayudaOpuesto);
	}); 
	//Opción mostrar líneas auxiliares.
	var mostrarlineasAuxiliares = folder4.add(parametros, 'lineasAuxiliares').name('Mostrar lineas auxiliares').listen();
	mostrarlineasAuxiliares.onChange(function(value) 
	{ 	lineasActivas = value;
		scene.remove(ayudaVector);
		scene.remove(ayudaOpuesto);
		ayudaVector = ayudaVisual(puntoV,ayudaVisualColor,puntosActivos,lineasActivas);
		ayudaOpuesto = ayudaVisual2(puntoOpuesto,ayudaOpuestoColor,puntosActivos,lineasActivas,opuestoActivo);
		scene.add(ayudaVector);
		scene.add(ayudaOpuesto);
	}); 
				
	//Color para las líneas y puntos auxiliares
	var auxColor = folder4.addColor( parametros, 'auxColor' ).name('Color').listen();
	auxColor.onChange(function(value) // onFinishChange
	{	ayudaVisualColor = parseInt(value.replace("#", "0x"), 16);
		scene.remove(ayudaVector);
		ayudaVector = ayudaVisual(puntoV,ayudaVisualColor,puntosActivos,lineasActivas);
		scene.add(ayudaVector);
	}); 

	//Opción mostrar separación ejes de coordenadas
	var mostrarSeparadorEjes = folder4.add(parametros, 'lineasAuxEjes').name('Separación ejes').listen();
	mostrarSeparadorEjes.onChange(function(value)
	{	separadoresActivos = value;
		scene.remove(lineasAuxEje);
		lineasAuxEje = lineasEjes(separadoresActivos);
		scene.add(lineasAuxEje);
	});

	//Opción mostrar numeración ejes de coordenadas.
	var mostrarNumeracionEjes = folder4.add(parametros, 'numAuxEjes').name('Numeración ejes').listen();
	mostrarNumeracionEjes.onChange(function(value)
	{	numeracionActiva = value;
		scene.remove(numAuxEje);
		numAuxEje = numEjes(numeracionActiva);
		scene.add(numAuxEje);
	});

	folder4.open();
	gui.open();
				
</script>