<?php get_header(); ?>
	<?php if (get_option('thesource_integration_single_top') <> '' && get_option('thesource_integrate_singletop_enable') == 'on') echo(get_option('thesource_integration_single_top')); ?>	
	
	<div id="main-content-wrap">
		<div id="main-content" class="clearfix">
			<?php get_template_part('includes/breadcrumb'); ?>
			<div id="top-shadow"></div>
				
			<div id="recent-posts" class="clearfix">
			<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
				<div class="entry post clearfix">
					<h1 class="title"><?php the_title(); ?></h1>
					
					<?php get_template_part('includes/postinfo'); ?>
					
					<div class="entry-content">
						<?php if (get_option('thesource_thumbnails') == 'on') { ?>
						
							<?php $width = get_option('thesource_thumbnail_width_posts');
								  $height = get_option('thesource_thumbnail_height_posts');
								  $classtext = 'thumb alignleft';
								  $titletext = get_the_title();
							
								  $thumbnail = get_thumbnail($width,$height,$classtext,$titletext,$titletext);
								  $thumb = $thumbnail["thumb"]; ?>
							
							<?php if($thumb <> '') { ?>
								<?php print_thumbnail($thumb, $thumbnail["use_timthumb"], $titletext , $width, $height, $classtext); ?>
								<?php if (get_option('thesource_postinfo2') <> '') { ?>
									<?php if (in_array('date', get_option('thesource_postinfo2'))) { ?>
										<p class="date"><span><?php the_time(get_option('thesource_date_format')) ?></span></p>	
									<?php }; ?>
								<?php }; ?>
							<?php }; ?>
								
						<?php }; ?>
				
						<?php the_content(); ?>
						<?php wp_link_pages(array('before' => '<p><strong>'.esc_html__('Pages','TheSource').':</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
						<?php edit_post_link(esc_html__('Edit this page','TheSource')); ?>
                        <?php if (get_option('thesource_integration_single_bottom') <> '' && get_option('thesource_integrate_singlebottom_enable') == 'on') echo(get_option('thesource_integration_single_bottom')); ?>
					</div> <!-- end .entry-content -->
					
				</div> <!-- end .entry -->
				
				<?php if (get_option('thesource_468_enable') == 'on') { ?>
					<?php if(get_option('thesource_468_adsense') <> '') echo(get_option('thesource_468_adsense'));
					else { ?>
						<a href="<?php echo esc_url(get_option('thesource_468_url')); ?>"><img src="<?php echo esc_url(get_option('thesource_468_image')); ?>" alt="468 ad" class="foursixeight" /></a>
					<?php } ?>	
				<?php } ?>
				
				<?php if (get_option('thesource_show_postcomments') == 'on') comments_template('', true); ?>
			<?php endwhile; endif; ?>
			</div> <!-- end #recent-posts -->

		<?php get_sidebar(); ?>
		
<?php get_footer(); ?>
