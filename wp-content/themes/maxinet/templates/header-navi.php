<?php
/**
 * The template to display the main menu
 *
 * @package WordPress
 * @subpackage MAXINET
 * @since MAXINET 1.0
 */
?>
<div class="top_panel_navi sc_layouts_row sc_layouts_row_type_compact sc_layouts_row_fixed sc_layouts_row_fixed_always<?php
			if (false) {
			echo ' scheme_'. esc_attr(maxinet_is_inherit(maxinet_get_theme_option('menu_scheme')) 
												? (maxinet_is_inherit(maxinet_get_theme_option('header_scheme')) 
													? maxinet_get_theme_option('color_scheme') 
													: maxinet_get_theme_option('header_scheme')) 
												: maxinet_get_theme_option('menu_scheme'));
			}
			?>">
	<div class="content_wrap">
		<div class="columns_wrap columns_fluid">
			<div class="sc_layouts_column sc_layouts_column_align_left sc_layouts_column_icons_position_left sc_layouts_column_fluid column-1_3">
				<?php
				// Logo
				?><div class="sc_layouts_item"><?php
					get_template_part( 'templates/header-logo' );
				?></div>
			</div><?php
			
			// Attention! Don't place any spaces between columns!
			?><div class="sc_layouts_column sc_layouts_column_align_right sc_layouts_column_icons_position_left sc_layouts_column_fluid column-2_3">
				<div class="sc_layouts_item">
					<?php
					// Main menu
					$maxinet_menu_main = maxinet_get_nav_menu(array(
						'location' => 'menu_main', 
						'class' => 'sc_layouts_menu sc_layouts_menu_default sc_layouts_hide_on_mobile'
						)
					);
					// Show any menu if no menu selected in the location 'menu_main'
					if (maxinet_get_theme_setting('autoselect_menu') && empty($maxinet_menu_main)) {
						$maxinet_menu_main = maxinet_get_nav_menu(array(
							'class' => 'sc_layouts_menu sc_layouts_menu_default sc_layouts_hide_on_mobile'
							)
						);
					}
					maxinet_show_layout($maxinet_menu_main);
					// Mobile menu button
					?>
					<div class="sc_layouts_iconed_text sc_layouts_menu_mobile_button">
						<a class="sc_layouts_item_link sc_layouts_iconed_text_link" href="#">
							<span class="sc_layouts_item_icon sc_layouts_iconed_text_icon trx_addons_icon-menu"></span>
						</a>
					</div>
				</div><?php
				
				if (maxinet_exists_trx_addons()) {
					?><div class="sc_layouts_item"><?php
						// Display search field
						do_action('maxinet_action_search', 'fullscreen', 'header_search', false);
					?></div><?php
				}
				?>
			</div>
		</div><!-- /.columns_wrap -->
	</div><!-- /.content_wrap -->
</div><!-- /.top_panel_navi -->