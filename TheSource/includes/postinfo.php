<?php if (!is_single() && (get_option('thesource_postinfo1') <> '')) { ?>
	<?php if ( (!(in_array('author', get_option('thesource_postinfo1'))) && !(in_array('categories', get_option('thesource_postinfo1'))) && !(in_array('comments', get_option('thesource_postinfo1')))) == FALSE) { ?>
		<p class="meta-info"><?php esc_html_e('Posted','TheSource'); ?> <?php if (in_array('author', get_option('thesource_postinfo1'))) { ?> <?php esc_html_e('by','TheSource'); ?> <?php the_author_posts_link(); } ?> <?php if (in_array('categories', get_option('thesource_postinfo1'))) { ?> <?php esc_html_e(' in','TheSource'); ?> <?php the_category(', ') ?><?php }; ?><?php if (in_array('comments', get_option('thesource_postinfo1'))) { ?> | <?php comments_popup_link(esc_html__('0 comments','TheSource'), esc_html__('1 comment','TheSource'), '% '.esc_html__('comments','TheSource')); ?><?php }; ?></p>
	<?php }; ?>
<?php } elseif (is_single() && (get_option('thesource_postinfo2') <> '')) { ?>
	<?php if ( (!(in_array('author', get_option('thesource_postinfo2'))) && !(in_array('categories', get_option('thesource_postinfo2'))) && !(in_array('comments', get_option('thesource_postinfo2')))) == FALSE) { ?>
		<div class="post-meta clearfix">
			<p class="meta-info"><?php esc_html_e('Posted','TheSource'); ?> <?php if (in_array('author', get_option('thesource_postinfo2'))) { ?> <?php esc_html_e('by','TheSource'); ?> <?php the_author_posts_link(); ?><?php }; ?><?php if (in_array('categories', get_option('thesource_postinfo2'))) { ?> <?php esc_html_e('in','TheSource'); ?> <?php the_category(', ') ?><?php }; ?><?php if (in_array('comments', get_option('thesource_postinfo2'))) { ?> | <?php comments_popup_link(esc_html__('0 comments','TheSource'), esc_html__('1 comment','TheSource'), '% '.esc_html__('comments','TheSource')); ?><?php }; ?></p>
		</div> <!-- end .post-meta -->
	<?php }; ?>
<?php }; ?>