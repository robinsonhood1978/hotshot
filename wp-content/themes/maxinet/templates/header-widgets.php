<?php
/**
 * The template to display the widgets area in the header
 *
 * @package WordPress
 * @subpackage MAXINET
 * @since MAXINET 1.0
 */

// Header sidebar
$maxinet_header_name = maxinet_get_theme_option('header_widgets');
$maxinet_header_present = !maxinet_is_off($maxinet_header_name) && is_active_sidebar($maxinet_header_name);
if ($maxinet_header_present) { 
	maxinet_storage_set('current_sidebar', 'header');
	$maxinet_header_wide = maxinet_get_theme_option('header_wide');
	ob_start();
	if ( is_active_sidebar($maxinet_header_name) ) {
		dynamic_sidebar($maxinet_header_name);
	}
	$maxinet_widgets_output = ob_get_contents();
	ob_end_clean();
	if (!empty($maxinet_widgets_output)) {
		$maxinet_widgets_output = preg_replace("/<\/aside>[\r\n\s]*<aside/", "</aside><aside", $maxinet_widgets_output);
		$maxinet_need_columns = strpos($maxinet_widgets_output, 'columns_wrap')===false;
		if ($maxinet_need_columns) {
			$maxinet_columns = max(0, (int) maxinet_get_theme_option('header_columns'));
			if ($maxinet_columns == 0) $maxinet_columns = min(6, max(1, substr_count($maxinet_widgets_output, '<aside ')));
			if ($maxinet_columns > 1)
				$maxinet_widgets_output = preg_replace("/<aside([^>]*)class=\"widget/", "<aside$1class=\"column-1_".esc_attr($maxinet_columns).' widget', $maxinet_widgets_output);
			else
				$maxinet_need_columns = false;
		}
		?>
		<div class="header_widgets_wrap widget_area<?php echo !empty($maxinet_header_wide) ? ' header_fullwidth' : ' header_boxed'; ?>">
			<div class="header_widgets_inner widget_area_inner">
				<?php 
				if (!$maxinet_header_wide) { 
					?><div class="content_wrap"><?php
				}
				if ($maxinet_need_columns) {
					?><div class="columns_wrap"><?php
				}
				do_action( 'maxinet_action_before_sidebar' );
				maxinet_show_layout($maxinet_widgets_output);
				do_action( 'maxinet_action_after_sidebar' );
				if ($maxinet_need_columns) {
					?></div>	<!-- /.columns_wrap --><?php
				}
				if (!$maxinet_header_wide) {
					?></div>	<!-- /.content_wrap --><?php
				}
				?>
			</div>	<!-- /.header_widgets_inner -->
		</div>	<!-- /.header_widgets_wrap -->
		<?php
	}
}
?>