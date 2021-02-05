<?php
/**
 * The Classic template to display the content
 *
 * Used for index/archive/search.
 *
 * @package WordPress
 * @subpackage MAXINET
 * @since MAXINET 1.0
 */

$maxinet_blog_style = explode('_', maxinet_get_theme_option('blog_style'));
$maxinet_columns = empty($maxinet_blog_style[1]) ? 2 : max(2, $maxinet_blog_style[1]);
$maxinet_expanded = !maxinet_sidebar_present() && maxinet_is_on(maxinet_get_theme_option('expand_content'));
$maxinet_post_format = get_post_format();
$maxinet_post_format = empty($maxinet_post_format) ? 'standard' : str_replace('post-format-', '', $maxinet_post_format);
$maxinet_animation = maxinet_get_theme_option('blog_animation');
$maxinet_components = maxinet_is_inherit(maxinet_get_theme_option_from_meta('meta_parts')) 
							? 'categories,date,counters'.($maxinet_columns < 3 ? ',edit' : '')
							: maxinet_array_get_keys_by_value(maxinet_get_theme_option('meta_parts'));
$maxinet_counters = maxinet_is_inherit(maxinet_get_theme_option_from_meta('counters')) 
							? 'comments'
							: maxinet_array_get_keys_by_value(maxinet_get_theme_option('counters'));

?><div class="<?php echo esc_attr($maxinet_blog_style[0] == 'classic' ? 'column' : 'masonry_item masonry_item'); ?>-1_<?php echo esc_attr($maxinet_columns); ?>"><article id="post-<?php the_ID(); ?>"
	<?php post_class( 'post_item post_format_'.esc_attr($maxinet_post_format)
					. ' post_layout_classic post_layout_classic_'.esc_attr($maxinet_columns)
					. ' post_layout_'.esc_attr($maxinet_blog_style[0]) 
					. ' post_layout_'.esc_attr($maxinet_blog_style[0]).'_'.esc_attr($maxinet_columns)
					); ?>
	<?php echo (!maxinet_is_off($maxinet_animation) ? ' data-animation="'.esc_attr(maxinet_get_animation_classes($maxinet_animation)).'"' : ''); ?>>
	<?php

	// Sticky label
	if ( is_sticky() && !is_paged() ) {
		?><span class="post_label label_sticky"></span><?php
	}

	// Featured image
	maxinet_show_post_featured( array( 'thumb_size' => maxinet_get_thumb_size($maxinet_blog_style[0] == 'classic'
													? (strpos(maxinet_get_theme_option('body_style'), 'full')!==false 
															? ( $maxinet_columns > 2 ? 'big' : 'huge' )
															: (	$maxinet_columns > 2
																? ($maxinet_expanded ? 'big' : 'med')
																: ($maxinet_expanded ? 'huge' : 'big')
																)
														)
													: (strpos(maxinet_get_theme_option('body_style'), 'full')!==false 
															? ( $maxinet_columns > 2 ? 'masonry-big' : 'full' )
															: (	$maxinet_columns <= 2 && $maxinet_expanded ? 'masonry-big' : 'masonry-big')
														)
								) ) );

	if ( !in_array($maxinet_post_format, array('link', 'aside', 'status', 'quote')) ) {
		?>
		<div class="post_header entry-header">

			<div class="post_meta_before_title">
				<?php
					// Post meta
					if (!empty($maxinet_components))
						maxinet_show_post_meta(apply_filters('maxinet_filter_post_meta_args', array(
								'components' => $maxinet_components,
								'counters' => '',
								'seo' => false
							), $maxinet_blog_style[0], $maxinet_columns)
						);
					?>
				</div>
			<?php
			do_action('maxinet_action_before_post_title'); 

			// Post title
			the_title( sprintf( '<h4 class="post_title entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h4>' );

			do_action('maxinet_action_before_post_meta'); 

			// Post meta
			?>
			<div class="post_meta_after_title">
				<?php
				if (!empty($maxinet_components))
					maxinet_show_post_meta(apply_filters('maxinet_filter_post_meta_args', array(
						'components' => $maxinet_components,
						'counters' => $maxinet_counters,
						'seo' => false
						), $maxinet_blog_style[0], $maxinet_columns)
					);

				do_action('maxinet_action_after_post_meta');
				?>
			</div>
		</div><!-- .entry-header -->
		<?php
	}		
	?>

	<div class="post_content entry-content">
		<div class="post_content_inner">
			<?php
			$maxinet_show_learn_more = !in_array($maxinet_post_format, array('link', 'aside', 'status', 'quote'));
			if (has_excerpt()) {
				the_excerpt();
			} else if (strpos(get_the_content('!--more'), '!--more')!==false) {
				the_content( '' );
			} else if (in_array($maxinet_post_format, array('link', 'aside', 'status'))) {
				the_content();
			} else if ($maxinet_post_format == 'quote') {
				if (($quote = maxinet_get_tag(get_the_content(), '<blockquote>', '</blockquote>'))!='')
					maxinet_show_layout(wpautop($quote));
				else
					the_excerpt();
			} else if (substr(get_the_content(), 0, 1)!='[') {
				the_excerpt();
			}
			?>
		</div>
		<?php
		// Post meta
		if (in_array($maxinet_post_format, array('link', 'aside', 'status', 'quote'))) {
			if (!empty($maxinet_components))
				maxinet_show_post_meta(apply_filters('maxinet_filter_post_meta_args', array(
					'components' => $maxinet_components,
					'counters' => $maxinet_counters
					), $maxinet_blog_style[0], $maxinet_columns)
				);
		}
		// More button
		if ( $maxinet_show_learn_more ) {
			?><p><a class="more-link" href="<?php echo esc_url(get_permalink()); ?>"><?php esc_html_e('Read more', 'maxinet'); ?></a></p><?php
		}
		?>
	</div><!-- .entry-content -->

</article></div>