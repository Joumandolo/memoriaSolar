<?php $url = $_POST["siteUrl"]."/wp-content/plugins/solarCalc/" ?>
<html><head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

<script language="javascript" type="text/javascript" src="<?echo $url;?>libs/jquery-ui/js/jquery-1.7.2.min.js"></script>
<script language="javascript" type="text/javascript" src="<?echo $url;?>libs/jquery-ui/js/jquery-ui-1.8.21.custom.min.js"></script>
<script language="javascript" type="text/javascript" src="<?echo $url;?>libs/flot/jquery.flot.js"></script>
<script language="javascript" type="text/javascript" src="<?echo $url;?>libs/flot/jquery.flot.axislabels.js"></script>
<script language="javascript" type="text/javascript" src="<?echo $url;?>libs/dateFormat.js"></script>

<link href="<?echo $url;?>libs/jquery-ui/css/ui-darkness/jquery-ui-1.8.21.custom.css" rel="stylesheet" type="text/css"/>
<link href="<?echo $url;?>solar.css" rel="stylesheet" type="text/css"/>
<!--[if IE]><script language="javascript" type="text/javascript" src="<?echo $url;?>libs/flot/excanvas.js"></script><![endif]-->
<!--[if IE]><script language="javascript" type="text/javascript" src="<?echo $url;?>libs/flot/excanvas.min.js"></script><![endif]-->

<div id="calculadora">
	<div id="ubicacion">
		<div id="imagen"></div>
		<div id="cabeza">
			<label id="titulo">Ubicación Geográfica</label>
		</div>
		<div id="cuerpo">
			<label>País:</label><select id='pais'></select>
			<label>Región:</label><select id='region'></select>
			<label>Comuna:</label><select id='comuna'></select>
			<label>Latitud:</label><input type="text" id='latitud' disabled></input>
			<label>Longitud:</label><input type="text" id='longitud' disabled></input>
		</div>
	</div>

	<div id="resultado">	
		<div id="cabeza"> 
			<label id="titulo">Resultados</label>
		</div>
		<div id="cuerpo">
			<div = id="sello"></div>
			<div = id="carga"></div>
			<div = id="tabla"></div>
			<div = id="grafico1"></div> 
			<div = id="grafico2"></div>
		</div>
	</div>

	<div id="planta">
		<div id="imagen"></div>
		<div id="cabeza">
			<label id="titulo">Especificaciones del sistema PV</label>
		</div>
		<div id="cuerpo">
			<label id='potenciap'>Potencia planta[W]:</label><input id='potenciap' value="4000"/>
			<label id='factorp'>Factor de rendimiento:</label><input id='factorp' value="0.77"/><br><hr>
			<label id='orientacion'>Orientación[°]:</label><input id='orientacion' value="0"/>
			<label 	id ='inclinacion'>Inclinación[°]:</label><input id='inclinacion' value="0"/>
			<label id='albedo'>Albedo:</label><input id='albedo' value="0.2"/>
		</div>
	</div>

	<div id="energia" >
		<div id="imagen"></div>
		<div id="cabeza">
			<label id="titulo">Información de energía</label>
		</div>
		<div id="cuerpo">
			<label id='cenergia'>Costo de la electricidad(moneda local)[$/kWh]:</label>
			<input id='cenergia'></input>
		</div>
	</div>

	<div id="boton">
		<a id="calcular"></a>
		<label id="calcular">Calcular</label>
	</div>
</div>

<script type="text/javascript">
var datos;
var paises = {};
var ciudades = {};
var comunas = {};
var etiquetaDatos = {};
var datosConsumo = [];

