<?php
/*
Plugin Name: Calculadora Solar 
Plugin URI: http://www.solaratacama.cl
Description: Herramienta de calculo y dimencionamiento de plantas solares para redSollac; Compatible con Chrome 19, IE 9, Firefox 12.
Version: 1.2.
Author: Manuel Arredondo
Author URI: http://wp.mnj.cl/
License: GPL v3.0
*/

/* Agregar opcion al inicializar el plugin */
//add_action( "init", array( "solarCalc", "iniciar") );
register_activation_hook(__FILE__, array('solarCalc','crearPagina') ); 
register_deactivation_hook(__FILE__, array('solarCalc', 'eliminarPagina') );

// Clase Widget_ultimosPostPorAutor
class solarCalc {

	private static $paginaId = 0;
	private static $paginaUrl = "http://";
	private static $paginaTitulo = "Pagina Nueva";

	private static function setPaginaId($id){self::$paginaId = $id;}
	private static function getPaginaId(){return self::$paginaId;}

	private static function setPaginaUrl($url){self::$paginaUrl = site_url()."/?page_id=".$url;}
	private static function getPaginaUrl(){return self::$paginaUrl;}

	private static function setPaginaTitulo($titulo){self::$paginaTitulo = $titulo;}
	private static function getPaginaTitulo(){return self::$paginaTitulo;}
	
	/* Crear pagina  */
	public function crearPagina(){
		self::setPaginaTitulo("Calculadora Solar");
		$pagina = get_page_by_title(self::getPaginaTitulo());
		self::setPaginaId($pagina->ID);
		if(!self::getPaginaId()){
			/* si no existe crear pagina nueva */
			self::setPaginaId(wp_insert_post( self::post( self::getPaginaTitulo() ), $wp_error ));
			update_post_meta( self::getPaginaId(), "_wp_page_template", "page-full.php" );
			self::setPaginaUrl(self::getPaginaId());
		}else{
			wp_delete_post( self::getPaginaId() );
			self::setPaginaId(wp_insert_post( self::post( self::getPaginaTitulo() ), $wp_error ));
			update_post_meta( self::getPaginaId(), "_wp_page_template", "page-full.php" );
			self::setPaginaUrl(self::getPaginaId());
		}
	}

	public function eliminarPagina(){
		self::setPaginaTitulo("Calculadora Solar");
		$pagina = get_page_by_title(self::getPaginaTitulo());
		self::setPaginaId($pagina->ID);
		if( self::getPaginaId() ) { wp_delete_post( self::getPaginaId() ); }
	}

	/* Contenido de la pagina */
	private static function contenido(){
		/* añadimos contenedores */
		$c = '
			<div id="calculadoraSolar" style="width:900px;height:870px"></div>
			<script type="text/javascript">
				jQuery.ajax({
        				url: "wp-content/plugins/solarCalc/calculadora.php",
        				type: "POST",
					data: {"siteUrl":"'.site_url().'"},
        				dataType: "html",
        				async: false,
        				success: function(data, textStatus, jqXHR){
        					jQuery("#calculadoraSolar").append(data);
					},
				});
			</script>';
		return $c;
	}

	/* Estructura de la pagina */
	private static function post($p){
		$post = array(
			'menu_order' => 1,
			'post_author' => 11,
			'post_content' => self::contenido(),
			'post_excerpt' => "Nueva herramienta para calcular sistemas fotovoltaicos.",
			//'post_date' => date("Y-m-d H:i:s"),
			'post_status' => 'publish', 
			'post_title' => $p,
			'post_type' => 'page',
			'post_parent' => 3,
			'comment_status' => 'closed',
			'post_category' => ""
			);  
		return $post;
	}
}
?>
