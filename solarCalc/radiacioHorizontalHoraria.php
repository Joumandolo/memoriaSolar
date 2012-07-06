<?php
/* Calcular la radiacion sobre el plano horizontal horaria a partir de la raciacion diaria de media mensual */

/* Definicion de Constantes */
/*Mes del año*/
$ma = array("Ene","Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic");
/*Numero de dia del año, mitad de mes*/
$nda = array(17,47,75,105,135,162,198,228,258,288,318,344);
/*Numero de disa del mes*/
$ndm = array(31,28,31,30,31,30,31,31,30,31,30,31);
/*Horas del dia */
$hd = array(0.5,1.5,2.5,3.5,4.5,5.5,6.5,7.5,8.5,9.5,10.5,11.5,12.5,13.5,14.5,15.5,16.5,17.5,18.5,19.5,20.5,21.5,22.5,23.5);
/*Constante solar*/
$cs = 1353;

/* Definicion de datos que dependen de la comuna a calcular */
/*Latitud*/
$l = -23.3;
/*Radiacion solar global Horizontal diaria de media mensual*/
$RGHd = 8647;

/*Datos ingresados por el usuario*/
/*Oientacion*/
$ori = 0;
/*Inclinacion*/
$incl = 0;
/*Albedo*/
$alb = 0.2;

/*Ecuaciones de calculo*/
/*Angulo del dia [rad]*/
function gamma($nda){
	$gamma = ( 2 * M_PI * ($nda - 1) ) / 365;
	return $gamma;
}
/*Declinacion solar [°]*/
function delta($nda){
	$delta = ( (180/M_PI) * (0.006918 - (0.399912*cos(gamma($nda))) + (0.070257*sin(gamma($nda))) - (0.006758*cos(2*gamma($nda))) + (0.000907*sin(2*gamma($nda))) - (0.002697*cos(3*gamma($nda))) + (0.00148*sin(3*gamma($nda))) ) );
	return $delta;
}
/*Angulo de puesta de sol [°]*/
function ws($l,$nda){
	$ws = ( acos( -tan(($l * M_PI) / 180) * tan((delta($nda) * M_PI) / 180) ) * (180/M_PI) );
	return $ws;
}
/*Radiacion solar extraterrestre, estimada a partir del indice de nuvocidad [Wh/m2 dia]*/
function rse($l,$nda,$cs){
	$rse = ( (24 / M_PI) * $cs * (1 + 0.033 * cos(360 * ($nda/365) * (M_PI/180))) * (cos($l * M_PI / 180)) * (cos(delta($nda) * M_PI / 180)) * (sin(ws($l,$nda) * M_PI / 180) - (M_PI / 180 * ws($l,$nda) * cos(ws($l,$nda) * M_PI / 180))));
	return $rse;
}
/*Indice nubosidad [Wh/m2 dia]*/
function kt($RGHd,$l,$nda,$cs){
	$kt = $RGHd / rse($l,$nda,$cs);
	return $kt;
}
/*Horas de sol al dia*/
function hsd($l,$nda){
	$hsd = ws($l,$nda) / 15 * 2 ;
	return $hsd;
}
/*Radiacion solar difusa horizontal diaria de media mensual, a partir de modelo gopinathan KK y soler A, 1995*/
function RDHd($RGHd,$l,$nda,$cs){
	$RDHd = $RGHd * (0.91138 - (0.96225 * kt($RGHd,$l,$nda,$cs)));
	return $RDHd;
}
/*factor a*/
function a($l,$nda){
	$a = (0.409 + (0.5016 * sin((ws($l,$nda) - 60) * M_PI / 180)));
	return $a;
}
/*factor b*/
function b($l,$nda){
	$b = (0.6609 - (0.4767 * sin((ws($l,$nda) - 60) * M_PI / 180)));
        return $b;
}

