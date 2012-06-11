<?php get_header(); ?>

<div id="main-content-wrap">
	<div id="main-content" class="clearfix">
		<?php get_template_part('includes/breadcrumb'); ?>
		<div id="top-shadow"></div>
			
		<div id="recent-posts" class="clearfix">	
			<?php get_template_part('includes/entry'); ?>
		</div> <!-- end #recent-posts -->

		<?php get_sidebar(); ?>
		
<?php get_footer(); ?>