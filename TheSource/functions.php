<?php 
add_action( 'after_setup_theme', 'et_setup_theme' );
if ( ! function_exists( 'et_setup_theme' ) ){
	function et_setup_theme(){
		global $themename, $shortname;
		$themename = "TheSource";
		$shortname = "thesource";
	
		require_once(TEMPLATEPATH . '/epanel/custom_functions.php'); 

		require_once(TEMPLATEPATH . '/includes/functions/comments.php'); 

		require_once(TEMPLATEPATH . '/includes/functions/sidebars.php'); 

		load_theme_textdomain('TheSource',get_template_directory().'/lang');

		require_once(TEMPLATEPATH . '/epanel/options_thesource.php');

		require_once(TEMPLATEPATH . '/epanel/core_functions.php'); 

		require_once(TEMPLATEPATH . '/epanel/post_thumbnails_thesource.php');

		include(TEMPLATEPATH . '/includes/widgets.php');
	}
}

add_action('wp_head','et_portfoliopt_additional_styles',100);
function et_portfoliopt_additional_styles(){ ?>
	<style type="text/css">
		#et_pt_portfolio_gallery { margin-left: 2px; }
		.et_pt_portfolio_item { margin-left: 11px; }
		.et_portfolio_small { margin-left: -14px !important; }
		.et_portfolio_small .et_pt_portfolio_item { margin-left: 22px !important; }
		.et_portfolio_large { margin-left: -12px !important; }
		.et_portfolio_large .et_pt_portfolio_item { margin-left: 13px !important; }
	</style>
<?php }

function register_main_menus() {
   register_nav_menus(
      array(
         'primary-menu' => __( 'Primary Menu' ),
         'secondary-menu' => __( 'Secondary Menu' ),
		 'footer-menu' => __( 'Footer Menu' ),
      )
   );
};
if (function_exists('register_nav_menus')) add_action( 'init', 'register_main_menus' );

if ( ! function_exists( 'et_list_pings' ) ){
	function et_list_pings($comment, $args, $depth) {
		$GLOBALS['comment'] = $comment; ?>
		<li id="comment-<?php comment_ID(); ?>"><?php comment_author_link(); ?> - <?php comment_excerpt(); ?>
	<?php }
} ?>
<?php
if ( function_exists('register_sidebar') )
register_sidebars(1,array(
	'name' => 'Head Widget',
	'id' => 'head-widget',
	'description' => __('Lugar para poner widgets en el head del template'),
	'before_widget' => '',
	'after_widget' => '',
	'before_title' => '',
	'after_title' => '',
	));
?>