/*Funciones para calcular algunos datos geometricos*/
/*[°]*/
function w($hd){
	$w = 15 * ($hd - 12);
	return $w;
}
/*Altura solar [°]*/
function hs($l,$nda,$hd){
	$hs = (asin((sin($l * M_PI / 180) * sin(delta($nda) * M_PI / 180)) + (cos($l * M_PI / 180) * cos(delta($nda) * M_PI / 180) * cos(w($hd) * M_PI / 180))) * (180 / M_PI));
	return $hs;
}
/*Acimut [°]*/
function acimut($l,$nda,$hd){
	$acimut = (acos(( (cos(delta($nda) * M_PI / 180) * cos(w($hd) * M_PI / 180)) - (cos($l * M_PI / 180) * sin(hs($l,$nda,$hd) * M_PI / 180)) ) / (cos(hs($l,$nda,$hd) * M_PI / 180) * sin($l * M_PI / 180))) * 180 / M_PI);
	return ($hd < 12) ? (-1 * $acimut) : $acimut;
}	 

/* Funciones para calcular la distribucion horaria de la radiacion a partir de la radiacion diaria de media mensual */
/*Factor radiacion global horizontal hora/dia*/
function FRGHhd($l,$nda,$hd){
	$FRGHhd = (abs((M_PI / 24) * (a($l,$nda) + (b($l,$nda) * cos(w($hd) * M_PI / 180))) * ((cos(w($hd) * M_PI / 180) - cos(ws($l,$nda) * M_PI / 180))) / (sin(ws($l,$nda) * M_PI / 180) - ((M_PI / 180) * (ws($l,$nda)) * cos(ws($l,$nda) * M_PI / 180)))));
	return $FRGHhd;
}
/*Factor radiacion difusa horizontal hora/dia*/
function FRDfHhd($l,$nda,$hd){
	$FRDfHhd = ((M_PI / 24) * (cos(w($hd) * M_PI / 180) - cos(ws($l,$nda) * M_PI / 180)) / (sin(ws($l,$nda) * M_PI / 180) - (M_PI / 180 * ws($l,$nda) * cos(ws($l,$nda) * M_PI / 180))));
	return $FRDfHhd;
}
/*Radiacion global horaria horizontal*/
function RGHh($RGHd,$l,$nda,$hd){
	if(hs($l,$nda,$hd) > 0){ $RGHh = ($RGHd * FRGHhd($l,$nda,$hd)); }else{ $RGHh = 0;}
	return $RGHh;
}
/*Radiacion difusa horaria horizontal*/
function RDfHh($RGHd,$l,$nda,$cs,$hd){
	if(hs($l,$nda,$hd) > 0){ $RDfHh = (RDHd($RGHd,$l,$nda,$cs) * FRDfHhd($l,$nda,$hd)); }else{ $RDfHh = 0; }
        return $RDfHh;
}
/*Radiacion directa horaria horizontal*/
function RDrHh($RGHd,$l,$nda,$cs,$hd){
	$RDrHh = RGHh($RGHd,$l,$nda,$hd) - RDfHh($RGHd,$l,$nda,$cs,$hd);
	return $RDrHh;
}

/* Funciones para calcular la influencia de la orientacion, inclinacion y refleccion a partir de la radiacion horaria horizontal de media diaria*/
/*Difrencia del angulo con el azimut*/
function difAzimut($ori,$l,$nda,$hd){
	return ($ori - acimut($l,$nda,$hd));
}
/*Angulo horizontal*/
function anguloHorizontal($ori,$l,$nda,$hd){
	return ( ( -1 * $ori ) + acimut($l,$nda,$hd) );
}
/*Angulo vertical*/
function anguloVertical($ori,$RGHd,$l,$nda,$hd){
	$AH = anguloHorizontal($ori,$l,$nda,$hd);
	$HS = hs($l,$nda,$hd);
	if( abs($AH) > 90 ){ 
		$anguloVertical = 90; 
	}else{ 
		if( $HS > 0 ) {
			$anguloVertical = (180 / M_PI * atan(tan($HS * M_PI / 180 ) / cos($AH * M_PI / 180)));
		} else { 
			$anguloVertical = 90;
		}
	}
	return $anguloVertical;
}
/*Angulo de incidencia*/
function anguloIncidencia($incl,$ori,$RGHd,$l,$nda,$hd){
        $HS = hs($l,$nda,$hd);
	$DA = difAzimut($ori,$l,$nda,$hd);
	$anguloIncidencia = (180 / M_PI) * acos( cos($HS * M_PI / 180) * cos($DA * M_PI / 180) * sin($incl * M_PI / 180) + sin($HS * M_PI / 180) * cos($incl * M_PI / 180) );
	return $anguloIncidencia;
}
/*Radiacion directa horaria en la superficie orientada */
function RDrhS($incl,$ori,$RGHd,$l,$nda,$cs,$hd){
	$AI = anguloIncidencia($incl,$ori,$RGHd,$l,$nda,$hd);
	$RDrHh = RDrHh($RGHd,$l,$nda,$cs,$hd);
	$HS = hs($l,$nda,$hd);
	if($AI < 90){ $RDrhS = $RDrHh / sin($HS * M_PI / 180) * cos($AI * M_PI / 180); }else{ $RDrhS = 0;}
	return round($RDrhS);
}
/*Radiacion difuza horaria en la superficie orientada*/
function RDfhS($alb,$incl,$ori,$RGHd,$l,$nda,$cs,$hd){
	$RDfhS = ((1 + cos($incl * M_PI / 180)) / 2 * RDfHh($RGHd,$l,$nda,$cs,$hd)) + (($alb * (1 - cos($incl * M_PI / 180))) / 2 * RGHh($RGHd,$l,$nda,$hd));
	return round($RDfhS);
}
/*Radiacion global horaria den la superficie orientada*/
function RGhS($alb,$incl,$ori,$RGHd,$l,$nda,$cs,$hd){
	$RGhS = (RDrhS($incl,$ori,$RGHd,$l,$nda,$cs,$hd) + RDfhS($alb,$incl,$ori,$RGHd,$l,$nda,$cs,$hd));
	return round($RGhS);
}

