<?php
/**
 * The template to display blog archive
 *
 * @package WordPress
 * @subpackage MAXINET
 * @since MAXINET 1.0
 */

/*
Template Name: Blog archive
*/

/**
 * Make page with this template and put it into menu
 * to display posts as blog archive
 * You can setup output parameters (blog style, posts per page, parent category, etc.)
 * in the Theme Options section (under the page content)
 * You can build this page in the WordPress editor or any Page Builder to make custom page layout:
 * just insert %%CONTENT%% in the desired place of content
 */

// Get template page's content
$maxinet_content = '';
$maxinet_blog_archive_mask = '%%CONTENT%%';
$maxinet_blog_archive_subst = sprintf('<div class="blog_archive">%s</div>', $maxinet_blog_archive_mask);
if ( have_posts() ) {
	the_post();
	if (($maxinet_content = apply_filters('the_content', get_the_content())) != '') {
		if (($maxinet_pos = strpos($maxinet_content, $maxinet_blog_archive_mask)) !== false) {
			$maxinet_content = preg_replace('/(\<p\>\s*)?'.$maxinet_blog_archive_mask.'(\s*\<\/p\>)/i', $maxinet_blog_archive_subst, $maxinet_content);
		} else
			$maxinet_content .= $maxinet_blog_archive_subst;
		$maxinet_content = explode($maxinet_blog_archive_mask, $maxinet_content);
		// Add VC custom styles to the inline CSS
		$vc_custom_css = get_post_meta( get_the_ID(), '_wpb_shortcodes_custom_css', true );
		if ( !empty( $vc_custom_css ) ) maxinet_add_inline_css(strip_tags($vc_custom_css));
	}
}

// Prepare args for a new query
$maxinet_args = array(
	'post_status' => current_user_can('read_private_pages') && current_user_can('read_private_posts') ? array('publish', 'private') : 'publish'
);
$maxinet_args = maxinet_query_add_posts_and_cats($maxinet_args, '', maxinet_get_theme_option('post_type'), maxinet_get_theme_option('parent_cat'));
$maxinet_page_number = get_query_var('paged') ? get_query_var('paged') : (get_query_var('page') ? get_query_var('page') : 1);
if ($maxinet_page_number > 1) {
	$maxinet_args['paged'] = $maxinet_page_number;
	$maxinet_args['ignore_sticky_posts'] = true;
}
$maxinet_ppp = maxinet_get_theme_option('posts_per_page');
if ((int) $maxinet_ppp != 0)
	$maxinet_args['posts_per_page'] = (int) $maxinet_ppp;
// Make a new main query
$GLOBALS['wp_the_query']->query($maxinet_args);


// Add internal query vars in the new query!
if (is_array($maxinet_content) && count($maxinet_content) == 2) {
	set_query_var('blog_archive_start', $maxinet_content[0]);
	set_query_var('blog_archive_end', $maxinet_content[1]);
}

get_template_part('index');
?>