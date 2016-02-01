//Funciones
//-------------------------------------------------------

//Función que crea un eje de coordenadas con un cilindro
function ejeCoord(x1,y1,z1,x2,y2,z2,color,radio){
	//Se crea un instancia de Objeto3D que englobará todas las geometrías que conformarán los ejes de coordenadas.
	var result = new THREE.Object3D();
	//Se calcula la distancia entre el extremo y el origen para cada coordenada.
	var dx = x2-x1;
	var dy = y2-y1;
	var dz = z2-z1;
	//Módulo
	var r = Math.sqrt(dx*dx+dy*dy+dz*dz);
	//Material que se le aplicará a las geometrías que vamos a construir.
	var material = new THREE.MeshBasicMaterial({ color: color});
	//Creamos el eje como cilindro en vez de línea para que así se vea más claro.
	var cilinder = new THREE.Mesh(new THREE.CylinderGeometry(radio,radio,r,30,30, false),material);
	//Le damos una posición al eje, que será la que ocupará en el escenario.	
	cilinder.position.set(0,r/2,0);
	//Añadimos el eje al Objeto3D
	result.add(cilinder);
					
	//Se aplica rotación al eje para situarlo en la dirección correcta.
	if (dx ==0 && dz == 0 && dy < 0)
		result.rotation.z = Math.PI;
	else {
		var orig = new THREE.Vector3(0,r,0);
		orig.normalize();
		var align = new THREE.Vector3(dx,dy,dz);
		align.normalize();
		var axis = new THREE.Vector3();
		axis.cross(orig,align);
		axis.normalize();
		var angle = Math.acos(align.dot(orig));
		var q = new THREE.Quaternion();
		q.setFromAxisAngle(axis,angle);
		result.useQuaternion = true;
		result.quaternion = q;
	}	
	result.position.set(x1,y1,z1);
	return(result);
}
			
///Función que crea texto en 3D
function texto(x,y,z,str,size,color,activado){
	//Creamos Objeto3D
	var result = new THREE.Object3D();
	//Creamos el material o textura que se le aplicará al objeto.
	var material = new THREE.MeshBasicMaterial( { color: color } );
	//Se crea una geometria de tipo texto con el tipo de fuente "helvetiker".
	var textWhy = new THREE.TextGeometry( str, { size: size,height: 0.15, curveSegments: 6, font: "helvetiker", weight: "normal", style: "normal"});
	//La geometría creada una vez que se le aplica el material, da lugar al objeto.
	var text = new THREE.Mesh(textWhy,material);
	//Se le asigna una posición al texto 3D.
	text.position.set(x,y,z);
	//Se le indica que esté activo.
	text.visible = activado;
	//Se añade a la instancia Objeto3D.
	result.add(text);
	return(result);
}

//Función que crea un objeto de tipo punto3D
function crearPunto(posicion, color, radio) {
	//Se crea el material para el punto3D
	var puntoMaterial = new THREE.MeshBasicMaterial( { color: color, transparent:true, opacity:1} );
	//Se crea el punto 3D
	var result = new THREE.Mesh( new THREE.SphereGeometry(radio, 30, 20 ), puntoMaterial);
	//Se le asigna una posición dentro del escenario.
	result.position.set(posicion.x, posicion.y, posicion.z);
	return(result);
}

//Función que crea un objeto de tipo vector3D a partir de un cilindro y un cono.
function crearVector(vstart,vend,color){
	//Se crea instancia del Objeto3D.
	var result = new THREE.Object3D();
	//Distancia entre punto origen y extremo para cada coordenada.
	var dx = vend.x-vstart.x;
	var dy = vend.y-vstart.y;
	var dz = vend.z-vstart.z;
	//Módulo del vector.
	var r = Math.sqrt(dx*dx+dy*dy+dz*dz);
	//Material del vector.
	var material = new THREE.MeshBasicMaterial({ color: color });
	//Creamos el cilindro.
	var cilinder = new THREE.Mesh(new THREE.CylinderGeometry(0.05, 0.05, r-1, 30, 30, false),material);	
	//Se le asigna una posición en el escenario.
	cilinder.position.set(0,(r-1)/2,0);
	result.add(cilinder);
				
	//Creamos un coño que será la flecha del vector.
	var cono = new THREE.Mesh(new THREE.CylinderGeometry(0, 4*0.05, 1, 30, 30, false),material);
	//Se le asigna como posición el extremo del cilindro.
	cono.position.set(0,r-0.5,0);
	result.add(cono);
				
	//Se calcula la rotación que tomará el vector según su posición.
	if (dx ==0 && dz == 0 && dy < 0)
		result.rotation.z = Math.PI;
	else {
		var orig = new THREE.Vector3(0,r,0);
		orig.normalize();
		var align = new THREE.Vector3(dx,dy,dz);
		align.normalize();
		var axis = new THREE.Vector3();
		axis.cross(orig,align);
		axis.normalize();
		var angle = Math.acos(align.dot(orig));
		var q = new THREE.Quaternion();
		q.setFromAxisAngle(axis,angle);
		result.useQuaternion = true;
		result.quaternion = q;
	}
	result.position.set(vstart.x,vstart.y,vstart.z);
	return(result);		
}

