<?php $url = "http://".$_SERVER["SERVER_NAME"]."/wordpress/wp-content/plugins/" ?>
<html><head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<script language="javascript" type="text/javascript" src="<?echo $url;?>solarGraficos/flot/jquery.js"></script>
<script language="javascript" type="text/javascript" src="<?echo $url;?>solarGraficos/flot/jquery.flot.js"></script>
<script language="javascript" type="text/javascript" src="<?echo $url;?>solarGraficos/dateFormat.js"></script>
<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/jquery-ui.min.js"></script>
<script src="http://jquery-ui.googlecode.com/svn/trunk/ui/i18n/jquery.ui.datepicker-es.js"></script>
<!--[if IE]><script language="javascript" type="text/javascript" src="<?echo $url;?>solarGraficos/flot/excanvas.js"></script><![endif]-->

<div id="calculadora" style="position:relative;width:700;height:500">
	<div id="ubicacion" style="position:absolute;top:0;left:0;width:410px;height:150px;border:3px black solid;">
		<label>Ubicacion</label>
	</div>
	<div id="consumo" style="position:absolute;top:0;left:430;width:520px;height:320px;border:3px black solid;">	
		<label>Perfil de consumo</label>
	</div>
	<div id="planta" style="position:absolute;top:170;left:0;width:410px;height:150px;border:3px black solid;">
		<label>Informacion de Planta</label>
	</div>
	<div id="energia" style="position:absolute;top:340;left:0;width:250px;height:50px;border:3px black solid;">
		<label>Informacion de energia</label>
	</div>
</div>

<script type="text/javascript">
var datos;
var paises = {};
var ciudades = {};
var comunas = {};
var etiquetaDatos = {};
var datosConsumo = [];

/* Campos necesarios  */
/* Ubicacion */
$("div#ubicacion").append("<br><label>Pais:</label><select name='ciudad' id='pais'></select");
$("div#ubicacion").append("<br><label>Región:</label><select name='region' id='region'><option value='0'>Seleccion una Región..</option></select");
$("div#ubicacion").append("<br><label>Comuna:</label><select name='latitud' id='comuna'><option value='0'>Seleccion una comuna...</option></select");
$("div#ubicacion").append("<br><label>Latitud:</label><label id='latitud'></label");
$("div#ubicacion").append("<br><label>Longitud:</label><label id='longitud'></label");
/* Informacion de Planta */
$("div#planta").append("<br><label>Orientacion[°]:</label><select id='orientacion'><option value='0'>Sur 0</option><option value='-90'>Este -90</option><option value='90'>Oeste 90</option></select>");
$("div#planta").append("<br><label>Inclinacion[°]:</label><select id='inclinacion'><option value='0'>0</option><option value='10'>10</option><option value='20'>20</option><option value='30'>30</option><option value='40'>40</option><option value='50'>50</option><option value='60'>60</option><option value='70'>70</option><option value='80'>80</option><option value='90'>90</option></select>");
$("div#planta").append("<br><label>Albedo:</label><select id='albedo'><option value='0.1'>Mar - 0,1</option><option value='0.2'>Terreno normal - 0,2</option><option value='0.4'>Pradera - 0,4</option><option value='0.9'>Nieve - 0,9</option></select><div id='fija'></div>");
/* Costo de la energia */
$("div#energia").append("<br><label>Costo:</label><input id='cenergia'></input>");
/* Perfil de consumo */
$("div#consumo").append("<br><label>Perfil:</label><select id='optconsumo'></div>");
$("div#consumo").append("<div id='graficoConsumo' style='width:450px;height:240px;top:60;left:20;border:2px solid black;position:absolute'></div>");
/* Poblar paises  */
etiquetaDatos.etiqueta = "ubicacion";
etiquetaDatos.campoDestino = "pais";
$.ajax({
	url:'http://'+self.location.hostname+'/wordpress/wp-content/plugins/solarCalc/solarDatos.php',
	type:'POST',async:true,data:etiquetaDatos,dataType:'json',
	success: function(data, textStatus, jqXHR){
		data = data["options"];
		//console.log(data);
		var options = '<option value=0>Seleccion una pais...</option>';
		for (var i = 0; i < data.length; i++) {
			options += '<option value="' + data[i] + '">' + data[i] + '</option>';
		}
	$("select#pais").html(options);
	}
});

