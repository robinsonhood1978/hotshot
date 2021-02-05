<?php
/**
 * The template for homepage posts with "Classic" style
 *
 * @package WordPress
 * @subpackage MAXINET
 * @since MAXINET 1.0
 */

maxinet_storage_set('blog_archive', true);

get_header(); 

if (have_posts()) {

	maxinet_show_layout(get_query_var('blog_archive_start'));

	$maxinet_classes = 'posts_container '
						. (substr(maxinet_get_theme_option('blog_style'), 0, 7) == 'classic' ? 'columns_wrap columns_padding_bottom' : 'masonry_wrap');
	$maxinet_stickies = is_home() ? get_option( 'sticky_posts' ) : false;
	$maxinet_sticky_out = maxinet_get_theme_option('sticky_style')=='columns' 
							&& is_array($maxinet_stickies) && count($maxinet_stickies) > 0 && get_query_var( 'paged' ) < 1;
	if ($maxinet_sticky_out) {
		?><div class="sticky_wrap columns_wrap"><?php	
	}
	if (!$maxinet_sticky_out) {
		if (maxinet_get_theme_option('first_post_large') && !is_paged() && !in_array(maxinet_get_theme_option('body_style'), array('fullwide', 'fullscreen'))) {
			the_post();
			get_template_part( 'content', 'excerpt' );
		}
		
		?><div class="<?php echo esc_attr($maxinet_classes); ?>"><?php
	}
	while ( have_posts() ) { the_post(); 
		if ($maxinet_sticky_out && !is_sticky()) {
			$maxinet_sticky_out = false;
			?></div><div class="<?php echo esc_attr($maxinet_classes); ?>"><?php
		}
		get_template_part( 'content', $maxinet_sticky_out && is_sticky() ? 'sticky' : 'classic' );
	}
	
	?></div><?php

	maxinet_show_pagination();

	maxinet_show_layout(get_query_var('blog_archive_end'));

} else {

	if ( is_search() )
		get_template_part( 'content', 'none-search' );
	else
		get_template_part( 'content', 'none-archive' );

}

get_footer();
?>