<?php
$etiquetaDatosCampoFuente = $_POST["campoFuente"];
$etiquetaDatosCampoDestino = $_POST["campoDestino"];
$etiquetaDatosWhere = $_POST["where"];

$datosConexion = array(
        'servidorBD' => "localhost",
        'usuario' => "root",
        'pass' => ".joumandolo",
        'bd' => "solar"
        );

$conexion = mysql_connect($datosConexion['servidorBD'], $datosConexion['usuario'], $datosConexion['pass']);
mysql_select_db($datosConexion['bd'], $conexion);

/* Seleccionar unidades para etiquetas */
if($etiquetaDatosCampoFuente){
	$q = "SELECT DISTINCT ".$etiquetaDatosCampoDestino." FROM ubicacion WHERE ".$etiquetaDatosCampoFuente."='".$etiquetaDatosWhere."'";
}else{
	 $q = "SELECT DISTINCT ".$etiquetaDatosCampoDestino." FROM ubicacion";
}

$r = mysql_query($q, $conexion) or die(mysql_error());

while($fila = mysql_fetch_row($r)){
        $ciudades[] = $fila;
}

$datos = array(
	'ciudades' => array('label' => "ciudades", "data" => $ciudades),
	);

#var_dump($datos);

header('Content-type: application/json');
$datos = json_encode($datos);
echo $datos;

