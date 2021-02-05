<?php
/**
 * The Gallery template to display posts
 *
 * Used for index/archive/search.
 *
 * @package WordPress
 * @subpackage MAXINET
 * @since MAXINET 1.0
 */

$maxinet_blog_style = explode('_', maxinet_get_theme_option('blog_style'));
$maxinet_columns = empty($maxinet_blog_style[1]) ? 2 : max(2, $maxinet_blog_style[1]);
$maxinet_post_format = get_post_format();
$maxinet_post_format = empty($maxinet_post_format) ? 'standard' : str_replace('post-format-', '', $maxinet_post_format);
$maxinet_animation = maxinet_get_theme_option('blog_animation');
$maxinet_image = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'full' );

?><article id="post-<?php the_ID(); ?>" 
	<?php post_class( 'post_item post_layout_portfolio post_layout_gallery post_layout_gallery_'.esc_attr($maxinet_columns).' post_format_'.esc_attr($maxinet_post_format) ); ?>
	<?php echo (!maxinet_is_off($maxinet_animation) ? ' data-animation="'.esc_attr(maxinet_get_animation_classes($maxinet_animation)).'"' : ''); ?>
	data-size="<?php if (!empty($maxinet_image[1]) && !empty($maxinet_image[2])) echo intval($maxinet_image[1]) .'x' . intval($maxinet_image[2]); ?>"
	data-src="<?php if (!empty($maxinet_image[0])) echo esc_url($maxinet_image[0]); ?>"
	>

	<?php

	// Sticky label
	if ( is_sticky() && !is_paged() ) {
		?><span class="post_label label_sticky"></span><?php
	}

	// Featured image
	$maxinet_image_hover = 'icon';
	if (in_array($maxinet_image_hover, array('icons', 'zoom'))) $maxinet_image_hover = 'dots';
	$maxinet_components = maxinet_is_inherit(maxinet_get_theme_option_from_meta('meta_parts')) 
								? 'categories,date,counters,share'
								: maxinet_array_get_keys_by_value(maxinet_get_theme_option('meta_parts'));
	$maxinet_counters = maxinet_is_inherit(maxinet_get_theme_option_from_meta('counters')) 
								? 'comments'
								: maxinet_array_get_keys_by_value(maxinet_get_theme_option('counters'));
	maxinet_show_post_featured(array(
		'hover' => $maxinet_image_hover,
		'thumb_size' => maxinet_get_thumb_size( strpos(maxinet_get_theme_option('body_style'), 'full')!==false || $maxinet_columns < 3 ? 'masonry-big' : 'masonry' ),
		'thumb_only' => true,
		'show_no_image' => true,
		'post_info' => '<div class="post_details">'
							. '<h2 class="post_title"><a href="'.esc_url(get_permalink()).'">'. esc_html(get_the_title()) . '</a></h2>'
							. '<div class="post_description">'
								. (!empty($maxinet_components)
										? maxinet_show_post_meta(apply_filters('maxinet_filter_post_meta_args', array(
											'components' => $maxinet_components,
											'counters' => $maxinet_counters,
											'seo' => false,
											'echo' => false
											), $maxinet_blog_style[0], $maxinet_columns))
										: '')
								. '<div class="post_description_content">'
									. apply_filters('the_excerpt', get_the_excerpt())
								. '</div>'
								. '<a href="'.esc_url(get_permalink()).'" class="theme_button post_readmore"><span class="post_readmore_label">' . esc_html__('Learn more', 'maxinet') . '</span></a>'
							. '</div>'
						. '</div>'
	));
	?>
</article>