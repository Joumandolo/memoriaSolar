<?php $thumb = '';
  	  
	$width = 74;
	$height = 74;
	$classtext = '';
	$titletext = get_the_title();
	
	$thumbnail = get_thumbnail($width,$height,$classtext,$titletext,$titletext);
	$thumb = $thumbnail["thumb"];	
?>

<li class="clearfix">
	<?php if ($thumb <> '') { ?>
		<a href="<?php the_permalink(); ?>">
			<?php print_thumbnail($thumb, $thumbnail["use_timthumb"], $titletext, $width, $height, $classtext); ?>
		</a>
	<?php }; ?>
	<span class="right">
		<a href="<?php the_permalink(); ?>">
			<span class="title"><?php the_title(); ?></span>
		</a>
		<span class="postinfo"><?php esc_html_e('Posted on','TheSource') ?> <?php the_time(get_option('thesource_date_format')); ?></span>
	</span>
</li>