<?php
/* Variables */
$radiacion = null;
$unidades = null;
$url = $_POST["siteUrl"]."/wp-content/plugins/solarFch/";

$datosConexion = array(
	'servidorBD' => "solar.db.7367634.hostedresource.com",
	'usuario' => "widgetSolar",
	'pass' => "corbet*Mount54",
	'bd' => "solar"
	);

$conexion = mysql_connect($datosConexion['servidorBD'], $datosConexion['usuario'], $datosConexion['pass']);
mysql_select_db($datosConexion['bd'], $conexion);

/* Consultamos la base de datos  */
$radiacion = "SELECT Rad_W, timestamp FROM solarDatos2 ORDER BY timestamp DESC LIMIT 1";
$radiacion = mysql_query($radiacion, $conexion) or die(mysql_error());
$radiacion = mysql_fetch_row($radiacion);
//var_dump($radiacion);

 $unidades = "SELECT etiqueta,unidad FROM solarUnidades";
 $unidades = mysql_query($unidades, $conexion) or die(mysql_error());
 $unidad[] = mysql_fetch_row($unidades);
 mysql_data_seek($unidades,4);
 $unidad[] = mysql_fetch_row($unidades);
 //var_dump($unidad);

/* Volcar widget */
echo "
	<div style='position:absolute;top:0px;left:0px;width:228px;height:49px;background-image:url(".$url."images/ghi.png)'>
		<div style='position:absolute;top:10px;left:50px;color:#fff11f;font-family:aero;font-weight:bold;font-size:14px'>".$unidad[1][0]."&nbsp;&nbsp;&nbsp;".round($radiacion[0])."&nbsp;&nbsp;&nbsp;".$unidad[1][1]."</div>
		<div style='position:absolute;top:26px;left:72px;color:#fff11f;font-family:aero;font-weight:normal;font-size:9px'>".$radiacion[1]." ".$unidad[0][0]."</div>
 	</div>
	";
?>
