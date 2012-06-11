<?php $url = $_SERVER["SERVER_NAME"] ?>
<html><head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<script language="javascript" type="text/javascript" src="http://<?echo $url;?>/wp-content/plugins/solarGraficos/flot/jquery.js"></script><script language="javascript" type="text/javascript" src="http://<?echo $url;?>/wp-content/plugins/solarGraficos/flot/jquery.flot.navigate.js"></script><script language="javascript" type="text/javascript" src="http://<?echo $url;?>/wp-content/plugins/solarGraficos/flot/jquery.flot.js"></script><script language="javascript" type="text/javascript" src="http://<?echo $url;?>/wp-content/plugins/solarGraficos/dateFormat.js"></script>
<link href="http://<?echo $url;?>/wp-content/plugins/solarGraficos/flot/examples/layout.css" rel="stylesheet" type="text/css">
<!--[if IE]><script language="javascript" type="text/javascript" src="http://<?echo $url;?>/wp-content/plugins/solarGraficos/flot/excanvas.js"></script><![endif]-->
<style type="text/css"> #grafico.button{position:absolute;cursor:pointer;} #grafico div.button{font-size:smaller;color:#999;background-color:#eee;padding: 2px;} .message{padding-left:50px;font-size:smaller;} </style></head><body>

<div id="grafico" style="width:500px;height:300px"></div>
<label id="graflabel">Mostrar en grafica:</label><br>
<div id="choices"></div>
<div id="fechas"></div>

<script type="text/javascript">
var datosJson = {};
var now = new Date(); 
var browserUtcOf = now.getTimezoneOffset() * 60 * 1000;

/* Definir opciones para el grafico*/
var placeholder = $("#grafico");
var options = {
	series:	{ lines: { show: true, shadowSize: 0 }},
	grid:	{ hoverable: true,axixMargin: 5},
	zoom:	{ interactive: true},
	pan:	{ interactive: true},
	xaxis:	{
		monthName: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"],
		mode: "time",ticks: 9,timeformat: "%d\r%b\r%Hhrs",TickSize: [1, "hour"],
		zoomRange: [10000000000, 1000000000000],panRange: [1300000000000, 1340000000000]
		},
	yaxis:	{ zoomRange: [1, 1000], panRange: [0, 1000]},
	legend:	{ show: true, noColumns: 1, margin: 2},
	};

var plot = $.plot(placeholder, datosJson, options);

//configurcion de la etiqueta que muestra informacion sobre los puntos
function showTooltip(x, y, contents) {
	$('<div id="tooltip">' + contents + '</div>').css( {
		position: 'absolute',
		display: 'none',
		top: y + 5,
		left: x + 5,
		border: '1px solid #fdd',
		padding: '2px',
		'background-color': '#fee',
		opacity: 0.80
	}).appendTo("body").fadeIn(200);
}

//habilitacion de las etiquetas que muetran informacion y llamar a la etiqueta sobre el grafico
var previousPoint = null;
placeholder.bind("plothover", function (event, pos, item) {
	if (item) {
		if (previousPoint != item.dataIndex) {
			previousPoint = item.dataIndex;       
			$("#tooltip").remove();
			var x = item.datapoint[0],
			y = item.datapoint[1].toFixed(2);
     				
			var fecha = new Date(x + browserUtcOf);
			fecha = fecha.format("d M Y, H:i:s");		
			var label = item.series.label.split(" ");
			showTooltip(item.pageX, item.pageY, fecha + " UTC<br>" + y + " " + label[1]);
		}
	}else{
		$("#tooltip").remove();
		previousPoint = null;            
		}
});

/*Selector de fechas*/
var datos = {};
var choiceContainer = $("#choices");
var fechasContainer = $("#fechas");

var nowUtc = new Date(now.getUTCFullYear(), now.getUTCMonth(), now.getUTCDate(),  now.getUTCHours(), now.getUTCMinutes(), now.getUTCSeconds());
var fechasAno = new Date(nowUtc - (60*60*24*365*1000)).format("Y-m-d H:i:s");
var fechasMes = new Date(nowUtc - (60*60*24*30*1000)).format("Y-m-d H:i:s");
var fechasSemana = new Date(nowUtc - (60*60*24*7*1000)).format("Y-m-d H:i:s");
var fechasDia = new Date(nowUtc - (60*60*24*1000)).format("Y-m-d H:i:s");

