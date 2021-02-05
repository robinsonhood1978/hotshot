<?php
/**
 * The template to display menu in the footer
 *
 * @package WordPress
 * @subpackage MAXINET
 * @since MAXINET 1.0.10
 */

// Footer menu
$maxinet_menu_footer = maxinet_get_nav_menu(array(
											'location' => 'menu_footer',
											'class' => 'sc_layouts_menu sc_layouts_menu_default'
											));
if (!empty($maxinet_menu_footer)) {
	?>
	<div class="footer_menu_wrap">
		<div class="footer_menu_inner">
			<?php maxinet_show_layout($maxinet_menu_footer); ?>
		</div>
	</div>
	<?php
}
?>