

	 
	var puntosActivos = new Array();
	var lineasActivas = new Array();
	var separadoresActivos = false;
	var numeracionActiva = false;
	var puntoOrigen = new THREE.Vector3();
	var puntoExtremo = new Array();
	var extremoResultado = new THREE.Vector3(0,0,0);
	var resultadoColor = 0xff0000;
	var vectorResultado = new THREE.Object3D();
	var vector = new Array();
	var ayudaV1End = new Array();
	var ayudaVSEnd = new THREE.Object3D();
	var vectorProdCruz = new THREE.Object3D();
	var ayudaProdCruz = new THREE.Object3D(); 
	var lineasAuxEje = new THREE.Object3D(); 
	var numAuxEje = new THREE.Object3D();

			
	
				
	//Interfaz 
	var gui;
	var numVectores = 0;
	var i=0;
	var folder = new Array();
	var coordX = new Array();
	var coordY = new Array();
	var coordZ = new Array();
	var modulo = new Array();
	var vectorColor = new Array();
	var vColor = new Array();
	var mostrarPuntosAuxiliares = new Array();
	var mostrarLineasAuxiliares = new Array();
	var puntosActivosResultado = false;
	var lineasActivasResultado = false;
	var text;
	var coordenadas = new Array();
	var indicadorOperacion;
	var resultado;
	var vRx;
	var vRy;
	var vRz;
	var mostrarPuntosAuxResultado;
	var mostrarLineasAuxResultado;
	var folderEscalar;
	var moduloV;
	var moduloW;
	var angulo;
	var resultado2;
	var folderMixto;
	var resultado3;
	var folderCruz;
	var pCx;
	var pCy;
	var pCz;
	var moduloU;
	//var moduloV;
	//var moduloW;
	var anguloVW;
	var anguloUyVW;
	var calcular;
	var n=0;

	indicadorOperacion = "";

	crearInterfaz(indicadorOperacion);
	
	//Función para crear la interfaz de usuario
	function crearInterfaz(indicadorOperacion) {

		var agregarEliminarVector = function() 
		{	
			this.adVector = function() { anadirVector(); };
			this.elimVector = function() { eliminarVector(); };
			this.tamannoEscenario = "10";
			this.lineasAuxEjes = false;
			this.numAuxEjes = false;
			this.operacion = indicadorOperacion;
			this.vRx = 0;
			this.vRy = 0;
			this.vRz = 0;
			this.calcular = function() { calcularResultado(indicadorOperacion); };
			this.puntosAuxResultado = false;
			this.lineasAuxResultado = false;
			//this.moduloV = "";
			//this.moduloW = "";
			this.modulo = "";
			this.angulo = "";
			this.productoEscalar = "";
			this.productoMixto = "";
			this.pCx = "";
			this.pCy = "";
			this.pCz = "";
			//this.moduloU = "";
			this.anguloVW = "";
			this.anguloUyVW = "";
			
		}
		
		text = new agregarEliminarVector();
		var anchuraGUI = $("#interfaz").width();
		gui = new dat.GUI({
			autoPlace: false,
			width: anchuraGUI
		});
		
		$("#interfaz").append(gui.domElement); 

		var addVector = gui.add(text, 'adVector').name("Añadir vector");
		var elimiVector = gui.add(text, 'elimVector').name("Eliminar vectores");
		var ayudas = gui.addFolder('Ayudas Visuales');
		var cambiarTamannoEscenario = ayudas.add(text, 'tamannoEscenario', ["10", "20", "40", "60"]).name('Tamaño escenario');

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
			if(size == 10) 
				camera.position.set(-0.3*size,size/2,25);
			else if(size == 20)
				camera.position.set(-0.3*size,size/2,40);
			else if(size == 40)
				camera.position.set(-0.3*size,size/2,70);
			else
				camera.position.set(-0.3*size,size/2,100);
		});
		
		var mostrarSeparadorEjes = ayudas.add(text, 'lineasAuxEjes').name('Separación ejes').listen();
		mostrarSeparadorEjes.onChange(function(value)
		{	separadoresActivos = value;
			scene.remove(lineasAuxEje);
			lineasAuxEje = lineasEjes(separadoresActivos);
			scene.add(lineasAuxEje);
		});

		var mostrarNumeracionEjes = ayudas.add(text, 'numAuxEjes').name('Numeración ejes').listen();
		mostrarNumeracionEjes.onChange(function(value)
		{	
			numeracionActiva = value;
			scene.remove(numAuxEje);
			numAuxEje = numEjes(numeracionActiva);
			scene.add(numAuxEje);
		});
		
		var operacion = gui.add(text, 'operacion', ["", "Suma", "Resta", "Producto Cruz", "Producto Escalar", "Producto Mixto"]).name('Operación');
		operacion.onChange(function(value) { 
			indicadorOperacion = value;
			generarCamposResultado(indicadorOperacion,numVectores,coordenadas);
		});
		
		gui.open();
	}

	function generarCamposResultado(indicadorOperacion,numVectores,coordenadas) {
		if(indicadorOperacion == "Suma" || indicadorOperacion == "Resta" || indicadorOperacion == "Producto Cruz") {					
			scene.remove(vectorResultado);
			limpiarGui(indicadorOperacion);

			calcular = gui.add(text, 'calcular').name("Calcular");
			resultado = gui.addFolder("Vector Resultado");
			vRx = resultado.add(text, 'vRx').name('x').listen();
			vRy = resultado.add(text, 'vRy').name('y').listen();
			vRz = resultado.add(text, 'vRz').name('z').listen();
			moduloResultado = resultado.add(text, 'modulo').name('Módulo').listen();

			mostrarPuntosAuxResultado = resultado.add(text, 'puntosAuxResultado').name('Mostrar puntos auxiliares').listen();
			mostrarLineasAuxResultado = resultado.add(text, 'lineasAuxResultado').name('Mostrar lineas auxiliares').listen();
			mostrarPuntosAuxResultado.onChange(function(value) { 
				puntosActivosResultado = value;
				scene.remove(ayudaVSEnd);
				ayudaVSEnd = ayudaVisual(extremoResultado,resultadoColor,puntosActivosResultado,lineasActivasResultado);
				scene.add(ayudaVSEnd);
			}); 
								
			mostrarLineasAuxResultado.onChange(function(value) { 
				lineasActivasResultado = value;
				scene.remove(ayudaVSEnd);
				ayudaVSEnd = ayudaVisual(extremoResultado,resultadoColor,puntosActivosResultado,lineasActivasResultado);
				scene.add(ayudaVSEnd);	
			}); 
			
			resultado.open();
			if(numVectores > 0) {
				for(var j=0;j<numVectores;j++) {
					recuperarVectoresGui(j, coordenadas);
				}
			}
		}
		else if(indicadorOperacion == "Producto Escalar") {
			limpiarGui(indicadorOperacion);
			calcular = gui.add(text, 'calcular').name("Calcular");
			folderEscalar = gui.addFolder('Producto Escalar');
			resultado2 = folderEscalar.add(text, 'productoEscalar').name('A · B = ').listen();
			//moduloV = folderEscalar.add(text, 'moduloV').name('Módulo Vector V').listen();
			//moduloW = folderEscalar.add(text, 'moduloW').name('Módulo Vector W').listen();
			angulo = folderEscalar.add(text, 'angulo').name('Ángulo (grados)').listen();
			folderEscalar.open();

			if(numVectores > 0) {
				for(var j=0;j<numVectores;j++) {
					recuperarVectoresGui(j, coordenadas);
				}
			}
		}
		else if(indicadorOperacion == "Producto Mixto") {
			scene.remove(vectorResultado);
			limpiarGui(indicadorOperacion);
			calcular = gui.add(text, 'calcular').name("Calcular");
			folderMixto = gui.addFolder('Producto Mixto');
			resultado3 = folderMixto.add(text, 'productoMixto').name('U · (V x W) =').listen();
			folderCruz = folderMixto.addFolder('Producto Cruz (V x W)');
			pCx = folderCruz.add(text, 'pCx').name('x').listen();
			pCy = folderCruz.add(text, 'pCy').name('y').listen();
			pCz = folderCruz.add(text, 'pCz').name('z').listen();
			moduloResultado = folderCruz.add(text, 'modulo').name('Módulo').listen()
			mostrarPuntosAuxResultado = folderCruz.add(text, 'puntosAuxResultado').name('Mostrar puntos auxiliares').listen();
			mostrarLineasAuxResultado = folderCruz.add(text, 'lineasAuxResultado').name('Mostrar lineas auxiliares').listen();
			mostrarPuntosAuxResultado.onChange(function(value) { 
				puntosActivosResultado = value;
				scene.remove(ayudaVSEnd);
				ayudaVSEnd = ayudaVisual(extremoResultado,resultadoColor,puntosActivosResultado,lineasActivasResultado);
				scene.add(ayudaVSEnd);
			}); 
			mostrarLineasAuxResultado.onChange(function(value) { 
				lineasActivasResultado = value;
				scene.remove(ayudaVSEnd);
				ayudaVSEnd = ayudaVisual(extremoResultado,resultadoColor,puntosActivosResultado,lineasActivasResultado);
				scene.add(ayudaVSEnd);	
			}); 
			//moduloU = folderMixto.add(text, 'moduloU').name('Módulo Vector U').listen();
			//moduloV = folderMixto.add(text, 'moduloV').name('Módulo Vector V').listen();
			//moduloW = folderMixto.add(text, 'moduloW').name('Módulo Vector W').listen();
			anguloVW = folderMixto.add(text, 'anguloVW').name('Ángulo V y W').listen();
			anguloUyVW = folderMixto.add(text, 'anguloUyVW').name('Ángulo U y (VxW)').listen();
			folderCruz.open();
			folderMixto.open();

			if(numVectores > 0) {
				for(var j=0;j<numVectores;j++) {
					recuperarVectoresGui(j, coordenadas);
				}
			}
		}
	}

	function limpiarGui(indicadorOperacion) {
		$("#interfaz").empty();
		crearInterfaz(indicadorOperacion);
		
	}

	function recuperarVectoresGui (j, coordenadas) {
		
			folder[j] = gui.addFolder('Vector '+ (j+1));
			coordX[j] = folder[j].add(coordenadas[j], 'x');
			coordY[j] = folder[j].add(coordenadas[j], 'y');
			coordZ[j] = folder[j].add(coordenadas[j], 'z');
			modulo[i] = folder[j].add(coordenadas[j], 'modulo').name('Módulo').listen();
			vectorColor[j] = folder[j].addColor(coordenadas[j], 'vColor' ).listen();
			mostrarPuntosAuxiliares[j] = folder[j].add(coordenadas[j], 'puntosAuxiliares').name('Mostrar puntos auxiliares').listen();
			mostrarLineasAuxiliares[j] = folder[j].add(coordenadas[j], 'lineasAuxiliares').name('Mostrar lineas auxiliares').listen();
			coordenadas[j].modulo = puntoExtremo[j].length();
			
			coordX[j].onChange(function(value) {
				puntoExtremo[j].setX(value); 
				scene.remove(vector[j]);
				scene.remove(ayudaV1End[j]);
				vector[j] = crearVector(puntoOrigen,puntoExtremo[j],vColor[j]);
				ayudaV1End[j] = ayudaVisual(puntoExtremo[j],vColor[j],puntosActivos[j],lineasActivas[j]);
				coordenadas[j].modulo = puntoExtremo[j].length();
				scene.add(vector[j]);
				scene.add(ayudaV1End[j]); 	
			});
			coordY[j].onChange(function(value) {
				puntoExtremo[j].setY(value); 
				scene.remove(vector[j]);
				scene.remove(ayudaV1End[j]);
				vector[j] = crearVector(puntoOrigen,puntoExtremo[j],vColor[j]);
				ayudaV1End[j] = ayudaVisual(puntoExtremo[j],vColor[j],puntosActivos[j],lineasActivas[j]);
				coordenadas[j].modulo = puntoExtremo[j].length();
				scene.add(vector[j]);
				scene.add(ayudaV1End[j]);
			});
			coordZ[j].onChange(function(value) { 
				puntoExtremo[j].setZ(value); 
				scene.remove(vector[j]);
				scene.remove(ayudaV1End[j]);
				vector[j] = crearVector(puntoOrigen,puntoExtremo[j],vColor[j]);
				ayudaV1End[j] = ayudaVisual(puntoExtremo[j],vColor[j],puntosActivos[j],lineasActivas[j]);
				coordenadas[j].modulo = puntoExtremo[j].length();
				scene.add(vector[j]);
				scene.add(ayudaV1End[j]);
			});
			
			vectorColor[j].onChange(function(value)	{// onFinishChange
				vColor[j] = parseInt(value.replace("#", "0x"), 16);	
				scene.remove(vector[j]);
				scene.remove(ayudaV1End[j]);
				vector[j] = crearVector(puntoOrigen,puntoExtremo[j],vColor[j]);
				ayudaV1End[j] = ayudaVisual(puntoExtremo[j],vColor[j],puntosActivos[j],lineasActivas[j]);
				scene.add(vector[j]);
				scene.add(ayudaV1End[j]);		
			}); 
			
			mostrarPuntosAuxiliares[j].onChange(function(value) { 
				puntosActivos[j] = value;
				scene.remove(ayudaV1End[j]);
				ayudaV1End[j] = ayudaVisual(puntoExtremo[j],vColor[j],puntosActivos[j],lineasActivas[j]);
				scene.add(ayudaV1End[j]);	
			}); 
					
			mostrarLineasAuxiliares[j].onChange(function(value) { 
				lineasActivas[j] = value;
				scene.remove(ayudaV1End[j]);
				ayudaV1End[j] = ayudaVisual(puntoExtremo[j],vColor[j],puntosActivos[j],lineasActivas[j]);
				scene.add(ayudaV1End[j]);
			}); 
	}

	var parametros = function() {
		this.x = parseFloat($("#coordX").val());
		this.y = parseFloat($("#coordY").val());
		this.z = parseFloat($("#coordZ").val());
		this.modulo = "";
		this.vColor = get_random_color();
		this.puntosAuxiliares = false;
		this.lineasAuxiliares = false;
	}
	
	function get_random_color() {
		var letters = '0123456789ABCDEF'.split('');
		var color = '#';
		for (var i = 0; i < 6; i++ ) {
			color += letters[Math.round(Math.random() * 15)];
		}
		return color;
	}
	
	function anadirVector() {
		
		$("#crearVector").dialog({
			height:150, 
			width:400,
			autoOpen: true,
			show: {
				effect: "blind",
				duration: 500
			},
			hide: {
				effect: "explode",
				duration: 1000
			}
		});
		$("#crearVector").dialog('open');
		var aux = $("input:text");
		aux.focus(enfocar);
		aux.hover(entraRaton, saleRaton);
		
		$("#botonAdVector").click(function() {
			$("#crearVector").dialog("close");
			coordenadas[i] = new parametros();	

			$("#coordX").val("");
			$("#coordY").val("");
			$("#coordZ").val("");

			if(isNaN(coordenadas[i].x) || isNaN(coordenadas[i].y) || isNaN(coordenadas[i].z)) 
				return false;
			
			numVectores++;
			folder[i] = gui.addFolder('Vector '+ numVectores);
			coordX[i] = folder[i].add(coordenadas[i], 'x');
			coordY[i] = folder[i].add(coordenadas[i], 'y');
			coordZ[i] = folder[i].add(coordenadas[i], 'z');
			modulo[i] = folder[i].add(coordenadas[i], 'modulo').name('Módulo').listen();
			vectorColor[i] = folder[i].addColor(coordenadas[i], 'vColor' ).listen();
			mostrarPuntosAuxiliares[i] = folder[i].add(coordenadas[i], 'puntosAuxiliares').name('Mostrar puntos auxiliares').listen();
			mostrarLineasAuxiliares[i] = folder[i].add(coordenadas[i], 'lineasAuxiliares').name('Mostrar lineas auxiliares').listen();
			
			vColor[i] = coordenadas[i].vColor;
			puntosActivos[i] =	false; 
			lineasActivas[i] =	false;
			puntoOrigen = new THREE.Vector3(0,0,0);
			puntoExtremo[i] = new THREE.Vector3(coordenadas[i].x,coordenadas[i].y,coordenadas[i].z);
			coordenadas[i].modulo = puntoExtremo[i].length();
			vector[i] = crearVector(puntoOrigen,puntoExtremo[i],vColor[i]);
			scene.add(vector[i]);
			
			var j;
			j=i;
		
			coordX[j].onChange(function(value) {
				puntoExtremo[j].setX(value); 
				scene.remove(vector[j]);
				scene.remove(ayudaV1End[j]);
				vector[j] = crearVector(puntoOrigen,puntoExtremo[j],vColor[j]);
				ayudaV1End[j] = ayudaVisual(puntoExtremo[j],vColor[j],puntosActivos[j],lineasActivas[j]);
				coordenadas[j].modulo = puntoExtremo[j].length();
				scene.add(vector[j]);
				scene.add(ayudaV1End[j]); 
			});
			coordY[j].onChange(function(value) {
				puntoExtremo[j].setY(value); 
				scene.remove(vector[j]);
				scene.remove(ayudaV1End[j]);
				vector[j] = crearVector(puntoOrigen,puntoExtremo[j],vColor[j]);
				ayudaV1End[j] = ayudaVisual(puntoExtremo[j],vColor[j],puntosActivos[j],lineasActivas[j]);
				coordenadas[j].modulo = puntoExtremo[j].length();
				scene.add(vector[j]);
				scene.add(ayudaV1End[j]);
			});
			coordZ[j].onChange(function(value) { 
				puntoExtremo[j].setZ(value); 
				scene.remove(vector[j]);
				scene.remove(ayudaV1End[j]);
				vector[j] = crearVector(puntoOrigen,puntoExtremo[j],vColor[j]);
				ayudaV1End[j] = ayudaVisual(puntoExtremo[j],vColor[j],puntosActivos[j],lineasActivas[j]);
				coordenadas[j].modulo = puntoExtremo[j].length();
				scene.add(vector[j]);
				scene.add(ayudaV1End[j]);
			});
			
			vectorColor[j].onChange(function(value)	{// onFinishChange
				vColor[j] = parseInt(value.replace("#", "0x"), 16);	
				scene.remove(vector[j]);
				scene.remove(ayudaV1End[j]);
				vector[j] = crearVector(puntoOrigen,puntoExtremo[j],vColor[j]);
				ayudaV1End[j] = ayudaVisual(puntoExtremo[j],vColor[j],puntosActivos[j],lineasActivas[j]);
				scene.add(vector[j]);
				scene.add(ayudaV1End[j]);		
			}); 
			
			mostrarPuntosAuxiliares[j].onChange(function(value) { 
				puntosActivos[j] = value;
				scene.remove(ayudaV1End[j]);
				ayudaV1End[j] = ayudaVisual(puntoExtremo[j],vColor[j],puntosActivos[j],lineasActivas[j]);
				scene.add(ayudaV1End[j]);	
			}); 
					
			mostrarLineasAuxiliares[j].onChange(function(value) { 
				lineasActivas[j] = value;
				scene.remove(ayudaV1End[j]);
				ayudaV1End[j] = ayudaVisual(puntoExtremo[j],vColor[j],puntosActivos[j],lineasActivas[j]);
				scene.add(ayudaV1End[j]);
			}); 

			i++;	
		});
		
	}

	function calcularResultado(indicadorOperacion) {
		if(indicadorOperacion == "Producto Escalar") {
			productoEscalar(puntoExtremo[0], puntoExtremo[1], text);
		}
		else if(indicadorOperacion == "Producto Mixto") {
			scene.remove(vectorResultado);
			scene.remove(ayudaVSEnd);
			//Lo que ocurre es que al realizar   extremoResultado.crossSelf(puntoExtremo[n]), el valor de extremoResultado, extrañamente se asigna
			//también a puntoExtremo[n]. Hay que arreglar eso con ayuda de una variable auxiliar donde guardamos los datos previo a cross y los recogemos después.
			// Se arregla usando copy()
			//Producto vectorial entre V y W
			extremoResultado.copy(puntoExtremo[1]); //PuntoV x Punto W
			extremoResultado.crossSelf(puntoExtremo[2]); 
			text.pCx = extremoResultado.x;
			text.pCy = extremoResultado.y;
			text.pCz = extremoResultado.z; 
			text.modulo = extremoResultado.length();
			vectorResultado = crearVector(puntoOrigen,extremoResultado,resultadoColor);
			ayudaVSEnd = ayudaVisual(extremoResultado,resultadoColor,puntosActivosResultado,lineasActivasResultado);
			scene.add(vectorResultado);
			scene.add(ayudaVSEnd);
			productoMixto(puntoExtremo[0], puntoExtremo[1], puntoExtremo[2], extremoResultado, text);
		}
		else {
			scene.remove(vectorResultado);
			scene.remove(ayudaVSEnd);
			extremoResultado = new THREE.Vector3(0,0,0);
			text.vRx = 0;
			text.vRy = 0;
			text.vRz = 0;
			vectorResultado = operacionVectoresCuestionario(puntoOrigen, puntoExtremo, extremoResultado, resultadoColor, text, indicadorOperacion);
			ayudaVSEnd = ayudaVisual(extremoResultado,resultadoColor,puntosActivosResultado,lineasActivasResultado);
			scene.add(vectorResultado);
			scene.add(ayudaVSEnd);
		}
	}
	
	function eliminarVector() {
		$("#interfaz").empty();
		
		for(var j=0;j<=i;j++) {
			scene.remove(vector[j]);
			scene.remove(ayudaV1End[j]);
		}
		scene.remove(vectorResultado);
		scene.remove(lineasAuxEje);
		scene.remove(numAuxEje);
		scene.remove(escenario);
		size = 10;
		escenario = new THREE.Object3D();
		lineasAuxEje = new THREE.Object3D();
		numAuxEje = new THREE.Object3D();
		escenario = escalaEscenario();
		scene.add(escenario);
		camera.position.set(-0.3*size,size/2,25);
		
		folder = null;
		folder = new Array();
		coordX = null;
		coordX = new Array();
		coordY = null;
		coordY = new Array();
		coordZ = null;
		coordZ = new Array();
		modulo = null;
		modulo = new Array();
		vector = null;
		vector = new Array();
		puntoExtremo = null;
		puntoExtremo = new Array();
		ayudaV1End = null;
		ayudaV1End = new Array();
		mostrarPuntosAuxiliares = null;
		mostrarPuntosAuxiliares = new Array();
		mostrarLineasAuxiliares = null;
		mostrarLineasAuxiliares = new Array();
		puntosActivos = null;
		puntosActivos = new Array();
		lineasActivas = null;
		lineasActivas = new Array();
		separadoresActivos = false;
		numeracionActiva = false;
		puntosActivosResultado = false;
		lineasActivasResultado = false;
		i=0;
		numVectores=0;
		coordenadas = null;
		coordenadas = new Array();

		crearInterfaz(indicadorOperacion);

	}

	function enfocar(){
		$(this).attr("value", "");
	}
	function entraRaton() {
		$(this).css("border-color", "#6eac2c");	
	}
	function saleRaton() {
		$(this).css("border-color", "");	
	}
					
