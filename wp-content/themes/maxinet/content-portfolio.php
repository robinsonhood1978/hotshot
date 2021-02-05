<?php
/**
 * The Portfolio template to display the content
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

?><article id="post-<?php the_ID(); ?>" 
	<?php post_class( 'post_item post_layout_portfolio post_layout_portfolio_'.esc_attr($maxinet_columns).' post_format_'.esc_attr($maxinet_post_format).(is_sticky() && !is_paged() ? ' sticky' : '') ); ?>
	<?php echo (!maxinet_is_off($maxinet_animation) ? ' data-animation="'.esc_attr(maxinet_get_animation_classes($maxinet_animation)).'"' : ''); ?>>
	<?php

	// Sticky label
	if ( is_sticky() && !is_paged() ) {
		?><span class="post_label label_sticky"></span><?php
	}

	$maxinet_image_hover = maxinet_get_theme_option('image_hover');
	// Featured image
	maxinet_show_post_featured(array(
		'thumb_size' => maxinet_get_thumb_size(strpos(maxinet_get_theme_option('body_style'), 'full')!==false || $maxinet_columns < 3 
								? 'big'
								: 'masonry-big'),
		'show_no_image' => true,
		'class' => $maxinet_image_hover == 'dots' ? 'hover_with_info' : '',
		'post_info' => $maxinet_image_hover == 'dots' ? '<div class="post_info">'.esc_html(get_the_title()).'</div>' : ''
	));
	?>
</article>