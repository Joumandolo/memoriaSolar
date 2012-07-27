<?php $url = $_POST["siteUrl"]."/wp-content/plugins/solarFch/" ?>
<html><head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<script language="javascript" type="text/javascript" src="<?echo $url;?>libs/jquery-ui/js/jquery-1.7.2.min.js"></script>
<script language="javascript" type="text/javascript" src="<?echo $url;?>libs/jquery-ui/js/jquery-ui-1.8.21.custom.min.js"></script>
<script src="<?echo $url;?>libs/jquery-ui/i18n/jquery.ui.datepicker-es.js"></script>
<script language="javascript" type="text/javascript" src="<?echo $url;?>libs/flot/jquery.flot.js"></script>
<script language="javascript" type="text/javascript" src="<?echo $url;?>libs/dateFormat.js"></script>

<link href="<?echo $url;?>libs/jquery-ui/css/ui-darkness/jquery-ui-1.8.21.custom.css" rel="stylesheet" type="text/css"/>

<!--[if IE]><script language="javascript" type="text/javascript" src="<?echo $url;?>solarGraficos/flot/excanvas.js"></script><![endif]-->
</head>
<body>

<div id="solarFch" style="width:840px;height:360px;position:relative">
	<label id="ubicacion">Ubicación: Vitacura, Santiago de Chile</label><br>
	<label id="latitud">Latitud: 33° 22' 23'' S</label><br>
	<label id="longitud">Longitud: 70° 34' 51'' W</label><br>
	<label id="altitud">Altitud: 672 m.s.n.m</label><br>
	<label id="zona">Zona horaria: UTC-4</label><br>
	<label id="ficha"> Ficha técnica:</label><a href="<?echo $url;?>fichaTecnicaDedalo.pdf"> Serie Dédalo</a><br>
	<div id="foto"s style="position:relative;top:10px;left:0px"><img style="width:200px;height:180px;" src="<?echo $url;?>images/estacionDedaloFch.jpg" /></div>
	<div id="solarGrafico"s style="width:520px;height:380px;position:absolute;top:-50px;left:210px">
		<div id="grafico" style="width:500px;height:300px;top:10px;left:10px;position:absolute"></div>
		<div id="opciones" style="width:300px;height:60px;top:320px;left:130px;position:absolute">
			<label for="opciones" id="graflabel">Mostrar en grafica:</label><br>
			<label for="choices" style="position:absolute;top:20px;left:10px">Datos:</label>
			<div id="choices" style="position:absolute;top:20px;left:60px"></div><br>
			<label for="fechas" style="position:absolute;top:40px;left:10px">Periodo:</label>
			<div id="fechas" style="position:absolute;top:40px;left:60px">
			</div>
		</div>
	</div>
	<div id="descarga" style="width:300px;height:100px;top:200px;left:740px;position:absolute">
		<div id="datepicker"></div>
		<label id="descargaLabel">Descarga de datos:</label><br>
		<form method='post' name='aa' id='aa' action='<?echo $url;?>csv.php'>
			<label for="desde">Desde:</label>
			<input type="text" id="desde" name="desde" size="12"/><br>
			<label for="hasta">Hasta:&nbsp;</label>
			<input type="text" id="hasta" name="hasta" size="12"/>
			<br><label for="formato">Formato:</label>
			<select disabled="disabled" id="formato" name="formato">
				<option value="csv">CSV</option>
				<option value="xls">XLS</option>
				<option value="sql">SQL</option>
			</select>
			<input type="submit" id="descargar" value="descargar"/>
		</form>
	</div>
	<div id="ghi2" style="width:200px;height:70px;top:-30px;left:730px;position:absolute"></div>
        <div id="hr2" style="width:200px;height:70px;top:50px;left:730px;position:absolute"></div>
        <div id="ta2" style="width:200px;height:70px;top:130px;left:730px;position:absolute"></div>	
</div>

<script type="text/javascript">
var datosJson = {};
var now = new Date(); 
var browserUtcOf = now.getTimezoneOffset() * 60 * 1000;

/* Definir opciones para el grafico*/
var placeholder = jQuery("#grafico");
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

var plot = jQuery.plot(placeholder, datosJson, options);

//configurcion de la etiqueta que muestra informacion sobre los puntos
function showTooltip(x, y, contents) {
	jQuery('<div id="tooltip">' + contents + '</div>').css( {
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
			jQuery("#tooltip").remove();
			var x = item.datapoint[0],
			y = item.datapoint[1].toFixed(2);
     				
			var fecha = new Date(x + browserUtcOf);
			fecha = fecha.format("d M Y, H:i:s");		
			var label = item.series.label.split(" ");
			showTooltip(item.pageX, item.pageY, fecha + " UTC<br>" + y + " " + label[1]);
		}
	}else{
		jQuery("#tooltip").remove();
		previousPoint = null;            
		}
});

