<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//ES" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	
	<head>
		<title>Gesti�n de alumnos</title>
		<script type="text/javascript">
			$("#contenedor").css("height", "100%");
		</script>
		<style>
			div#contenido {
				height: auto;
				font-size: 100%;
				font-weight:bold;
				margin: 2% 0 2% 0;
			}
			
		</style>
	</head>
	<body>
		<div id="contenido" class="ui-corner-all">
		<div id="eliminarUsuario" style="display:none">
		<!-- Mensaje de confirmaci�n que se mostrar� cuando se vaya a eliminar a un alumno -->
		<p><span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span>�Est�s seguro de que quieres eliminar este usuario de la aplicaci�n?</p></div>
		<div id="graficaNotas"></div>
		</div>
	</body>
</html>

<script type="text/javascript">
	
	var i;
	var obj;
	$(document).ready(function() {
		//Creamos una tabla donde mostraremos los datos de los alumnos registrados en la aplicaci�n
		var tabla = '<table border=1 width=100%><tr><td>NOMBRE</td><td>PRIMER APELLIDO</td><td>SEGUNDO APELLIDO</td><td>EMAIL</td><td>ESTADO</td><td>NOTA M�S ALTA</td><td>NOTA M�S BAJA</td><td></td><td></td></tr>';
		//Mediante ajax() recibimos el listado de alumnos registrados en el sistema y los vamos colocando en la tabla
		$.ajax({
			url: '../mysql/tablaAlumnos.php',
			type: 'POST',
			datatype: 'json',
			success: function(data) {
				var $graph = data;
				obj = $.parseJSON($graph);
				for(i=0;i<obj.length;i++) {
					tabla += '<tr><td>'+obj[i][2]+'</td><td>'+obj[i][3]+'</td><td>'+obj[i][4]+'</td><td>'+obj[i][5]+'</td><td>'+obj[i][6]+'</td><td>'+obj[i][7]+'</td><td>'+obj[i][8]+'</td>';
					if(obj[i][9] == 0) {
						tabla += '<td><a href="#" id="eliminarAlumno" value='+obj[i][10]+'>Eliminar</a></td>';
					}
					else if(obj[i][9] == 1) {
						tabla += '<td></td>';	
					}
					
					tabla += '<td><a href="#" id="mostrarGrafica" value='+obj[i][10]+'>Mostrar gr�fica</a></td></tr>';
					console.log(obj[i]);		
				}
				tabla += '</table>';
				//Asignamos la tabla al DOM
				$("#contenido").append(tabla);
			}
		});

		//Al tratarse de contenido din�mico generado en tiempo de ejecuci�n, tendremos que usar la siguiente funci�n en vez de
		//$("#contenido").click(function(){}), ya que cuando est� listo el documento, no existe la tabla de alumnos, por lo que no existe ning�n
		//enlace en el que pinchar.
		$("#contenido").on("click", "a", function() {
			var aux = $(this).attr("value");
				var jObject={};
				jObject = obj[aux];
				jObject= JSON.stringify(jObject);
				//Si el enlace pulsado es 'eliminar alumno'
			if($(this).attr("id")=='eliminarAlumno') {
				
				//Se muestra una ventana de di�logo con el nombre y apellidos del alumno a eliminar en la cabecera
				$("#eliminarUsuario").dialog({
					title: 'Eliminar: ' + obj[aux][2] +' '+ obj[aux][3] +' '+ obj[aux][4],
					 modal: true,
					  buttons: {
						//Muestra el mensaje que ya teniamos preparado en html. Si el profesor pulsa en SI, se elimina el alumno.
						"SI": function() {
							$.ajax({
								url: '../mysql/eliminarAlumno.php',
								type: 'POST',
								datatype: 'json',
								data:{jObject:  jObject},
								success: function(data) {
									if(data==1) {
										//location.reload();
										//window.location = "vectoresPrincipales.php";
										$("#contenido").load("gestionAlumnos.php");
									}
								}
							});
							$( this ).dialog( "close" );
						},
						//Si pulsa en NO, no pasa nada y se cierra la ventana de confirmaci�n.
						"NO": function() {
						  $( this ).dialog( "close" );
						}
					  }

				});
			}
			//Si el enlace pulsado es 'mostrar gr�fica'
			if($(this).attr("id")=='mostrarGrafica') {
				var user = obj[aux][0];
				
				//Se env�a por ajax() el nombre de usuario del que queremos saber su historial de notas
				$.ajax({
					url: '../mysql/grafico.php',
					type: 'POST',
					data: {user: user},
					success: function(data) {
						var $graph = data;
						var grafica = $.parseJSON($graph);
						//Se devuelve el objeto JSON con los �ltimos 20 resultados de realizaci�n de cuestionarios y se carga el gr�fico lineal 
						//en una ventana de di�logo con el nombre y apellidos del alumno como t�tulo de la ventana.
						$("#graficaNotas").dialog({
							height:400, 
							width:650,
							title: 'Gr�fica Notas: ' + obj[aux][2] +' '+ obj[aux][3] +' '+ obj[aux][4],
							close: function() {			
								$(this).remove();
								$('#contenido').append("<div id='graficaNotas'></div>"); 
							}
						});
						//Con morris.js creamos la gr�fica lineal
						new Morris.Line({
							// ID of the element in which to draw the chart.
							element: 'graficaNotas',
							// Chart data records -- each entry in this array corresponds to a point on
							// the chart.
							data: grafica,
							// The name of the data record attribute that contains x-values.
							xkey: 'fechaTest',
							// A list of names of data record attributes that contain y-values.
							ykeys: ['nota'],
							// Labels for the ykeys -- will be displayed when you hover over the
							// chart.
							labels: ['Nota', 'Fecha'],
							ymin: 0,
							ymax: 10,
							hideHover: 'auto',
							goals: [0,5,10],
							goalLineColors: ['red', 'brown', 'green']
							
						});
					}
				});
				
			}
		});
	});		
  
</script>