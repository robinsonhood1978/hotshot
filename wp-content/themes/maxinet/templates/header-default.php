<?php
/**
 * The template to display default site header
 *
 * @package WordPress
 * @subpackage MAXINET
 * @since MAXINET 1.0
 */


$maxinet_header_css = $maxinet_header_image = '';
$maxinet_header_video = maxinet_get_header_video();
if (true || empty($maxinet_header_video)) {
	$maxinet_header_image = get_header_image();
	if (maxinet_trx_addons_featured_image_override()) $maxinet_header_image = maxinet_get_current_mode_image($maxinet_header_image);
}

?><header class="top_panel top_panel_default default_header_bg<?php
					echo !empty($maxinet_header_image) || !empty($maxinet_header_video) ? ' with_bg_image' : ' without_bg_image';
					if ($maxinet_header_video!='') echo ' with_bg_video';
					if ($maxinet_header_image!='') echo ' '.esc_attr(maxinet_add_inline_css_class('background-image: url('.esc_url($maxinet_header_image).');'));
					if (is_single() && has_post_thumbnail()) echo ' with_featured_image';
					if (maxinet_is_on(maxinet_get_theme_option('header_fullheight'))) echo ' header_fullheight maxinet-full-height';
					?> scheme_<?php echo esc_attr(maxinet_is_inherit(maxinet_get_theme_option('header_scheme')) 
													? maxinet_get_theme_option('color_scheme') 
													: maxinet_get_theme_option('header_scheme'));
					?>"><?php

	// Background video
	if (!empty($maxinet_header_video)) {
		get_template_part( 'templates/header-video' );
	}
	
	// Main menu
	if (maxinet_get_theme_option("menu_style") == 'top') {
		get_template_part( 'templates/header-navi' );
	}

	// Page title and breadcrumbs area
	get_template_part( 'templates/header-title');

	// Header widgets area
	get_template_part( 'templates/header-widgets' );

	// Header for single posts
	get_template_part( 'templates/header-single' );

?></header>