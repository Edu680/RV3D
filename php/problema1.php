<?php
	session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//ES" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>Cuestionario</title>	
		<script type="text/javascript">
			$("#contenedor").css("height", "100%");
		</script>
		<script type="text/javascript" src="../js/escenaInicial.js"></script>
		<script type="text/javascript" src="../js/interfazGenerica.js"></script>
		
		<style>
			/*Se esconde el menú principal para que el usuario no pueda salir del cuestionario hasta terminarlo*/
			div#barramenu {
				display: none;
			}

			#correcto {
				color: green;
			}
			#incorrecto {
				color: red;
			}
		</style>
		<script type="text/javascript">

	var operacion;
	$(document).ready(cargarTest);
	
	function cargarTest(){
		var pregunta;
		var formulario;
		var datos = new Array();
		//Se deshabilita el enlace de cerrar sesión para que el usuario no pueda salir del cuestionario hasta terminarlo.
		$("#pie").click("a", function() {
			return false;
		});
		/*Método ajax() que llama al script cargarCuestionario.php y éste le devuelve un objeto JSON con el enunciado de la pregunta del cuestionario y
		el tipo de operación a realizar*/
		$.ajax({
			url: '../mysql/cargarCuestionario.php',
			type: 'POST',
			success: function(data) {
						var $dat = data;
						datos = $.parseJSON($dat);
						pregunta = '<strong>Pregunta ' + datos[1] +': </strong>' + datos[0];
						$("#pregunta").append(pregunta);
						operacion = datos[2];
						$("#pregunta").fadeIn('fast',function(){
							//Dependiendo del tipo de problema, el formulario de respuesta será de un tipo u otro.
							if( operacion == "productoMixto" || operacion == "productoEscalar" || operacion.substring(0,14) == "anguloRadianes" || operacion.substring(0,12) == "anguloGrados" || operacion == 'area' || operacion == 'volumen') {
								formulario = 'Respuesta: <input type="text" name="respuesta" id="respuesta" value="" size="5" />					<input type="button" id="boton" value="Enviar respuesta" />';

								$("#formdata").append(formulario);
								$("#boton").button();
							}
							else {
								formulario = 'Respuesta: (<input type="text" name="respuestaX" id="respuestaX" value="x" size="5" />,'+
												'<input type="text" name="respuestaY" id="respuestaY" value="y" size="5" />,'+
												'<input type="text" name="respuestaZ" id="respuestaZ" value="z" size="5" />)						<input type="button" id="boton" value="Enviar respuesta" />';
								$("#formdata").append(formulario);
								$("#boton").button();
							}
							
						});
						
			}
				
		});

		var x = $("input:text");
		x.focus(enfocar);
		x.hover(entraRaton, saleRaton);

		//Al pulsar el botón "Enviar respuesta", ésta se valida para saber si es correcta o incorrecta.
		$("#respuestas").on("click", "input:button", function() {
			verificarRespuesta();
		});

		//Botón de ayuda con icono de bombilla
		$("#botonAyuda").button({
			icons: {
				primary: 'ui-icon-lightbulb'
			}
		});
		//Al pulsar en el botón de ayuda, según los permisos del usuario, se muestra la ventana de ayuda con cada módulo separado por pestañas
		$("#botonAyuda").click(function() {
			if(<?= $_SESSION['permisos'];?> == 1) {
				$("#tabsGestion").show();
			}
			$("#tabsAyuda").tabs();
			$("#ventanaAyuda").dialog({
				width: 500,
				//height: 500,
				title: 'Ayuda'
			});
		});
	}
	
	function enfocar(){
		$(this).attr("value", "");
		$("#correcto").hide();
		$("#incorrecto").hide();
	}
	function entraRaton() {
		$(this).css("border-color", "#6eac2c");	
	}
	function saleRaton() {
		$(this).css("border-color", "");	
	}			
	//Función que envía los datos del formulario de respuesta para que sean validados
	function verificarRespuesta() {
		$.post("comprobarRespuesta.php",$("#formdata").serialize(),recibir);	
	}	
	/*Función que recibe la salida de comprobarRespuesta.php en función de si los datos introducidos por el usuario como solución al problema 
	son correctos o no y si la pregunta contestada es la última o no. */
	function recibir(datos){

		if(datos == 1){
			$("#correcto").fadeIn(1000, function() {
				//$("#contenedor").css("height", "100%");
				$("#contenido").load("problema1.php");
				
			});
		}
		else if(datos == 0){
			$("#incorrecto").fadeIn(1000, function() {
				//$("#contenedor").css("height", "100%");
				$("#contenido").load("problema1.php");
				
			});
		}
		else if(datos == 2) {
			$("#correcto").fadeIn(1000, function() {
				$("#contenido").load("../mysql/calificacionCuestionario.php");
			});
		}
		else if(datos == 3) {
			$("#incorrecto").fadeIn(1000, function() {
				$("#contenido").load("../mysql/calificacionCuestionario.php");
			});
		}
	}

	
	
