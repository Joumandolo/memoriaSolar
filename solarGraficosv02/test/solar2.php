<html>
 <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Ejemplos Solar</title>
    <link href="wp-content/plugins/solarGraficos/flot/examples/layout.css" rel="stylesheet" type="text/css">
    <!--[if lte IE 8]><script language="javascript" type="text/javascript" src="./flot/excanvas.min.js"></script><![endif]-->
    <script language="javascript" type="text/javascript" src="wp-content/plugins/solarGraficos/flot/jquery.js"></script>
    <script language="javascript" type="text/javascript" src="wp-content/plugins/solarGraficos/flot/jquery.flot.js"></script>
    <script language="javascript" type="text/javascript" src="wp-content/plugins/solarGraficos/flot/jquery.flot.navigate.js"></script>
    <script language="javascript" type="text/javascript" src="wp-content/plugins/solarGraficos/dateFormat.js"></script>
 
<style type="text/css">
    #grafico .button {
        position: absolute;
        cursor: pointer;
    }
    #grafico div.button {
        font-size: smaller;
        color: #999;
        background-color: #eee;
        padding: 2px;
    }
    .message {
        padding-left: 50px;
        font-size: smaller;
    }
    </style>

</head>
<body>
	<div id="grafico" style="width:500px;height:300px"></div>
	<div id="legenda" style="width:500px;height:10px;font-size:smaller"></div>
	<label id="graflabel">Mostrar en grafica:</label><br>
	<div id="choices"></div>
	<div id="fechas"></div>

<script type="text/javascript">
var datosJson = {};
//var fechasWhere = {};
//fechasWhere.a = new Date(new Date().getTime() - (60*60*24*1000)).format("Y m d, H:i:s");
/*$.ajax({
	url: 'wp-content/plugins/solarGraficos/solarDatos.php',
	type: 'POST',
	data: fechasWhere,
	dataType: 'json',
	async: false,
	success: function(data, textStatus, jqXHR){
	datosJson = data;
	},
});*/

var placeholder = $("#grafico");
var options = {
	series: {
		lines: { show: true, shadowSize: 0 },
		//points: { show: true }
	},
	grid: {
		hoverable: true, 
		//clickable: true,
		axixMargin: 5
	},
	zoom: { interactive: true},
	pan: { interactive: true},
	xaxis: {
		//min: -1.2,
		//max: 1.2 }
		monthName: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"],
		mode: "time",
		ticks: 9,
		timeformat: "%d\r%b\r%Hhrs",
		TickSize: [1, "hour"],
		zoomRange: [10000000000, 1000000000000],
                panRange: [1300000000000, 1340000000000]
	},
	yaxis: {
		zoomRange: [1, 1000],
		panRange: [0, 1000]
	},
	legend: {
		show: true,
		//labelFormatter: "fn", //null or (fn: string, series object -> string)
		//labelBoxBorderColor: "white",
		noColumns: 5,
		//position: "nw", //"sw" or "nw" or "se" or "sw"
		margin: 2, //number of pixels or [x margin, y margin]
		//backgroundColor: "red", //null or color
		//backgroundOpacity: "0.8", //number between 0 and 1
		container: $("#legenda"),//null or jQuery object/DOM element/jQuery expression
	},
};

var plot = $.plot(placeholder, datosJson, options);
$.plot(placeholder, datosJson, options);

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
     				
				var fecha = new Date(x);
				fecha = fecha.format("d M Y, H:i:s");		
				var label = item.series.label.split(" ");
				showTooltip(item.pageX, item.pageY, fecha + "<br>" + y + " " + label[1]);
			}
		}
	else {
		$("#tooltip").remove();
		previousPoint = null;            
		}
	//}
});

//add zoom out button 
$('<div class="button" style="right:1085px;top:20px">zoom out</div>').appendTo(placeholder).click(function (e) {
	e.preventDefault();
	plot.zoomOut();
});

// and add panning buttons
// little helper for taking the repetitive work out of placing
// panning arrows
function addArrow(dir, right, top, offset) {
	$('<img class="button" src="arrow-' + dir + '.gif" style="right:' + right + 'px;top:' + top + 'px">').appendTo(placeholder).click(function (e) {
		e.preventDefault();
		plot.pan(offset);
	});
}

//addArrow('left', 1120, 60, { left: -100 });
//addArrow('right', 1090, 60, { left: 100 });
//addArrow('up', 1105, 45, { top: -100 });
//addArrow('down',1105, 75, { top: 100 });

//hard-code color indices to prevent them from shifting as
// countries are turned on/off
var i = 0;
$.each(datosJson, function(key, val) {
	val.color = i;
	++i;
});
    
/*Selector de fechas*/
var choiceContainer = $("#choices");
var fechasContainer = $("#fechas");
var fechasAno = new Date(new Date().getTime() - (60*60*24*365*1000)).format("Y-m-d H:i:s");
var fechasMes = new Date(new Date().getTime() - (60*60*24*30*1000)).format("Y-m-d H:i:s");
var fechasSemana = new Date(new Date().getTime() - (60*60*24*7*1000)).format("Y-m-d H:i:s");
var fechasDia = new Date(new Date().getTime() - (60*60*24*1000)).format("Y-m-d H:i:s");

fechasContainer.append(
	'<input type="radio" name="fechasWhere" value="'+fechasAno+'">Año'
	+'<input type="radio" name="fechasWhere" value="'+fechasMes+'">Mes'
	+'<input type="radio" name="fechasWhere" value="'+fechasSemana+'">Semana'
	+'<input type="radio" name="fechasWhere" value="'+fechasDia+'" checked="checked">Dia'
	);
fechasContainer.find("input").click(plotAccordingToChoices);
choiceContainer.find("input").click(plotAccordingToChoices);

/* Get datos */
function getDatos(){}

/* Plot fechas */
function plotFechas(){

}

/* Plot choices */
function plotChoices(){}

/*Reacer el grafico de acuerdo a las opciones*/    
function plotAccordingToChoices() {
	var data = [];
	var fechasWhere = {};

	/*Setear el universo de datos de acuerdo a las fechas*/
	fechasContainer.find("input:checked").each(function () {
		fechasWhere.a = $(this).attr("value");
		$.ajax({
			url: 'wp-content/plugins/solarGraficos/solarDatos.php',
			type: 'POST',
			async: false,
			data: fechasWhere,
			dataType: 'json',
			success: function(data, textStatus, jqXHR){
				datosJson = data;
				console.log(datosJson);
			},
		});
	});

	/*Selector de opciones*/
	choiceContainer.empty(); 
	$.each(datosJson, function(key, val) {
        	choiceContainer.append('<input type="checkbox" name="' + key +
			'" checked="checked" id="id' + key + '">' +
			'<label for="id' + key + '">'
			+ val.label + '</label>');
		});

	/*Buscar las curvas chequedas para mostrar en el grafico*/
	choiceContainer.find("input:checked").each(function () {
		var key = $(this).attr("name");
		if (key && datosJson[key])
		data.push(datosJson[key]);
	});

	if (data.length > 0)
	plot = $.plot(placeholder, data, options);
}
//plotAccordingToChoices();

</script>
</body>
</html>
