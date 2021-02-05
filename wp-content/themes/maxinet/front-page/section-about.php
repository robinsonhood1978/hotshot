<div class="front_page_section front_page_section_about<?php
			$maxinet_scheme = maxinet_get_theme_option('front_page_about_scheme');
			if (!maxinet_is_inherit($maxinet_scheme)) echo ' scheme_'.esc_attr($maxinet_scheme);
			echo ' front_page_section_paddings_'.esc_attr(maxinet_get_theme_option('front_page_about_paddings'));
		?>"<?php
		$maxinet_css = '';
		$maxinet_bg_image = maxinet_get_theme_option('front_page_about_bg_image');
		if (!empty($maxinet_bg_image)) 
			$maxinet_css .= 'background-image: url('.esc_url(maxinet_get_attachment_url($maxinet_bg_image)).');';
		if (!empty($maxinet_css))
			echo ' style="' . esc_attr($maxinet_css) . '"';
?>><?php
	// Add anchor
	$maxinet_anchor_icon = maxinet_get_theme_option('front_page_about_anchor_icon');	
	$maxinet_anchor_text = maxinet_get_theme_option('front_page_about_anchor_text');	
	if ((!empty($maxinet_anchor_icon) || !empty($maxinet_anchor_text)) && shortcode_exists('trx_sc_anchor')) {
		echo do_shortcode('[trx_sc_anchor id="front_page_section_about"'
										. (!empty($maxinet_anchor_icon) ? ' icon="'.esc_attr($maxinet_anchor_icon).'"' : '')
										. (!empty($maxinet_anchor_text) ? ' title="'.esc_attr($maxinet_anchor_text).'"' : '')
										. ']');
	}
	?>
	<div class="front_page_section_inner front_page_section_about_inner<?php
			if (maxinet_get_theme_option('front_page_about_fullheight'))
				echo ' maxinet-full-height sc_layouts_flex sc_layouts_columns_middle';
			?>"<?php
			$maxinet_css = '';
			$maxinet_bg_mask = maxinet_get_theme_option('front_page_about_bg_mask');
			$maxinet_bg_color = maxinet_get_theme_option('front_page_about_bg_color');
			if (!empty($maxinet_bg_color) && $maxinet_bg_mask > 0)
				$maxinet_css .= 'background-color: '.esc_attr($maxinet_bg_mask==1
																	? $maxinet_bg_color
																	: maxinet_hex2rgba($maxinet_bg_color, $maxinet_bg_mask)
																).';';
			if (!empty($maxinet_css))
				echo ' style="' . esc_attr($maxinet_css) . '"';
	?>>
		<div class="front_page_section_content_wrap front_page_section_about_content_wrap content_wrap">
			<?php
			// Caption
			$maxinet_caption = maxinet_get_theme_option('front_page_about_caption');
			if (!empty($maxinet_caption) || (current_user_can('edit_theme_options') && is_customize_preview())) {
				?><h2 class="front_page_section_caption front_page_section_about_caption front_page_block_<?php echo !empty($maxinet_caption) ? 'filled' : 'empty'; ?>"><?php echo wp_kses_post($maxinet_caption); ?></h2><?php
			}
		
			// Description (text)
			$maxinet_description = maxinet_get_theme_option('front_page_about_description');
			if (!empty($maxinet_description) || (current_user_can('edit_theme_options') && is_customize_preview())) {
				?><div class="front_page_section_description front_page_section_about_description front_page_block_<?php echo !empty($maxinet_description) ? 'filled' : 'empty'; ?>"><?php echo wp_kses_post(wpautop($maxinet_description)); ?></div><?php
			}
			
			// Content
			$maxinet_content = maxinet_get_theme_option('front_page_about_content');
			if (!empty($maxinet_content) || (current_user_can('edit_theme_options') && is_customize_preview())) {
				?><div class="front_page_section_content front_page_section_about_content front_page_block_<?php echo !empty($maxinet_content) ? 'filled' : 'empty'; ?>"><?php
					$maxinet_page_content_mask = '%%CONTENT%%';
					if (strpos($maxinet_content, $maxinet_page_content_mask) !== false) {
						$maxinet_content = preg_replace(
									'/(\<p\>\s*)?'.$maxinet_page_content_mask.'(\s*\<\/p\>)/i',
									sprintf('<div class="front_page_section_about_source">%s</div>',
												apply_filters('the_content', get_the_content())),
									$maxinet_content
									);
					}
					maxinet_show_layout($maxinet_content);
				?></div><?php
			}
			?>
		</div>
	</div>
</div>