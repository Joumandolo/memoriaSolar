<?php 
/*
Template Name: Gallery Page
*/
?>
<?php 
$et_ptemplate_settings = array();
$et_ptemplate_settings = maybe_unserialize( get_post_meta($post->ID,'et_ptemplate_settings',true) );

$fullwidth = isset( $et_ptemplate_settings['et_fullwidthpage'] ) ? (bool) $et_ptemplate_settings['et_fullwidthpage'] : (bool) $et_ptemplate_settings['et_fullwidthpage'];

$gallery_cats = isset( $et_ptemplate_settings['et_ptemplate_gallerycats'] ) ? $et_ptemplate_settings['et_ptemplate_gallerycats'] : array();
$et_ptemplate_gallery_perpage = isset( $et_ptemplate_settings['et_ptemplate_gallery_perpage'] ) ? (int) $et_ptemplate_settings['et_ptemplate_gallery_perpage'] : 12;
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
						
						<div id="et_pt_gallery" class="clearfix">
							<?php $gallery_query = ''; 
							if ( !empty($gallery_cats) ) $gallery_query = '&cat=' . implode(",", $gallery_cats);
							else echo '<!-- gallery category is not selected -->'; ?>
							<?php 
								$et_paged = is_front_page() ? get_query_var( 'page' ) : get_query_var( 'paged' );
							?>
							<?php query_posts("showposts=$et_ptemplate_gallery_perpage&paged=" . $et_paged . $gallery_query); ?>
							<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
								
								<?php $width = 207;
								$height = 136;
								$titletext = get_the_title();

								$thumbnail = get_thumbnail($width,$height,'portfolio',$titletext,$titletext,true,'Portfolio');
								$thumb = $thumbnail["thumb"]; ?>
								
								<div class="et_pt_gallery_entry">
									<div class="et_pt_item_image">
										<?php print_thumbnail($thumb, $thumbnail["use_timthumb"], $titletext, $width, $height, 'portfolio'); ?>
										<span class="overlay"></span>
										
										<a class="zoom-icon fancybox" title="<?php the_title(); ?>" rel="gallery" href="<?php echo($thumbnail['fullpath']); ?>"><?php esc_html_e('Zoom in','TheSource'); ?></a>
										<a class="more-icon" href="<?php the_permalink(); ?>"><?php esc_html_e('Read more','TheSource'); ?></a>
									</div> <!-- end .et_pt_item_image -->
								</div> <!-- end .et_pt_gallery_entry -->
								
							<?php endwhile; ?>
								<div class="page-nav clearfix">
									<?php if(function_exists('wp_pagenavi')) { wp_pagenavi(); }
									else { ?>
										 <?php get_template_part('includes/navigation'); ?>
									<?php } ?>
								</div> <!-- end .entry -->
							<?php else : ?>
								<?php get_template_part('includes/no-results'); ?>
							<?php endif; wp_reset_query(); ?>
						
						</div> <!-- end #et_pt_gallery -->
						
						<?php edit_post_link(esc_html__('Edit this page','TheSource')); ?>							
					</div> <!-- end .entry-content -->		
				</div> <!-- end .entry -->
			<?php endwhile; endif; ?>
			</div> <!-- end #recent-posts -->
			
	<?php if (!$fullwidth) get_sidebar(); ?>
<?php get_footer(); ?>