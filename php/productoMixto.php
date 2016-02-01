<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//ES" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>Producto Mixto</title>	
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
	var puntoU = new THREE.Vector3(5,6,8);
	var puntoV = new THREE.Vector3(-4,2,-6);
	var puntoW = new THREE.Vector3(-1,-7,5);
	var productoCruz = new THREE.Vector3();
	var vectorUColor = 0xff0000;
	var vectorVColor = 0x0000ff;
	var vectorWColor = 0x00ff00;
	var prodCruzColor = 0x00ffff;
	var ayudaVisualColor = 0xff0000;
	var vectorU = new THREE.Object3D();
	var vectorV = new THREE.Object3D();
	var vectorW = new THREE.Object3D();
	var vectorProdCruz = new THREE.Object3D();
	var ayudaVU = new THREE.Object3D();
	var ayudaVV = new THREE.Object3D();
	var ayudaVW = new THREE.Object3D();
	var ayudaProdCruz = new THREE.Object3D();
	var lineasAuxEje = new THREE.Object3D(); 
	var numAuxEje = new THREE.Object3D();
				
	//Se crean y añaden al escenario 3 vectores.
	vectorU = crearVector(puntoOrigen,puntoU,vectorUColor);
	vectorV = crearVector(puntoOrigen,puntoV,vectorVColor);
	vectorW = crearVector(puntoOrigen,puntoW,vectorWColor);
		
	scene.add(vectorU);
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
	var parametros = 
	{
		pUx: 5, pUy: 6, pUz: 8,
		pVx: -4, pVy: 2, pVz: -6,
		pWx: -1, pWy: -7, pWz: 5,
		vUcolor: "#ff0000", // color (change "#" to "0x")
		vVcolor: "#0000ff",
		vWcolor: "#00ff00",
		vRcolor: "#00ffff",
		pCx: "", pCy: "", pCz: "",
		moduloU: "",
		moduloV: "",
		moduloW: "",
		moduloCruz: "",
		anguloVW: "",
		anguloVWRad: "",
		anguloUyVW: "",
		anguloUyVWRad: "",
		productoMixto: "",
		tamannoEscenario: "10",
		puntosAuxiliares: false,
		lineasAuxiliares: false,
		lineasAuxEjes: false,
		numAuxEjes: false,
		botonOperacion: function(){ 
			calcular();
		}
	};
				
	//Campos de la interfaz para Vector u
	var folder1 = gui.addFolder('Vector u');
	var pUx = folder1.add( parametros, 'pUx').name('x');
	var pUy = folder1.add( parametros, 'pUy').name('y');
	var pUz = folder1.add( parametros, 'pUz').name('z');
	var moduloU = folder1.add(parametros, 'moduloU').name('Módulo Vector U').listen();
	//Se calcula el módulo del vector U y se asigna el valor a su campo 
	parametros.moduloU = puntoU.length();

	/*Si cambia el valor de la coordenada x del vector U, hay que repintar el vector y las ayudas visuales. */
	pUx.onChange(function(value) 
	{	puntoU.setX(value); 
		scene.remove(vectorU);
		scene.remove(ayudaVU);
		scene.remove(vectorProdCruz);
		scene.remove(ayudaProdCruz);
		vectorU = crearVector(puntoOrigen,puntoU,vectorUColor);
		ayudaVU = ayudaVisual(puntoU,vectorUColor,puntosActivos,lineasActivas);
		scene.add(vectorU);
		scene.add(ayudaVU);
	});
	/*Si cambia el valor de la coordenada y del vector U, hay que repintar el vector y las ayudas visuales. */
	pUy.onChange(function(value) 
	{   puntoU.setY(value); 
		scene.remove(vectorU);
		scene.remove(ayudaVU);
		scene.remove(vectorProdCruz);
		scene.remove(ayudaProdCruz);
		vectorU = crearVector(puntoOrigen,puntoU,vectorUColor);
		ayudaVU = ayudaVisual(puntoU,vectorUColor,puntosActivos,lineasActivas);
		scene.add(vectorU);
		scene.add(ayudaVU);
	});
	/*Si cambia el valor de la coordenada z del vector U, hay que repintar el vector y las ayudas visuales. */
	pUz.onChange(function(value) 
	{  	puntoU.setZ(value); 
		scene.remove(vectorU);
		scene.remove(ayudaVU);
		scene.remove(vectorProdCruz);
		scene.remove(ayudaProdCruz);
		vectorU = crearVector(puntoOrigen,puntoU,vectorUColor);
		ayudaVU = ayudaVisual(puntoU,vectorUColor,puntosActivos,lineasActivas);
		scene.add(vectorU);
		scene.add(ayudaVU);
	});
			
	//Color del vector U. Si cambia el color del vector a través de la paleta de colores, se actualiza el color en el escenario.
	var vUColor = folder1.addColor( parametros, 'vUcolor' ).name('Color Vector u').listen();
	vUColor.onChange(function(value) // onFinishChange
	{	vectorUColor = parseInt(value.replace("#", "0x"), 16);	
		scene.remove(vectorU);
		scene.remove(ayudaVU);
		vectorU = crearVector(puntoOrigen,puntoU,vectorUColor);
		ayudaVU = ayudaVisual(puntoU,vectorUColor,puntosActivos,lineasActivas);
		scene.add(vectorU);
		scene.add(ayudaVU);		
	}); 

	//Mismo tratamiento para el Vector v
	var folder2 = gui.addFolder('Vector v');
	var pVx = folder2.add( parametros, 'pVx').name('x');
	var pVy = folder2.add( parametros, 'pVy').name('y');
	var pVz = folder2.add( parametros, 'pVz').name('z');
	var moduloV = folder2.add(parametros, 'moduloV').name('Módulo Vector V').listen();
	
	parametros.moduloV = puntoV.length();
	


	pVx.onChange(function(value) 
	{	puntoV.setX(value); 
		scene.remove(vectorV);
		scene.remove(ayudaVV);
		scene.remove(vectorProdCruz);
		scene.remove(ayudaProdCruz);
		vectorV = crearVector(puntoOrigen,puntoV,vectorVColor);
		ayudaVV = ayudaVisual(puntoV,vectorVColor,puntosActivos,lineasActivas);
		scene.add(vectorV);
		scene.add(ayudaVV);
	});
	pVy.onChange(function(value) 
	{   puntoV.setY(value); 
		scene.remove(vectorV);
		scene.remove(ayudaVV);
		scene.remove(vectorProdCruz);
		scene.remove(ayudaProdCruz);
		vectorV = crearVector(puntoOrigen,puntoV,vectorVColor);
		ayudaVV = ayudaVisual(puntoV,vectorVColor,puntosActivos,lineasActivas);
		scene.add(vectorV);
		scene.add(ayudaVV);
	});
	pVz.onChange(function(value) 
	{  	puntoV.setZ(value); 
		scene.remove(vectorV);
		scene.remove(ayudaVV);
		scene.remove(vectorProdCruz);
		scene.remove(ayudaProdCruz);
		vectorV = crearVector(puntoOrigen,puntoV,vectorVColor);
		ayudaVV = ayudaVisual(puntoV,vectorVColor,puntosActivos,lineasActivas);
		scene.add(vectorV);
		scene.add(ayudaVV);
	});
			
	var vVColor = folder2.addColor( parametros, 'vVcolor' ).name('Color Vector v').listen();
	vVColor.onChange(function(value) // onFinishChange
	{	vectorVColor = parseInt(value.replace("#", "0x"), 16);	
		scene.remove(vectorV);
		scene.remove(ayudaVV);
		vectorV = crearVector(puntoOrigen,puntoV,vectorVColor);
		ayudaVV = ayudaVisual(puntoV,vectorVColor,puntosActivos,lineasActivas);
		scene.add(vectorV);
		scene.add(ayudaVV);		
	}); 

	//Mismo tratamiento para Vector w
	var folder3 = gui.addFolder('Vector w');
	var pWx = folder3.add( parametros, 'pWx').name('x');
	var pWy = folder3.add( parametros, 'pWy').name('y');
	var pWz = folder3.add( parametros, 'pWz').name('z');
	var moduloW = folder4.add(parametros, 'moduloW').name('Módulo Vector W').listen();
	
	parametros.moduloW = puntoW.length();
	


	pWx.onChange(function(value) 
	{	puntoW.setX(value); 
		scene.remove(vectorW);
		scene.remove(ayudaVW);
		scene.remove(vectorProdCruz);
		scene.remove(ayudaProdCruz);
		vectorW = crearVector(puntoOrigen,puntoW,vectorWColor);
		ayudaVW = ayudaVisual(puntoW,vectorWColor,puntosActivos,lineasActivas);
		scene.add(vectorW);
		scene.add(ayudaVW);
	});
	pWy.onChange(function(value) 
	{   puntoW.setY(value); 
		scene.remove(vectorW);
		scene.remove(ayudaVW);
		scene.remove(vectorProdCruz);
		scene.remove(ayudaProdCruz);
		vectorW = crearVector(puntoOrigen,puntoW,vectorWColor);
		ayudaVW = ayudaVisual(puntoW,vectorWColor,puntosActivos,lineasActivas);
		scene.add(vectorW);
		scene.add(ayudaVW);
	});
	pWz.onChange(function(value) 
	{  	puntoW.setZ(value); 
		scene.remove(vectorW);
		scene.remove(ayudaVW);
		scene.remove(vectorProdCruz);
		scene.remove(ayudaProdCruz);
		vectorW = crearVector(puntoOrigen,puntoW,vectorWColor);
		ayudaVW = ayudaVisual(puntoW,vectorWColor,puntosActivos,lineasActivas);
		scene.add(vectorW);
		scene.add(ayudaVW);
	});
			
	var vWColor = folder3.addColor( parametros, 'vWcolor' ).name('Color Vector w').listen();
	vWColor.onChange(function(value) // onFinishChange
	{	vectorWColor = parseInt(value.replace("#", "0x"), 16);	
		scene.remove(vectorW);
		scene.remove(ayudaVW);
		vectorW = crearVector(puntoOrigen,puntoW,vectorWColor);
		ayudaVW = ayudaVisual(puntoW,vectorWColor,puntosActivos,lineasActivas);
		scene.add(vectorW);
		scene.add(ayudaVW);
	}); 

	//Botón para calcular el producto mixto de 3 vectores.
	var botonOperacion = gui.add(parametros, 'botonOperacion').name('C A L C U L A R');
	//Campos de la interfaz para mostrar el resultado.
	var folder4 = gui.addFolder('Producto Mixto');
	var resultado = folder4.add(parametros, 'productoMixto').name('U · (V x W) =').listen();
	var folderAngulo = folder4.addFolder('Ángulo U - (VxW)');
	var anguloUyVW = folderAngulo.add(parametros, 'anguloUyVW').name('Grados').listen();
	var anguloUyVWRad = folderAngulo.add(parametros, 'anguloUyVWRad').name('Radianes').listen();
	var folder5 = folder4.addFolder('Producto cruz (V x W)');
	var pCx = folder5.add(parametros, 'pCx').name('x').listen();
	var pCy = folder5.add(parametros, 'pCy').name('y').listen();
	var pCz = folder5.add(parametros, 'pCz').name('z').listen();
	var moduloCruz = folder5.add(parametros, 'moduloCruz').name('Modulo Cruz').listen();
	var folderAngulo2 = folder5.addFolder('Ángulo V - W');
	var anguloVW = folderAngulo2.add(parametros, 'anguloVW').name('Grados').listen();
	var anguloVWRad = folderAngulo2.add(parametros, 'anguloVWRad').name('Radianes').listen();
	folderAngulo.open();
	folderAngulo2.open();
	folder5.open();
	folder4.open();

	//Campos de la interfaz para Ayudas Visuales.
	var folder6 = gui.addFolder('Ayudas Visuales');
	var cambiarTamannoEscenario = folder6.add(parametros, 'tamannoEscenario', ["10", "20", "40", "60"]).name('Tamaño escenario');
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

	var mostrarPuntosAuxiliares = folder6.add(parametros, 'puntosAuxiliares').name('Mostrar puntos auxiliares').listen();
	mostrarPuntosAuxiliares.onChange(function(value) 
	{ 	puntosActivos = value;
		scene.remove(ayudaVU);
		scene.remove(ayudaVV);
		scene.remove(ayudaVW);
		scene.remove(ayudaProdCruz);
		ayudaVU = ayudaVisual(puntoU,vectorUColor,puntosActivos,lineasActivas);
		ayudaVV = ayudaVisual(puntoV,vectorVColor,puntosActivos,lineasActivas);
		ayudaVW = ayudaVisual(puntoW,vectorWColor,puntosActivos,lineasActivas);
		ayudaProdCruz = ayudaVisual(productoCruz,prodCruzColor,puntosActivos,lineasActivas);
		scene.add(ayudaVU);
		scene.add(ayudaVV);
		scene.add(ayudaVW);
		scene.add(ayudaProdCruz);
	}); 
				
	var mostrarlineasAuxiliares = folder6.add(parametros, 'lineasAuxiliares').name('Mostrar lineas auxiliares').listen();
	mostrarlineasAuxiliares.onChange(function(value) 
	{ 	lineasActivas = value;
		scene.remove(ayudaVU);
		scene.remove(ayudaVV);
		scene.remove(ayudaVW);
		scene.remove(ayudaProdCruz);
		ayudaVU = ayudaVisual(puntoU,vectorUColor,puntosActivos,lineasActivas);
		ayudaVV = ayudaVisual(puntoV,vectorVColor,puntosActivos,lineasActivas);
		ayudaVW = ayudaVisual(puntoW,vectorWColor,puntosActivos,lineasActivas);
		ayudaProdCruz = ayudaVisual(productoCruz,prodCruzColor,puntosActivos,lineasActivas);
		scene.add(ayudaVU);
		scene.add(ayudaVV);
		scene.add(ayudaVW);
		scene.add(ayudaProdCruz);
	}); 
	var mostrarSeparadorEjes = folder6.add(parametros, 'lineasAuxEjes').name('Separación ejes').listen();
	mostrarSeparadorEjes.onChange(function(value)
	{	separadoresActivos = value;
		scene.remove(lineasAuxEje);
		lineasAuxEje = lineasEjes(separadoresActivos);
		scene.add(lineasAuxEje);
	});

	var mostrarNumeracionEjes = folder6.add(parametros, 'numAuxEjes').name('Numeración ejes').listen();
	mostrarNumeracionEjes.onChange(function(value)
	{	numeracionActiva = value;
		scene.remove(numAuxEje);
		numAuxEje = numEjes(numeracionActiva);
		scene.add(numAuxEje);
	});
	
	gui.open();
		
	//Función que realiza el producto mixto.	
	function calcular(){
		scene.remove(vectorProdCruz);
		scene.remove(ayudaProdCruz);
		//Lo que ocurre es que al realizar   extremoResultado.crossSelf(puntoExtremo[n]), el valor de extremoResultado, extrañamente se asigna
		//también a puntoExtremo[n]. Hay que arreglar eso con ayuda de una variable auxiliar donde guardamos los datos previo a cross y los recogemos después.
		// Se arregla usando copy()
		//Producto vectorial entre V y W
		productoCruz.copy(puntoV);
		productoCruz.crossSelf(puntoW);
		parametros.pCx = productoCruz.x;
		parametros.pCy = productoCruz.y;
		parametros.pCz = productoCruz.z; 
		parametros.moduloCruz = productoCruz.length();
		vectorProdCruz = crearVector(puntoOrigen,productoCruz,prodCruzColor);
		ayudaProdCruz = ayudaVisual(productoCruz,prodCruzColor,puntosActivos,lineasActivas);
		scene.add(vectorProdCruz);
		scene.add(ayudaProdCruz);
		productoMixto(puntoU, puntoV, puntoW, productoCruz, parametros);
	}
				
</script>