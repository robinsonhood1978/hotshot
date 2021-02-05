<?php
/**
 * The template for homepage posts with "Portfolio" style
 *
 * @package WordPress
 * @subpackage MAXINET
 * @since MAXINET 1.0
 */

maxinet_storage_set('blog_archive', true);

get_header(); 

if (have_posts()) {

	maxinet_show_layout(get_query_var('blog_archive_start'));

	$maxinet_stickies = is_home() ? get_option( 'sticky_posts' ) : false;
	$maxinet_sticky_out = maxinet_get_theme_option('sticky_style')=='columns' 
							&& is_array($maxinet_stickies) && count($maxinet_stickies) > 0 && get_query_var( 'paged' ) < 1;
	
	// Show filters
	$maxinet_cat = maxinet_get_theme_option('parent_cat');
	$maxinet_post_type = maxinet_get_theme_option('post_type');
	$maxinet_taxonomy = maxinet_get_post_type_taxonomy($maxinet_post_type);
	$maxinet_show_filters = maxinet_get_theme_option('show_filters');
	$maxinet_tabs = array();
	if (!maxinet_is_off($maxinet_show_filters)) {
		$maxinet_args = array(
			'type'			=> $maxinet_post_type,
			'child_of'		=> $maxinet_cat,
			'orderby'		=> 'name',
			'order'			=> 'ASC',
			'hide_empty'	=> 1,
			'hierarchical'	=> 0,
			'exclude'		=> '',
			'include'		=> '',
			'number'		=> '',
			'taxonomy'		=> $maxinet_taxonomy,
			'pad_counts'	=> false
		);
		$maxinet_portfolio_list = get_terms($maxinet_args);
		if (is_array($maxinet_portfolio_list) && count($maxinet_portfolio_list) > 0) {
			$maxinet_tabs[$maxinet_cat] = esc_html__('All', 'maxinet');
			foreach ($maxinet_portfolio_list as $maxinet_term) {
				if (isset($maxinet_term->term_id)) $maxinet_tabs[$maxinet_term->term_id] = $maxinet_term->name;
			}
		}
	}
	if (count($maxinet_tabs) > 0) {
		$maxinet_portfolio_filters_ajax = true;
		$maxinet_portfolio_filters_active = $maxinet_cat;
		$maxinet_portfolio_filters_id = 'portfolio_filters';
		?>
		<div class="portfolio_filters maxinet_tabs maxinet_tabs_ajax">
			<ul class="portfolio_titles maxinet_tabs_titles">
				<?php
				foreach ($maxinet_tabs as $maxinet_id=>$maxinet_title) {
					?><li><a href="<?php echo esc_url(maxinet_get_hash_link(sprintf('#%s_%s_content', $maxinet_portfolio_filters_id, $maxinet_id))); ?>" data-tab="<?php echo esc_attr($maxinet_id); ?>"><?php echo esc_html($maxinet_title); ?></a></li><?php
				}
				?>
			</ul>
			<?php
			$maxinet_ppp = maxinet_get_theme_option('posts_per_page');
			if (maxinet_is_inherit($maxinet_ppp)) $maxinet_ppp = '';
			foreach ($maxinet_tabs as $maxinet_id=>$maxinet_title) {
				$maxinet_portfolio_need_content = $maxinet_id==$maxinet_portfolio_filters_active || !$maxinet_portfolio_filters_ajax;
				?>
				<div id="<?php echo esc_attr(sprintf('%s_%s_content', $maxinet_portfolio_filters_id, $maxinet_id)); ?>"
					class="portfolio_content maxinet_tabs_content"
					data-blog-template="<?php echo esc_attr(maxinet_storage_get('blog_template')); ?>"
					data-blog-style="<?php echo esc_attr(maxinet_get_theme_option('blog_style')); ?>"
					data-posts-per-page="<?php echo esc_attr($maxinet_ppp); ?>"
					data-post-type="<?php echo esc_attr($maxinet_post_type); ?>"
					data-taxonomy="<?php echo esc_attr($maxinet_taxonomy); ?>"
					data-cat="<?php echo esc_attr($maxinet_id); ?>"
					data-parent-cat="<?php echo esc_attr($maxinet_cat); ?>"
					data-need-content="<?php echo (false===$maxinet_portfolio_need_content ? 'true' : 'false'); ?>"
				>
					<?php
					if ($maxinet_portfolio_need_content) 
						maxinet_show_portfolio_posts(array(
							'cat' => $maxinet_id,
							'parent_cat' => $maxinet_cat,
							'taxonomy' => $maxinet_taxonomy,
							'post_type' => $maxinet_post_type,
							'page' => 1,
							'sticky' => $maxinet_sticky_out
							)
						);
					?>
				</div>
				<?php
			}
			?>
		</div>
		<?php
	} else {
		maxinet_show_portfolio_posts(array(
			'cat' => $maxinet_cat,
			'parent_cat' => $maxinet_cat,
			'taxonomy' => $maxinet_taxonomy,
			'post_type' => $maxinet_post_type,
			'page' => 1,
			'sticky' => $maxinet_sticky_out
			)
		);
	}

	maxinet_show_layout(get_query_var('blog_archive_end'));

} else {

	if ( is_search() )
		get_template_part( 'content', 'none-search' );
	else
		get_template_part( 'content', 'none-archive' );

}

get_footer();
?>