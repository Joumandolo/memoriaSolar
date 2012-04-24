var datosJson = {};

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
	legend:	{ show: true, noColumns: 5, margin: 2, container: $("#legenda"),},
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
     				
			var fecha = new Date(x);
			fecha = fecha.format("d M Y, H:i:s");		
			var label = item.series.label.split(" ");
			showTooltip(item.pageX, item.pageY, fecha + "<br>" + y + " " + label[1]);
		}
	}else{
		$("#tooltip").remove();
		previousPoint = null;            
		}
});

//hard-code color indices to prevent them from shifting as
var i = 0;
$.each(datosJson, function(key, val) {
	val.color = i;
	++i;
});
    
/*Selector de fechas*/
var datos = {};
var choiceContainer = $("#choices");
var fechasContainer = $("#fechas");
var fechasAno = new Date(new Date().getTime() - (60*60*24*365*1000)).format("Y-m-d H:i:s");
var fechasMes = new Date(new Date().getTime() - (60*60*24*30*1000)).format("Y-m-d H:i:s");
var fechasSemana = new Date(new Date().getTime() - (60*60*24*7*1000)).format("Y-m-d H:i:s");
var fechasDia = new Date(new Date().getTime() - (60*60*24*1000)).format("Y-m-d H:i:s");

fechasContainer.append(
	'<input type="radio" name="ano" value="'+fechasAno+'">Año'
	+'<input type="radio" name="mes" value="'+fechasMes+'">Mes'
	+'<input type="radio" name="semana" value="'+fechasSemana+'">Semana'
	+'<input type="radio" name="dia" value="'+fechasDia+'" checked="checked">Dia'
	);

/* Cargar todos los datos de manera asincronica */
/* Datos del año */
fechasWhere.a = fechaAno;
$.ajax({
	url: 'wp-content/plugins/solarGraficos/solarDatos.php',
	type: 'POST',async: true,data: fechasWhere,dataType: 'json',
	success: function(data, textStatus, jqXHR){datos.Ano = data;},
}

/* Datos del mes */
fechasWhere.a = fechaMes;
$.ajax({
        url: 'wp-content/plugins/solarGraficos/solarDatos.php',
        type: 'POST',async: true,data: fechasWhere,dataType: 'json',
        success: function(data, textStatus, jqXHR){datos.Mes = data;},
}

/* Datos del semana */
fechasWhere.a = fechaSemana;
$.ajax({
        url: 'wp-content/plugins/solarGraficos/solarDatos.php',
        type: 'POST',async: true,data: fechasWhere,dataType: 'json',
        success: function(data, textStatus, jqXHR){datos.Semana = data;},
}

/* Datos del dia */
fechasWhere.a = fechaAno;
$.ajax({
        url: 'wp-content/plugins/solarGraficos/solarDatos.php',
        type: 'POST',async: true,data: fechasWhere,dataType: 'json',
        success: function(data, textStatus, jqXHR){datos.Dia = data;},
}

/* Reacer el grafico de acuerdo a las opciones */    
function plotAccordingToChoices(choice) {
	
	/*Selector de opciones*/
	choiceContainer.empty(); 
	$.each(datos.choice, function(key, val) {
        	choiceContainer.append('<input type="checkbox" name="' + key +
			'" checked="checked" id="id' + key + '">' +
			'<label for="id' + key + '">'
			+ val.label + '</label>');
		});

	/*Buscar las curvas chequedas para mostrar en el grafico*/
	choiceContainer.find("input:checked").each(function () {
		var key = $(this).attr("name");
		if (key && datos.choice[key])
		data.push(datos.choice[key]);
	});

	if (data.length > 0)
	plot = $.plot(placeholder, data, options);
}

/* Graficar de acuerdo a las opciones de fecha */
choiceContainer.find("input").click(plotAccordingToChoices(choiceContainer.find("input:checked").attr("name")));
