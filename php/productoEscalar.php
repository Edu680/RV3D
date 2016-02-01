<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//ES" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>Producto escalar de 2 vectores</title>
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
	var puntoV = new THREE.Vector3(4,3,2);
	var puntoW = new THREE.Vector3(-5,3,-3);
	var vectorVColor = 0x0000ff;
	var vectorWColor = 0xff0000;
	var vectorV = new THREE.Object3D();
	var vectorW = new THREE.Object3D();
	var productoEscalar;
	var ayudaVAB = new THREE.Object3D();
	var ayudaVCD = new THREE.Object3D();
	var lineasAuxEje = new THREE.Object3D(); 
	var numAuxEje = new THREE.Object3D();
				
	//Se crean y se añaden al escenario 2 vectores
	vectorV = crearVector(puntoOrigen,puntoV,vectorVColor);
	vectorW = crearVector(puntoOrigen,puntoW,vectorWColor);
	scene.add(vectorV);
	scene.add(vectorW);
						
				
	//Interfaz
	var anchuraGUI = $("#interfaz").width();
	var gui = new dat.GUI({
		autoPlace: false,
		width: anchuraGUI 
	});
	
	$("#interfaz").append(gui.domElement);
	//Parámetros a añadir a la interfaz
	var parametros = new function() {
		this.vABx = 4;
		this.vABy = 3;
		this.vABz = 2;
		this.vCDx = -5;
		this.vCDy = 3;
		this.vCDz = -3;
		this.v1color = "#0000ff";
		this.v2color = "#ff0000";
		this.moduloV = "";
		this.moduloW = "";
		this.angulo = "";
		this.anguloRad = "";
		this.productoEscalar = "";
		this.tamannoEscenario = "10";
		this.puntosAuxiliares = false;
		this.lineasAuxiliares = false;
		this.lineasAuxEjes = false;
		this.numAuxEjes = false;
		this.botonOperacion = function(){
			productoEscalar(puntoV, puntoW, parametros);
		}
	}
	
	//Campos de la interfaz para Vector v
	var folder1 = gui.addFolder('Vector v');
	var vABx = folder1.add( parametros, 'vABx').name('x');
	var vABy = folder1.add( parametros, 'vABy').name('y');
	var vABz = folder1.add( parametros, 'vABz').name('z');
	var moduloV = folder1.add(parametros, 'moduloV').name('Módulo').listen();
	folder1.open();
	
	//Se calcula el módulo del vector V y se asigna el valor a su campo 
	parametros.moduloV = puntoV.length();

	/*Si cambia el valor de la coordenada x del vector V, hay que repintar el vector y las ayudas visuales. */
	vABx.onChange(function(value) 
	{	puntoV.setX(value); 
		scene.remove(vectorV);
		scene.remove(ayudaVAB);
		vectorV = crearVector(puntoOrigen,puntoV,vectorVColor);
		ayudaVAB = ayudaVisual(puntoV,vectorVColor,puntosActivos,lineasActivas);
		scene.add(vectorV);
		scene.add(ayudaVAB);
	});
	/*Si cambia el valor de la coordenada y del vector V, hay que repintar el vector y las ayudas visuales. */
	vABy.onChange(function(value) 
	{   puntoV.setY(value); 
		scene.remove(vectorV);
		scene.remove(ayudaVAB);
		vectorV = crearVector(puntoOrigen,puntoV,vectorVColor);
		ayudaVAB = ayudaVisual(puntoV,vectorVColor,puntosActivos,lineasActivas);
		scene.add(vectorV);
		scene.add(ayudaVAB);
	});
	/*Si cambia el valor de la coordenada z del vector V, hay que repintar el vector y las ayudas visuales. */
	vABz.onChange(function(value) 
	{  	puntoV.setZ(value); 
		scene.remove(vectorV);
		scene.remove(ayudaVAB);
		vectorV = crearVector(puntoOrigen,puntoV,vectorVColor);
		ayudaVAB = ayudaVisual(puntoV,vectorVColor,puntosActivos,lineasActivas);
		scene.add(vectorV);
		scene.add(ayudaVAB);
	});
			
	//Color del vector V. Si cambia el color del vector a través de la paleta de colores, se actualiza el color en el escenario.
	var v1Color = folder1.addColor( parametros, 'v1color' ).name('Color').listen();
	v1Color.onChange(function(value) // onFinishChange
	{	vectorVColor = parseInt(value.replace("#", "0x"), 16);	
		scene.remove(vectorV);
		scene.remove(ayudaVAB);
		vectorV = crearVector(puntoOrigen,puntoV,vectorVColor);
		ayudaVAB = ayudaVisual(puntoV,vectorVColor,puntosActivos,lineasActivas);
		scene.add(vectorV);
		scene.add(ayudaVAB);		
	}); 

	//Campos de la interfaz para Vector w
	var folder2 = gui.addFolder('Vector w');
	var vCDx = folder2.add( parametros, 'vCDx').name('x');
	var vCDy = folder2.add( parametros, 'vCDy').name('y');
	var vCDz = folder2.add( parametros, 'vCDz').name('z');
	var moduloW = folder2.add(parametros, 'moduloW').name('Módulo').listen();
	folder2.open();

	//Se calcula el módulo del vector W y se asigna el valor a su campo 
	parametros.moduloW = puntoW.length();
		
	/*Si cambia el valor de la coordenada x del vector W, hay que repintar el vector y las ayudas visuales. */
	vCDx.onChange(function(value) 
	{	puntoW.setX(value); 
		scene.remove(vectorW);
		scene.remove(ayudaVCD);
		vectorW = crearVector(puntoOrigen,puntoW,vectorWColor);
		ayudaVCD = ayudaVisual(puntoW,vectorWColor,puntosActivos,lineasActivas);
		scene.add(vectorW);
		scene.add(ayudaVCD);
	});
	/*Si cambia el valor de la coordenada y del vector W, hay que repintar el vector y las ayudas visuales. */
	vCDy.onChange(function(value) 
	{   puntoW.setY(value); 
		scene.remove(vectorW);
		scene.remove(ayudaVCD);
		vectorW = crearVector(puntoOrigen,puntoW,vectorWColor);
		ayudaVCD = ayudaVisual(puntoW,vectorWColor,puntosActivos,lineasActivas);
		scene.add(vectorW);
		scene.add(ayudaVCD);
	});
	/*Si cambia el valor de la coordenada z del vector W, hay que repintar el vector y las ayudas visuales. */
	vCDz.onChange(function(value) 
	{  	puntoW.setZ(value); 
		scene.remove(vectorW);
		scene.remove(ayudaVCD);
		vectorW = crearVector(puntoOrigen,puntoW,vectorWColor);
		ayudaVCD = ayudaVisual(puntoW,vectorWColor,puntosActivos,lineasActivas);
		scene.add(vectorW);
		scene.add(ayudaVCD);
	});
	
	//Color del vector W. Si cambia el color del vector a través de la paleta de colores, se actualiza el color en el escenario.
	var v2Color = folder2.addColor( parametros, 'v2color' ).name('Color').listen();
	v2Color.onChange(function(value) // onFinishChange
	{	vectorWColor = parseInt(value.replace("#", "0x"), 16);	
		scene.remove(vectorW);
		scene.remove(ayudaVCD);
		vectorW = crearVector(puntoOrigen,puntoW,vectorWColor);
		ayudaVCD = ayudaVisual(puntoW,vectorWColor,puntosActivos,lineasActivas);
		scene.add(vectorW);
		scene.add(ayudaVCD);	
	}); 

	//Botón para calcular el producto escalar de los 2 vectores representados y el ángulo, tanto en grados como en radianes, que forman los 2 vectores.
	var botonOperacion = gui.add(parametros, 'botonOperacion').name('C A L C U L A R');
	var folder3 = gui.addFolder('Producto Escalar');
	var resultado = folder3.add(parametros, 'productoEscalar').name('V · W = ').listen();
	var angulo = folder3.add(parametros, 'angulo').name('Ángulo (grados)').listen();
	var anguloRad = folder3.add(parametros, 'anguloRad').name('Ángulo (radianes)').listen();
	
	folder3.open();

	//Campos de la interfaz para las Ayudas Visuales.
	var folder4 = gui.addFolder('Ayudas Visuales');
	//Cambiar tamaño del escenario
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
		scene.remove(ayudaVAB);
		scene.remove(ayudaVCD);
		ayudaVAB = ayudaVisual(puntoV,vectorVColor,puntosActivos,lineasActivas);
		ayudaVCD = ayudaVisual(puntoW,vectorWColor,puntosActivos,lineasActivas);
		scene.add(ayudaVAB);
		scene.add(ayudaVCD);
	}); 
	//Opción mostrar líneas auxiliares.	
	var mostrarlineasAuxiliares = folder4.add(parametros, 'lineasAuxiliares').name('Mostrar lineas auxiliares').listen();
	mostrarlineasAuxiliares.onChange(function(value) 
	{ 	lineasActivas = value;
		scene.remove(ayudaVAB);
		scene.remove(ayudaVCD);
		ayudaVAB = ayudaVisual(puntoV,vectorVColor,puntosActivos,lineasActivas);
		ayudaVCD = ayudaVisual(puntoW,vectorWColor,puntosActivos,lineasActivas);
		scene.add(ayudaVAB);
		scene.add(ayudaVCD);
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