<div class="front_page_section front_page_section_contacts<?php
			$maxinet_scheme = maxinet_get_theme_option('front_page_contacts_scheme');
			if (!maxinet_is_inherit($maxinet_scheme)) echo ' scheme_'.esc_attr($maxinet_scheme);
			echo ' front_page_section_paddings_'.esc_attr(maxinet_get_theme_option('front_page_contacts_paddings'));
		?>"<?php
		$maxinet_css = '';
		$maxinet_bg_image = maxinet_get_theme_option('front_page_contacts_bg_image');
		if (!empty($maxinet_bg_image)) 
			$maxinet_css .= 'background-image: url('.esc_url(maxinet_get_attachment_url($maxinet_bg_image)).');';
		if (!empty($maxinet_css))
			echo ' style="' . esc_attr($maxinet_css) . '"';
?>><?php
	// Add anchor
	$maxinet_anchor_icon = maxinet_get_theme_option('front_page_contacts_anchor_icon');	
	$maxinet_anchor_text = maxinet_get_theme_option('front_page_contacts_anchor_text');	
	if ((!empty($maxinet_anchor_icon) || !empty($maxinet_anchor_text)) && shortcode_exists('trx_sc_anchor')) {
		echo do_shortcode('[trx_sc_anchor id="front_page_section_contacts"'
										. (!empty($maxinet_anchor_icon) ? ' icon="'.esc_attr($maxinet_anchor_icon).'"' : '')
										. (!empty($maxinet_anchor_text) ? ' title="'.esc_attr($maxinet_anchor_text).'"' : '')
										. ']');
	}
	?>
	<div class="front_page_section_inner front_page_section_contacts_inner<?php
			if (maxinet_get_theme_option('front_page_contacts_fullheight'))
				echo ' maxinet-full-height sc_layouts_flex sc_layouts_columns_middle';
			?>"<?php
			$maxinet_css = '';
			$maxinet_bg_mask = maxinet_get_theme_option('front_page_contacts_bg_mask');
			$maxinet_bg_color = maxinet_get_theme_option('front_page_contacts_bg_color');
			if (!empty($maxinet_bg_color) && $maxinet_bg_mask > 0)
				$maxinet_css .= 'background-color: '.esc_attr($maxinet_bg_mask==1
																	? $maxinet_bg_color
																	: maxinet_hex2rgba($maxinet_bg_color, $maxinet_bg_mask)
																).';';
			if (!empty($maxinet_css))
				echo ' style="' . esc_attr($maxinet_css) . '"';
	?>>
		<div class="front_page_section_content_wrap front_page_section_contacts_content_wrap content_wrap">
			<?php

			// Title and description
			$maxinet_caption = maxinet_get_theme_option('front_page_contacts_caption');
			$maxinet_description = maxinet_get_theme_option('front_page_contacts_description');
			if (!empty($maxinet_caption) || !empty($maxinet_description) || (current_user_can('edit_theme_options') && is_customize_preview())) {
				// Caption
				if (!empty($maxinet_caption) || (current_user_can('edit_theme_options') && is_customize_preview())) {
					?><h2 class="front_page_section_caption front_page_section_contacts_caption front_page_block_<?php echo !empty($maxinet_caption) ? 'filled' : 'empty'; ?>"><?php
						echo wp_kses_post($maxinet_caption);
					?></h2><?php
				}
			
				// Description
				if (!empty($maxinet_description) || (current_user_can('edit_theme_options') && is_customize_preview())) {
					?><div class="front_page_section_description front_page_section_contacts_description front_page_block_<?php echo !empty($maxinet_description) ? 'filled' : 'empty'; ?>"><?php
						echo wp_kses_post(wpautop($maxinet_description));
					?></div><?php
				}
			}

			// Content (text)
			$maxinet_content = maxinet_get_theme_option('front_page_contacts_content');
			$maxinet_layout = maxinet_get_theme_option('front_page_contacts_layout');
			if ($maxinet_layout == 'columns' && (!empty($maxinet_content) || (current_user_can('edit_theme_options') && is_customize_preview()))) {
				?><div class="front_page_section_columns front_page_section_contacts_columns columns_wrap">
					<div class="column-1_3">
				<?php
			}

			if ((!empty($maxinet_content) || (current_user_can('edit_theme_options') && is_customize_preview()))) {
				?><div class="front_page_section_content front_page_section_contacts_content front_page_block_<?php echo !empty($maxinet_content) ? 'filled' : 'empty'; ?>"><?php
					echo wp_kses_post($maxinet_content);
				?></div><?php
			}

			if ($maxinet_layout == 'columns' && (!empty($maxinet_content) || (current_user_can('edit_theme_options') && is_customize_preview()))) {
				?></div><div class="column-2_3"><?php
			}
		
			// Shortcode output
			$maxinet_sc = maxinet_get_theme_option('front_page_contacts_shortcode');
			if (!empty($maxinet_sc) || (current_user_can('edit_theme_options') && is_customize_preview())) {
				?><div class="front_page_section_output front_page_section_contacts_output front_page_block_<?php echo !empty($maxinet_sc) ? 'filled' : 'empty'; ?>"><?php
					maxinet_show_layout(do_shortcode($maxinet_sc));
				?></div><?php
			}

			if ($maxinet_layout == 'columns' && (!empty($maxinet_content) || (current_user_can('edit_theme_options') && is_customize_preview()))) {
				?></div></div><?php
			}
			?>			
		</div>
	</div>
</div>