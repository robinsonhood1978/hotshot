<?php
/**
 * The Footer: widgets area, logo, footer menu and socials
 *
 * @package WordPress
 * @subpackage MAXINET
 * @since MAXINET 1.0
 */

						// Widgets area inside page content
						maxinet_create_widgets_area('widgets_below_content');
						?>				
					</div><!-- </.content> -->

					<?php
					// Show main sidebar
					get_sidebar();

					// Widgets area below page content
					maxinet_create_widgets_area('widgets_below_page');

					$maxinet_body_style = maxinet_get_theme_option('body_style');
					if ($maxinet_body_style != 'fullscreen') {
						?></div><!-- </.content_wrap> --><?php
					}
					?>
			</div><!-- </.page_content_wrap> -->

			<?php
			// Footer
			$maxinet_footer_type = maxinet_get_theme_option("footer_type");
			if ($maxinet_footer_type == 'custom' && !maxinet_is_layouts_available())
				$maxinet_footer_type = 'default';
			get_template_part( "templates/footer-{$maxinet_footer_type}");
			?>

		</div><!-- /.page_wrap -->

	</div><!-- /.body_wrap -->

	<?php if (maxinet_is_on(maxinet_get_theme_option('debug_mode')) && maxinet_get_file_dir('images/makeup.jpg')!='') { ?>
		<img src="<?php echo esc_url(maxinet_get_file_url('images/makeup.jpg')); ?>" id="makeup">
	<?php } ?>

	<?php wp_footer(); ?>

</body>
</html>