</script>
	</head>

	<body>
		
			<div id="pregunta" class="ui-corner-all" style="display:none"></div>
			<div id="contenido" class="limpiar">
        	<div id="grafico" class="ui-corner-all">
				<div id="crearVector" title="Añadir vector" style="display:none">
					<h2>X: <input type="text" name="coordX" id="coordX" value="" size="5" />
					Y: <input type="text" name="coordY" id="coordY" value="" size="5" />
					Z: <input type="text" name="coordZ" id="coordZ" value="" size="5" /><br>
					<br><input type="button" id="botonAdVector" value="Añadir" />
					</h2>
				</div>
				<div id="botonAyuda"></div>
            	<div id="inset" class="ui-corner-all"></div>
				<div id="stats"></div>
           	</div>
            <div id="interfaz" class="ui-corner-all"></div>
			</div>
			<div id="respuestas" class="ui-corner-all">
				<div id="correcto" style="display:none">CORRECTO</div>
				<div id="incorrecto" style="display:none">INCORRECTO</div>
				<form method="post" id="formdata" >
				</form>
			</div>
			<!-- Ayuda de la aplicación-->
			<div id="ventanaAyuda" style="display:none">
			<div id="tabsAyuda">
			 <ul>
				<li><a href="#fragment-1"><span>RV3D</span></a></li>
				<li><a href="#fragment-2"><span>Ejemplos</span></a></li>
				<li><a href="#fragment-3"><span>Practica</span></a></li>
				<li><a href="#fragment-4"><span>Problemas</span></a></li>
				<li><a href="#fragment-5" id="tabsGestion" style="display:none"><span>Gestión Alumnos</span></a></li>
			  </ul>
			  <div id="fragment-1" class="panel" style="text-align:justify">
				<p><h3>Bienvenido a RV3D</h3></p>
				<p><strong>¿Qué es RV3D?</strong></p>
				<p><span class="sangrar">RV3D son las siglas de <strong>R</strong>epresentación de <strong>V</strong>ectores en <strong>3D.</strong></span></p>
				<p>Es una aplicación Web desarrollada por Eduardo Pérez Cañas para el departamento de Física Aplicada como Proyecto Fin de Carrera de la Ingeniería Técnica en Informática de Gestión.</p>
				<br><p><strong>¿Para qué sirve?</strong></p>
				<p><span class="sangrar">RV3D está enfocada como herramienta de ayuda para que los alumnos de primer año de carrera que no tengan claros los conceptos básicos en materia de vectores, mediante su uso adquieran una base sólida gracias a la interacción con los ejemplos planteados y la realización de cuestionarios.</span></p>
				<br><p><strong>Suena bien pero, ¿cómo la uso?</strong></p>
				<p><span class="sangrar">Como puedes observar, moverse por aqui es muy sencillo.</span></p>
				<p class="alumno">Arriba, justo debajo del título, tienes un menú horizontal con las opciones <u>Ejemplos</u>, <u>Practica</u>, <u>Problemas</u> y <u>Ayuda</u> desde el que podrás acceder a cada uno de esos módulos.</p>
				<p>Encontrarás información detallada sobre cada módulo pinchando en la pestaña correspondiente de esta ayuda.</p>
				<br><p><strong>¿Y cuando quiera salir?</strong></p>
				<p><span class="sangrar">Tan fácil como finalizar tu sesión de usuario pinchando en el enlace situado a la derecha de tu nombre que encontrarás en el pie de página.</span></p>
				<br><p><strong>¿Y la barra roja que me aparece debajo del pie de página?</strong></p>
				<p><span class="sangrar">La barra roja aparece, como bien indica, debido a que tu navegador no soporta el renderizado de gráficos 3D con WebGL, por lo que la aplicación carga los ejemplos con renderizado Canvas, que aparte de que tienen una peor calidad de imagen, pueden no mostrarse o mostrarse con cierta dificultad algunas de las opciones visuales disponibles para cada gráfico de ejemplo.</span></p>
				<br><p><strong>Vaya, ¿y eso como lo arreglo?</strong></p>
				<p><span class="sangrar">Puedes cambiar de navegador. Te recomiendo Google Chrome que ha demostrado ser el más fiable en la representación con renderizado WebGL. Si aún así te sigue apareciendo la barra roja, tu tarjeta gráfica no soporta renderizado de gráficos con WebGL, bien porque tenga esa opción deshabilitada, el controlador no esté actualizado o simplemente porque no tiene soporte para ello.</span></p>
			  </div>
			  <div id="fragment-2" class="panel" style="text-align:justify">
				<p><strong>¿Qué puedo hacer aquí?</strong></p>
				<p><span class="sangrar">De manera predeterminada, cuando accedes a la aplicación, se muestra la representación 3D correspondiente al ejemplo <strong>Vector</strong>, pero si quieres acceder a cualquier otro,
				pincha o pasa con el ratón por encima de <strong>Ejemplos</strong> en el menú horizontal. Se desplegará otro submenú horizontal mostrando cada uno de los ejemplos disponibles. Pincha sobre el que quieras para acceder a su representación 3D.</span></p>
				<p>Cada ejemplo lo forman un escenario 3D, donde se representará el gráfico del ejemplo, y una interfaz de control para ese escenario. Dependiendo del ejemplo que se represente, la interfaz de control variará ligeramente.</p>
				<br><p><strong>&bull; Escenario 3D</strong></p>
				<p><span class="sangrar">Es el área de representación gráfica del ejemplo. Se compone de, lo que denominaremos de aquí en adelante, un <strong>Escenario inicial</strong>, que consiste en los 3 ejes de coordenadas del espacio (<italic>x, y, z</italic>) y una malla perpendicular al eje <italic>y</italic>; unos ejes de coordenadas auxiliares, colocados en la zona inferior izquierda del escenario; y el objeto u objetos 3D representativos del ejemplo.</span></p>
				<br><p>El escenario se puede:</p>
				<p><span class="sangrar">&bull; <strong>Rotar:</strong> Manteniendo pulsado el botón izquierdo del ratón y moviendo este por encima del escenario.</span></p>
				<p><span class="sangrar">&bull; <strong>Mover:</strong> Manteniendo pulsado el botón derecho del ratón y moviendo este por encima del escenario.</span></p>
				<p><span class="sangrar">&bull; <strong>Acercar:</strong> Moviendo la rueda del ratón hacia delante o manteniendo pulsado el botón o rueda central del ratón y moviendo hacia delante sobre el escenario.</span></p>
				<p><span class="sangrar">&bull; <strong>Alejar:</strong> Moviendo la rueda del ratón hacia atrás o manteniendo pulsado el botón o rueda central del ratón y moviendo hacia atrás sobre el escenario.</span></p>
				<br><p><strong>&bull; Interfaz de control</strong></p>
				<p><span class="sangrar">Es la zona desde la que puedes modificar diferentes parámetros de entrada del escenario 3D, obteniendo sus correspondientes parámetros de salida, reflejados tanto en el escenario como en la propia interfaz.</span></p>
				<br><p>Desde la interfaz puedes:</p>
				<p><span class="sangrar">&bull; <strong>Cambiar coordenadas:</strong> Introduciendo en el campo correspondiente a cada coordenada el valor y pulsando la tecla <italic>Intro</italic>. Para el ejemplo <strong>Punto</strong>, se puede además cambiar su posición a través del slider disponible.</span></p>
				<p><span class="sangrar">&bull; <strong>Cambiar color del objeto 3D:</strong> Mediante la paleta de colores, puedes seleccionar el color que quieras para cada punto o vector representado.</span></p>
				<p><span class="sangrar">&bull; <strong>Seleccionar operación:</strong> En algunos ejemplos se pueden representar más de una operación. Para ello dispones de una lista desplegable donde podrás elegir la operación que quieras representar.</span></p>
				<p><span class="sangrar">&bull; <strong>Calcular resultado:</strong> Hay ejemplos en los que no aparece representado el resultado inicialmente y para el que tendrás que pulsar sobre el botón <italic>CALCULAR</italic> para obtener la solución.</span></p>
				<p><span class="sangrar">&bull; <strong>Activar/Desactivar ayudas visuales:</strong> En la pestaña <italic>Ayudas visuales</italic> encontrarás, dependiendo de cada ejemplo, diferentes ayudas a nivel gráfico que puedes aplicar al escenario. Estas ayudas son:</span></p>
				<p><span class="sangrar2"><strong>- <italic>Mostrar puntos:</italic></strong> Activando el <italic>check-box</italic> se muestra en el escenario, para cada objeto 3D representado, 3 puntos, cada uno a lo largo de un eje de coordenadas indicando la posición para ese eje de la coordenada del objeto. Disponible para todos los ejemplos.</span></p>
				<p><span class="sangrar2"><strong>- <italic>Mostrar líneas:</italic></strong> Activando el <italic>check-box</italic> se muestra en el escenario, para cada objeto 3D representado, una serie de líneas discontínuas que unen cada eje de coordenadas con el objeto 3D de manera que relacionan cada coordenada del objeto con su eje. Disponible para todos los ejemplos.</span></p>
				<p><span class="sangrar2"><strong>- <italic>Mostrar numeración ejes:</italic></strong> Activando el <italic>check-box</italic> se muestra en el escenario la numeración de cada punto de los ejes de coordenadas con una precisión de una unidad. Disponible para todos los ejemplos.</span></p>
				<p><span class="sangrar2"><strong>- <italic>Mostrar separación ejes:</italic></strong> Activando el <italic>check-box</italic> se muestra en el escenario la separación de cada punto de los ejes de coordenadas con una precisión de una unidad. Disponible para todos los ejemplos.</span></p>
				<p><span class="sangrar2"><strong>- <italic>Tamaño escenario:</italic></strong> Seleccionando de la lista desplegable una de las opciones disponibles, cambiará el tamaño del escenario. Cada valor de la lista indica la longitud para cada eje, desde el punto origen (coordenada 0) hasta el extremo del eje. Disponible para todos los ejemplos salvo <italic>Punto</italic> y <italic>Vector</italic>.</span></p>
				<p><span class="sangrar2"><strong>- <italic>Descomponer:</italic></strong> Activando el <italic>check-box</italic> se muestran las componentes cartesianas del vector representado. Disponible únicamente para el ejemplo <italic>Vector</italic>.</span></p>
				<p><span class="sangrar2"><strong>- <italic>Mostrar opuesto:</italic></strong> Activando el <italic>check-box</italic> se muestra el vector opuesto del vector representado. Disponible únicamente para el ejemplo <italic>Vector</italic>.</span></p>
				<br><p>Como parámetros de salida en la interfaz puedes tener:</p>
				<p><span class="sangrar">&bull;<strong> Módulo: </strong> Módulo de cada vector representado, ya sea vector inicial o vector resultado.</span></p>
				<p><span class="sangrar">&bull;<strong> Ángulos directores: </strong> Ángulos que forma el vector representado con cada uno de los 3 ejes de coordenadas en grados y radianes. Disponible únicamente para el ejemplo <italic>Vector</italic>.</span></p>
				<p><span class="sangrar">&bull;<strong> Ángulo: </strong> Ángulo que forman dos vectores representados. Disponible para los ejemplos <italic>Producto Escalar</italic> y <italic>Producto Mixto</italic>.</span></p>
				<p><span class="sangrar">&bull;<strong> Producto escalar (V · W):</strong> Producto escalar de los vectores <italic>v</italic> y <italic>w</italic>.</span></p>
				<p><span class="sangrar">&bull;<strong> Producto mixto (U · (V x W)):</strong> Producto mixto del vector <italic>u</italic> y el producto cruz de los vectores <italic>v</italic> y <italic>w</italic>.</span></p>
			  </div>
			  <div id="fragment-3" class="panel" style="text-align:justify">
				<p><strong>¿Qué puedo hacer aquí?</strong></p>
				<p><span class="sangrar">Practicar, practicar, practicar...</span></p>
				<br><p><span class="sangrar">En este módulo te encontrarás con un escenario 3D conteniendo únicamente al escenario inicial y con una interfaz de control, que podriamos llamar genérica, ya que desde ella puedes controlar cualquiera de las operaciones que has podido ver en <strong>Ejemplos</strong>.</span></p>
				<br><p><span class="sangrar">La idea es que te vayas familiarizando con el uso de la interfaz y el escenario 3D. Que vayas añadiendo y eliminando vectores, operando con ellos, interpretando resultados,... ya que más adelante, en el módulo <strong>Problemas</strong>, te será de gran ayuda.</span></p>
				<br><p><span class="sangrar">Para comenzar tendrás que añadir un vector al escenario pulsando sobre el botón <italic>Añadir vector</italic>. Te aparecerá una ventana donde introducirás las coordenadas del vector y tras pulsar en <italic>Añadir</italic> verás que queda representado en el escenario. Puedes añadir tantos vectores como quieras y desde la interfaz, usando la lista <italic>Operación</italic>, puedes operar con ellos.</span><p>
				<br><p><span class="sangrar">Cuando quieras eliminarlos, pulsa en <italic>Eliminar vectores</italic> y el escenario quedará como al principio.</span></p>
				<br><p><span class="sangrar">También dispondrás de las ayudas visuales de los ejemplos: mostrar puntos, mostrar líneas, mostrar numeración ejes, mostrar separación ejes y cambiar tamaño de escenario.</span></p>
			  </div>
			  <div id="fragment-4" class="panel" style="text-align:justify">
				<p><strong>¿Qué puedo hacer aquí?</strong></p>
				<p><span class="sangrar">En este módulo podrás evaluar los conceptos aprendidos en clase en materia de vectores y comprobar si el uso de la aplicación te ha ayudado a asimilar mejor esos conceptos.</span></p>
				<br><p><strong>¿Y en qué consiste esa evaluación?</strong></p>
				<p><span class="sangrar">La evaluación consistirá en contestar 10 preguntas sobre materia de vectores. Cada pregunta acertada suma un punto. Las falladas no suman nada, pero tampoco restan (de momento).</span></p>
				<p>El funcionamiento es bien sencillo: La pregunta aparece arriba, justo debajo tienes el escenario 3D y la interfaz de control tal cual lo has visto y practicado en el módulo <italic>Practica</italic>. Debajo del escenario tienes el formulario para introducir la respuesta y el botón <italic>Enviar respuesta</italic> para validarla. Al validar la respuesta, la aplicación te mostrará un mensaje justo encima del formulario de respuesta indicando si la solución que has introducido es correcta o no.</p>
				<p>Es importante que valides la respuesta ya que es requisito indispensable para pasar a la siguiente pregunta.</p>
				<br><p><strong>Lo he entendido pero, ¿para qué sirve el escenario y la interfaz de control?</strong></p>
				<p><span class="sangrar">Pues sirve para que añadas al escenario la información que aparece en el problema y operes con ella hasta dar con el resultado. En vez de una calculadora, tienes esto.</span></p>
				<br><p><strong>Y cuando termine, ¿cómo sé mi nota?</strong></p>
				<p><span class="sangrar">Al finalizar el cuestionario, se mostrará una página de resultados en el que se indicarán en color verde el número de respuestas acertadas, en rojo el número de respuestas falladas y en negro la nota final. Además se cargará un gráfico lineal de las notas obtenidas en los últimos 20 cuestionarios realizados, así podrás comprobar cómo está siendo tu evolución.</span></p>
			  </div>
			  <div id="fragment-5" class="panel" style="text-align:justify">
				<p><strong>¿Qué puedo hacer aquí?</strong></p>
				<p><span class="sangrar">Desde este módulo podrás observar un listado de todos los alumnos registrados en la aplicación.</span></p>
				<p>Para cada alumno se muestra su nombre y apellidos, email, si se encuentran conectados o no a la aplicación, nota más alta obtenida en el cuestionario, nota más baja y 2 enlaces, uno para eliminarlo de la aplicación y otro para mostrar los resultados de los últimos 20 cuestionarios realizados en una gráfica lineal.</p>
			  </div>
			
			</div>
		</div>
	</body>
</html>


