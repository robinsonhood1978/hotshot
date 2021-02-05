<?php
/**
 * The template to display the socials in the footer
 *
 * @package WordPress
 * @subpackage MAXINET
 * @since MAXINET 1.0.10
 */


// Socials
if ( maxinet_is_on(maxinet_get_theme_option('socials_in_footer')) && ($maxinet_output = maxinet_get_socials_links()) != '') {
	?>
	<div class="footer_socials_wrap socials_wrap">
		<div class="footer_socials_inner">
			<?php maxinet_show_layout($maxinet_output); ?>
		</div>
	</div>
	<?php
}
?>