<?php $url = $_SERVER["SERVER_NAME"] ?>
<html><head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<script language="javascript" type="text/javascript" src="http://<?echo $url;?>/wordpress/wp-content/plugins/solarGraficos/flot/jquery.js"></script><script language="javascript" type="text/javascript" src="http://<?echo $url;?>/wordpress/wp-content/plugins/solarGraficos/flot/jquery.flot.js"></script><script language="javascript" type="text/javascript" src="http://<?echo $url;?>/wordpress/wp-content/plugins/solarGraficos/dateFormat.js"></script>

<div id="calculadora" style="position:relative;width:700;height:500">
	<div id="ubicacion" style="position:absolute;top:0;left:0;width:250px;height:150px;border:3px black solid;">
		<label>Ubicacion</label>
	</div>
	<div id="planta" style="position:absolute;top:0;left:270;width:250px;height:150px;border:3px black solid;">
		<label>Informacion de Planta</label>
	</div>
	<div id="energia" style="position:absolute;top:170;left:0;width:250px;height:50px;border:3px black solid;">
		<label>Informacion de energia</label>
	</div>
</div>

<script type="text/javascript">
var datos;
var paises = {};
var ciudades = {};
var comunas = {};
var etiquetaDatos = {};

/* Campos necesarios  */
/* Ubicacion */
$("div#ubicacion").append("<br><label>Pais:</label><select name='ciudad' id='pais'></select");
$("div#ubicacion").append("<br><label>Ciudad:</label><select name='comuna' id='ciudad'><option value='0'>Seleccion una ciudad...</option></select");
$("div#ubicacion").append("<br><label>Comuna:</label><select name='latitud' id='comuna'><option value='0'>Seleccion una comuna...</option></select");
$("div#ubicacion").append("<br><label>Latitud:</label><label id='latitud'></label");
$("div#ubicacion").append("<br><label>Longitud:</label><label id='longitud'></label");
/* Informacion de Planta */
$("div#planta").append("<br><label>Potencia Instalada:</label><input id='potenciai'></input>");
$("div#planta").append("<br><label>Factor de rateo:</label><input id='frateo'></input>");
$("div#planta").append("<br><label>Fijacion:</label><select id='fijacion'><option value='seguimiento'>Seguimiento</option><option value='fija'>Fija</option></select><div id='fija'></div>");
/* Costo de la energia */
$("div#energia").append("<br><label>Costo:</label><input id='cenergia'></input>");

/* Poblar paises  */
etiquetaDatos.campoDestino = "pais";
$.ajax({
	url:'http://'+self.location.hostname+'/wordpress/wp-content/plugins/solarCalc/solarDatos.php',
	type:'POST',async:true,data:etiquetaDatos,dataType:'json',
	success: function(data, textStatus, jqXHR){
		data = data["ciudades"]["data"];
		console.log(data);
		var options = '<option value=0>Seleccion una pais...</option>';
		for (var i = 0; i < data.length; i++) {
			options += '<option value="' + data[i] + '">' + data[i] + '</option>';
		}
	$("select#pais").html(options);
	}
});

/* Poblar ciudades */
$("select#pais").bind("change", function(event){
	etiquetaDatos.campoFuente = "pais";
        etiquetaDatos.where = event.target.value;
        etiquetaDatos.campoDestino = "ciudad";

	if(etiquetaDatos.where != "0"){
		$.ajax({
			url:'http://'+self.location.hostname+'/wordpress/wp-content/plugins/solarCalc/solarDatos.php',
			type:'POST',async:true,data:etiquetaDatos,dataType:'json',
			success: function(data, textStatus, jqXHR){
				data = data["ciudades"]["data"];
				var options = '<option value=0>Seleccion una ciudad...</option>';
				for (var i = 0; i < data.length; i++) {
					options += '<option value="' + data[i] + '">' + data[i] + '</option>';
				}
				$("select#"+etiquetaDatos.campoDestino).html(options);
				var options = '<option value=0>Seleccion una comuna...</option>';
				$("select#comuna").html(options);
				$("label#latitud").html("");
				$("label#longitud").html("");
			}
		});
	}else{
		var options = '<option value=0>Seleccion una ciudad...</option>';
		$("select#ciudad").html(options);
		var options = '<option value=0>Seleccion una comuna...</option>';
                $("select#comuna").html(options);
		$("label#latitud").html("");
                $("label#longitud").html("");
	}
});

/* Poblar comunas */
$("select#ciudad").bind("change", function(event){
	etiquetaDatos.campoFuente = "ciudad";
	etiquetaDatos.where = event.target.value;
	etiquetaDatos.campoDestino = "comuna";

	if(etiquetaDatos.where != "0"){
		$.ajax({
			url:'http://'+self.location.hostname+'/wordpress/wp-content/plugins/solarCalc/solarDatos.php',
			type:'POST',async:true,data:etiquetaDatos,dataType:'json',
			success: function(data, textStatus, jqXHR){
				data = data["ciudades"]["data"];
				var options = '<option value=0>Seleccion una comuna...</option>';
				for (var i = 0; i < data.length; i++) {
					options += '<option value="' + data[i] + '">' + data[i] + '</option>';
				}
				$("select#"+etiquetaDatos.campoDestino).html(options);
				$("label#latitud").html("");
				$("label#longitud").html("");
			}
		});
	}else{
		var options = '<option value=0>Seleccion una comuna...</option>';
		$("select#comuna").html(options);
		$("label#latitud").html("");
                $("label#longitud").html("");
	}
});

/* Mostrar coordenadas */
$("select#comuna").bind("change", function(event){
	etiquetaDatos.campoFuente = "comuna";
	etiquetaDatos.where = event.target.value;
	etiquetaDatos.campoDestino = "latitud, longitud"

	if(etiquetaDatos.where != "0"){
		$.ajax({
			url:'http://'+self.location.hostname+'/wordpress/wp-content/plugins/solarCalc/solarDatos.php',
			type:'POST',async:true,data:etiquetaDatos,dataType:'json',
			success: function(data, textStatus, jqXHR){
				data = data["ciudades"]["data"];
				console.log(data);
				$("label#latitud").html(data[0][0]);
				$("label#longitud").html(data[0][1]);
				}
			});
	}else{
		$("label#latitud").html("");
                $("label#longitud").html("");
	}
});

/* Mostrar div de fijaciones */
$("select#fijacion").bind("change", function(event){
	if(event.target.value == "fija"){
		$("div#planta").append("<div id='fija'><label>Inclinacion:</label><input id='inclinacion'></input><br><label>Azimuth:</label><input id='azimuth'></input></div>");
		$("div#planta").css("height","280px");
		$("div#fija").css({'position':"absolute","top":"150","left":"20","width":"180px","height":"100px","border":"3px black solid"});
	}else{
		$("div#fija").remove();
		$("div#planta").css("height","150px");
	}
	
});

</script>
</body>
</html>
