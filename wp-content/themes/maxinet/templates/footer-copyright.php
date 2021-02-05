<?php
/**
 * The template to display the copyright info in the footer
 *
 * @package WordPress
 * @subpackage MAXINET
 * @since MAXINET 1.0.10
 */

// Copyright area
$maxinet_footer_scheme =  maxinet_is_inherit(maxinet_get_theme_option('footer_scheme')) ? maxinet_get_theme_option('color_scheme') : maxinet_get_theme_option('footer_scheme');
$maxinet_copyright_scheme = maxinet_is_inherit(maxinet_get_theme_option('copyright_scheme')) ? $maxinet_footer_scheme : maxinet_get_theme_option('copyright_scheme');
?> 
<div class="footer_copyright_wrap scheme_<?php echo esc_attr($maxinet_copyright_scheme); ?>">
	<div class="footer_copyright_inner">
		<div class="content_wrap">
			<div class="copyright_text"><?php
				// Replace {{...}} and ((...)) on the <i>...</i> and <b>...</b>
				$maxinet_copyright = maxinet_prepare_macros(maxinet_get_theme_option('copyright'));
				if (!empty($maxinet_copyright)) {
					// Replace {date_format} on the current date in the specified format
					if (preg_match("/(\\{[\\w\\d\\\\\\-\\:]*\\})/", $maxinet_copyright, $maxinet_matches)) {
						$maxinet_copyright = str_replace($maxinet_matches[1], date_i18n(str_replace(array('{', '}'), '', $maxinet_matches[1])), $maxinet_copyright);
					}
					// Display copyright
					echo wp_kses_data(nl2br($maxinet_copyright));
				}
			?></div>
		</div>
	</div>
</div>
