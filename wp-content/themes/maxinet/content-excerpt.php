<?php
/**
 * The default template to display the content
 *
 * Used for index/archive/search.
 *
 * @package WordPress
 * @subpackage MAXINET
 * @since MAXINET 1.0
 */

$maxinet_post_format = get_post_format();
$maxinet_post_format = empty($maxinet_post_format) ? 'standard' : str_replace('post-format-', '', $maxinet_post_format);
$maxinet_animation = maxinet_get_theme_option('blog_animation');

?><article id="post-<?php the_ID(); ?>" 
	<?php post_class( 'post_item post_layout_excerpt post_format_'.esc_attr($maxinet_post_format) ); ?>
	<?php echo (!maxinet_is_off($maxinet_animation) ? ' data-animation="'.esc_attr(maxinet_get_animation_classes($maxinet_animation)).'"' : ''); ?>
	><?php

	// Sticky label
	if ( is_sticky() && !is_paged() ) {
		?><span class="post_label label_sticky"></span><?php
	}

	// Featured image
	maxinet_show_post_featured(array( 'thumb_size' => maxinet_get_thumb_size( strpos(maxinet_get_theme_option('body_style'), 'full')!==false ? 'full' : 'big' ) ));

	// Title and post meta
	if (get_the_title() != '') {
		?>
		<div class="post_header entry-header">
			<?php
			do_action('maxinet_action_before_post_title'); 

			// Post title
			the_title( sprintf( '<h2 class="post_title entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' );

			do_action('maxinet_action_before_post_meta'); 

			// Post meta
			$maxinet_components = maxinet_is_inherit(maxinet_get_theme_option_from_meta('meta_parts')) 
										? 'categories,date,counters,edit'
										: maxinet_array_get_keys_by_value(maxinet_get_theme_option('meta_parts'));
			$maxinet_counters = maxinet_is_inherit(maxinet_get_theme_option_from_meta('counters')) 
										? 'views,likes,comments'
										: maxinet_array_get_keys_by_value(maxinet_get_theme_option('counters'));

			if (!empty($maxinet_components))
				maxinet_show_post_meta(apply_filters('maxinet_filter_post_meta_args', array(
					'components' => $maxinet_components,
					'counters' => $maxinet_counters,
					'seo' => false
					), 'excerpt', 1)
				);
			?>
		</div><!-- .post_header --><?php
	}
	
	// Post content
	?><div class="post_content entry-content"><?php
		if (maxinet_get_theme_option('blog_content') == 'fullpost') {
			// Post content area
			?><div class="post_content_inner"><?php
				the_content( '' );
			?></div><?php
			// Inner pages
			wp_link_pages( array(
				'before'      => '<div class="page_links"><span class="page_links_title">' . esc_html__( 'Pages:', 'maxinet' ) . '</span>',
				'after'       => '</div>',
				'link_before' => '<span>',
				'link_after'  => '</span>',
				'pagelink'    => '<span class="screen-reader-text">' . esc_html__( 'Page', 'maxinet' ) . ' </span>%',
				'separator'   => '<span class="screen-reader-text">, </span>',
			) );

		} else {

			$maxinet_show_learn_more = !in_array($maxinet_post_format, array('link', 'aside', 'status', 'quote'));

			// Post content area
			?><div class="post_content_inner"><?php
				if (has_excerpt()) {
					the_excerpt();
				} else if (strpos(get_the_content('!--more'), '!--more')!==false) {
					the_content( '' );
				} else if (in_array($maxinet_post_format, array('link', 'aside', 'status'))) {
					the_content();
				} else if ($maxinet_post_format == 'quote') {
					if (($quote = maxinet_get_tag(get_the_content(), '<blockquote>', '</blockquote>'))!='')
						maxinet_show_layout(wpautop($quote));
					else
						the_excerpt();
				} else if (substr(get_the_content(), 0, 1)!='[') {
					the_excerpt();
				}
			?></div><?php
			// More button
			if ( $maxinet_show_learn_more ) {
				?><p><a class="more-link" href="<?php echo esc_url(get_permalink()); ?>"><?php esc_html_e('Read more', 'maxinet'); ?></a></p><?php
			}

		}
	?></div><!-- .entry-content -->
</article>