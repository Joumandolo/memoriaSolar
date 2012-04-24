<?php
$conexion = mysql_connect("localhost", "root", ".joumandolo") or die("no conexion");
mysql_select_db("wordpress", $conexion) or die("no bd");

/* Seleccionar unidades para etiquetas */
$q = "SELECT campo, etiqueta, unidad FROM solarUnidades";
$r = mysql_query($q, $conexion) or die(mysql_error());

while($fila = mysql_fetch_row($r)){
        $unidades[$fila[0]] = array($fila[1], $fila[2]); //array($fila[0],$fila[1]);
}

$q = "SELECT UNIX_TIMESTAMP(timestamp)*1000, Rad_W, Rad_kJ_Tot, rh, Temp_TC, BattV FROM solarDatos where Rad_W !=0 order by timestamp";
$r = mysql_query($q, $conexion) or die(mysql_error());

while($fila = mysql_fetch_row($r)){
	$timeradw[] = array($fila[0],$fila[1]);
	$timeradkjtot[] = array($fila[0],$fila[2]);	
	$timerh[] = array($fila[0],$fila[3]);	
	$timetemptc[] = array($fila[0],$fila[4]);	
	$timebattv[] = array($fila[0],$fila[5]);	
}

$datos = array(
	'rad_w'	=> array('label' => $unidades['Rad_W'][0]." ".$unidades['Rad_W'][1], "data" => $timeradw),
	'rad_kj'=> array('label' => $unidades['Rad_kJ_Tot'][0]." ".$unidades['Rad_kJ_Tot'][1], "data" => $timeradkjtot),
	'rh'	=> array('label' => $unidades['rh'][0]." ".$unidades['rh'][1], "data" => $timerh),
	'temp_c'=> array('label' => $unidades['Temp_TC'][0]." ".$unidades['Temp_TC'][1], "data" => $timetemptc),
	//'battv'=> array('label' => "Batería ".$unidades['BattV'], "data" => $timebattv)
	);

#var_dump($datos);

header('Content-type: application/json');
$datos = json_encode($datos);
echo $datos;

