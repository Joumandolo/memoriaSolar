<?php get_header(); ?>
	<?php if (get_option('thesource_integration_single_top') <> '' && get_option('thesource_integrate_singletop_enable') == 'on') echo(get_option('thesource_integration_single_top')); ?>	
	
	<div id="main-content-wrap">
		<div id="main-content" class="clearfix">
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
						<?php edit_post_link(esc_html__('Edit this page','TheSource')); ?>							
					</div> <!-- end .entry-content -->		
				</div> <!-- end .entry -->
				
				<?php if (get_option('thesource_show_pagescomments') == 'on') comments_template('', true); ?>
			<?php endwhile; endif; ?>
			</div> <!-- end #recent-posts -->
			
	<?php get_sidebar(); ?>
<?php get_footer(); ?>