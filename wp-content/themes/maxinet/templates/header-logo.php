<?php
/**
 * The template to display the logo or the site name and the slogan in the Header
 *
 * @package WordPress
 * @subpackage MAXINET
 * @since MAXINET 1.0
 */

$maxinet_args = get_query_var('maxinet_logo_args');

// Site logo
$maxinet_logo_type   = isset($maxinet_args['type']) ? $maxinet_args['type'] : '';
$maxinet_logo_image  = maxinet_get_logo_image($maxinet_logo_type);
$maxinet_logo_text   = maxinet_is_on(maxinet_get_theme_option('logo_text')) ? get_bloginfo( 'name' ) : '';
$maxinet_logo_slogan = get_bloginfo( 'description', 'display' );
if (!empty($maxinet_logo_image) || !empty($maxinet_logo_text)) {
	?><a class="sc_layouts_logo" href="<?php echo esc_url(home_url('/')); ?>"><?php
		if (!empty($maxinet_logo_image)) {
			if (empty($maxinet_logo_type) && function_exists('the_custom_logo') && (int) $maxinet_logo_image > 0) {
				the_custom_logo();
			} else {
				$maxinet_attr = maxinet_getimagesize($maxinet_logo_image);
				echo '<img src="'.esc_url($maxinet_logo_image).'" alt="header_logo_image"'.(!empty($maxinet_attr[3]) ? ' '.wp_kses_data($maxinet_attr[3]) : '').'>';
			}
		} else {
			maxinet_show_layout(maxinet_prepare_macros($maxinet_logo_text), '<span class="logo_text">', '</span>');
			maxinet_show_layout(maxinet_prepare_macros($maxinet_logo_slogan), '<span class="logo_slogan">', '</span>');
		}
	?></a><?php
}
?>