/*Selector de fechas*/
var datos = {};
var choiceContainer = jQuery("#choices");
var fechasContainer = jQuery("#fechas");

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
jQuery.ajax({
	url: '<?echo $url;?>solarDatos.php',
	type: 'POST',async: true,data: fechasWhere,dataType: 'json',
	success: function(data, textStatus, jqXHR){
		datos.Ano = data;
		fechasContainer.append('<input type="radio" name="fechasWhere" value="Ano">Año');
		fechasContainer.find("input").click(plotCurvas);
	},
});
//console.log(datos);
/* Datos del mes */
fechasWhere.a = fechasMes;
jQuery.ajax({
        url: '<?echo $url;?>solarDatos.php',
        type: 'POST',async: true,data: fechasWhere,dataType: 'json',
        success: function(data, textStatus, jqXHR){
		datos.Mes = data;
		fechasContainer.append('<input type="radio" name="fechasWhere" value="Mes">Mes');
		fechasContainer.find("input").click(plotCurvas);
	},
});

/* Datos del semana */
fechasWhere.a = fechasSemana;
jQuery.ajax({
        url: '<?echo $url;?>solarDatos.php',
        type: 'POST',async: true,data: fechasWhere,dataType: 'json',
        success: function(data, textStatus, jqXHR){
		datos.Semana = data;
		fechasContainer.append('<input type="radio" name="fechasWhere" value="Semana">Semana');
		fechasContainer.find("input").click(plotCurvas);
	},
});

/* Datos del dia */
fechasWhere.a = fechasDia;
jQuery.ajax({
        url: '<?echo $url;?>solarDatos.php',
        type: 'POST',async: false,data: fechasWhere,dataType: 'json',
        success: function(data, textStatus, jqXHR){
		datos.Dia = data;
		fechasContainer.append('<input type="radio" name="fechasWhere" value="Dia" checked>Dia');
		fechasContainer.find("input").click(plotCurvas);
	},
});

/* Mostrar opciones de ploteo */
/* opciones de curvas */
jQuery.each(datos.Dia, function(key, val) {
	choiceContainer.append('<input type="checkbox" name="'+key+'" id="'+ key+ '">' +
		'<label for="id'+key+'">'+val.label+'</label>');
	});

jQuery(document).ready(function(){
	choiceContainer.find("input:checkbox:first").attr('checked',"true");
	plotCurvas();
	choiceContainer.find("input").click(plotCurvas);
});
 
/* Reacer el grafico de acuerdo a las opciones */    
function plotFechas() {
	/*Seleccionar conjunto de datos*/
	var key = fechasContainer.find("input:checked").attr("value");
	var datosFecha = datos[key];

	/*Selector de opciones*/
	choiceContainer.empty(); 
	jQuery.each(datosFecha, function(key, val) {
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
	jQuery.each(datosFecha, function(key, val) {
		val.color = i;
		++i;
		});
	
	/* Plotear de acuerdo a las curvas checkeadas */
        choiceContainer.find("input:checked").each(function () {
                var key = jQuery(this).attr("name");
                if (key && datosFecha[key]) data.push(datosFecha[key]);
        	});
        plot = jQuery.plot(placeholder, data, options);
	}

/* Opciones del calendario */
jQuery( "#desde" ).datepicker({
        changeMonth: true,
        numberOfMonths: 1,
        onSelect: function( selectedDate ) {
                jQuery( "#hasta" ).datepicker( "option", "minDate", selectedDate );
                fechasWhere.desde = selectedDate;
        },
        showOn: "button",
        buttonImage: "<?echo $url;?>images/calendar.gif",
        buttonImageOnly: true,
        dateFormat: "yy-mm-dd"
});
jQuery( "#hasta" ).datepicker({
        changeMonth: true,
        numberOfMonths: 1,
        onSelect: function( selectedDate ) {
                jQuery( "#desde" ).datepicker( "option", "maxDate", selectedDate );
                fechasWhere.hasta = selectedDate;
        },
        showOn: "button",
        buttonImage: "<?echo $url;?>images/calendar.gif",
        buttonImageOnly: true,
        dateFormat: "yy-mm-dd"
});

/* cargar plugin meteorologico ghi */
jQuery.ajax({
        url: '<?echo $url;?>ghi.php',
        type: "POST",
	data: {"siteUrl":"<?echo $_POST['siteUrl'];?>"},
        dataType: "html",
        async: false,
        success: function(data, textStatus, jqXHR){
                jQuery("#ghi2").append(data);
                },
        });

/* cargar plugin meteorologico ghi */
jQuery.ajax({
        url: '<?echo $url;?>hr.php',
        type: "POST",
	data: {"siteUrl":"<?echo $_POST['siteUrl'];?>"},
        dataType: "html",
        async: true,
        success: function(data, textStatus, jqXHR){
                jQuery("#hr2").append(data);
                },
        });

/* cargar plugin meteorologico ghi */
jQuery.ajax({
        url: '<?echo $url;?>ta.php',
        type: "POST",
	data: {"siteUrl":"<?echo $_POST['siteUrl'];?>"},
        dataType: "html",
        async: true,
        success: function(data, textStatus, jqXHR){
                jQuery("#ta2").append(data);
                },
        });
</script>
</body>
</html>