/* Cargar todos los datos de manera asincronica */
/* Datos del año */
var fechasWhere = {};
fechasWhere.b = browserUtcOf;
fechasWhere.a = fechasAno;
$.ajax({
	url: 'http://'+self.location.hostname+'/wp-content/plugins/solarGraficos/solarDatos.php',
	type: 'POST',async: true,data: fechasWhere,dataType: 'json',
	success: function(data, textStatus, jqXHR){datos.Ano = data;},
});
//console.log(datos);
/* Datos del mes */
fechasWhere.a = fechasMes;
$.ajax({
        url: 'http://'+self.location.hostname+'/wp-content/plugins/solarGraficos/solarDatos.php',
        type: 'POST',async: true,data: fechasWhere,dataType: 'json',
        success: function(data, textStatus, jqXHR){datos.Mes = data;},
});

/* Datos del semana */
fechasWhere.a = fechasSemana;
$.ajax({
        url: 'http://'+self.location.hostname+'/wp-content/plugins/solarGraficos/solarDatos.php',
        type: 'POST',async: true,data: fechasWhere,dataType: 'json',
        success: function(data, textStatus, jqXHR){datos.Semana = data;},
});

/* Datos del dia */
fechasWhere.a = fechasDia;
$.ajax({
        url: 'http://'+self.location.hostname+'/wp-content/plugins/solarGraficos/solarDatos.php',
        type: 'POST',async: false,data: fechasWhere,dataType: 'json',
        success: function(data, textStatus, jqXHR){datos.Dia = data;},
});

/* Mostrar opciones de ploteo */
/* opciones de fecha*/
fechasContainer.append(
        '<input type="radio" name="fechasWhere" value="Ano">Año'
        +'<input type="radio" name="fechasWhere" value="Mes">Mes'
        +'<input type="radio" name="fechasWhere" value="Semana">Semana'
        +'<input type="radio" name="fechasWhere" value="Dia" checked>Dia'
        );

/* opciones de curvas */
$.each(datos.Dia, function(key, val) {
	choiceContainer.append('<input type="checkbox" name="'+key+'" id="'+ key+ '">' +
		'<label for="id'+key+'">'+val.label+'</label>');
	});

$(document).ready(function(){
	choiceContainer.find("input:checkbox:first").attr('checked',"true");
	plotCurvas();
});
//$(document).ready(plotCurvas);
 
/* Cada vez que se haga click en una opcion de fechas modificar variables de datos*/
fechasContainer.find("input").click(plotCurvas);
choiceContainer.find("input").click(plotCurvas);

//body.bind.click(
/* Reacer el grafico de acuerdo a las opciones */    
function plotFechas() {
	/*Seleccionar conjunto de datos*/
	var key = fechasContainer.find("input:checked").attr("value");
	var datosFecha = datos[key];

	/*Selector de opciones*/
	choiceContainer.empty(); 
	$.each(datosFecha, function(key, val) {
        	choiceContainer.append('<input type="checkbox" name="'+key+'" id="'+ key+ '">' +
			'<label for="id'+key+'">'+val.label+'</label>');
		});
		choiceContainer.find("input").click(plotCurvas);
	}
	
/*Buscar las curvas chequedas para mostrar en el grafico*/
function plotCurvas(){
	var key = fechasContainer.find("input:checked").attr("value");
        var datosFecha = datos[key];
	var data = [];

	/* Arreglar para que no cambien los colores */
	var i = 0;
	$.each(datosFecha, function(key, val) {
		val.color = i;
		++i;
		});
	
	/* Plotear de acuerdo a las curvas checkeadas */
        choiceContainer.find("input:checked").each(function () {
                var key = $(this).attr("name");
                if (key && datosFecha[key]) data.push(datosFecha[key]);
        	});
	//console.log($('.legend'));
	//$("div#legenda").empty();
        plot = $.plot(placeholder, data, options);
	}

</script>
</body>
</html>
