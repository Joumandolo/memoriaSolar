<?php 
	if ( is_home() ){
		global $ids;
		if (get_option('thesource_duplicate') == 'false') {
			$args=array(
				   'showposts'=> (int) get_option('thesource_homepage_posts'),
				   'post__not_in' => $ids,
				   'paged'=>$paged,
				   'category__not_in' => (array) get_option('thesource_exlcats_recent'),
			);
		} else {
			$args=array(
			   'showposts'=> (int) get_option('thesource_homepage_posts'),
			   'paged'=>$paged,
			   'category__not_in' => (array) get_option('thesource_exlcats_recent'),
			);
		};
		query_posts($args); 
	} ?>
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	<?php $thumb = '';
		$width = (int) get_option('thesource_thumbnail_width_usual');
		$height = (int) get_option('thesource_thumbnail_height_usual');
		$classtext = 'thumb alignleft';
		$titletext = get_the_title();
		
		$thumbnail = get_thumbnail($width,$height,$classtext,$titletext,$titletext);
		$thumb = $thumbnail["thumb"]; ?>

	<div class="entry clearfix" >
		<h2 class="title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
		
		<div class="entry-content clearfix">
			<?php if ($thumb <> '' && get_option('thesource_thumbnails_index') == 'on') { ?>
				<a href="<?php the_permalink(); ?>">
					<?php print_thumbnail($thumb, $thumbnail["use_timthumb"], $titletext , $width, $height, $classtext); ?>
				</a>
				
				<?php if (get_option('thesource_postinfo1') <> '') { ?>
					<?php if (in_array('date', get_option('thesource_postinfo1'))) { ?>
						<p class="date"><span><?php the_time(get_option('thesource_date_format')) ?></span></p>
					<?php }; ?>
				<?php }; ?>
			<?php }; ?>
			
			<?php if (get_option('thesource_blog_style') == 'on') the_content(""); else { ?>
				<p><?php truncate_post(365); ?></p>
			<?php }; ?>
			
		</div> <!-- end .entry-content -->
		
		<div class="post-meta clearfix">
			<?php get_template_part('includes/postinfo'); ?>
			
			<a href="<?php the_permalink(); ?>" class="readmore"><span><?php esc_html_e('Read More','TheSource'); ?></span></a>
		</div>
	</div> <!-- end .entry -->
<?php endwhile; ?>
	<?php if(function_exists('wp_pagenavi')) { wp_pagenavi(); }
	else { ?>
		 <?php get_template_part('includes/navigation'); ?>
	<?php } ?>
<?php else : ?>
	<?php get_template_part('includes/no-results'); ?>
<?php endif; if ( is_home() ) wp_reset_query(); ?>