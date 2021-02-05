<?php
/**
 * The Sidebar containing the main widget areas.
 *
 * @package WordPress
 * @subpackage MAXINET
 * @since MAXINET 1.0
 */

if (maxinet_sidebar_present()) {
	ob_start();
	$maxinet_sidebar_name = maxinet_get_theme_option('sidebar_widgets');
	maxinet_storage_set('current_sidebar', 'sidebar');
	if ( is_active_sidebar($maxinet_sidebar_name) ) {
		dynamic_sidebar($maxinet_sidebar_name);
	}
	$maxinet_out = trim(ob_get_contents());
	ob_end_clean();
	if (!empty($maxinet_out)) {
		$maxinet_sidebar_position = maxinet_get_theme_option('sidebar_position');
		?>
		<div class="sidebar <?php echo esc_attr($maxinet_sidebar_position); ?> widget_area<?php if (!maxinet_is_inherit(maxinet_get_theme_option('sidebar_scheme'))) echo ' scheme_'.esc_attr(maxinet_get_theme_option('sidebar_scheme')); ?>" role="complementary">
			<div class="sidebar_inner">
				<?php
				do_action( 'maxinet_action_before_sidebar' );
				maxinet_show_layout(preg_replace("/<\/aside>[\r\n\s]*<aside/", "</aside><aside", $maxinet_out));
				do_action( 'maxinet_action_after_sidebar' );
				?>
			</div><!-- /.sidebar_inner -->
		</div><!-- /.sidebar -->
		<?php
	}
}
?>