<?php
/**
 * The template to display posts in widgets and/or in the search results
 *
 * @package WordPress
 * @subpackage MAXINET
 * @since MAXINET 1.0
 */

$maxinet_post_id    = get_the_ID();
$maxinet_post_date  = maxinet_get_date();
$maxinet_post_title = get_the_title();
$maxinet_post_link  = get_permalink();
$maxinet_post_author_id   = get_the_author_meta('ID');
$maxinet_post_author_name = get_the_author_meta('display_name');
$maxinet_post_author_url  = get_author_posts_url($maxinet_post_author_id, '');

$maxinet_args = get_query_var('maxinet_args_widgets_posts');
$maxinet_show_date = isset($maxinet_args['show_date']) ? (int) $maxinet_args['show_date'] : 1;
$maxinet_show_image = isset($maxinet_args['show_image']) ? (int) $maxinet_args['show_image'] : 1;
$maxinet_show_author = isset($maxinet_args['show_author']) ? (int) $maxinet_args['show_author'] : 1;
$maxinet_show_counters = isset($maxinet_args['show_counters']) ? (int) $maxinet_args['show_counters'] : 1;
$maxinet_show_categories = isset($maxinet_args['show_categories']) ? (int) $maxinet_args['show_categories'] : 1;

$maxinet_output = maxinet_storage_get('maxinet_output_widgets_posts');

$maxinet_post_counters_output = '';
if ( $maxinet_show_counters ) {
	$maxinet_post_counters_output = '<span class="post_info_item post_info_counters">'
								. maxinet_get_post_counters('comments')
							. '</span>';
}


$maxinet_output .= '<article class="post_item with_thumb">';

if ($maxinet_show_image) {
	$maxinet_post_thumb = get_the_post_thumbnail($maxinet_post_id, maxinet_get_thumb_size('tiny'), array(
		'alt' => get_the_title()
	));
	if ($maxinet_post_thumb) $maxinet_output .= '<div class="post_thumb">' . ($maxinet_post_link ? '<a href="' . esc_url($maxinet_post_link) . '">' : '') . ($maxinet_post_thumb) . ($maxinet_post_link ? '</a>' : '') . '</div>';
}

$maxinet_output .= '<div class="post_content">'
			. ($maxinet_show_categories 
					? '<div class="post_categories">'
						. maxinet_get_post_categories()
						. $maxinet_post_counters_output
						. '</div>' 
					: '')
			. '<h6 class="post_title">' . ($maxinet_post_link ? '<a href="' . esc_url($maxinet_post_link) . '">' : '') . ($maxinet_post_title) . ($maxinet_post_link ? '</a>' : '') . '</h6>'
			. apply_filters('maxinet_filter_get_post_info', 
								'<div class="post_info">'
									. ($maxinet_show_date 
										? '<span class="post_info_item post_info_posted">'
											. ($maxinet_post_link ? '<a href="' . esc_url($maxinet_post_link) . '" class="post_info_date">' : '') 
											. esc_html($maxinet_post_date) 
											. ($maxinet_post_link ? '</a>' : '')
											. '</span>'
										: '')
									. ($maxinet_show_author 
										? '<span class="post_info_item post_info_posted_by">' 
											. esc_html__('by', 'maxinet') . ' ' 
											. ($maxinet_post_link ? '<a href="' . esc_url($maxinet_post_author_url) . '" class="post_info_author">' : '') 
											. esc_html($maxinet_post_author_name) 
											. ($maxinet_post_link ? '</a>' : '') 
											. '</span>'
										: '')
									. (!$maxinet_show_categories && $maxinet_post_counters_output
										? $maxinet_post_counters_output
										: '')
								. '</div>')
		. '</div>'
	. '</article>';
maxinet_storage_set('maxinet_output_widgets_posts', $maxinet_output);
?>