//Función que crea un objeto de tipo vector3D permitiendo que éste sea visible o invisible.
function crearVector2(vstart,vend,color,visible){
	var result = new THREE.Object3D();
	var dx = vend.x-vstart.x;
	var dy = vend.y-vstart.y;
	var dz = vend.z-vstart.z;
	var r = Math.sqrt(dx*dx+dy*dy+dz*dz);
	var material = new THREE.MeshBasicMaterial({ color: color });
	var cilinder = new THREE.Mesh(new THREE.CylinderGeometry(0.05, 0.05, r-1, 30, 30, false),material);	
	cilinder.position.set(0,(r-1)/2,0);
	cilinder.visible = visible;
	result.add(cilinder);
				
	var cono = new THREE.Mesh(new THREE.CylinderGeometry(0, 4*0.05, 1, 30, 30, false),material);
	cono.position.set(0,r-0.5,0);
	cono.visible = visible;
	result.add(cono);
				
	if (dx ==0 && dz == 0 && dy < 0)
		result.rotation.z = Math.PI;
	else {
		var orig = new THREE.Vector3(0,r,0);
		orig.normalize();
		var align = new THREE.Vector3(dx,dy,dz);
		align.normalize();
		var axis = new THREE.Vector3();
		axis.cross(orig,align);
		axis.normalize();
		var angle = Math.acos(align.dot(orig));
		var q = new THREE.Quaternion();
		q.setFromAxisAngle(axis,angle);
		result.useQuaternion = true;
		result.quaternion = q;
	}
	result.position.set(vstart.x,vstart.y,vstart.z);
	return(result);		
}

//Función que calcula la suma vectorial, resta vectorial y producto vectorial de 2 vectores
function operacionVectores(puntoV, puntoW, extremoResultado, color, parametros, indicadorOperacion) {
	var result = new THREE.Object3D();
	var puntoOrigen = new THREE.Vector3(0,0,0);

	if(indicadorOperacion == "Suma") {
		extremoResultado.add(puntoV,puntoW);
		
	}
	else if(indicadorOperacion == "Resta") {
		extremoResultado.sub(puntoV,puntoW);
	}
	else if(indicadorOperacion == "Producto Cruz") {
		extremoResultado.cross(puntoV,puntoW);
	}
	
	//Añade los valores obtenidos a los campos de la interfaz de control del escenario.
	parametros.vsx1 = puntoOrigen.x;
	parametros.vsy1 = puntoOrigen.y;
	parametros.vsz1 = puntoOrigen.z;
	parametros.vResultadoX = extremoResultado.x;
	parametros.vResultadoY = extremoResultado.y;
	parametros.vResultadoZ = extremoResultado.z; 
	//Se crea el vector resultado.
	result = crearVector(puntoOrigen,extremoResultado,color);
	return(result);
}

//Función que calcula suma, resta y producto vectorial de 'n' vectores
function operacionVectoresCuestionario(puntoOrigen, puntoExtremo, extremoResultado, color, parametros, indicadorOperacion) {
	var result = new THREE.Object3D();
	
	if(indicadorOperacion == "Suma") {
		for(var n=0;n<puntoExtremo.length;n++) {
			extremoResultado.add(puntoExtremo[n],extremoResultado);
		}
	}
	else if(indicadorOperacion == "Resta") {
		for(var n=0;n<puntoExtremo.length;n++) {
			if(n==0) {
				extremoResultado.copy(puntoExtremo[0]);
			}
			else {
				extremoResultado.sub(extremoResultado,puntoExtremo[n]);
			}
		}
		
	}
	else if(indicadorOperacion == "Producto Cruz") {
		//Lo que ocurre es que al realizar   extremoResultado.crossSelf(puntoExtremo[n]), el valor de extremoResultado, extrañamente se asigna
		//también a puntoExtremo[n]. Hay que arreglar eso con ayuda de una variable auxiliar donde guardamos los datos previo a cross y los recogemos después.
		// Se arregla usando copy()
		extremoResultado.copy(puntoExtremo[0]);
			
		for(var n=1;n<puntoExtremo.length;n++) {
			extremoResultado.crossSelf(puntoExtremo[n]);
		}
	}
	
	//Se le asignan a los campos de la interfaz los resultados obtenidos.
	parametros.vRx = extremoResultado.x;
	parametros.vRy = extremoResultado.y;
	parametros.vRz = extremoResultado.z; 
	parametros.modulo = extremoResultado.length();

	//Se crea el vector resultado.
	result = crearVector(puntoOrigen,extremoResultado,color);
	return(result);
	
}

