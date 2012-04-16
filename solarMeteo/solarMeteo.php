<?php
/*
Plugin Name: Widget solar meteorologico
Plugin URI: http://www.solaratacama.cl
Description: muestra datos meteorologicos incluyendo radiacion solar en tiempo real
Version: 0.1
Author: Manuel Arredondo
Author URI:
*/

// Cuando se inicializa el widget llamaremos al metodo register de la clase Widget_ultimosPostPorAutor
add_action( "widgets_init", array( "Widget_solarMeteo", "register" ) );

// Clase Widget_ultimosPostPorAutor
class Widget_solarMeteo {
// Panel de control que se mostrara abajo de nuestro Widget en el panel de configuración de Widgets
function control() {
	echo "Hola, soy el panel de control.";
	}

// Metodo que se llamara cuando se visualize el Widget en pantalla
function widget($args) {
	echo $args["before_widget"];
	echo $args["before_title"] . "Editores de " . get_option( "blogname" ) . $args["after_title"];
	
	// Variables
	$radiacion = null;
	$unidades = null;
	global $wpdb;

	/* Consultamos la base de datos  */
	$radiacion = $wpdb->get_results( "SELECT Rad_W, timestamp FROM solarDatos ORDER BY timestamp DESC LIMIT 1", "ARRAY_A" );
	$unidades = $wpdb->get_results( "SELECT * FROM solarUnidades", "ARRAY_A" );
	
	//var_dump($unidades);
	echo "<div>
		<table style='border:2px solid black;'>
			<tr>
				<td colspan='2'>".$radiacion[0]['timestamp']."</td>
			</tr>
			<tr>
				<td><img src='wp-content/plugins/solarMeteo/images/radiacionIcon.jpg'/></td>
				<td style='text-align:center;vertical-align:middle'>".$radiacion[0]['Rad_W']." ".$unidades[4]["etiqueta"]." ".$unidades[4]['unidad']."</td>
			</tr>
		<table>
		";

	echo $args["after_widget"];
	}

// Meotodo que se llamara cuando se inicialice el Widget
function register() {
	// Incluimos el widget en el panel control de Widgets
	register_sidebar_widget( "Radiacion Solar", array( "Widget_solarMeteo", "widget" ) );
	// Formulario para editar las propiedades de nuestro Widget
	register_widget_control( "Raciacion Solar", array( "Widget_solarMeteo", "control" ) );
	}	
}
?>