/* Poblar ciudades */
$("select#pais").bind("change", function(event){
	etiquetaDatos.etiqueta = "ubicacion";
	etiquetaDatos.campoFuente = "pais";
        etiquetaDatos.where = event.target.value;
        etiquetaDatos.campoDestino = "region";

	if(etiquetaDatos.where != "0"){
		$.ajax({
			url:'http://'+self.location.hostname+'/wordpress/wp-content/plugins/solarCalc/solarDatos.php',
			type:'POST',async:true,data:etiquetaDatos,dataType:'json',
			success: function(data, textStatus, jqXHR){
				data = data["options"];
				//console.log(data);
				var options = '<option value=0>Seleccion una región...</option>';
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
		var options = '<option value=0>Seleccion una región...</option>';
		$("select#region").html(options);
		var options = '<option value=0>Seleccion una comuna...</option>';
                $("select#comuna").html(options);
		$("label#latitud").html("");
                $("label#longitud").html("");
	}
});

/* Poblar comunas */
$("select#region").bind("change", function(event){
	etiquetaDatos.etiqueta = "ubicacion";
	etiquetaDatos.campoFuente = "region";
	etiquetaDatos.where = event.target.value;
	etiquetaDatos.campoDestino = "comuna";

	if(etiquetaDatos.where != "0"){
		$.ajax({
			url:'http://'+self.location.hostname+'/wordpress/wp-content/plugins/solarCalc/solarDatos.php',
			type:'POST',async:true,data:etiquetaDatos,dataType:'json',
			success: function(data, textStatus, jqXHR){
				data = data["options"];
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
	etiquetaDatos.etiqueta = "ubicacion";
	etiquetaDatos.campoFuente = "comuna";
	etiquetaDatos.where = event.target.value;
	etiquetaDatos.campoDestino = "latitud, longitud"

	if(etiquetaDatos.where != "0"){
		$.ajax({
			url:'http://'+self.location.hostname+'/wordpress/wp-content/plugins/solarCalc/solarDatos.php',
			type:'POST',async:true,data:etiquetaDatos,dataType:'json',
			success: function(data, textStatus, jqXHR){
				data = data["options"];
				//console.log(data);
				$("label#latitud").html(data[0][0]);
				$("label#longitud").html(data[0][1]);
				}
			});
	}else{
		$("label#latitud").html("");
                $("label#longitud").html("");
	}
});

/* Graficar Perfiles */
etiquetaDatos.etiqueta = "perfil";
$.ajax({
	url:'http://'+self.location.hostname+'/wordpress/wp-content/plugins/solarCalc/solarDatos.php',
	type:'POST',async:true,data:etiquetaDatos,dataType:'json',
	success: function(data, textStatus, jqXHR){
		datosConsumo = data["options"];
		//console.log(datosConsumo);
		var options = '<option value=0>Seleccione un perfil de consumo...</option>';
		for (var i = 0; i < datosConsumo.length; i++) {
			options += '<option value="' + datosConsumo[i]["label"] + '">' + datosConsumo[i]["label"] + '</option>';
		}
		$("select#optconsumo").html(options);
	}
});

/*Crear la grafica*/
$("select#optconsumo").bind("change", function(event){
	var a = event.target.value;
	var data = [];
	jQuery.each(datosConsumo, function(key, val){ if(val.label == a) { data.push(val); } });
	//console.log(data);
	var placeholder = jQuery("div#graficoConsumo");
	var options = {
		series: { bars: { show: true, barWidth: 0.6, align: "center" }},
		legend: { show: false, noColumns: 1, margin: 2},
		xaxis:  { ticks: 24, minTickSize: 1, tickDecimals:0 },
	};
	var plot = jQuery.plot(placeholder, data, options);

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
