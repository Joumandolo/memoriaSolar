<?php
/*
Plugin Name: Solar Graficos
Plugin URI: http://www.solaratacama.cl
Description: Grafica variables a paratir de datos obtenidos de las estaciones de medicion solar de la redSolLac
Version: 0.7
Author: Manuel Arredondo
Author URI:
*/

/* Inicializar clases y fucniones*/
register_activation_hook(__FILE__,'activarPlugin'); 
register_deactivation_hook( __FILE__, 'desactivarPlugin' );

function activarPlugin(){
	$page_title = "Graficos solares";

	/* verificar si la pagina existe */
	$pagina = get_page_by_title( $page_title );
	if( !$pagina->ID ){
		/* si no existe crear pagina nueva */
		wp_insert_post( post( $page_title ), $wp_error );

 		/* agregar contenido al header */
		//add_action('wp_head', estilos());
		//add_action('wp_head', 'librerias');
		}
	}

function desactivarPlugin(){
	$page_title = "Graficos solares";

	/* si la pagina existe eliminar */
	$page = get_page_by_title( $page_title );
	if( $page->ID ) { wp_delete_post( $page->ID ); }
	}

/* estilos css */
function estilos(){
	$e = '<link href="wp-content/plugins/solarGraficos/flot/examples/layout.css" rel="stylesheet" type="text/css">'
		.'<style type="text/css">'
		.'#grafico.button{position:absolute;cursor:pointer;}'
		.'#grafico div.button{font-size:smaller;color:#999;background-color:#eee;padding: 2px;}'
		.'.message{padding-left:50px;font-size:smaller;}'
		.'</style>';
	echo $e;
	}

/* cargar librerias */
function librerias(){
	$l = '<script language="javascript" type="text/javascript" src="wp-content/plugins/solarGraficos/flot/jquery.flot.js"></script>'
	.'<script language="javascript" type="text/javascript" src="wp-content/plugins/solarGraficos/flot/jquery.flot.navigate.js"></script>'
	.'<script language="javascript" type="text/javascript" src="wp-content/plugins/solarGraficos/dateFormat.js"></script>';
	echo $l;
}

/* rederizar graficas */
function contenido(){
	/* añadimos contenedores */
	$c = '
		<div id="solar" style="width:600px;height:400px"></div>
		<script type="text/javascript">
			jQuery.ajax({
        			url: "wp-content/plugins/solarGraficos/grafico.php",
        			type: "POST",
        			dataType: "html",
        			async: false,
        			success: function(data, textStatus, jqXHR){
        				jQuery("#solar").append(data);
				},
			});
		</script>';
	return $c;
}

function post($p){
	$post = array(
		'menu_order' => 3,
		'post_content' => contenido(), 
		'post_date' => date("Y-m-d H:i:s"),
		'post_status' => 'publish', 
		'post_title' => $p,
		'post_type' => 'page',
		'comment_status' => 'closed',
		'post_category' => array(1)
		);  
	return $post;
	}
?>
