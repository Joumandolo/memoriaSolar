<?php
$etiqueta = $_POST["etiqueta"];
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

/* Seleccionar datos de Ubicacion */
if($etiqueta == 'ubicacion'){
	if($etiquetaDatosCampoWhere){
		$q = "SELECT DISTINCT ".$etiquetaDatosCampoDestino." FROM comunas2 WHERE ".$etiquetaDatosCampoFuente."='".$etiquetaDatosWhere."' ORDER BY ".$etiquetaDatosCampoDestino." ASC";
		$r = mysql_query($q, $conexion) or die(mysql_error());
		while($fila = mysql_fetch_row($r)){ $ciudades[] = $fila; }
		$datos = array( 'options' => $ciudades,	);
	}elseif($etiquetaDatosCampoDestino){
		$q = "SELECT DISTINCT ".$etiquetaDatosCampoDestino." FROM comunas2 ORDER BY ".$etiquetaDatosCampoDestino." ASC";
		$r = mysql_query($q, $conexion) or die(mysql_error());
		while($fila = mysql_fetch_row($r)){ $ciudades[] = $fila; }
		$datos = array( 'options' => $ciudades, );
	}
}elseif($etiqueta == 'perfil'){
	$q = "SELECT DISTINCT * FROM iasolar_perfil_consumo";
	$r = mysql_query($q, $conexion) or die(mysql_error());
	while($fila = mysql_fetch_row($r)){ 
		$dat = array();
		for($i=3;$i<27;$i++){ $dat[] = array($i-2,$fila[$i]); }
		$perfiles[] = array('label' => $fila[2], 'data' => $dat); 
	}
	$datos = array( 'options' => array_utf8_encode_recursive($perfiles) );
}

/* Función para que funcionen acentos y ñ */
function array_utf8_encode_recursive($dat){
	if (is_string($dat)) { return utf8_encode($dat); } 
	if (is_object($dat)) { 
		$ovs = get_object_vars($dat); 
		$new = $dat; 
		foreach ($ovs as $k =>$v)    { 
			$new->$k = array_utf8_encode_recursive($new->$k); 
		}
		return $new; 
	}   
	if (!is_array($dat)) return $dat; 
	$ret = array(); 
	foreach($dat as $i=>$d) $ret[$i] = array_utf8_encode_recursive($d);  
	return $ret; 
}

header('Content-type: application/json');
$datos = json_encode($datos);
echo $datos;

