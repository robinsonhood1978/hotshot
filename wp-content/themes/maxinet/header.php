<?php
/**
 * The Header: Logo and main menu
 *
 * @package WordPress
 * @subpackage MAXINET
 * @since MAXINET 1.0
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js scheme_<?php
										 // Class scheme_xxx need in the <html> as context for the <body>!
										 echo esc_attr(maxinet_get_theme_option('color_scheme'));
										 ?>">
<head>
	<?php wp_head(); ?>
</head>

<body <?php	body_class(); ?>>
	<?php if ( function_exists( 'gtm4wp_the_gtm_tag' ) ) { gtm4wp_the_gtm_tag(); } ?>

	<?php do_action( 'maxinet_action_before_body' ); ?>

	<div class="body_wrap">

		<div class="page_wrap"><?php
			
			// Desktop header
			$maxinet_header_type = maxinet_get_theme_option("header_type");
			if ($maxinet_header_type == 'custom' && !maxinet_is_layouts_available())
				$maxinet_header_type = 'default';
			get_template_part( "templates/header-{$maxinet_header_type}");

			// Side menu
			if (in_array(maxinet_get_theme_option('menu_style'), array('left', 'right'))) {
				get_template_part( 'templates/header-navi-side' );
			}

			// Mobile header
			get_template_part( 'templates/header-mobile');
			?>

			<div class="page_content_wrap">

				<?php if (maxinet_get_theme_option('body_style') != 'fullscreen') { ?>
				<div class="content_wrap">
				<?php } ?>

					<?php
					// Widgets area above page content
					maxinet_create_widgets_area('widgets_above_page');
					?>				

					<div class="content">
						<?php
						// Widgets area inside page content
						maxinet_create_widgets_area('widgets_above_content');
						?>				
