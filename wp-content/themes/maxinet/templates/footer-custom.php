<?php
/**
 * The template to display default site footer
 *
 * @package WordPress
 * @subpackage MAXINET
 * @since MAXINET 1.0.10
 */

$maxinet_footer_scheme =  maxinet_is_inherit(maxinet_get_theme_option('footer_scheme')) ? maxinet_get_theme_option('color_scheme') : maxinet_get_theme_option('footer_scheme');
$maxinet_footer_id = str_replace('footer-custom-', '', maxinet_get_theme_option("footer_style"));
if ((int) $maxinet_footer_id == 0) {
	$maxinet_footer_id = maxinet_get_post_id(array(
												'name' => $maxinet_footer_id,
												'post_type' => defined('TRX_ADDONS_CPT_LAYOUTS_PT') ? TRX_ADDONS_CPT_LAYOUTS_PT : 'cpt_layouts'
												)
											);
} else {
	$maxinet_footer_id = apply_filters('maxinet_filter_get_translated_layout', $maxinet_footer_id);
}
$maxinet_footer_meta = get_post_meta($maxinet_footer_id, 'trx_addons_options', true);
?>
<footer class="footer_wrap footer_custom footer_custom_<?php echo esc_attr($maxinet_footer_id); 
						?> footer_custom_<?php echo esc_attr(sanitize_title(get_the_title($maxinet_footer_id))); 
						if (!empty($maxinet_footer_meta['margin']) != '') 
							echo ' '.esc_attr(maxinet_add_inline_css_class('margin-top: '.maxinet_prepare_css_value($maxinet_footer_meta['margin']).';'));
						?> scheme_<?php echo esc_attr($maxinet_footer_scheme); 
						?>">
	<?php
    // Custom footer's layout
    do_action('maxinet_action_show_layout', $maxinet_footer_id);
	?>
</footer><!-- /.footer_wrap -->
