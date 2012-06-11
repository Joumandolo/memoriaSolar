<?php 
/*
Template Name: Sitemap Page
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
						
						<div id="sitemap">
							<div class="sitemap-col">
								<h2><?php esc_html_e('Pages','TheSource'); ?></h2>
								<ul id="sitemap-pages"><?php wp_list_pages('title_li='); ?></ul>
							</div> <!-- end .sitemap-col -->
							
							<div class="sitemap-col">
								<h2><?php esc_html_e('Categories','TheSource'); ?></h2>
								<ul id="sitemap-categories"><?php wp_list_categories('title_li='); ?></ul>
							</div> <!-- end .sitemap-col -->
							
							<div class="sitemap-col">
								<h2><?php esc_html_e('Tags','TheSource'); ?></h2>
								<ul id="sitemap-tags">
									<?php $tags = get_tags();
									if ($tags) {
										foreach ($tags as $tag) {
											echo '<li><a href="' . esc_url( get_tag_link( $tag->term_id ) ) . '">' . esc_html( $tag->name ) . '</a></li> ';
										}
									} ?>
								</ul>
							</div> <!-- end .sitemap-col -->
														
							<div class="sitemap-col<?php echo ' last'; ?>">
								<h2><?php esc_html_e('Authors','TheSource'); ?></h2>
								<ul id="sitemap-authors" ><?php wp_list_authors('show_fullname=1&optioncount=1&exclude_admin=0'); ?></ul>
							</div> <!-- end .sitemap-col -->
						</div> <!-- end #sitemap -->
						
						<div class="clear"></div>
						
						<?php edit_post_link(esc_html__('Edit this page','TheSource')); ?>							
					</div> <!-- end .entry-content -->		
				</div> <!-- end .entry -->
			<?php endwhile; endif; ?>
			</div> <!-- end #recent-posts -->
			
	<?php if (!$fullwidth) get_sidebar(); ?>
<?php get_footer(); ?>