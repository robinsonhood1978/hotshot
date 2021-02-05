<div class="front_page_section front_page_section_woocommerce<?php
			$maxinet_scheme = maxinet_get_theme_option('front_page_woocommerce_scheme');
			if (!maxinet_is_inherit($maxinet_scheme)) echo ' scheme_'.esc_attr($maxinet_scheme);
			echo ' front_page_section_paddings_'.esc_attr(maxinet_get_theme_option('front_page_woocommerce_paddings'));
		?>"<?php
		$maxinet_css = '';
		$maxinet_bg_image = maxinet_get_theme_option('front_page_woocommerce_bg_image');
		if (!empty($maxinet_bg_image)) 
			$maxinet_css .= 'background-image: url('.esc_url(maxinet_get_attachment_url($maxinet_bg_image)).');';
		if (!empty($maxinet_css))
			echo ' style="' . esc_attr($maxinet_css) . '"';
?>><?php
	// Add anchor
	$maxinet_anchor_icon = maxinet_get_theme_option('front_page_woocommerce_anchor_icon');	
	$maxinet_anchor_text = maxinet_get_theme_option('front_page_woocommerce_anchor_text');	
	if ((!empty($maxinet_anchor_icon) || !empty($maxinet_anchor_text)) && shortcode_exists('trx_sc_anchor')) {
		echo do_shortcode('[trx_sc_anchor id="front_page_section_woocommerce"'
										. (!empty($maxinet_anchor_icon) ? ' icon="'.esc_attr($maxinet_anchor_icon).'"' : '')
										. (!empty($maxinet_anchor_text) ? ' title="'.esc_attr($maxinet_anchor_text).'"' : '')
										. ']');
	}
	?>
	<div class="front_page_section_inner front_page_section_woocommerce_inner<?php
			if (maxinet_get_theme_option('front_page_woocommerce_fullheight'))
				echo ' maxinet-full-height sc_layouts_flex sc_layouts_columns_middle';
			?>"<?php
			$maxinet_css = '';
			$maxinet_bg_mask = maxinet_get_theme_option('front_page_woocommerce_bg_mask');
			$maxinet_bg_color = maxinet_get_theme_option('front_page_woocommerce_bg_color');
			if (!empty($maxinet_bg_color) && $maxinet_bg_mask > 0)
				$maxinet_css .= 'background-color: '.esc_attr($maxinet_bg_mask==1
																	? $maxinet_bg_color
																	: maxinet_hex2rgba($maxinet_bg_color, $maxinet_bg_mask)
																).';';
			if (!empty($maxinet_css))
				echo ' style="' . esc_attr($maxinet_css) . '"';
	?>>
		<div class="front_page_section_content_wrap front_page_section_woocommerce_content_wrap content_wrap woocommerce">
			<?php
			// Content wrap with title and description
			$maxinet_caption = maxinet_get_theme_option('front_page_woocommerce_caption');
			$maxinet_description = maxinet_get_theme_option('front_page_woocommerce_description');
			if (!empty($maxinet_caption) || !empty($maxinet_description) || (current_user_can('edit_theme_options') && is_customize_preview())) {
				// Caption
				if (!empty($maxinet_caption) || (current_user_can('edit_theme_options') && is_customize_preview())) {
					?><h2 class="front_page_section_caption front_page_section_woocommerce_caption front_page_block_<?php echo !empty($maxinet_caption) ? 'filled' : 'empty'; ?>"><?php
						echo wp_kses_post($maxinet_caption);
					?></h2><?php
				}
			
				// Description (text)
				if (!empty($maxinet_description) || (current_user_can('edit_theme_options') && is_customize_preview())) {
					?><div class="front_page_section_description front_page_section_woocommerce_description front_page_block_<?php echo !empty($maxinet_description) ? 'filled' : 'empty'; ?>"><?php
						echo wp_kses_post(wpautop($maxinet_description));
					?></div><?php
				}
			}
		
			// Content (widgets)
			?><div class="front_page_section_output front_page_section_woocommerce_output list_products shop_mode_thumbs"><?php 
				$maxinet_woocommerce_sc = maxinet_get_theme_option('front_page_woocommerce_products');
				if ($maxinet_woocommerce_sc == 'products') {
					$maxinet_woocommerce_sc_ids = maxinet_get_theme_option('front_page_woocommerce_products_per_page');
					$maxinet_woocommerce_sc_per_page = count(explode(',', $maxinet_woocommerce_sc_ids));
				} else {
					$maxinet_woocommerce_sc_per_page = max(1, (int) maxinet_get_theme_option('front_page_woocommerce_products_per_page'));
				}
				$maxinet_woocommerce_sc_columns = max(1, min($maxinet_woocommerce_sc_per_page, (int) maxinet_get_theme_option('front_page_woocommerce_products_columns')));
				echo do_shortcode("[{$maxinet_woocommerce_sc}"
									. ($maxinet_woocommerce_sc == 'products' 
											? ' ids="'.esc_attr($maxinet_woocommerce_sc_ids).'"' 
											: '')
									. ($maxinet_woocommerce_sc == 'product_category' 
											? ' category="'.esc_attr(maxinet_get_theme_option('front_page_woocommerce_products_categories')).'"' 
											: '')
									. ($maxinet_woocommerce_sc != 'best_selling_products' 
											? ' orderby="'.esc_attr(maxinet_get_theme_option('front_page_woocommerce_products_orderby')).'"'
											  . ' order="'.esc_attr(maxinet_get_theme_option('front_page_woocommerce_products_order')).'"' 
											: '')
									. ' per_page="'.esc_attr($maxinet_woocommerce_sc_per_page).'"' 
									. ' columns="'.esc_attr($maxinet_woocommerce_sc_columns).'"' 
									. ']');
			?></div>
		</div>
	</div>
</div>