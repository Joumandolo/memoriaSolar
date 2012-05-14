<?php
/* Obtenemos los datos provenientes por get desde el dataloger*/
$dataloger = array(
        //"timestamp" => date("Y-m-d H:i:s",time()),
        "battV" => $_GET["battV"],
        "radW" => $_GET["radW"],
        "radW2" => $_GET["radW2"],
        "airTc" => $_GET["airTc"],
        "rh" => $_GET["rh"]
        );

/* Verificar si los valores enviados por el dataloger son numericos */
foreach( $dataloger as $key => $val ){ if( !is_numeric($val)){  $dataloger[$key] = 0; }}
		
/* Conectarse a la base de datos */
$conexion = mysql_connect("localhost", "root", ".joumandolo") or die("no conexion");
mysql_select_db("wordpress", $conexion) or die("Error al conectarse con la bases de datos...");

/* insertar registros */
$q = "INSERT INTO solarDatos2 (timestamp, Rad_W, Rad_kJ_Tot, rh, Temp_TC, BattV) VALUES (UTC_TIMESTAMP(), ".$dataloger['radW'].",".$dataloger['radW2'].",".$dataloger['rh'].",".$dataloger['airTc'].",".$dataloger['battV'].")";

$r = mysql_query($q, $conexion) or die(mysql_error());

//var_dump($dataloger);

//header('Content-type: application/json');
//$datos = json_encode($datos);
//echo $datos;