/*Calcular*/
jQuery("#calcular").bind("click", function(event){
	//event.preventDefault();
	etiquetaDatos.comuna = jQuery("#comuna").val();
	etiquetaDatos.ori = jQuery("input#orientacion").val();
	etiquetaDatos.incl = jQuery("input#inclinacion").val();
	etiquetaDatos.alb = jQuery("input#albedo").val();
	etiquetaDatos.pnp = jQuery("input#potenciap").val();
	etiquetaDatos.fp = jQuery("input#factorp").val();
	etiquetaDatos.vkwh = jQuery("input#cenergia").val();
	jQuery.ajax({
		url:'<?echo $url;?>radiacioHorizontalHoraria.php',
		type:'POST',async:true,data:etiquetaDatos,dataType:'json',
		beforeSend:function(){
			// this is where we append a loading image
			jQuery("div#resultado div#cuerpo div#carga").html("");
			jQuery("div#resultado div#cuerpo div#tabla").html("");
			jQuery("div#resultado div#cuerpo div#grafico1").html("");
			jQuery("div#resultado div#cuerpo div#grafico2").html("");
			
			jQuery("div#resultado div#cuerpo div#carga").append('<div id="load"><img src="<?echo $url;?>img/ajax-loader.gif" alt="Loading..." /></div>');
			//jQuery(this).delay(10000);
		},
		success: function(data, textStatus, jqXHR){
			jQuery("div#resultado div#cuerpo div#carga").html("");
			//jQuery("div#resultado div#cuerpo div#tabla").html("");
			//jQuery("div#resultado div#cuerpo div#grafico1").html("");
			//jQuery("div#resultado div#cuerpo div#grafico2").html("");
			
			jQuery("div#resultado div#cuerpo div#tabla").append(mostrarTablaResultado(data));
			jQuery("div#resultado div#cuerpo div#grafico1").append(mostrarGraficaTabla(data));
			jQuery("div#resultado div#cuerpo div#grafico2").append(mostrarGraficaHoraria(data));
		}
	});
	etiquetaDatos = {};
});
/* crear grafica */
function mostrarGraficaTabla(datos){
	datos = [datos[0],datos[1]];
	var placeholder = jQuery("div#resultado div#cuerpo div#grafico1");
	var options = {
		series: { lines: { show: true, shadowSize: 0 }},
		legend: { show: true, noColumns: 2, position:'se'},
		xaxis:  { ticks: 12, minTickSize: 1, tickDecimals:0 },
		yaxix:  [{ min:0, position:'left'},{min:0, position:'right'}],
		y1axix:  { min:0, position:'left'},
		y2axis: { min:0, position:'right'}
	};
	var plot = jQuery.plot(placeholder, datos, options);
	var tags = '<label id="tagAri">Radiación solar y Energia producida en un año</label>'+'<label id="tagDer">[kWh/m2]</label>'+'<label id="tagIzq">[kWh]</label>'+'<label id="tagAbj">[Mes]</label>';
	return tags;
}

/* crear grafica de radiacion horaria para el año*/
function mostrarGraficaHoraria(datos){
	datos = [datos[7],datos[8],datos[9],datos[10],datos[11],datos[12],datos[13],datos[14],datos[15],datos[16],datos[17],datos[18]];
	var placeholder = jQuery("div#resultado div#cuerpo div#grafico2");
	var options = {
		series: { lines: { show: true, shadowSize: 0 }},
		legend: { show: true, noColumns: 1, position:'se'},
		xaxis:  { max:24,min:0,ticks: 24, minTickSize: 1, tickDecimals:0 },
		yaxix:  { min:0, label:'[Wh]'}
	};
	var plot = jQuery.plot(placeholder, datos, options);
	var tags = '<label id="tagAri">Radiación solar horaria para un dia del mes</label>'+'<label id="tagIzq">[Wh/m2]</label>'+'<label id="tagAbj">Hora del día</label>';
	return tags;
}

/* crear tabla */
function mostrarTablaResultado(datos){
	console.log(datos);
	var filaMes = '<th id=col1 >Mes</th>';
	var filaRadiacion = '<td id=col1>Radiación solar[kWh]</td>';
	var filaEnergia = '<td id=col1>Energia AC [kWh]</td>';
	var filaCosto = '<td id=col1>Valor de la energía</td>';

	//var r = 0;
	//var e = 0;
	//var c = 0;

	for (var i = 0; i < 12; i++) {
		//r = r + datos[1]['data'][i][1];
        	//e = e + datos[0]['data'][i][1];
        	//c = c + datos[3]['data'][i][1];

        	filaMes = filaMes + '<th>' + datos[2]['data'][i][1] + '</th>';
        	filaRadiacion = filaRadiacion + '<td id="val">' + datos[1]['data'][i][1] + '</td>';
        	filaEnergia = filaEnergia + '<td id="val">' + datos[0]['data'][i][1] + '</td>';
        	filaCosto = filaCosto + '<td id="val">'  + datos[3]['data'][i][1] + '</td>';
	}
	//r = Math.round( r * 100 ) / 100;
	//e = Math.round( e * 100 ) / 100;
	//c = Math.round( c * 100 ) / 100;
	
	var tabla = '<table>' + 
                	'<tr>'+filaMes+'<th>Total</th></tr>' + 
                	'<tr>'+filaRadiacion+'<td id=val>'+datos[4]['data']+'</td></tr>' +
                	'<tr>'+filaEnergia+'<td id=val>'+datos[5]['data']+'</td></tr>' +
                	'<tr>'+filaCosto+'<td id=val>'+datos[6]['data']+'</td></tr>' +
        	'</table>';
	return tabla;
}

