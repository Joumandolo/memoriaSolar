<?php
/* verificar variables que llegan */
if($_POST["a"]){
        $where = "WHERE timestamp > '".$_POST["a"]."'";
}elseif($_POST["desde"] AND $_POST["hasta"]){
        $where = "WHERE timestamp BETWEEN '".$_POST['desde']." 00:00:00' AND '".$_POST['hasta']." 23:59:59'";
}

$fechaWhere = $_POST["a"];
//$fechaHoy = Date();
$fechaBrowserOf = $_POST["b"];

$datosConexion = array(
        'servidorBD' => "solar.db.7367634.hostedresource.com",
        'usuario' => "widgetSolar",
        'pass' => "corbet*Mount54",
        'bd' => "solar",
	'servidorBD2' => "184.168.194.156",
        'usuario2' => "datosestaciones",
        'pass2' => "vB1p1I9k2uz06Y",
        'bd2' => "datosestaciones"
        );

$conexion = mysql_connect($datosConexion['servidorBD'], $datosConexion['usuario'], $datosConexion['pass']);
mysql_select_db($datosConexion['bd'], $conexion);
$conexion2 = mysql_connect($datosConexion['servidorBD2'], $datosConexion['usuario2'], $datosConexion['pass2']);
mysql_select_db($datosConexion['bd2'], $conexion2);

/* Seleccionar unidades para etiquetas */
$q = "SELECT campo, etiqueta, unidad FROM solarUnidades";
$r = mysql_query($q, $conexion) or die(mysql_error());

while($fila = mysql_fetch_row($r)){
        $unidades[$fila[0]] = array($fila[1], $fila[2]); //array($fila[0],$fila[1]);
}

//$q = "SELECT UNIX_TIMESTAMP(timestamp)*1000, Rad_W, rh, Temp_TC, BattV FROM solarDatos2 where timestamp > '$fechaWhere' order by timestamp";
//$q = "SELECT timestamp, Rad_W, rh, Temp_TC, BattV FROM solarDatos2 where timestamp > '$fechaWhere' order by timestamp";
//$q = "SELECT timestamp, Rad_W, rh, Temp_TC, BattV FROM solarDatos2 $where order by timestamp";
$q = "SELECT timestamp, ghi2wm2, rh, airtc, battv FROM solarFch $where order by timestamp";
$r = mysql_query($q, $conexion2) or die(mysql_error());

while($fila = mysql_fetch_row($r)){
	$time = (strtotime($fila[0]."UTC") * 1000);// + $fechaBrowserOf;
	$timeradw[] = array($time,$fila[1]);
	//$timeradkjtot[] = array($fila[0],$fila[2]);	
	$timerh[] = array($time,$fila[2]);	
	$timetemptc[] = array($time,$fila[3]);	
	//$timebattv[] = array($time,$fila[4]);	
}

$datos = array(
	'rad_w'	=> array('label' => $unidades['Rad_W'][0]." ".$unidades['Rad_W'][1], "data" => $timeradw),
	//'rad_kj'=> array('label' => $unidades['Rad_kJ_Tot'][0]." ".$unidades['Rad_kJ_Tot'][1], "data" => $timeradkjtot),
	'rh'	=> array('label' => $unidades['rh'][0]." ".$unidades['rh'][1], "data" => $timerh),
	'temp_c'=> array('label' => $unidades['Temp_TC'][0]." ".$unidades['Temp_TC'][1], "data" => $timetemptc),
	//'battv'=> array('label' => "Batería ".$unidades['BattV'], "data" => $timebattv)
	);

#var_dump($datos);

header('Content-type: application/json');
$datos = json_encode($datos);
echo $datos;

