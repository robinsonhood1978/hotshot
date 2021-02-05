<?php
/**
 * The template to display the widgets area in the footer
 *
 * @package WordPress
 * @subpackage MAXINET
 * @since MAXINET 1.0.10
 */

// Footer sidebar
$maxinet_footer_name = maxinet_get_theme_option('footer_widgets');
$maxinet_footer_present = !maxinet_is_off($maxinet_footer_name) && is_active_sidebar($maxinet_footer_name);
if ($maxinet_footer_present) { 
	maxinet_storage_set('current_sidebar', 'footer');
	$maxinet_footer_wide = maxinet_get_theme_option('footer_wide');
	ob_start();
	if ( is_active_sidebar($maxinet_footer_name) ) {
		dynamic_sidebar($maxinet_footer_name);
	}
	$maxinet_out = trim(ob_get_contents());
	ob_end_clean();
	if (!empty($maxinet_out)) {
		$maxinet_out = preg_replace("/<\\/aside>[\r\n\s]*<aside/", "</aside><aside", $maxinet_out);
		$maxinet_need_columns = true;	//or check: strpos($maxinet_out, 'columns_wrap')===false;
		if ($maxinet_need_columns) {
			$maxinet_columns = max(0, (int) maxinet_get_theme_option('footer_columns'));
			if ($maxinet_columns == 0) $maxinet_columns = min(4, max(1, substr_count($maxinet_out, '<aside ')));
			if ($maxinet_columns > 1)
				$maxinet_out = preg_replace("/<aside([^>]*)class=\"widget/", "<aside$1class=\"column-1_".esc_attr($maxinet_columns).' widget', $maxinet_out);
			else
				$maxinet_need_columns = false;
		}
		?>
		<div class="footer_widgets_wrap widget_area<?php echo !empty($maxinet_footer_wide) ? ' footer_fullwidth' : ''; ?> sc_layouts_row  sc_layouts_row_type_normal">
			<div class="footer_widgets_inner widget_area_inner">
				<?php 
				if (!$maxinet_footer_wide) { 
					?><div class="content_wrap"><?php
				}
				if ($maxinet_need_columns) {
					?><div class="columns_wrap"><?php
				}
				do_action( 'maxinet_action_before_sidebar' );
				maxinet_show_layout($maxinet_out);
				do_action( 'maxinet_action_after_sidebar' );
				if ($maxinet_need_columns) {
					?></div><!-- /.columns_wrap --><?php
				}
				if (!$maxinet_footer_wide) {
					?></div><!-- /.content_wrap --><?php
				}
				?>
			</div><!-- /.footer_widgets_inner -->
		</div><!-- /.footer_widgets_wrap -->
		<?php
	}
}
?>