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
$maxinet_columns = empty($maxinet_blog_style[1]) ? 1 : max(1, $maxinet_blog_style[1]);
$maxinet_expanded = !maxinet_sidebar_present() && maxinet_is_on(maxinet_get_theme_option('expand_content'));
$maxinet_post_format = get_post_format();
$maxinet_post_format = empty($maxinet_post_format) ? 'standard' : str_replace('post-format-', '', $maxinet_post_format);
$maxinet_animation = maxinet_get_theme_option('blog_animation');

?><article id="post-<?php the_ID(); ?>" 
	<?php post_class( 'post_item post_layout_chess post_layout_chess_'.esc_attr($maxinet_columns).' post_format_'.esc_attr($maxinet_post_format) ); ?>
	<?php echo (!maxinet_is_off($maxinet_animation) ? ' data-animation="'.esc_attr(maxinet_get_animation_classes($maxinet_animation)).'"' : ''); ?>>

	<?php
	// Add anchor
	if ($maxinet_columns == 1 && shortcode_exists('trx_sc_anchor')) {
		echo do_shortcode('[trx_sc_anchor id="post_'.esc_attr(get_the_ID()).'" title="'.esc_attr(get_the_title()).'"]');
	}

	// Sticky label
	if ( is_sticky() && !is_paged() ) {
		?><span class="post_label label_sticky"></span><?php
	}

	// Featured image
	maxinet_show_post_featured( array(
											'class' => $maxinet_columns == 1 ? 'maxinet-full-height' : '',
											'show_no_image' => true,
											'thumb_bg' => true,
											'thumb_size' => maxinet_get_thumb_size(
																	strpos(maxinet_get_theme_option('body_style'), 'full')!==false
																		? ( $maxinet_columns > 1 ? 'huge' : 'original' )
																		: (	$maxinet_columns > 2 ? 'big' : 'huge')
																	)
											) 
										);

	?><div class="post_inner"><div class="post_inner_content"><?php 

		?><div class="post_header entry-header"><?php 
			do_action('maxinet_action_before_post_title'); 

			// Post title
			the_title( sprintf( '<h3 class="post_title entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h3>' );
			
			do_action('maxinet_action_before_post_meta'); 

			// Post meta
			$maxinet_components = maxinet_is_inherit(maxinet_get_theme_option_from_meta('meta_parts')) 
										? 'categories,date'.($maxinet_columns < 3 ? ',counters' : '').($maxinet_columns == 1 ? ',edit' : '')
										: maxinet_array_get_keys_by_value(maxinet_get_theme_option('meta_parts'));
			$maxinet_counters = maxinet_is_inherit(maxinet_get_theme_option_from_meta('counters')) 
										? 'comments'
										: maxinet_array_get_keys_by_value(maxinet_get_theme_option('counters'));
			$maxinet_post_meta = empty($maxinet_components) 
										? '' 
										: maxinet_show_post_meta(apply_filters('maxinet_filter_post_meta_args', array(
												'components' => $maxinet_components,
												'counters' => $maxinet_counters,
												'seo' => false,
												'echo' => false
												), $maxinet_blog_style[0], $maxinet_columns)
											);
			maxinet_show_layout($maxinet_post_meta);
		?></div><!-- .entry-header -->
	
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
				maxinet_show_layout($maxinet_post_meta);
			}
			// More button
			if ( $maxinet_show_learn_more ) {
				?><p><a class="more-link" href="<?php echo esc_url(get_permalink()); ?>"><?php esc_html_e('Read more', 'maxinet'); ?></a></p><?php
			}
			?>
		</div><!-- .entry-content -->

	</div></div><!-- .post_inner -->

</article>