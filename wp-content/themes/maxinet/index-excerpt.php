<?php
/**
 * The template for homepage posts with "Excerpt" style
 *
 * @package WordPress
 * @subpackage MAXINET
 * @since MAXINET 1.0
 */

maxinet_storage_set('blog_archive', true);

get_header(); 

if (have_posts()) {

	maxinet_show_layout(get_query_var('blog_archive_start'));

	?><div class="posts_container"><?php
	
	$maxinet_stickies = is_home() ? get_option( 'sticky_posts' ) : false;
	$maxinet_sticky_out = maxinet_get_theme_option('sticky_style')=='columns' 
							&& is_array($maxinet_stickies) && count($maxinet_stickies) > 0 && get_query_var( 'paged' ) < 1;
	if ($maxinet_sticky_out) {
		?><div class="sticky_wrap columns_wrap"><?php	
	}
	while ( have_posts() ) { the_post(); 
		if ($maxinet_sticky_out && !is_sticky()) {
			$maxinet_sticky_out = false;
			?></div><?php
		}
		get_template_part( 'content', $maxinet_sticky_out && is_sticky() ? 'sticky' : 'excerpt' );
	}
	if ($maxinet_sticky_out) {
		$maxinet_sticky_out = false;
		?></div><?php
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