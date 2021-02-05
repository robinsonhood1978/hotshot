<?php
/**
 * The template to display the page title and breadcrumbs
 *
 * @package WordPress
 * @subpackage MAXINET
 * @since MAXINET 1.0
 */

// Page (category, tag, archive, author) title

if ( maxinet_need_page_title() ) {
	maxinet_sc_layouts_showed('title', true);
	maxinet_sc_layouts_showed('postmeta', true);
	?>
	<div class="top_panel_title sc_layouts_row sc_layouts_row_type_normal">
		<div class="content_wrap">
			<div class="sc_layouts_column sc_layouts_column_align_center">
				<div class="sc_layouts_item">
					<div class="sc_layouts_title sc_align_center">
						<?php
						// Post meta on the single post
						if ( is_single() )  {
							?><div class="sc_layouts_title_meta"><?php
								maxinet_show_post_meta(apply_filters('maxinet_filter_post_meta_args', array(
									'components' => '',
									'counters' => '',
									'seo' => true
									), 'header', 1)
								);
							?></div><?php
						}
						
						// Blog/Post title
						?><div class="sc_layouts_title_title"><?php
							$maxinet_blog_title = maxinet_get_blog_title();
							$maxinet_blog_title_text = $maxinet_blog_title_class = $maxinet_blog_title_link = $maxinet_blog_title_link_text = '';
							if (is_array($maxinet_blog_title)) {
								$maxinet_blog_title_text = $maxinet_blog_title['text'];
								$maxinet_blog_title_class = !empty($maxinet_blog_title['class']) ? ' '.$maxinet_blog_title['class'] : '';
								$maxinet_blog_title_link = !empty($maxinet_blog_title['link']) ? $maxinet_blog_title['link'] : '';
								$maxinet_blog_title_link_text = !empty($maxinet_blog_title['link_text']) ? $maxinet_blog_title['link_text'] : '';
							} else
								$maxinet_blog_title_text = $maxinet_blog_title;
							?>
							<h1 itemprop="headline" class="sc_layouts_title_caption<?php echo esc_attr($maxinet_blog_title_class); ?>"><?php
								$maxinet_top_icon = maxinet_get_category_icon();
								if (!empty($maxinet_top_icon)) {
									$maxinet_attr = maxinet_getimagesize($maxinet_top_icon);
									?><img src="<?php echo esc_url($maxinet_top_icon); ?>" alt="header_top_icon" <?php if (!empty($maxinet_attr[3])) maxinet_show_layout($maxinet_attr[3]);?>><?php
								}
								echo wp_kses_data($maxinet_blog_title_text);
							?></h1>
							<?php
							if (!empty($maxinet_blog_title_link) && !empty($maxinet_blog_title_link_text)) {
								?><a href="<?php echo esc_url($maxinet_blog_title_link); ?>" class="theme_button theme_button_small sc_layouts_title_link"><?php echo esc_html($maxinet_blog_title_link_text); ?></a><?php
							}
							
							// Category/Tag description
							if ( is_category() || is_tag() || is_tax() ) 
								the_archive_description( '<div class="sc_layouts_title_description">', '</div>' );
		
						?></div><?php
	
						// Breadcrumbs
						?><div class="sc_layouts_title_breadcrumbs"><?php
							do_action( 'maxinet_action_breadcrumbs');
						?></div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php
}
?>