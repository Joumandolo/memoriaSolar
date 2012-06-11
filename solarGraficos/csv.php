<?php
header("Content-type: text/csv");
header("Content-Disposition: attachment; filename=file.csv");
header("Content-type: application/force-download");  
header("Content-Transfer-Encoding: Binary");  
header("Pragma: no-cache");
header("Expires: 0");

/* Recivimos datos */
if($_POST["desde"] AND $_POST["hasta"]){
	$where = "WHERE timestamp BETWEEN '".$_POST['desde']." 00:00:00' AND '".$_POST['hasta']." 23:59:59'";
}

/* Conexion BD */
$datosConexion = array(
        'servidorBD' => "solar.db.7367634.hostedresource.com",
        'usuario' => "widgetSolar",
        'pass' => "corbet*Mount54",
        'bd' => "solar"
        );
$conexion = mysql_connect($datosConexion['servidorBD'], $datosConexion['usuario'], $datosConexion['pass']);
mysql_select_db($datosConexion['bd'], $conexion);
$q = "SELECT timestamp, Rad_W, rh, Temp_TC, BattV FROM solarDatos2 $where order by timestamp";
$r = mysql_query($q, $conexion) or die(mysql_error());

$datos = array(array("timestamp", "rad_w", "rh", "temp_tc"));
while($fila = mysql_fetch_row($r)){
        $time = strtotime($fila[0]."UTC");
        array_push($datos, array($fila[0], $fila[1], $fila[2], $fila[3]));
}

outputCSV($datos);

function outputCSV($data) {
    $outstream = fopen("php://output", "w");
    function __outputCSV(&$vals, $key, $filehandler) {
        fputcsv($filehandler, $vals); // add parameters if you want
    }
    array_walk($data, "__outputCSV", $outstream);
    fclose($outstream);
}
?>
