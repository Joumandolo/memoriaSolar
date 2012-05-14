<?php $url = $_SERVER["SERVER_NAME"] ?>
<html><head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<script language="javascript" type="text/javascript" src="http://<?echo $url;?>/wordpress/wp-content/plugins/solarGraficos/flot/jquery.js"></script>

<label id="titulo">Estacion Solar Fundacion Chile</label><br> 
<label id="ubicacion">Ubicacion: Vitacura, Santiago de Chile</label><br> 
<label id="zona">Zona horaria: UTC-4</label><br>
<div id="solarFch" style="padding:20px;width:90%;height:90%">
	<div id="graf" style="width:500px;height:400px;float:left"></div>
	<div id="ghi" style="width:200px;height:100px;float:right"></div>
	<div id="hr" style="width:200px;height:100px;float:right"></div>
	<div id="ta" style="width:200px;height:100px;float:right"></div>
</div>

<script type="text/javascript">
/* cargar el plugin grafico */
jQuery.ajax({
	url: 'http://'+self.location.hostname+'/wordpress/wp-content/plugins/solarGraficos/grafico.php',
	type: "POST",
	dataType: "html",
	async: true,
	success: function(data, textStatus, jqXHR){
		jQuery("#graf").append(data);
		},
	});

/* cargar plugin meteorologico ghi */
jQuery.ajax({
        url: 'http://'+self.location.hostname+'/wordpress/wp-content/plugins/solarMeteo/ghi.php',
        type: "POST",
        dataType: "html",
        async: true,
        success: function(data, textStatus, jqXHR){
                jQuery("#ghi").append(data);
                },
        });

/* cargar plugin meteorologico hr */
jQuery.ajax({
        url: 'http://'+self.location.hostname+'/wordpress/wp-content/plugins/solarMeteo/hr.php',
        type: "POST",
        dataType: "html",
        async: true,
        success: function(data, textStatus, jqXHR){
                jQuery("#hr").append(data);
                },
        });

/* cargar plugin meteorologico ta */
jQuery.ajax({
        url: 'http://'+self.location.hostname+'/wordpress/wp-content/plugins/solarMeteo/ta.php',
        type: "POST",
        dataType: "html",
        async: true,
        success: function(data, textStatus, jqXHR){
                jQuery("#ta").append(data);
                },
        });
</script>
</body></html>