/* Poblar paises  */
jQuery(document).ready(function(){
	etiquetaDatos.etiqueta = "ubicacion";
	etiquetaDatos.campoDestino = "pais";
	//etiquetaDatos.campoFuente = "pais";
	//etiquetaDatos.where = "V Región de Valparaíso";
	jQuery.ajax({
		url:'<?echo $url;?>solarDatos.php',
		type:'POST',async:false,data:etiquetaDatos,dataType:'json',
		success: function(data, textStatus, jqXHR){
			data = data["options"];
			//console.log(data);
			var options = '<option value=0>Seleccion una pais...</option>';
			for (var i = 0; i < data.length; i++) {
				options += '<option value="' + data[i] + '">' + data[i] + '</option>';
			}
		jQuery("select#pais").html(options);
		//$("#resultado").html(data);
		}
	});
	//etiquetaDatos = {};
});

/* Poblar ciudades */
jQuery("select#pais").bind("change", function(event){
	etiquetaDatos.etiqueta = "ubicacion";
	etiquetaDatos.campoFuente = "pais";
        etiquetaDatos.where = event.target.value;
        etiquetaDatos.campoDestino = "region";
	//console.log(etiquetaDatos);

	if(etiquetaDatos.where != "0"){
		jQuery.ajax({
			url:'<?echo $url;?>solarDatos.php',
			type:'POST',async:false,data:etiquetaDatos,dataType:'json',
			success: function(data, textStatus, jqXHR){
				data = data["options"];
				//console.log(textStatus);
				var options = '<option value=0>Seleccion una ...</option>';
				for (var i = 0; i < data.length; i++) {
					options += '<option value="' + data[i] + '">' + data[i] + '</option>';
				}
				jQuery("select#region").html(options);
				var options = '<option value=0>Seleccion una comuna...</option>';
				jQuery("select#comuna").html(options);
				jQuery("input#latitud").empty();
				jQuery("input#longitud").empty();
				//$("#resultado").empty();
			}
		});
	}else{
		var options = '<option value=0>Región...</option>';
		jQuery("select#region").html(options);
		var options = '<option value=0>Comuna...</option>';
                jQuery("select#comuna").html(options);
		jQuery("input#latitud").empty();
                jQuery("input#longitud").empty();
	}
});

/* Poblar comunas */
jQuery("select#region").bind("change", function(event){
	etiquetaDatos.etiqueta = "ubicacion";
	etiquetaDatos.campoFuente = "region";
	etiquetaDatos.where = event.target.value;
	etiquetaDatos.campoDestino = "comuna";
	//console.log(etiquetaDatos);

	if(etiquetaDatos.where != "0"){
		jQuery.ajax({
			url:'<?echo $url;?>solarDatos.php',
			type:'POST',async:false,data:etiquetaDatos,dataType:'json',
			success: function(data, textStatus, jqXHR){
				data = data["options"];
				//console.log(data);
				var options = '<option value=0>Comuna...</option>';
				for (var i = 0; i < data.length; i++) {
					options += '<option value="' + data[i] + '">' + data[i] + '</option>';
				}
				jQuery("select#"+etiquetaDatos.campoDestino).html(options);
				jQuery("input#latitud").empty();
				jQuery("input#longitud").empty();
			}
		});
	}else{
		var options = '<option value=0>Comuna...</option>';
		jQuery("select#comuna").html(options);
		jQuery("input#latitud").empty();
                jQuery("input#longitud").empty();
	}
	//etiquetaDatos = {};
});

/* Mostrar coordenadas */
jQuery("select#comuna").bind("change", function(event){
	etiquetaDatos.etiqueta = "ubicacion";
	etiquetaDatos.campoFuente = "comuna";
	etiquetaDatos.where = event.target.value;
	etiquetaDatos.campoDestino = "latitud, longitud"

	if(etiquetaDatos.where != "0"){
		jQuery.ajax({
			url:'<?echo $url;?>solarDatos.php',
			type:'POST',async:true,data:etiquetaDatos,dataType:'json',
			success: function(data, textStatus, jqXHR){
				data = data["options"];
				//console.log(data);
				jQuery("input#latitud").val(parseFloat(data[0][0]).toFixed(2));
				jQuery("input#longitud").val(parseFloat(data[0][1]).toFixed(2));
				}
			});
	}else{
		jQuery("input#latitud").empty();
                jQuery("input#longitud").empty();
	}
	etiquetaDatos = {};
});

/* Mostrar div de fijaciones */
jQuery("select#fijacion").bind("change", function(event){
	if(event.target.value == "fija"){
		jQuery("div#planta").append("<div id='fija'><label>Inclinacion:</label><input id='inclinacion'></input><br><label>Azimuth:</label><input id='azimuth'></input></div>");
		jQuery("div#planta").css("height","280px");
		jQuery("div#fija").css({'position':"absolute","top":"150","left":"20","width":"180px","height":"100px","border":"3px black solid"});
	}else{
		jQuery("div#fija").remove();
		jQuery("div#planta").css("height","150px");
	}
	
});

</script>
</body>
</html>
