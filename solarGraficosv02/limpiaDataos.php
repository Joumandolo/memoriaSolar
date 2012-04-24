<?php
$conexion = mysql_connect("localhost", "root", ".joumandolo") or die("no conexion");
mysql_select_db("solar", $conexion) or die("no bd");

$q = "SELECT id,timestamp FROM test";
$r = mysql_query($q, $conexion) or die(mysql_error());

while($fila = mysql_fetch_row($r)){
	$time = str_replace("\"",'',$fila[1]);
	#$q = "update 'test' set timestamp='".$time."' where id=".$fila[0];
	$q = "update test set `timestamp`='".$time."' where `id`=".$fila[0];
	#$q = "update test set timestamp= \' 18:27:00\' where `id`=10418";
	mysql_query($q) or die(mysql_error());
}

#var_dump($datos);

#header('Content-type: application/json');
#$datos = json_encode($datos);
#echo $datos;

