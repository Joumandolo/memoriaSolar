<?php
/*
Plugin Name: Widget solar meteorologico
Plugin URI: http://www.solaratacama.cl
Description: muestra datos meteorologicos incluyendo radiacion solar en tiempo real
Version: 0.5
Author: Manuel Arredondo
Author URI:
*/

// Cuando se inicializa el widget llamaremos al metodo register de la clase Widget_ultimosPostPorAutor
add_action( "widgets_init", array( "Widget_solarMeteo", "register" ) );

// Conectar con base de datos de RedSolLac
global $conexion;
$conexion = mysql_connect("solar.db.7367634.hostedresource.com", "widgetSolar", "corbet*Mount54") or die("Error al conectarse al servidor...");
mysql_select_db("solar", $conexion) or die("Error al seleccionar la base de datos...");

// Clase Widget_ultimosPostPorAutor
class Widget_solarMeteo {

// Panel de control que se mostrara abajo de nuestro Widget en el panel de configuración de Widgets
function control() {
	echo "Hola, soy el panel de control.";
	}

// Metodo que se llamara cuando se visualize el Widget en pantalla
function widget($args) {
	echo $args["before_widget"];
	//echo $args["before_title"] . "Editores de " . get_option( "blogname" ) . $args["after_title"];
	
	echo'
		<div id="ghi" style="width:600px;height:400px"></div>
		<script type="text/javascript">
			jQuery.ajax({
				url: "wp-content/plugins/solarMeteo/ghi.php",
				type: "POST",
				dataType: "html",
				async: false,
				success: function(data, textStatus, jqXHR){
					jQuery("#ghi").append(data);
					},
				});
		</script>';
	
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
