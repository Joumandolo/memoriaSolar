<?php 
/*
Template Name: Login Page
*/
?>
<?php 
	$et_ptemplate_settings = array();
	$et_ptemplate_settings = maybe_unserialize( get_post_meta($post->ID,'et_ptemplate_settings',true) );
	
	$fullwidth = isset( $et_ptemplate_settings['et_fullwidthpage'] ) ? (bool) $et_ptemplate_settings['et_fullwidthpage'] : false;
?>

<?php get_header(); ?>
	<?php if (get_option('thesource_integration_single_top') <> '' && get_option('thesource_integrate_singletop_enable') == 'on') echo(get_option('thesource_integration_single_top')); ?>	
	
	<div id="main-content-wrap">
		<div id="main-content" class="clearfix<?php if($fullwidth) echo(' fullwidth');?>">
			<?php get_template_part('includes/breadcrumb'); ?>
			<div id="top-shadow"<?php if(is_front_page()) echo(' class="nobg"'); ?>></div>
				
			<div id="recent-posts" class="clearfix">
			<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
				<div class="entry post clearfix">
					<h1 class="title"><?php the_title(); ?></h1>
					<div class="entry-content">				
						<?php $thumb = '';
							  $width = (int) get_option('thesource_thumbnail_width_pages');
							  $height = (int) get_option('thesource_thumbnail_height_pages');
							  $classtext = 'thumb alignleft';
							  $titletext = get_the_title();
							
							  $thumbnail = get_thumbnail($width,$height,$classtext,$titletext,$titletext);
							  $thumb = $thumbnail["thumb"]; ?>
							  
						<?php if($thumb <> '' && get_option('thesource_page_thumbnails') == 'on') { ?>						
							<?php print_thumbnail($thumb, $thumbnail["use_timthumb"], $titletext , $width, $height, $classtext); ?>
							<p class="date"><span><?php the_time(get_option('thesource_date_format')) ?></span></p>	
						<?php }; ?>

						<?php the_content(); ?>
						<?php wp_link_pages(array('before' => '<p><strong>'.esc_html__('Pages','TheSource').':</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
						
						<div id="et-login">
							<div class='et-protected'>
								<div class='et-protected-form'>
									<form action='<?php echo home_url(); ?>/wp-login.php' method='post'>
										<p><label><?php esc_html_e('Username','TheSource'); ?>: <input type='text' name='log' id='log' value='<?php echo esc_attr($user_login); ?>' size='20' /></label></p>
										<p><label><?php esc_html_e('Password','TheSource'); ?>: <input type='password' name='pwd' id='pwd' size='20' /></label></p>
										<input type='submit' name='submit' value='Login' class='etlogin-button' />
									</form> 
								</div> <!-- .et-protected-form -->
								<p class='et-registration'><?php esc_html_e('Not a member?','TheSource'); ?> <a href='<?php echo site_url('wp-login.php?action=register', 'login_post'); ?>'><?php esc_html_e('Register today!','TheSource'); ?></a></p>
							</div> <!-- .et-protected -->
						</div> <!-- end #et-login -->
						
						<div class="clear"></div>
						
						<?php edit_post_link(esc_html__('Edit this page','TheSource')); ?>							
					</div> <!-- end .entry-content -->		
				</div> <!-- end .entry -->
			<?php endwhile; endif; ?>
			</div> <!-- end #recent-posts -->
			
	<?php if (!$fullwidth) get_sidebar(); ?>
<?php get_footer(); ?>