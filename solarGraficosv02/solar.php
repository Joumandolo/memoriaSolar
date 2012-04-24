<?php
/*
Plugin Name: Solar Graficos
Plugin URI: http://www.solaratacama.cl
Description: Grafica variables a paratir de datos obtenidos de las estaciones de medicion solar de la redSolLac
Version: 0.1
Author: Manuel Arredondo
Author URI:
*/

/* Variables */
$page_title = "Graficos solares";


/* Inicializar clases y fucniones*/
register_activation_hook(__FILE__,'activarPlugin'); 
register_deactivation_hook( __FILE__, 'desactivarPlugin' );

function activarPlugin(){
	/* verificar si la pagina existe */
	$pagina = get_page_by_title( $page_title );
	if( !$pagina->ID ){
		/* Instanciamos la clase solar */
		$solar = new solarGraf; 

		/* si no existe crear pagina nueva */
		wp_insert_post( $solar->post(), $wp_error );

 		/* agregar contenido al header */
		add_action('wp_head', $solar->estilos());
		add_action('wp_head', $solar->librerias());
		}
	}

function desactivarPlugin(){
	/* si la pagina existe eliminar */
	$page = get_page_by_title( $page_title );
	if( $page->ID ) { wp_delete_post( $page->ID ); }
	}

/* Clase solarGraf */
class solarGraf {

/* estilos css */
function estilos(){
	echo '<link href="wp-content/plugins/solarGraficos/flot/examples/layout.css" rel="stylesheet" type="text/css">';
	echo '<style type="text/css">';
	echo '#grafico.button{position:absolute;cursor:pointer;}';
	echo '#grafico div.button{font-size:smaller;color:#999;background-color:#eee;padding: 2px;}';
	echo '.message{padding-left:50px;font-size:smaller;}';
	echo '</style>';
}

/* cargar librerias */
function librerias(){
	echo '<script language="javascript" type="text/javascript" src="wp-content/plugins/solarGraficos/flot/jquery.flot.js"></script>';
	echo '<script language="javascript" type="text/javascript" src="wp-content/plugins/solarGraficos/flot/jquery.flot.navigate.js"></script>';
	echo '<script language="javascript" type="text/javascript" src="wp-content/plugins/solarGraficos/dateFormat.js"></script>';
}

/* rederizar graficas */
function contenido(){
/* añadimos contenedores */
echo '<div id="grafico" style="width:500px;height:300px"></div>';
echo '<div id="legenda" style="width:500px;height:10px;font-size:smaller"></div>';
echo '<label id="graflabel">Mostrar en grafica:</label><br>';
echo '<div id="choices"></div>';
echo '<div id="fechas"></div>';

/* obtener datos y renderizar -> agregar el js*/
echo '<script language="javascript" type="text/javascript" src="wp-content/plugins/solarGraficos/solar.js"></script>';

/* administracion y configuracion */
}

function post(){
	$post = array(
		'menu_order' => 5,
		'post_content' => $this->content(), 
		'post_date' => date("Y-m-d H:i:s"),
		'post_status' => 'publish', 
		'post_title' => $page_title,
		'post_type' => 'page',
		'comment_status' => 'closed',
		'post_category' => array(1)
		);  
	return $post;
	}
}
?>
