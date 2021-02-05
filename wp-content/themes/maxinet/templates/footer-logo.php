<?php
/**
 * The template to display the site logo in the footer
 *
 * @package WordPress
 * @subpackage MAXINET
 * @since MAXINET 1.0.10
 */

// Logo
if (maxinet_is_on(maxinet_get_theme_option('logo_in_footer'))) {
	$maxinet_logo_image = '';
	if (maxinet_is_on(maxinet_get_theme_option('logo_retina_enabled')) && maxinet_get_retina_multiplier() > 1)
		$maxinet_logo_image = maxinet_get_theme_option( 'logo_footer_retina' );
	if (empty($maxinet_logo_image)) 
		$maxinet_logo_image = maxinet_get_theme_option( 'logo_footer' );
	$maxinet_logo_text   = get_bloginfo( 'name' );
	if (!empty($maxinet_logo_image) || !empty($maxinet_logo_text)) {
		?>
		<div class="footer_logo_wrap">
			<div class="footer_logo_inner">
				<?php
				if (!empty($maxinet_logo_image)) {
					$maxinet_attr = maxinet_getimagesize($maxinet_logo_image);
					echo '<a href="'.esc_url(home_url('/')).'"><img src="'.esc_url($maxinet_logo_image).'" class="logo_footer_image" alt="logo_footer_image"'.(!empty($maxinet_attr[3]) ? ' ' . wp_kses_data($maxinet_attr[3]) : '').'></a>' ;
				} else if (!empty($maxinet_logo_text)) {
					echo '<h1 class="logo_footer_text"><a href="'.esc_url(home_url('/')).'">' . esc_html($maxinet_logo_text) . '</a></h1>';
				}
				?>
			</div>
		</div>
		<?php
	}
}
?>