//Función que calcula el resultado de un vector por un escalar
function vectorPorEscalar(origenVector, extremoVector, escalar, extremoResultado, color, parametros) {
	var result = new THREE.Object3D();
	
	//Vector por escalar.
	extremoVector.multiplyScalar(escalar);
	
	extremoResultado.x = extremoVector.x;
	extremoResultado.y = extremoVector.y;
	extremoResultado.z = extremoVector.z;
	
	//Se crea el vector resultado.
	result = crearVector(origenVector,extremoVector,color);
	
	//Se le asignan los valores calculados a los campos de la interfaz.
	parametros.vrx = extremoVector.x;
	parametros.vry = extremoVector.y;
	parametros.vrz = extremoVector.z;
	extremoVector.x = parametros.vABx;
	extremoVector.y = parametros.vABy;
	extremoVector.z = parametros.vABz;
	return(result);
}

//Función que calcula el producto Escalar de 2 vectores.
function productoEscalar(extremoVectorAB, extremoVectorCD, parametros) {
	//Ángulo en radianes que forman los 2 vectores.
	var anguloRad = parseFloat(Math.acos(extremoVectorAB.dot(extremoVectorCD)/(extremoVectorAB.length() * extremoVectorCD.length())));
	anguloRad = +anguloRad || 0;
	//Ángulo en grados.
	var anguloGrad = anguloRad * 180 / Math.PI;
	anguloGrad = +anguloGrad || 0;
	//Se asigna el valor de los ángulos a los campos de la interfaz.
	parametros.angulo = anguloGrad;
	parametros.anguloRad = anguloRad;
	//Producto Escalar
	parametros.productoEscalar = extremoVectorAB.dot(extremoVectorCD);
}

//Función que calcula el producto Mixto de 3 vectores.
function productoMixto(puntoU, puntoV, puntoW, extremoResultado, parametros) {
	var anguloRad;
	var anguloGrad;
	//Producto Mixto
	parametros.productoMixto = puntoU.dot(extremoResultado);
	//Ángulo en radianes entre el vector V y el vector W.
	anguloRad = Math.acos(puntoV.dot(puntoW)/(puntoV.length() * puntoW.length()));
	anguloRad = +anguloRad || 0;
	//Ángulo en grados
	anguloGrad = anguloRad * 180 / Math.PI;
	anguloGrad = +anguloGrad || 0;
	//Se asigna el valor de los ángulos a la interfaz.
	parametros.anguloVW = anguloGrad;
	parametros.anguloVWRad = anguloRad;
	//Ángulo entre U y (V x W)
	anguloRad = Math.acos(puntoU.dot(extremoResultado)/(puntoU.length() * extremoResultado.length()));
	anguloRad = +anguloRad || 0;
	anguloGrad = anguloRad * 180 / Math.PI;
	anguloGrad = +anguloGrad || 0;
	parametros.anguloUyVW = anguloGrad;
	parametros.anguloUyVWRad = anguloRad;
} 

