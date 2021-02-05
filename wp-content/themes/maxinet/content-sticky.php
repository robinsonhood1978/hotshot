<?php
/**
 * The Sticky template to display the sticky posts
 *
 * Used for index/archive
 *
 * @package WordPress
 * @subpackage MAXINET
 * @since MAXINET 1.0
 */

$maxinet_columns = max(1, min(3, count(get_option( 'sticky_posts' ))));
$maxinet_post_format = get_post_format();
$maxinet_post_format = empty($maxinet_post_format) ? 'standard' : str_replace('post-format-', '', $maxinet_post_format);
$maxinet_animation = maxinet_get_theme_option('blog_animation');

?><div class="column-1_<?php echo esc_attr($maxinet_columns); ?>"><article id="post-<?php the_ID(); ?>" 
	<?php post_class( 'post_item post_layout_sticky post_format_'.esc_attr($maxinet_post_format) ); ?>
	<?php echo (!maxinet_is_off($maxinet_animation) ? ' data-animation="'.esc_attr(maxinet_get_animation_classes($maxinet_animation)).'"' : ''); ?>
	>

	<?php
	if ( is_sticky() && is_home() && !is_paged() ) {
		?><span class="post_label label_sticky"></span><?php
	}

	// Featured image
	maxinet_show_post_featured(array(
		'thumb_size' => maxinet_get_thumb_size($maxinet_columns==1 ? 'big' : ($maxinet_columns==2 ? 'med' : 'avatar'))
	));

	if ( !in_array($maxinet_post_format, array('link', 'aside', 'status', 'quote')) ) {
		?>
		<div class="post_header entry-header">
			<?php
			// Post title
			the_title( sprintf( '<h6 class="post_title entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h6>' );
			// Post meta
			maxinet_show_post_meta(apply_filters('maxinet_filter_post_meta_args', array(), 'sticky', $maxinet_columns));
			?>
		</div><!-- .entry-header -->
		<?php
	}
	?>
</article></div>