<?php class RandomWidget extends WP_Widget
{
    function RandomWidget(){
		$widget_ops = array('description' => 'Displays Random Posts');
		$control_ops = array('width' => 400, 'height' => 300);
		parent::WP_Widget(false,$name='ET Random Widget',$widget_ops,$control_ops);
    }

  /* Displays the Widget in the front-end */
    function widget($args, $instance){
		extract($args);
		$title = apply_filters('widget_title', empty($instance['title']) ? 'Random Posts' : $instance['title']);
		$postsNum = empty($instance['postsNum']) ? '' : (int) $instance['postsNum'];

?>

<div class="widget random">
	<h4 class="widgettitle"><span><?php echo $title; ?></span></h4> 
	<ul>
		<?php query_posts("showposts=$postsNum&ignore_sticky_posts=1&orderby=rand");
		 	if (have_posts()) : while (have_posts()) : the_post(); ?>
				<?php get_template_part('includes/widgets/custom_post'); ?>
			<?php endwhile; 
		endif; wp_reset_query(); ?>
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
		$instance = wp_parse_args( (array) $instance, array('title'=>'Random posts', 'postsNum'=>'') );

		$title = $instance['title'];
		$postsNum = (int) $instance['postsNum'];
		
		# Title
		echo '<p><label for="' . $this->get_field_id('title') . '">' . 'Title:' . '</label><input class="widefat" id="' . $this->get_field_id('title') . '" name="' . $this->get_field_name('title') . '" type="text" value="' . esc_attr($title) . '" /></p>';
		# Number of posts
		echo '<p><label for="' . $this->get_field_id('postsNum') . '">' . 'Number of posts:' . '</label><input class="widefat" id="' . $this->get_field_id('postsNum') . '" name="' . $this->get_field_name('postsNum') . '" type="text" value="' . esc_attr($postsNum) . '" /></p>';		
	}

}// end AboutMeWidget class

function RandomWidgetInit() {
  register_widget('RandomWidget');
}

add_action('widgets_init', 'RandomWidgetInit');

?>