//Función que cambia el tamaño del escenario 3D.
function escalaEscenario() {
	// Grid
	
	var result = new THREE.Object3D();
	var step = 1;
	//Se crea la malla metálica
	var geometry = new THREE.Geometry();
	for ( var i = - size; i <= size; i += step ) {
		geometry.vertices.push( new THREE.Vector3( - size, 0, i ) );
		geometry.vertices.push( new THREE.Vector3(   size, 0, i ) );
		geometry.vertices.push( new THREE.Vector3( i, 0, - size ) );
		geometry.vertices.push( new THREE.Vector3( i, 0,   size ) );
	}
	//Material para asignar a la malla.
	var material = new THREE.LineBasicMaterial( { color: 0x000000, opacity: 0.1 } );
	var line = new THREE.Line( geometry, material );
	line.type = THREE.LinePieces;
	result.add(line);
				
	//Ejes de coordenadas
	var ejeY = new THREE.Object3D();
	ejeY.add(ejeCoord(0,-size,0,0,size,0,0x000,0.04));
	result.add(ejeY);
				
	var ejeZ = new THREE.Object3D();
	ejeZ.add(ejeCoord(0,0,-size,0,0,size,0x000,0.04));
	result.add(ejeZ);
				
	var ejeX = new THREE.Object3D();
	ejeX.add(ejeCoord(-size,0,0,size,0,0,0x000,0.04));
	result.add(ejeX);
				
	//Texto ejes
	var textoEjesAux = new THREE.Object3D();
	textoEjesAux.add(texto(size+1,0,0,"X",0.5,0x000,true));
	textoEjesAux.add(texto(0,size+1,0,"Y",0.5,0x000,true));
	textoEjesAux.add(texto(0,0,size+1,"Z",0.5,0x000,true));
	textoEjesAux.add(texto(-(size+1),0,0,"-X",0.5,0x000,true));
	textoEjesAux.add(texto(0,-(size+1),0,"-Y",0.5,0x000,true));
	textoEjesAux.add(texto(0,0,-(size+1),"-Z",0.5,0x000,true));
	result.add(textoEjesAux);

	return(result);
	
}

//Función que crea puntos auxiliares 
function puntoAuxEjes(x,y,z,radio,color,puntosActivos){
	var result = new THREE.Object3D();
	//Material del punto
	var material = new THREE.MeshBasicMaterial( { color: color} );
	//Creamos el punto.
	var punto = new THREE.Mesh( new THREE.SphereGeometry(radio, 30, 20 ), material);
	//Le damos una posición inicial.
	punto.position.set(x,y,z);
	//Dotamos o quitamos al punto la visibilidad.
	punto.visible = puntosActivos;
	result.add(punto);
	return(result);
}

//Función alternativa para crear puntos auxiliares
function puntoAuxEjes2(x,y,z,radio,color,puntosActivos,visible){
	var result = new THREE.Object3D();
	var material = new THREE.MeshBasicMaterial( { color: color} );
	var punto = new THREE.Mesh( new THREE.SphereGeometry(radio, 30, 20 ), material);
	punto.position.set(x,y,z);
	if(puntosActivos == visible)
		punto.visible = puntosActivos;
	else
		punto.visible = false;
	result.add(punto);
	return(result);
}			

//Función que crea líneas auxiliares
function lineasAuxVector(vector, color, lineasActivas) {
	var result = new THREE.Object3D();
	function v(x,y,z){ 
		return new THREE.Vector3(x,y,z); 
	}
	var lineasAyuda = new THREE.Geometry();
	lineasAyuda.vertices.push(
		v(vector.x, 0, 0), v(vector.x, 0, vector.z),
		v(0, vector.y, 0), v(vector.x, vector.y, vector.z),
		v(0, 0, vector.z), v(vector.x, 0, vector.z),
		v(vector.x, 0, vector.z), v(vector.x, vector.y, vector.z) 
	);
	var lineasMaterial = new THREE.LineDashedMaterial({color: color, lineWidth: 1.5, dashSize: 0.2, gapSize: 0.2});
	lineasAyuda.computeLineDistances(); //IMPORTANTE. Si no ejecutamos esto, las líneas aparecen lisas como si usásemos LineBasicMaterial().
	var lineasVector = new THREE.Line(lineasAyuda, lineasMaterial);
	lineasVector.type = THREE.LinePieces;
	lineasVector.visible = lineasActivas;
	result.add(lineasVector);
	return(result);
}

//Función alternativa para crear líneas auxiliares
function lineasAuxVector2(vector,color,lineasActivas,visible) {
	var result = new THREE.Object3D();
	function v(x,y,z){ 
		return new THREE.Vector3(x,y,z); 
	}
	var lineasAyuda = new THREE.Geometry();
	lineasAyuda.vertices.push(
		v(vector.x, 0, 0), v(vector.x, 0, vector.z),
		v(0, vector.y, 0), v(vector.x, vector.y, vector.z),
		v(0, 0, vector.z), v(vector.x, 0, vector.z),
		v(vector.x, 0, vector.z), v(vector.x, vector.y, vector.z) 
	);
	var lineasMaterial = new THREE.LineDashedMaterial({color: color, lineWidth: 1.5, dashSize: 0.2, gapSize: 0.2});
	lineasAyuda.computeLineDistances(); //IMPORTANTE. Si no ejecutamos esto, las líneas aparecen lisas como si usásemos LineBasicMaterial().
	var lineasVector = new THREE.Line(lineasAyuda, lineasMaterial);
	lineasVector.type = THREE.LinePieces;
	if(lineasActivas == visible)
		lineasVector.visible = lineasActivas;
	else
		lineasVector.visible = false;
	result.add(lineasVector);
	return(result);
}

