	//escena
	var scene = new THREE.Scene();
	//cámara
	var camera;
	//Tipo de cámara y enfoque
	camera = new THREE.PerspectiveCamera(55, $("#grafico").width()/$("#grafico").height(), 1, 10000);
	camera.lookAt( scene.position );
	scene.add(camera);
	//renderizador. Si el navegador no soporta WebGL, el renderizador será Canvas.
	var renderer;
	if ( Detector.webgl )
		renderer = new THREE.WebGLRenderer( {antialias:true} );
	else
		renderer = new THREE.CanvasRenderer(); 
	renderer.setSize($("#grafico").width(),$("#grafico").height());
	$("#grafico").append(renderer.domElement);
	$("#contenedor").css("height", "auto");

	//Se crea un escenario de tamaño 10.
	var size;
	var escenario = new THREE.Object3D();
	size = 10;
	escenario = escalaEscenario();
	scene.add(escenario);
	camera.position.set(-0.3*size,size/2,25);
	
	// controles para el movimiento del escenario: rotar, mover, acercar y alejar.
	var controls = new THREE.TrackballControls( camera, renderer.domElement );
	controls.noPan = false; // it's probably best to prevent panning in this case

	
	// STATS. Plugin gráfico para comprobar a cuantos frames por segundo trabaja nuestro escenario.
	var stats = new Stats();
	$("#stats").append(stats.domElement);

			
	//Escenario para ejes auxiliares --> "#inset"
	//------------------------------------------------
	// Escena
	var scene2 = new THREE.Scene();
	// Cámara
	var camera2;
	camera2 = new THREE.PerspectiveCamera( 55, $("#inset").width()/$("#inset").height(), 1, 1000 );
	camera2.up = camera.up; // important!
	scene2.add( camera2 );
	// Renderizador
	var renderer2;
	if ( Detector.webgl )
		renderer2 = new THREE.WebGLRenderer( {antialias:true} );
	else
		renderer2 = new THREE.CanvasRenderer();
	renderer2.setSize( $("#inset").width(),$("#inset").height() );
	$("#inset").append(renderer2.domElement);
	// Ejes 
	var puntoEjeAuxOrigen = new THREE.Vector3(0,0,0);
	var puntoEjeAuxX = new THREE.Vector3(10,0,0);
	var puntoEjeAuxY = new THREE.Vector3(0,10,0);
	var puntoEjeAuxZ = new THREE.Vector3(0,0,10);
	var ejeAux = new THREE.Object3D();
	ejeAux.add(crearVector(puntoEjeAuxOrigen, puntoEjeAuxX, 0xff0000));
	ejeAux.add(crearVector(puntoEjeAuxOrigen, puntoEjeAuxY, 0x00ff00));
	ejeAux.add(crearVector(puntoEjeAuxOrigen, puntoEjeAuxZ, 0x0000ff));
	scene2.add(ejeAux);

	//Texto Ejes
	var textoEjesAux = new THREE.Object3D();
	textoEjesAux.add(texto(11,0,0,"X",1,0xff0000,true));
	textoEjesAux.add(texto(0,11,0,"Y",1,0x00ff00,true));
	textoEjesAux.add(texto(0,0,11,"Z",1,0x0000ff,true));
	scene2.add(textoEjesAux);
				
	//Función de renderizado
	function render() {
		renderer.render( scene, camera );
		renderer2.render( scene2, camera2 );
	}
	//Cada cambio en el escenario, por modificación de variables o por interacción con el ratón se controla en esta función.
	(function animate() { 
		requestAnimationFrame(animate);  
						
		controls.update( );
		stats.update();
				
		var newPos = camera.position.clone();
		newPos.subSelf(controls.target);
		//CAM_DISTANCE = 22
		newPos.setLength(22);
	
		camera2.position.set(newPos.x, newPos.y, newPos.z);
		camera2.lookAt(new THREE.Vector3(0.0, 0.0, 0.0)); 

		render();
	})();