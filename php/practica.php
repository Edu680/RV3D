<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//ES" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>Practica y diviertete</title>
		<script type="text/javascript">
			$("#contenedor").css("height", "100%");
		</script>
		<script type="text/javascript" src="../js/escenaInicial.js"></script>
		<script type="text/javascript" src="../js/interfazGenerica.js"></script>
		
		
	</head>

	<body>
        	<div id="grafico" class="ui-corner-all">
				<!-- Formulario para añadir un vector al escenario -->
					<div id="crearVector" title="Añadir vector" style="display:none">
					<h2>X: <input type="text" name="coordX" id="coordX" value="" size="5" />
					Y: <input type="text" name="coordY" id="coordY" value="" size="5" />
					Z: <input type="text" name="coordZ" id="coordZ" value="" size="5" /><br>
					<br><input type="button" id="botonAdVector" value="Añadir" />
					</h2>
				</div>
            	<div id="inset" class="ui-corner-all"></div>
				<div id="stats"></div>
           	</div>
            <div id="interfaz" class="ui-corner-all">
            </div>
	</body>
</html>