/* Realizar los calculos y mostrar el resultado */
/*iterar para todos los meses del año*/
for($i = 0; $i < 12; $i++){
	echo $ma[$i]." - ".$nda[$i]." - ".$ndm[$i]."<br>";
	echo $l." - ".$RGHd." - ".$cs."<br>";
	//echo gamma($nda[$i])." - ".delta($nda[$i])." - ".ws($l,$nda[$i])."<br>";
	//echo rse($l,$nda[$i],$cs)." - ".kt($RGHd,$l,$nda[$i],$cs)." - ".hsd($l,$nda[$i])."<br>";
	echo RDHd($RGHd,$l,$nda[$i],$cs)."<br>";
	//echo a($l,$nda[$i])." - ".b($l,$nda[$i])."<br><br>";
	foreach($hd as $var) echo $var." | "; echo "<br><br>";
	//foreach($hd as $var) echo w($var)." | "; echo "<br>";
	//foreach($hd as $var) echo hs($l,$nda[$i],$var)." | "; echo "<br><br>";
	//foreach($hd as $var) echo acimut($l,$nda[$i],$var)." | "; echo "<br><br>";
	//foreach($hd as $var) echo FRGHhd($l,$nda[$i],$var)." | "; echo "<br>";
	//foreach($hd as $var) echo RGHh($RGHd,$l,$nda[$i],$var)." | "; echo "<br><br>";
	//foreach($hd as $var) echo FRDfHhd($l,$nda[$i],$var)." | "; echo "<br>";
	//foreach($hd as $var) echo RDfHh($RGHd,$l,$nda[$i],$cs,$var)." | "; echo "<br><br>";
	//foreach($hd as $var) echo RDrHh($RGHd,$l,$nda[$i],$cs,$var)." | "; echo "<br><br>";
	//foreach($hd as $var) echo difAzimut($ori,$l,$nda[$i],$var)." | "; echo "<br><br>";
	//foreach($hd as $var) echo anguloHorizontal($ori,$l,$nda[$i],$var)." | "; echo "<br><br>";
	//foreach($hd as $var) echo anguloVertical($ori,$RGHd,$l,$nda[$i],$var)." | "; echo "<br><br>";
	//foreach($hd as $var) echo anguloIncidencia($incl,$ori,$RGHd,$l,$nda[$i],$var)." | "; echo "<br><br>";
	foreach($hd as $var) echo RDrhS($incl,$ori,$RGHd,$l,$nda[$i],$cs,$var)." &#09; "; echo "<br><br>";
	foreach($hd as $var) echo RDfhS($alb,$incl,$ori,$RGHd,$l,$nda[$i],$cs,$var)." &#09; "; echo "<br><br>";
	foreach($hd as $var) echo RGhS($alb,$incl,$ori,$RGHd,$l,$nda[$i],$cs,$var)." &#09; "; echo "<br><br>";
	echo "<br> ---------------------------------------------------- <br>";
}
?>
