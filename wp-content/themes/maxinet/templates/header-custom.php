<?php
/**
 * The template to display custom header from the ThemeREX Addons Layouts
 *
 * @package WordPress
 * @subpackage MAXINET
 * @since MAXINET 1.0.06
 */

$maxinet_header_css = $maxinet_header_image = '';
$maxinet_header_video = maxinet_get_header_video();
if (true || empty($maxinet_header_video)) {
	$maxinet_header_image = get_header_image();
	if (maxinet_trx_addons_featured_image_override(true)) $maxinet_header_image = maxinet_get_current_mode_image($maxinet_header_image);
}

$maxinet_header_id = str_replace('header-custom-', '', maxinet_get_theme_option("header_style"));
if ((int) $maxinet_header_id == 0) {
	$maxinet_header_id = maxinet_get_post_id(array(
												'name' => $maxinet_header_id,
												'post_type' => defined('TRX_ADDONS_CPT_LAYOUTS_PT') ? TRX_ADDONS_CPT_LAYOUTS_PT : 'cpt_layouts'
												)
											);
} else {
	$maxinet_header_id = apply_filters('maxinet_filter_get_translated_layout', $maxinet_header_id);
}
$maxinet_header_meta = get_post_meta($maxinet_header_id, 'trx_addons_options', true);

?><header class="top_panel top_panel_custom top_panel_custom_<?php echo esc_attr($maxinet_header_id); 
				?> top_panel_custom_<?php echo esc_attr(sanitize_title(get_the_title($maxinet_header_id)));
				echo !empty($maxinet_header_image) || !empty($maxinet_header_video) 
					? ' with_bg_image' 
					: ' without_bg_image';
				if ($maxinet_header_video!='') 
					echo ' with_bg_video';
				if ($maxinet_header_image!='') 
					echo ' '.esc_attr(maxinet_add_inline_css_class('background-image: url('.esc_url($maxinet_header_image).');'));
				if (!empty($maxinet_header_meta['margin']) != '') 
					echo ' '.esc_attr(maxinet_add_inline_css_class('margin-bottom: '.esc_attr(maxinet_prepare_css_value($maxinet_header_meta['margin'])).';'));
				if (is_single() && has_post_thumbnail()) 
					echo ' with_featured_image';
				if (maxinet_is_on(maxinet_get_theme_option('header_fullheight'))) 
					echo ' header_fullheight maxinet-full-height';
				?> scheme_<?php echo esc_attr(maxinet_is_inherit(maxinet_get_theme_option('header_scheme')) 
												? maxinet_get_theme_option('color_scheme') 
												: maxinet_get_theme_option('header_scheme'));
				?>"><?php

	// Background video
	if (!empty($maxinet_header_video)) {
		get_template_part( 'templates/header-video' );
	}
		
	// Custom header's layout
	do_action('maxinet_action_show_layout', $maxinet_header_id);

	// Header widgets area
	get_template_part( 'templates/header-widgets' );
		
?></header>