<?php $url = $_SERVER["SERVER_NAME"] ?>
<html><head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<script language="javascript" type="text/javascript" src="http://<?echo $url;?>/wordpress/wp-content/plugins/solarGraficos/flot/jquery.js"></script><script language="javascript" type="text/javascript" src="http://<?echo $url;?>/wordpress/wp-content/plugins/solarGraficos/dateFormat.js"></script>
<!--[if IE]><script language="javascript" type="text/javascript" src="http://<?echo $url;?>/wordpress/wp-content/plugins/solarGraficos/flot/excanvas.js"></script><![endif]-->
<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/jquery-ui.min.js"></script>
<script src="http://jquery-ui.googlecode.com/svn/trunk/ui/i18n/jquery.ui.datepicker-es.js"></script>
	
</head><body>

<div id="datepicker"></div>
<label id="descargaLabel">Descarga de datos:</label><br>

<form method='post' name='aa' id='aa' action='http://<?echo $url;?>/wordpress/wp-content/plugins/solarGraficos/csv.php'>
	<label for="desde">Desde:</label>
	<input type="text" id="desde" name="desde"/>
	<label for="hasta">Hasta:</label>
	<input type="text" id="hasta" name="hasta"/>
	<br><label for="formato">Formato:</label>
	<select disabled="disabled" id="formato" name="formato"><option value="csv">CSV</option><option value="xls">XLS</option><option value="sql">SQL</option></select>
	<input type="submit" id="descargar"/>
</form>

<script>
/* setear data */
var fechasWhere = {};
var datos;

$( "#desde" ).datepicker({
	//defaultDate: "+1w",
	changeMonth: true,
	numberOfMonths: 1,
	onSelect: function( selectedDate ) {
		$( "#hasta" ).datepicker( "option", "minDate", selectedDate );
		fechasWhere.desde = selectedDate;
	},
	showOn: "button",
	buttonImage: "./calendar.gif",
	buttonImageOnly: true,
	dateFormat: "yy-mm-dd"
});

$( "#hasta" ).datepicker({
	//defaultDate: "+1w",
	changeMonth: true,
	numberOfMonths: 1,
	onSelect: function( selectedDate ) {
		$( "#desde" ).datepicker( "option", "maxDate", selectedDate );
		fechasWhere.hasta = selectedDate;
	},
	showOn: "button",
	buttonImage: "./calendar.gif",
	buttonImageOnly: true,
	dateFormat: "yy-mm-dd"
});

/*
$("#aa").submit(function(event){
	 event.preventDefault();
});

$("#descargar").bind("click", function(){
        $("#aa").attr("action") = 'http://'+self.location.hostname+'/wordpress/wp-content/plugins/solarGraficos/csv.php';
	$("#aa").submit();
	console.log($("#aa"));
});
*/

/*	$.ajax({
                //url: 'http://'+self.location.hostname+'/wordpress/wp-content/plugins/solarGraficos/solarDatos.php',
                url: 'http://'+self.location.hostname+'/wordpress/wp-content/plugins/solarGraficos/csv.php',
                type: 'POST',async: false,data: fechasWhere,dataType: 'html',
                //success: function(data, textStatus, jqXHR){datos = data;},
                success: function(data, textStatus, jqXHR){
			$(document).html();
			console.log(data);	
		},
        });
        console.log(datos);
});
*/

</script>
</body>
</html>
