<?php class PopularWidget extends WP_Widget
{
    function PopularWidget(){
		$widget_ops = array('description' => 'Displays Popular Posts');
		$control_ops = array('width' => 400, 'height' => 300);
		parent::WP_Widget(false,$name='ET Popular Widget',$widget_ops,$control_ops);
    }

  /* Displays the Widget in the front-end */
    function widget($args, $instance){
		extract($args);
		$title = apply_filters('widget_title', empty($instance['title']) ? 'Popular Posts' : $instance['title']);
		$postsNum = empty($instance['postsNum']) ? '' : (int) $instance['postsNum'];

?>
<div class="widget popular">					
	<h4 class="widgettitle"><span><?php echo esc_html($title); ?></span></h4> 
	<ul>
		<?php global $wpdb;
		$result = $wpdb->get_results("SELECT comment_count,ID,post_title FROM $wpdb->posts ORDER BY comment_count DESC LIMIT 0 , $postsNum");
		foreach ($result as $post) {
			//setup_postdata($post);
			$postid = $post->ID;
			$title = $post->post_title;
			$commentcount = $post->comment_count;
			if ($commentcount != 0) { ?>
				<?php query_posts("p=$postid&&caller_get_posts=1"); ?>
				<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
					<?php get_template_part('includes/widgets/custom_post'); ?>
				<?php endwhile; endif; wp_reset_query(); ?>
			<?php };
		}; ?>
	</ul> 	
</div> <!-- end .widget -->

<?php

	}

  /*Saves the settings. */
    function update($new_instance, $old_instance){
		$instance = $old_instance;
		$instance['title'] = esc_attr($new_instance['title']);
		$instance['postsNum'] = (int) $new_instance['postsNum'];
		
		return $instance;
	}

  /*Creates the form for the widget in the back-end. */
    function form($instance){
		//Defaults
		$instance = wp_parse_args( (array) $instance, array('title'=>'Popular Posts', 'postsNum'=>'') );

		$title = $instance['title'];
		$postsNum = (int) $instance['postsNum'];
		
		# Title
		echo '<p><label for="' . $this->get_field_id('title') . '">' . 'Title:' . '</label><input class="widefat" id="' . $this->get_field_id('title') . '" name="' . $this->get_field_name('title') . '" type="text" value="' . esc_attr($title) . '" /></p>';
		# Number of posts
		echo '<p><label for="' . $this->get_field_id('postsNum') . '">' . 'Number of posts:' . '</label><input class="widefat" id="' . $this->get_field_id('postsNum') . '" name="' . $this->get_field_name('postsNum') . '" type="text" value="' . esc_attr($postsNum) . '" /></p>';		
	}

}// end AboutMeWidget class

function PopularWidgetInit() {
  register_widget('PopularWidget');
}

add_action('widgets_init', 'PopularWidgetInit');

?>