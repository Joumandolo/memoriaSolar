<?php
/*
Plugin Name: Estacion FCh
Plugin URI: http://www.solaratacama.cl
Description: Muestra informacion capturada mediante estaciones de medicion solar de la redSolLac; Compatible con Chrome 19, IE 9, Firefox 12.
Version: 1.2.2
Author: Manuel Arredondo
Author URI: http://wp.mnj.cl/
License: GPL v3.0
*/

/* Agregar opcion al inicializar el plugin */
//add_action( "widgets_init", array( "solarFch", "iniciar" ) );
add_action( "init", array( "solarFch", "iniciar") );
register_activation_hook(__FILE__, array('solarFch','crearPagina') ); 
register_deactivation_hook(__FILE__, array('solarFch', 'eliminarPagina') );

// Clase Widget_ultimosPostPorAutor
class solarFch {

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
		self::setPaginaTitulo("Estación FCh");
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
		self::setPaginaTitulo("Estación FCh");
		$pagina = get_page_by_title(self::getPaginaTitulo());
		self::setPaginaId($pagina->ID);
		if( self::getPaginaId() ) { wp_delete_post( self::getPaginaId() ); }
	}

	/* Contenido de la pagina */
	private static function contenido(){
		/* añadimos contenedores */
		$c = '
			<div id="estacionfch" style="width:840px;height:370px"></div>
			<script type="text/javascript">
				jQuery.ajax({
        				url: "wp-content/plugins/solarFch/graficoWp.php",
        				type: "POST",
					data: {"siteUrl":"'.site_url().'"},
        				dataType: "html",
        				async: false,
        				success: function(data, textStatus, jqXHR){
        					jQuery("#estacionfch").append(data);
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
			'post_excerpt' => "Nueva herramienta para visualizar los datos obtenidos mediante las estaciones meteorologicas solares.",
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

	/* Panel de control ghi */
	public function ghiControl() {
	        echo "No hay opciones disponibles.";
	        }
	/* Visualizar Widget ghi */
	public function ghi($args) {
	        self::setPaginaTitulo("Estación FCh");
		$pagina = get_page_by_title(self::getPaginaTitulo());
		self::setPaginaId($pagina->ID);
		self::setPaginaUrl(self::getPaginaId());
		echo'
	                <div id="ghi" style="position:relative;width:200px;height:100px">
				<a href="'.self::getPaginaUrl().'" style="z-index:1000;position:absolute;top:0px;left:0px;display:block;height:100%;width:100%;cursor:pointer;"></a>
			</div>
	                <script type="text/javascript">
	                        jQuery.ajax({
	                                url: "wp-content/plugins/solarFch/ghi.php",
	                                type: "POST",
					data: {"siteUrl":"'.site_url().'"},
	                                dataType: "html",
	                                async: false,
	                                success: function(data, textStatus, jqXHR){
	                                        jQuery("#ghi").append(data);
	                                        },
	                                });
	                </script>';
		}

	/* Panel de control hr */
        public function hrControl() {
                echo "No hay opciones disponibles.";
                }
        /* Visualizar Widget hr */
        public function hr($args) {
		self::setPaginaTitulo("Estación FCh");
		$pagina = get_page_by_title(self::getPaginaTitulo());
		self::setPaginaId($pagina->ID);
		self::setPaginaUrl(self::getPaginaId());
                echo'
                        <div id="hr" style="position:relative;width:228px;height:49px;">
				<a href="'.self::getPaginaUrl().'" style="z-index:1000;position:absolute;top:0px;left:0px;display:block;height:100%;width:100%;cursor:pointer;"></a>
			</div>
                        <script type="text/javascript">
                                jQuery.ajax({
                                        url: "wp-content/plugins/solarFch/hr.php",
                                        type: "POST",
					data: {"siteUrl":"'.site_url().'"},
                                        dataType: "html",
                                        async: false,
                                        success: function(data, textStatus, jqXHR){
                                                jQuery("#hr").append(data);
                                                },
                                        });
                        </script>';
                }
	
	/* Panel de control ta */
        public function taControl() {
                echo "No hay opciones disponibles.";
                }
        /* Visualizar Widget ta */
        public function ta($args) {
                self::setPaginaTitulo("Estación FCh");
		$pagina = get_page_by_title(self::getPaginaTitulo());
		self::setPaginaId($pagina->ID);
		self::setPaginaUrl(self::getPaginaId());
                echo'
                        <div id="ta" style="position:relative;width:200px;height:100px">
				<a href="'.self::getPaginaUrl().'" style="z-index:1000;position:absolute;top:0px;left:0px;display:block;height:100%;width:100%;cursor:pointer;"></a>
			</div>
                        <script type="text/javascript">
                                jQuery.ajax({
                                        url: "wp-content/plugins/solarFch/ta.php",
                                        type: "POST",
					data: {"siteUrl":"'.site_url().'"},
                                        dataType: "html",
                                        async: false,
                                        success: function(data, textStatus, jqXHR){
                                                jQuery("#ta").append(data);
                                                },
                                        });
                        </script>';
                }
	/* Meotodo que se llamara cuando se inicialice el Widget */
	public function iniciar() {
		/* Iniciar Pagina */
		//register_activation_hook(__FILE__, 'crearPagina' ); 
		//register_deactivation_hook(__FILE__, 'eliminarPagina' );
	        /* Incluimos el widget en el panel control de Widgets */
	        register_sidebar_widget( "Estación GHI", array( "solarFch", "ghi" ) );
	        register_sidebar_widget( "Estación Solar HR", array( "solarFch", "hr" ) );
	        register_sidebar_widget( "Estación Solar TA", array( "solarFch", "ta" ) );
	        /* Formulario para editar las propiedades de nuestro Widget */
	        register_widget_control( "Estación GHI", array( "solarFch", "ghiControl" ) );
	        register_widget_control( "Estación HR", array( "solarFch", "hrControl" ) );
	        register_widget_control( "Estación TA", array( "solarFch", "taControl" ) );
	        }
	}
?>
