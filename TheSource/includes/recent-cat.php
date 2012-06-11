<div class="top<?php if($last) echo(" last"); ?>">
   <h4 class="title"><?php echo(esc_html(get_option($cat_option))); ?></h4>
</div>

<?php $thumb = '';      

   $width = 239;
   $height = 133;
   $classtext = 'thumb';
   $titletext = get_the_title();
   
   $thumbnail = get_thumbnail($width,$height,$classtext,$titletext,$titletext);
   $thumb = $thumbnail["thumb"]; ?>

<?php if ($thumb <> '') print_thumbnail($thumb, $thumbnail["use_timthumb"], $titletext , $width, $height, $classtext); ?>

<div class="entry <?php echo($headingColor); ?>"<?php if ($thumb == '') echo(' style="padding-top: 70px;"'); ?>>
   <div class="title"<?php if ($thumb == '') echo(' style="top: 13px;"'); ?>>
      <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
   </div>
   <p class="meta-info"><?php esc_html_e('Posted by','TheSource') ?> <?php the_author_posts_link(); ?> <?php esc_html_e('on','TheSource') ?> <?php the_time(get_option('thesource_date_format')) ?></p>
   <p><?php truncate_post(182); ?></p>
   <a href="<?php the_permalink(); ?>" class="readmore"><span><?php esc_html_e('Read More','TheSource'); ?></span></a>
</div>