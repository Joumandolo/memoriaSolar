<!-- Start Featured -->	
<div id="featured">

	<?php global $ids;
	$ids = array();
	$arr = array();
	$i=1;
			
	$featured_cat = get_option('thesource_feat_cat');
	$featured_num = get_option('thesource_featured_num');
	
	if (get_option('thesource_use_pages') == 'false') query_posts("showposts=$featured_num&cat=".get_catId($featured_cat));
	else { 
		global $pages_number;
		
		if (get_option('thesource_feat_pages') <> '') $featured_num = count(get_option('thesource_feat_pages'));
		else $featured_num = $pages_number;
		
		query_posts(array
						('post_type' => 'page',
						'orderby' => 'menu_order',
						'order' => 'ASC',
						'post__in' => (array) get_option('thesource_feat_pages'),
						'showposts' => (int) $featured_num
					));
	};
	
	while (have_posts()) : the_post(); ?>
		<?php $bgColor = get_post_meta($post->ID, 'Color',true);
		if ($bgColor == '') $bgColor = 'ffffff'; ?>

		<?php $width = 1400;
			  $height = 300;
			  $classtext = '';
			  $titletext = get_the_title();
				
			  $thumbnail = get_thumbnail($width,$height,$classtext,$titletext,$titletext,true);
			  $thumb = $thumbnail["thumb"]; ?>
		
		<div class="slide" style="background: #<?php echo($bgColor); ?> url('<?php print_thumbnail($thumb, $thumbnail["use_timthumb"], $titletext, $width, $height, '', true, true); ?>') top center no-repeat;">
			<div class="container clearfix">
				<div class="description">
					<h2 class="title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
					<p class="tagline"><?php esc_html_e('Posted by','TheSource') ?> <?php the_author_posts_link(); ?> <?php esc_html_e('on','TheSource') ?> <?php the_time(get_option('thesource_date_format')) ?></p>
					<p><?php truncate_post(290); ?></p>
					<a href="<?php the_permalink(); ?>" class="readmore"><span><?php esc_html_e('Read More','TheSource'); ?></span></a>
				</div> <!-- end .description -->			
			</div> 	<!-- end .container -->	
			
			<div class="top-overlay"></div>
			<div class="overlay"></div>
			
		</div> <!-- end .slide -->
		
	<?php $i++;
	$ids[]= $post->ID;
	endwhile; wp_reset_query();	?>
		
</div> <!-- end #featured -->
<!-- End Featured -->


<div id="featured-control">
	<div class="container">
		<a id="prevlink" href="#"><?php esc_html_e('Previous','TheSource'); ?></a>
		<a id="nextlink" href="#"><?php esc_html_e('Next','TheSource'); ?></a>
	</div>
</div> <!-- end #featured-control -->