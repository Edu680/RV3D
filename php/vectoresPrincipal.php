<?php 
	session_start(); 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//ES" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>Operaciones con vectores</title>
		<script type="text/javascript" src="../js/jquery.js"></script>
    	<script type="text/javascript" src="../js/jquery-ui.js"></script>
		<link href="../css/global.css" rel="stylesheet" type="text/css" />
    	<link href="../css/start/jquery-ui-1.10.1.custom.min.css" rel="stylesheet" type="text/css" />
		<link rel="stylesheet" type="text/css" href="../css/morris.css" />
		<!--<script type='text/javascript' src='http://cdnjs.cloudflare.com/ajax/libs/three.js/r54/three.min.js'></script>-->
        <script type="text/javascript" src="../js/three.min.js"></script>
		<script type="text/javascript" src="../js/controls/TrackballControls.js"></script>
		<script type="text/javascript" src="../js/LineDashedMaterial.js"></script>
		<script type="text/javascript" src="../js/helvetiker_regular.typeface.js"></script>
 	 	<script type="text/javascript" src="../js/dat.gui.min.js"></script>
		<script type="text/javascript" src="../js/Stats.js"></script>
		<script type="text/javascript" src="../js/funciones.js"></script>
		<script type="text/javascript" src="../js/Detector.js"></script>
		<script type="text/javascript" src="../js/raphael-min.js"></script>
		<script type="text/javascript" src="../js/morris.min.js"></script>
        
        <style type="text/css">
			.ui-menu { overflow: hidden; z-index: 1;}
			.ui-menu .ui-menu { overflow: visible !important; }
			.ui-menu > li { float: left; display: block; width: auto !important; }
			.ui-menu > li { margin: 5px 5px !important; padding: 0 0 !important; }
			.ui-menu > li > a { float: left; display: block; clear: both; overflow: hidden;}
			.ui-menu .ui-menu-icon { margin-top: 0.3em !important;}
			.ui-menu .ui-menu .ui-menu li { float: left; display: block;}
			.sangrar {
				padding-left: 10%;
			}
			.sangrar2 {
				padding-left: 20%;
			}
			.panel {
				height: 400px;
				overflow: auto;
			}
		</style>
		<script type="text/javascript">
			$(document).ready(function() {
				//Se crea el menú horizontal
				$("#menu").menu({
					position: {at: "left bottom"},
					icons: { submenu: "ui-icon-carat-1-s" }
				});	

				/*Queremos que aparezca de manera predeterminada el ejemplo Vector. Luego cuando vayamos pulsando en
				los distintos enlaces del menú, se irán cargando el resto de ejemplos según proceda */
				$("#contenido").load("vector.php");		

				/*Al pinchar en un enlace, iremos a la pagina asociada a ese enlace. Si es el enlace de Ayuda, se comprueba si el usuario es 
				alumno o profesor para añadir el módulo Gestión de alumnos en la ayuda. La información mostrada se dividirá en pestañas dentro
				de una ventana de diálogo.*/
				$("a").click(function() {
					$.ajax({
						url: '../mysql/funcionOnline.php',
						type: 'POST',
					});
					
					//$("#contenedor").css("height", "100%");
					$("#contenido").load($(this).attr("value"));
					if($(this).attr("id") == "ayuda") {
							if(<?= $_SESSION['permisos'];?> == 1) {
								$("#tabsGestion").show();
							}
							$("#tabsAyuda").tabs();
							$("#ventanaAyuda").dialog({
								width: 500,
								title: 'Ayuda'
							});
					} 
					
				});
				//Si se detecta que el navegador no soporta WebGL, se muestra la barra roja de información.
				if ( ! Detector.webgl ) {
					$("#mserror").show();
				}

				//Si el usuario tiene permisos de profesor, se le mostrará en el menú la opción "Gestión Alumnos"
				if( <?= $_SESSION['permisos'];?> == 1) {
					$("#gestion").show();
				}

    		});
			
		</script>
	</head>

	<body>
    <div id="contenedor">
        <div id="cabecera" class="ui-corner-all">
		<h1>RV3D - Representación de vectores en 3D</h1>
        </div>
		<!-- Menú principal horizontal -->
        <div id="barramenu">
        <ul id="menu">
            <li  style="text-align:center"><a href="#"><strong>Ejemplos</strong></a>
            <ul>
            	<li><a href="#" value="punto.php"><strong>Punto</strong></a></li>
            	<li><a href="#" value="vector.php"><strong>Vector</strong></a></li>
            	<li><a href="#" value="suma_resta_pCruz.php"><strong>Suma/Resta/Producto Vectorial de 2 vectores</strong></a></li>
            	<li><a href="#" value="vectorPorEscalar.php"><strong>Producto de un vector por un escalar</strong></a></li>
            	<li><a href="#" value="productoEscalar.php"><strong>Producto escalar</strong></a></li>
				<li><a href="#" value="productoMixto.php"><strong>Producto mixto</strong></a></li>  	
            </ul>
            </li>
            <li><a href="#" value="practica.php"><strong>Practica</strong></a></li>
            <li><a href="#" value="problemasPrincipal.php"><strong>Problemas</strong></a></li>
			<li><a href="#" id="gestion" value="gestionAlumnos.php" style="display:none"><strong>Gestión Alumnos</strong></a></li>
			<li><a href="#" id="ayuda"><strong>Ayuda</strong></a></li>
		</ul>
        </div>
		<!-- Código HTML para el módulo ayuda de la aplicación-->
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
		
        <div id="contenido" class="limpiar">
			<!-- Div donde se muestra el escenario 3D -->
			<div id="grafico" class="ui-corner-all">
				<!-- Ejes de coordenadas auxiliares -->
            	<div id="inset" class="ui-corner-all"></div>
				<!-- Pantalla de marca los fps del escenario 3D -->
				<div id="stats"></div>
           	</div>
			<!-- Interfaz de control del escenario-->
			<div id="interfaz" class="ui-corner-all">
            </div>
        </div>
		<div id="pie" class="ui-corner-all">
		<!-- Se muestra el nombre y apellidos del usuario y un enlace para cerrar sesión. Se llama a funcionOnline para actualizar el valor tiempo
		para que el sistema determine su estado a "Conectado" -->
		<?php
			echo $_SESSION['nombre'].' '.$_SESSION['apellido_1'].' '.$_SESSION['apellido_2'].' <a href="cerrarsesion.php">Cerrar Sesión</a>';	
			
			  	
		?>
        </div>
		<!-- Barra roja que aparece como aviso de que el navegador utilizado o la tarjeta gráfica de nuestro equipo no soporta WebGL -->
		<div id="mserror" class="ui-widget" style="display:none">
			<div class="ui-state-error ui-corner-all" style="padding: 0 .7em;">
				<p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>
				<strong>Aviso:</strong> Tu tarjeta gráfica o navegador parece que no soporta WebGL.<br> Algunas funcionalidades podrían verse afectadas. </p>
			</div>
		</div>
    </div>
	</body>
</html>
        
    	
       	
