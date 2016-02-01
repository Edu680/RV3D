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
				//Se crea el men� horizontal
				$("#menu").menu({
					position: {at: "left bottom"},
					icons: { submenu: "ui-icon-carat-1-s" }
				});	

				/*Queremos que aparezca de manera predeterminada el ejemplo Vector. Luego cuando vayamos pulsando en
				los distintos enlaces del men�, se ir�n cargando el resto de ejemplos seg�n proceda */
				$("#contenido").load("vector.php");		

				/*Al pinchar en un enlace, iremos a la pagina asociada a ese enlace. Si es el enlace de Ayuda, se comprueba si el usuario es 
				alumno o profesor para a�adir el m�dulo Gesti�n de alumnos en la ayuda. La informaci�n mostrada se dividir� en pesta�as dentro
				de una ventana de di�logo.*/
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
				//Si se detecta que el navegador no soporta WebGL, se muestra la barra roja de informaci�n.
				if ( ! Detector.webgl ) {
					$("#mserror").show();
				}

				//Si el usuario tiene permisos de profesor, se le mostrar� en el men� la opci�n "Gesti�n Alumnos"
				if( <?= $_SESSION['permisos'];?> == 1) {
					$("#gestion").show();
				}

    		});
			
		</script>
	</head>

	<body>
    <div id="contenedor">
        <div id="cabecera" class="ui-corner-all">
		<h1>RV3D - Representaci�n de vectores en 3D</h1>
        </div>
		<!-- Men� principal horizontal -->
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
			<li><a href="#" id="gestion" value="gestionAlumnos.php" style="display:none"><strong>Gesti�n Alumnos</strong></a></li>
			<li><a href="#" id="ayuda"><strong>Ayuda</strong></a></li>
		</ul>
        </div>
		<!-- C�digo HTML para el m�dulo ayuda de la aplicaci�n-->
		<div id="ventanaAyuda" style="display:none">
			<div id="tabsAyuda">
			 <ul>
				<li><a href="#fragment-1"><span>RV3D</span></a></li>
				<li><a href="#fragment-2"><span>Ejemplos</span></a></li>
				<li><a href="#fragment-3"><span>Practica</span></a></li>
				<li><a href="#fragment-4"><span>Problemas</span></a></li>
				<li><a href="#fragment-5" id="tabsGestion" style="display:none"><span>Gesti�n Alumnos</span></a></li>
			  </ul>
			  <div id="fragment-1" class="panel" style="text-align:justify">
				<p><h3>Bienvenido a RV3D</h3></p>
				<p><strong>�Qu� es RV3D?</strong></p>
				<p><span class="sangrar">RV3D son las siglas de <strong>R</strong>epresentaci�n de <strong>V</strong>ectores en <strong>3D.</strong></span></p>
				<p>Es una aplicaci�n Web desarrollada por Eduardo P�rez Ca�as para el departamento de F�sica Aplicada como Proyecto Fin de Carrera de la Ingenier�a T�cnica en Inform�tica de Gesti�n.</p>
				<br><p><strong>�Para qu� sirve?</strong></p>
				<p><span class="sangrar">RV3D est� enfocada como herramienta de ayuda para que los alumnos de primer a�o de carrera que no tengan claros los conceptos b�sicos en materia de vectores, mediante su uso adquieran una base s�lida gracias a la interacci�n con los ejemplos planteados y la realizaci�n de cuestionarios.</span></p>
				<br><p><strong>Suena bien pero, �c�mo la uso?</strong></p>
				<p><span class="sangrar">Como puedes observar, moverse por aqui es muy sencillo.</span></p>
				<p class="alumno">Arriba, justo debajo del t�tulo, tienes un men� horizontal con las opciones <u>Ejemplos</u>, <u>Practica</u>, <u>Problemas</u> y <u>Ayuda</u> desde el que podr�s acceder a cada uno de esos m�dulos.</p>
				<p>Encontrar�s informaci�n detallada sobre cada m�dulo pinchando en la pesta�a correspondiente de esta ayuda.</p>
				<br><p><strong>�Y cuando quiera salir?</strong></p>
				<p><span class="sangrar">Tan f�cil como finalizar tu sesi�n de usuario pinchando en el enlace situado a la derecha de tu nombre que encontrar�s en el pie de p�gina.</span></p>
				<br><p><strong>�Y la barra roja que me aparece debajo del pie de p�gina?</strong></p>
				<p><span class="sangrar">La barra roja aparece, como bien indica, debido a que tu navegador no soporta el renderizado de gr�ficos 3D con WebGL, por lo que la aplicaci�n carga los ejemplos con renderizado Canvas, que aparte de que tienen una peor calidad de imagen, pueden no mostrarse o mostrarse con cierta dificultad algunas de las opciones visuales disponibles para cada gr�fico de ejemplo.</span></p>
				<br><p><strong>Vaya, �y eso como lo arreglo?</strong></p>
				<p><span class="sangrar">Puedes cambiar de navegador. Te recomiendo Google Chrome que ha demostrado ser el m�s fiable en la representaci�n con renderizado WebGL. Si a�n as� te sigue apareciendo la barra roja, tu tarjeta gr�fica no soporta renderizado de gr�ficos con WebGL, bien porque tenga esa opci�n deshabilitada, el controlador no est� actualizado o simplemente porque no tiene soporte para ello.</span></p>
			  </div>
			  <div id="fragment-2" class="panel" style="text-align:justify">
				<p><strong>�Qu� puedo hacer aqu�?</strong></p>
				<p><span class="sangrar">De manera predeterminada, cuando accedes a la aplicaci�n, se muestra la representaci�n 3D correspondiente al ejemplo <strong>Vector</strong>, pero si quieres acceder a cualquier otro,
				pincha o pasa con el rat�n por encima de <strong>Ejemplos</strong> en el men� horizontal. Se desplegar� otro submen� horizontal mostrando cada uno de los ejemplos disponibles. Pincha sobre el que quieras para acceder a su representaci�n 3D.</span></p>
				<p>Cada ejemplo lo forman un escenario 3D, donde se representar� el gr�fico del ejemplo, y una interfaz de control para ese escenario. Dependiendo del ejemplo que se represente, la interfaz de control variar� ligeramente.</p>
				<br><p><strong>&bull; Escenario 3D</strong></p>
				<p><span class="sangrar">Es el �rea de representaci�n gr�fica del ejemplo. Se compone de, lo que denominaremos de aqu� en adelante, un <strong>Escenario inicial</strong>, que consiste en los 3 ejes de coordenadas del espacio (<italic>x, y, z</italic>) y una malla perpendicular al eje <italic>y</italic>; unos ejes de coordenadas auxiliares, colocados en la zona inferior izquierda del escenario; y el objeto u objetos 3D representativos del ejemplo.</span></p>
				<br><p>El escenario se puede:</p>
				<p><span class="sangrar">&bull; <strong>Rotar:</strong> Manteniendo pulsado el bot�n izquierdo del rat�n y moviendo este por encima del escenario.</span></p>
				<p><span class="sangrar">&bull; <strong>Mover:</strong> Manteniendo pulsado el bot�n derecho del rat�n y moviendo este por encima del escenario.</span></p>
				<p><span class="sangrar">&bull; <strong>Acercar:</strong> Moviendo la rueda del rat�n hacia delante o manteniendo pulsado el bot�n o rueda central del rat�n y moviendo hacia delante sobre el escenario.</span></p>
				<p><span class="sangrar">&bull; <strong>Alejar:</strong> Moviendo la rueda del rat�n hacia atr�s o manteniendo pulsado el bot�n o rueda central del rat�n y moviendo hacia atr�s sobre el escenario.</span></p>
				<br><p><strong>&bull; Interfaz de control</strong></p>
				<p><span class="sangrar">Es la zona desde la que puedes modificar diferentes par�metros de entrada del escenario 3D, obteniendo sus correspondientes par�metros de salida, reflejados tanto en el escenario como en la propia interfaz.</span></p>
				<br><p>Desde la interfaz puedes:</p>
				<p><span class="sangrar">&bull; <strong>Cambiar coordenadas:</strong> Introduciendo en el campo correspondiente a cada coordenada el valor y pulsando la tecla <italic>Intro</italic>. Para el ejemplo <strong>Punto</strong>, se puede adem�s cambiar su posici�n a trav�s del slider disponible.</span></p>
				<p><span class="sangrar">&bull; <strong>Cambiar color del objeto 3D:</strong> Mediante la paleta de colores, puedes seleccionar el color que quieras para cada punto o vector representado.</span></p>
				<p><span class="sangrar">&bull; <strong>Seleccionar operaci�n:</strong> En algunos ejemplos se pueden representar m�s de una operaci�n. Para ello dispones de una lista desplegable donde podr�s elegir la operaci�n que quieras representar.</span></p>
				<p><span class="sangrar">&bull; <strong>Calcular resultado:</strong> Hay ejemplos en los que no aparece representado el resultado inicialmente y para el que tendr�s que pulsar sobre el bot�n <italic>CALCULAR</italic> para obtener la soluci�n.</span></p>
				<p><span class="sangrar">&bull; <strong>Activar/Desactivar ayudas visuales:</strong> En la pesta�a <italic>Ayudas visuales</italic> encontrar�s, dependiendo de cada ejemplo, diferentes ayudas a nivel gr�fico que puedes aplicar al escenario. Estas ayudas son:</span></p>
				<p><span class="sangrar2"><strong>- <italic>Mostrar puntos:</italic></strong> Activando el <italic>check-box</italic> se muestra en el escenario, para cada objeto 3D representado, 3 puntos, cada uno a lo largo de un eje de coordenadas indicando la posici�n para ese eje de la coordenada del objeto. Disponible para todos los ejemplos.</span></p>
				<p><span class="sangrar2"><strong>- <italic>Mostrar l�neas:</italic></strong> Activando el <italic>check-box</italic> se muestra en el escenario, para cada objeto 3D representado, una serie de l�neas discont�nuas que unen cada eje de coordenadas con el objeto 3D de manera que relacionan cada coordenada del objeto con su eje. Disponible para todos los ejemplos.</span></p>
				<p><span class="sangrar2"><strong>- <italic>Mostrar numeraci�n ejes:</italic></strong> Activando el <italic>check-box</italic> se muestra en el escenario la numeraci�n de cada punto de los ejes de coordenadas con una precisi�n de una unidad. Disponible para todos los ejemplos.</span></p>
				<p><span class="sangrar2"><strong>- <italic>Mostrar separaci�n ejes:</italic></strong> Activando el <italic>check-box</italic> se muestra en el escenario la separaci�n de cada punto de los ejes de coordenadas con una precisi�n de una unidad. Disponible para todos los ejemplos.</span></p>
				<p><span class="sangrar2"><strong>- <italic>Tama�o escenario:</italic></strong> Seleccionando de la lista desplegable una de las opciones disponibles, cambiar� el tama�o del escenario. Cada valor de la lista indica la longitud para cada eje, desde el punto origen (coordenada 0) hasta el extremo del eje. Disponible para todos los ejemplos salvo <italic>Punto</italic> y <italic>Vector</italic>.</span></p>
				<p><span class="sangrar2"><strong>- <italic>Descomponer:</italic></strong> Activando el <italic>check-box</italic> se muestran las componentes cartesianas del vector representado. Disponible �nicamente para el ejemplo <italic>Vector</italic>.</span></p>
				<p><span class="sangrar2"><strong>- <italic>Mostrar opuesto:</italic></strong> Activando el <italic>check-box</italic> se muestra el vector opuesto del vector representado. Disponible �nicamente para el ejemplo <italic>Vector</italic>.</span></p>
				<br><p>Como par�metros de salida en la interfaz puedes tener:</p>
				<p><span class="sangrar">&bull;<strong> M�dulo: </strong> M�dulo de cada vector representado, ya sea vector inicial o vector resultado.</span></p>
				<p><span class="sangrar">&bull;<strong> �ngulos directores: </strong> �ngulos que forma el vector representado con cada uno de los 3 ejes de coordenadas en grados y radianes. Disponible �nicamente para el ejemplo <italic>Vector</italic>.</span></p>
				<p><span class="sangrar">&bull;<strong> �ngulo: </strong> �ngulo que forman dos vectores representados. Disponible para los ejemplos <italic>Producto Escalar</italic> y <italic>Producto Mixto</italic>.</span></p>
				<p><span class="sangrar">&bull;<strong> Producto escalar (V � W):</strong> Producto escalar de los vectores <italic>v</italic> y <italic>w</italic>.</span></p>
				<p><span class="sangrar">&bull;<strong> Producto mixto (U � (V x W)):</strong> Producto mixto del vector <italic>u</italic> y el producto cruz de los vectores <italic>v</italic> y <italic>w</italic>.</span></p>
			  </div>
			  <div id="fragment-3" class="panel" style="text-align:justify">
				<p><strong>�Qu� puedo hacer aqu�?</strong></p>
				<p><span class="sangrar">Practicar, practicar, practicar...</span></p>
				<br><p><span class="sangrar">En este m�dulo te encontrar�s con un escenario 3D conteniendo �nicamente al escenario inicial y con una interfaz de control, que podriamos llamar gen�rica, ya que desde ella puedes controlar cualquiera de las operaciones que has podido ver en <strong>Ejemplos</strong>.</span></p>
				<br><p><span class="sangrar">La idea es que te vayas familiarizando con el uso de la interfaz y el escenario 3D. Que vayas a�adiendo y eliminando vectores, operando con ellos, interpretando resultados,... ya que m�s adelante, en el m�dulo <strong>Problemas</strong>, te ser� de gran ayuda.</span></p>
				<br><p><span class="sangrar">Para comenzar tendr�s que a�adir un vector al escenario pulsando sobre el bot�n <italic>A�adir vector</italic>. Te aparecer� una ventana donde introducir�s las coordenadas del vector y tras pulsar en <italic>A�adir</italic> ver�s que queda representado en el escenario. Puedes a�adir tantos vectores como quieras y desde la interfaz, usando la lista <italic>Operaci�n</italic>, puedes operar con ellos.</span><p>
				<br><p><span class="sangrar">Cuando quieras eliminarlos, pulsa en <italic>Eliminar vectores</italic> y el escenario quedar� como al principio.</span></p>
				<br><p><span class="sangrar">Tambi�n dispondr�s de las ayudas visuales de los ejemplos: mostrar puntos, mostrar l�neas, mostrar numeraci�n ejes, mostrar separaci�n ejes y cambiar tama�o de escenario.</span></p>
			  </div>
			  <div id="fragment-4" class="panel" style="text-align:justify">
				<p><strong>�Qu� puedo hacer aqu�?</strong></p>
				<p><span class="sangrar">En este m�dulo podr�s evaluar los conceptos aprendidos en clase en materia de vectores y comprobar si el uso de la aplicaci�n te ha ayudado a asimilar mejor esos conceptos.</span></p>
				<br><p><strong>�Y en qu� consiste esa evaluaci�n?</strong></p>
				<p><span class="sangrar">La evaluaci�n consistir� en contestar 10 preguntas sobre materia de vectores. Cada pregunta acertada suma un punto. Las falladas no suman nada, pero tampoco restan (de momento).</span></p>
				<p>El funcionamiento es bien sencillo: La pregunta aparece arriba, justo debajo tienes el escenario 3D y la interfaz de control tal cual lo has visto y practicado en el m�dulo <italic>Practica</italic>. Debajo del escenario tienes el formulario para introducir la respuesta y el bot�n <italic>Enviar respuesta</italic> para validarla. Al validar la respuesta, la aplicaci�n te mostrar� un mensaje justo encima del formulario de respuesta indicando si la soluci�n que has introducido es correcta o no.</p>
				<p>Es importante que valides la respuesta ya que es requisito indispensable para pasar a la siguiente pregunta.</p>
				<br><p><strong>Lo he entendido pero, �para qu� sirve el escenario y la interfaz de control?</strong></p>
				<p><span class="sangrar">Pues sirve para que a�adas al escenario la informaci�n que aparece en el problema y operes con ella hasta dar con el resultado. En vez de una calculadora, tienes esto.</span></p>
				<br><p><strong>Y cuando termine, �c�mo s� mi nota?</strong></p>
				<p><span class="sangrar">Al finalizar el cuestionario, se mostrar� una p�gina de resultados en el que se indicar�n en color verde el n�mero de respuestas acertadas, en rojo el n�mero de respuestas falladas y en negro la nota final. Adem�s se cargar� un gr�fico lineal de las notas obtenidas en los �ltimos 20 cuestionarios realizados, as� podr�s comprobar c�mo est� siendo tu evoluci�n.</span></p>
			  </div>
			  <div id="fragment-5" class="panel" style="text-align:justify">
				<p><strong>�Qu� puedo hacer aqu�?</strong></p>
				<p><span class="sangrar">Desde este m�dulo podr�s observar un listado de todos los alumnos registrados en la aplicaci�n.</span></p>
				<p>Para cada alumno se muestra su nombre y apellidos, email, si se encuentran conectados o no a la aplicaci�n, nota m�s alta obtenida en el cuestionario, nota m�s baja y 2 enlaces, uno para eliminarlo de la aplicaci�n y otro para mostrar los resultados de los �ltimos 20 cuestionarios realizados en una gr�fica lineal.</p>
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
		<!-- Se muestra el nombre y apellidos del usuario y un enlace para cerrar sesi�n. Se llama a funcionOnline para actualizar el valor tiempo
		para que el sistema determine su estado a "Conectado" -->
		<?php
			echo $_SESSION['nombre'].' '.$_SESSION['apellido_1'].' '.$_SESSION['apellido_2'].' <a href="cerrarsesion.php">Cerrar Sesi�n</a>';	
			
			  	
		?>
        </div>
		<!-- Barra roja que aparece como aviso de que el navegador utilizado o la tarjeta gr�fica de nuestro equipo no soporta WebGL -->
		<div id="mserror" class="ui-widget" style="display:none">
			<div class="ui-state-error ui-corner-all" style="padding: 0 .7em;">
				<p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>
				<strong>Aviso:</strong> Tu tarjeta gr�fica o navegador parece que no soporta WebGL.<br> Algunas funcionalidades podr�an verse afectadas. </p>
			</div>
		</div>
    </div>
	</body>
</html>
        
    	
       	