//Función que reúne en un solo objeto 3D tanto puntos como líneas auxiliares
function ayudaVisual(vector,color,puntosActivos,lineasActivas) {
	var result = new THREE.Object3D();
	result.add(puntoAuxEjes(vector.x,0,0,0.1,color,puntosActivos));
	result.add(puntoAuxEjes(0,vector.y,0,0.1,color,puntosActivos));
	result.add(puntoAuxEjes(0,0,vector.z,0.1,color,puntosActivos));
	result.add(lineasAuxVector(vector,color,lineasActivas));
	return(result);
}

//Función que reúne en un solo objeto 3D tanto puntos como líneas auxiliares
function ayudaVisual2(vector,color,puntosActivos,lineasActivas,visible) {
	var result = new THREE.Object3D();
	result.add(puntoAuxEjes2(vector.x,0,0,0.1,color,puntosActivos,visible));
	result.add(puntoAuxEjes2(0,vector.y,0,0.1,color,puntosActivos,visible));
	result.add(puntoAuxEjes2(0,0,vector.z,0.1,color,puntosActivos,visible));
	result.add(lineasAuxVector2(vector,color,lineasActivas,visible));
	return(result);
}

//Función que dibuja las líneas de separación de los ejes de coordenadas.
function lineasEjes(activado) {
	var result = new THREE.Object3D();
	var material = new THREE.MeshBasicMaterial({ color: 0x000 });
	var cilinderX, cilinderY, cilinderZ, cilinderNX, cilinderNY, cilinderNZ; 
	var i;

		for(i=1;i<=size;i++) {
			cilinderX = new THREE.Mesh(new THREE.CylinderGeometry(0.05, 0.05, 0.5, 30, 30, false),material);
			cilinderY = new THREE.Mesh(new THREE.CylinderGeometry(0.05, 0.05, 0.5, 30, 30, false),material);
			cilinderZ = new THREE.Mesh(new THREE.CylinderGeometry(0.05, 0.05, 0.5, 30, 30, false),material);
			cilinderNX = new THREE.Mesh(new THREE.CylinderGeometry(0.05, 0.05, 0.5, 30, 30, false),material);
			cilinderNY = new THREE.Mesh(new THREE.CylinderGeometry(0.05, 0.05, 0.5, 30, 30, false),material);
			cilinderNZ = new THREE.Mesh(new THREE.CylinderGeometry(0.05, 0.05, 0.5, 30, 30, false),material);
			cilinderX.position.set(i,0,0);
			cilinderNX.position.set(-i,0,0);
			cilinderY.rotation.z = Math.PI/2;
			cilinderY.position.set(0,i,0);
			cilinderNY.rotation.z = Math.PI/2;
			cilinderNY.position.set(0,-i,0);
			cilinderZ.position.set(0,0,i);
			cilinderNZ.position.set(0,0,-i);
			cilinderX.visible = activado;
			cilinderY.visible = activado;
			cilinderZ.visible = activado;
			cilinderNX.visible = activado;
			cilinderNY.visible = activado;
			cilinderNZ.visible = activado;
			result.add(cilinderX);
			result.add(cilinderY);
			result.add(cilinderZ);
			result.add(cilinderNX);
			result.add(cilinderNY);
			result.add(cilinderNZ);
		}
	return(result);
}

//Función que dibuja la numeración de los ejes de coordenadas con texto 3D.
function numEjes(activado) {
	var result = new THREE.Object3D();
	var i;

		for(i=1;i<=size;i++) {
			result.add(texto(i-0.1,0.5,0,i,0.3,0x000,activado));
			result.add(texto(-i-0.1,0.5,0,-i,0.3,0x000,activado));
			if(i>=10) {
				result.add(texto(-1,i,0,i,0.3,0x000,activado));
				result.add(texto(-1.2,-i,0,i,0.3,0x000,activado));
			}
			else { 
				result.add(texto(-0.8,i,0,i,0.3,0x000,activado));
				result.add(texto(-1,-i,0,-i,0.3,0x000,activado));
			}
			result.add(texto(-0.1,0.5,i,i,0.3,0x000,activado));
			result.add(texto(-0.1,0.5,-i,-i,0.3,0x000,activado));
		}
	return(result);
}

