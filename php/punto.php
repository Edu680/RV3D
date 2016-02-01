<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//ES" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>Punto</title>		
		<script type="text/javascript">
			$("#contenedor").css("height", "100%");
		</script>
		<!-- Se añade al escenario la escena inicial (malla y ejes de coordenadas) -->
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
	var puntoColor = 0x00ff00;
	var ayudaVisualColor = 0xff0000;
	var punto;
	var radio = 0.2;
	var ayudaPosicion = new THREE.Object3D();
	var lineasAuxEje = new THREE.Object3D(); 
	var numAuxEje = new THREE.Object3D();
				
	//Se crea un punto que aparecerá inicialmente en la coordenada (3,5,4)
	var puntoPosicion = new THREE.Vector3(3,5,4);
	punto = crearPunto(puntoPosicion, puntoColor, radio);
	scene.add(punto);
			

				
	//Interfaz
	var anchuraGUI = $("#interfaz").width();
	var gui = new dat.GUI({
		autoPlace: false,
		width: anchuraGUI 
	});
	
	$("#interfaz").append(gui.domElement);
	//Parámetros a añadir a la interfaz
	var parametros = new function() {
		this.x = 3;
		this.y = 5;
		this.z = 4;
		this.radio = 0.2;
		this.color = "#00ff00";	// color (change "#" to "0x")
		this.auxColor = "#ff0000";
		this.puntosAuxiliares = false;
		this.lineasAuxiliares = false;
		this.lineasAuxEjes = false;
		this.numAuxEjes = false;
	}
		//Pestañas y campos de la interfaz
		var folder1 = gui.addFolder('Punto');
		var folder3 = folder1.addFolder('Posición');
		var x = folder3.add( parametros, 'x', -10, 10 );
		var y = folder3.add( parametros, 'y', -10, 10 );
		var z = folder3.add( parametros, 'z', -10, 10 );
		folder3.open();
		folder1.open();
			
		//Añadimos eventListener a cada campo de la interfaz.
		/*Si el usuario cambia el valor de la coordenada x, se modifica la coordenada x del punto, por lo que se actualiza su posición
		y la ayuda visual (puntos y líneas) en el escenario*/
		x.onChange(function(value) 
		{   puntoPosicion.setX(value);
			scene.remove(punto);
			scene.remove(ayudaPosicion);
			punto = crearPunto(puntoPosicion, puntoColor, radio);
			ayudaPosicion = ayudaVisual(puntoPosicion,ayudaVisualColor,puntosActivos,lineasActivas);
			scene.add(punto);
			scene.add(ayudaPosicion);

		});
		/*Si el usuario cambia el valor de la coordenada y, se modifica la coordenada y del punto, por lo que se actualiza su posición
		y la ayuda visual (puntos y líneas) en el escenario*/
		y.onChange(function(value) 
		{   puntoPosicion.setY(value);
			scene.remove(punto);
			scene.remove(ayudaPosicion);
			punto = crearPunto(puntoPosicion, puntoColor, radio);
			ayudaPosicion = ayudaVisual(puntoPosicion,ayudaVisualColor,puntosActivos,lineasActivas);
			scene.add(punto);
			scene.add(ayudaPosicion);
		});
		/*Si el usuario cambia el valor de la coordenada z, se modifica la coordenada z del punto, por lo que se actualiza su posición
		y la ayuda visual (puntos y líneas) en el escenario*/
		z.onChange(function(value) 
		{	puntoPosicion.setZ(value);
			scene.remove(punto);
			scene.remove(ayudaPosicion);
			punto = crearPunto(puntoPosicion, puntoColor, radio);
			ayudaPosicion = ayudaVisual(puntoPosicion,ayudaVisualColor,puntosActivos,lineasActivas);
			scene.add(punto);
			scene.add(ayudaPosicion);
		});
	
		//Campo radio. Si se modifica, se actualiza la representación del punto en el escenario para ese nuevo valor del radio.
		var tamanno = gui.add( parametros, 'radio' ).name('Radio');
		tamanno.onChange(function(value) {
			radio = value;
			scene.remove(punto);
			punto = crearPunto(puntoPosicion, puntoColor, radio);
			scene.add(punto);
		});

		//Color del punto. Si cambia el color del punto a través de la paleta de colores, se actualiza el color en el escenario.
		var pColor = gui.addColor( parametros, 'color' ).name('Color').listen();
		pColor.onChange(function(value) // onFinishChange
		{	puntoColor = parseInt(value.replace("#", "0x"), 16);	
			scene.remove(punto);
			punto = crearPunto(puntoPosicion, puntoColor, radio);
			scene.add(punto);
		}); 
			
		//Pestaña Ayudas Visuales.
		var folder2 = gui.addFolder('Ayudas Visuales');
		//Los puntos auxiliares se mostrarán dependiendo del valor del check-box.
		var mostrarPuntosAuxiliares = folder2.add(parametros, 'puntosAuxiliares').name('Mostrar puntos auxiliares').listen();
		mostrarPuntosAuxiliares.onChange(function(value) 
		{ 	puntosActivos = value;
			scene.remove(ayudaPosicion);
			ayudaPosicion = ayudaVisual(puntoPosicion,ayudaVisualColor,puntosActivos,lineasActivas);
			scene.add(ayudaPosicion);
		}); 
		//Las líneas auxiliares se mostrarán dependiendo del valor del check-box.
		var mostrarlineasAuxiliares = folder2.add(parametros, 'lineasAuxiliares').name('Mostrar lineas auxiliares').listen();
		mostrarlineasAuxiliares.onChange(function(value) 
		{ 	lineasActivas = value;
			scene.remove(ayudaPosicion);
			ayudaPosicion = ayudaVisual(puntoPosicion,ayudaVisualColor,puntosActivos,lineasActivas);
			scene.add(ayudaPosicion);
		}); 
				
		//Color de puntos y líneas auxiliares.
		var auxColor = folder2.addColor( parametros, 'auxColor' ).name('Color').listen();
		auxColor.onChange(function(value) // onFinishChange
		{	ayudaVisualColor = parseInt(value.replace("#", "0x"), 16);
			scene.remove(ayudaPosicion);
			ayudaPosicion = ayudaVisual(puntoPosicion,ayudaVisualColor,puntosActivos,lineasActivas);
			scene.add(ayudaPosicion);
		}); 

		//Los separadores de los ejes se mostrarán dependiendo del valor de su check-box
		var mostrarSeparadorEjes = folder2.add(parametros, 'lineasAuxEjes').name('Separación ejes').listen();
		mostrarSeparadorEjes.onChange(function(value)
		{	separadoresActivos = value;
			scene.remove(lineasAuxEje);
			lineasAuxEje = lineasEjes(separadoresActivos);
			scene.add(lineasAuxEje);
		});

		//La numeración de los ejes se mostrará dependiendo del valor de su check-box
		var mostrarNumeracionEjes = folder2.add(parametros, 'numAuxEjes').name('Numeración ejes').listen();
		mostrarNumeracionEjes.onChange(function(value)
		{	numeracionActiva = value;
			scene.remove(numAuxEje);
			numAuxEje = numEjes(numeracionActiva);
			scene.add(numAuxEje);
		});

		folder2.open();
		gui.open();
				
</script>