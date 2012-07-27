<?php
/* Definicion de Constantes */
/*Mes del año*/
$ma = array("ene","feb","mar","abr","may","jun","jul","ago","sep","oct","nov","dic");
/*Numero de dia del año, mitad de mes*/
$nda = array(17,47,75,105,135,162,198,228,258,288,318,344);
/*Numero de disa del mes*/
$ndm = array(31,28,31,30,31,30,31,31,30,31,30,31);

$datosConexion = array(
        'servidorBD' => "localhost",
        'usuario' => "root",
        'pass' => ".joumandolo",
        'bd' => "solar"
        );

$conexion = mysql_connect($datosConexion['servidorBD'], $datosConexion['usuario'], $datosConexion['pass']);
mysql_select_db($datosConexion['bd'], $conexion);

/* Seleccionar datos de Ubicacion */
$q = "SELECT comuna,ene,feb,mar,abr,may,jun,jul,ago,sep,oct,nov,dic FROM rad_kwh_mes";
$r = mysql_query($q, $conexion) or die(mysql_error());
while($fila = mysql_fetch_row($r)){ 
	for($i = 1;$i<13;$i++){ 
		$fila[$i] = round($fila[$i] * 1000 / $ndm[$i-1]);
		$q = "UPDATE rad_kwh_mes SET ".$ma[$i-1]."=".$fila[$i]." WHERE comuna='".$fila[0]."'";
		//mysql_query($q, $conexion) or die(mysql_error());
	}
	$ciudades[] = $fila;
}

$datos = array( 'options' => array_utf8_encode_recursive($ciudades) );

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

//header('Content-type: application/json');
//$datos = json_encode($datos);
//echo $datos;

?>
