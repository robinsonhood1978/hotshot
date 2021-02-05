<?php
/* Woocommerce support functions
------------------------------------------------------------------------------- */

// Theme init priorities:
// 1 - register filters, that add/remove lists items for the Theme Options
if (!function_exists('maxinet_woocommerce_theme_setup1')) {
	add_action( 'after_setup_theme', 'maxinet_woocommerce_theme_setup1', 1 );
	function maxinet_woocommerce_theme_setup1() {

		add_theme_support( 'woocommerce' );

		// Next setting from the WooCommerce 3.0+ enable built-in image zoom on the single product page
		add_theme_support( 'wc-product-gallery-zoom' );

		// Next setting from the WooCommerce 3.0+ enable built-in image slider on the single product page
		add_theme_support( 'wc-product-gallery-slider' );

		// Next setting from the WooCommerce 3.0+ enable built-in image lightbox on the single product page
		add_theme_support( 'wc-product-gallery-lightbox' );

		add_filter( 'maxinet_filter_list_sidebars', 	'maxinet_woocommerce_list_sidebars' );
		add_filter( 'maxinet_filter_list_posts_types',	'maxinet_woocommerce_list_post_types');
	}
}

// Theme init priorities:
// 3 - add/remove Theme Options elements
if (!function_exists('maxinet_woocommerce_theme_setup3')) {
	add_action( 'after_setup_theme', 'maxinet_woocommerce_theme_setup3', 3 );
	function maxinet_woocommerce_theme_setup3() {
		if (maxinet_exists_woocommerce()) {

			// Section 'WooCommerce'
			maxinet_storage_set_array_before('options', 'fonts', array_merge(
				array(
					'shop' => array(
						"title" => esc_html__('Shop', 'maxinet'),
						"desc" => wp_kses_data( __('Select parameters to display the shop pages', 'maxinet') ),
						"priority" => 80,
						"type" => "section"
						),

					'products_info_shop' => array(
						"title" => esc_html__('Products list', 'maxinet'),
						"desc" => '',
						"type" => "info",
						),
					'posts_per_page_shop' => array(
						"title" => esc_html__('Products per page', 'maxinet'),
						"desc" => wp_kses_data( __('How many products should be displayed on the shop page. If empty - use global value from the menu Settings - Reading', 'maxinet') ),
						"std" => '',
						"type" => "text"
						),
					'blog_columns_shop' => array(
						"title" => esc_html__('Shop loop columns', 'maxinet'),
						"desc" => wp_kses_data( __('How many columns should be used in the shop loop?', 'maxinet') ),
						"std" => 2,
						"options" => maxinet_get_list_range(2,3),
						"type" => "select"
						),
					'shop_mode' => array(
						"title" => esc_html__('Shop mode', 'maxinet'),
						"desc" => wp_kses_data( __('Select style for the products list', 'maxinet') ),
						"std" => 'thumbs',
						"options" => array(
							'thumbs'=> esc_html__('Thumbnails', 'maxinet'),
							'list'	=> esc_html__('List', 'maxinet'),
						),
						"type" => "select"
						),
					'shop_hover' => array(
						"title" => esc_html__('Hover style', 'maxinet'),
						"desc" => wp_kses_data( __('Hover style on the products in the shop archive', 'maxinet') ),
						"std" => 'none',
						"options" => apply_filters('maxinet_filter_shop_hover', array(
							'none' => esc_html__('None', 'maxinet'),
							'shop' => esc_html__('Icons', 'maxinet'),
							'shop_buttons' => esc_html__('Buttons', 'maxinet')
						)),
						"type" => "select"
						),

					'single_info_shop' => array(
						"title" => esc_html__('Single product', 'maxinet'),
						"desc" => '',
						"type" => "info",
						),
					'stretch_tabs_area' => array(
						"title" => esc_html__('Stretch tabs area', 'maxinet'),
						"desc" => wp_kses_data( __('Stretch area with tabs on the single product to the screen width if the sidebar is hidden', 'maxinet') ),
						"std" => 1,
						"type" => "checkbox"
						),
					'show_related_posts_shop' => array(
						"title" => esc_html__('Show related products', 'maxinet'),
						"desc" => wp_kses_data( __("Show section 'Related products' on the single product page", 'maxinet') ),
						"std" => 1,
						"type" => "checkbox"
						),
					'related_posts_shop' => array(
						"title" => esc_html__('Related products', 'maxinet'),
						"desc" => wp_kses_data( __('How many related products should be displayed on the single product page?', 'maxinet') ),
						"dependency" => array(
							'show_related_posts_shop' => array(1)
						),
						"std" => 3,
						"options" => maxinet_get_list_range(1,9),
						"type" => "select"
						),
					'related_columns_shop' => array(
						"title" => esc_html__('Related columns', 'maxinet'),
						"desc" => wp_kses_data( __('How many columns should be used to output related products on the single product page?', 'maxinet') ),
						"dependency" => array(
							'show_related_posts_shop' => array(1)
						),
						"std" => 3,
						"options" => maxinet_get_list_range(1,4),
						"type" => "select"
						)
				),
				maxinet_options_get_list_cpt_options('shop')
			));
		}
	}
}


// Add section 'Products' to the Front Page option
if (!function_exists('maxinet_woocommerce_front_page_options')) {
	if (!MAXINET_THEME_FREE) add_filter( 'maxinet_filter_front_page_options', 'maxinet_woocommerce_front_page_options' );
	function maxinet_woocommerce_front_page_options($options) {
		if (maxinet_exists_woocommerce()) {

			$options['front_page_sections']['std'] .= (!empty($options['front_page_sections']['std']) ? '|' : '') . 'woocommerce=1';
			$options['front_page_sections']['options'] = array_merge($options['front_page_sections']['options'],
																	array(
																		'woocommerce' => esc_html__('Products', 'maxinet')
																		)
																	);
			$options = array_merge($options, array(

				// Front Page Sections - WooCommerce
				'front_page_woocommerce' => array(
					"title" => esc_html__('Products', 'maxinet'),
					"desc" => '',
					"priority" => 200,
					"type" => "section",
					),
				'front_page_woocommerce_layout_info' => array(
					"title" => esc_html__('Layout', 'maxinet'),
					"desc" => '',
					"type" => "info",
					),
				'front_page_woocommerce_fullheight' => array(
					"title" => esc_html__('Full height', 'maxinet'),
					"desc" => wp_kses_data( __('Stretch this section to the window height', 'maxinet') ),
					"std" => 0,
					"refresh" => false,
					"type" => "checkbox"
					),
				'front_page_woocommerce_paddings' => array(
					"title" => esc_html__('Paddings', 'maxinet'),
					"desc" => wp_kses_data( __('Select paddings inside this section', 'maxinet') ),
					"std" => 'medium',
					"options" => maxinet_get_list_paddings(),
					"refresh" => false,
					"type" => "switch"
					),
				'front_page_woocommerce_heading_info' => array(
					"title" => esc_html__('Title', 'maxinet'),
					"desc" => '',
					"type" => "info",
					),
				'front_page_woocommerce_caption' => array(
					"title" => esc_html__('Section title', 'maxinet'),
					"desc" => '',
					"refresh" => false,
					"std" => wp_kses_data(__('This text can be changed in the section "Products"', 'maxinet')),
					"type" => "text"
					),
				'front_page_woocommerce_description' => array(
					"title" => esc_html__('Description', 'maxinet'),
					"desc" => wp_kses_data( __("Short description after the section's title", 'maxinet') ),
					"refresh" => false,
					"std" => wp_kses_data(__('This text can be changed in the section "Products"', 'maxinet')),
					"type" => "textarea"
					),
				'front_page_woocommerce_products_info' => array(
					"title" => esc_html__('Products parameters', 'maxinet'),
					"desc" => '',
					"type" => "info",
					),
				'front_page_woocommerce_products' => array(
					"title" => esc_html__('Type of the products', 'maxinet'),
					"desc" => '',
					"std" => 'products',
					"options" => array(
									'recent_products' => esc_html__('Recent products', 'maxinet'),
									'featured_products' => esc_html__('Featured products', 'maxinet'),
									'top_rated_products' => esc_html__('Top rated products', 'maxinet'),
									'sale_products' => esc_html__('Sale products', 'maxinet'),
									'best_selling_products' => esc_html__('Best selling products', 'maxinet'),
									'product_category' => esc_html__('Products from categories', 'maxinet'),
									'products' => esc_html__('Products by IDs', 'maxinet')
									),
					"type" => "select"
					),
				'front_page_woocommerce_products_categories' => array(
					"title" => esc_html__('Categories', 'maxinet'),
					"desc" => esc_html__('Comma separated category slugs. Used only with "Products from categories"', 'maxinet'),
					"dependency" => array(
						'front_page_woocommerce_products' => array('product_category')
					),
					"std" => '',
					"type" => "text"
					),
				'front_page_woocommerce_products_per_page' => array(
					"title" => esc_html__('Per page', 'maxinet'),
					"desc" => wp_kses_data( __('How many products will be displayed on the page. Attention! For "Products by IDs" specify comma separated list of the IDs', 'maxinet') ),
					"std" => 3,
					"type" => "text"
					),
				'front_page_woocommerce_products_columns' => array(
					"title" => esc_html__('Columns', 'maxinet'),
					"desc" => wp_kses_data( __("How many columns will be used", 'maxinet') ),
					"std" => 3,
					"type" => "text"
					),
				'front_page_woocommerce_products_orderby' => array(
					"title" => esc_html__('Order by', 'maxinet'),
					"desc" => wp_kses_data( __("Not used with Best selling products", 'maxinet') ),
					"std" => 'date',
					"options" => array(
									'date' => esc_html__('Date', 'maxinet'),
									'title' => esc_html__('Title', 'maxinet')
									),
					"type" => "switch"
					),
				'front_page_woocommerce_products_order' => array(
					"title" => esc_html__('Order', 'maxinet'),
					"desc" => wp_kses_data( __("Not used with Best selling products", 'maxinet') ),
					"std" => 'desc',
					"options" => array(
									'asc' => esc_html__('Ascending', 'maxinet'),
									'desc' => esc_html__('Descending', 'maxinet')
									),
					"type" => "switch"
					),
				'front_page_woocommerce_color_info' => array(
					"title" => esc_html__('Colors and images', 'maxinet'),
					"desc" => '',
					"type" => "info",
					),
				'front_page_woocommerce_scheme' => array(
					"title" => esc_html__('Color scheme', 'maxinet'),
					"desc" => wp_kses_data( __('Color scheme for this section', 'maxinet') ),
					"std" => 'inherit',
					"options" => array(),
					"refresh" => false,
					"type" => "switch"
					),
				'front_page_woocommerce_bg_image' => array(
					"title" => esc_html__('Background image', 'maxinet'),
					"desc" => wp_kses_data( __('Select or upload background image for this section', 'maxinet') ),
					"refresh" => '.front_page_section_woocommerce',
					"refresh_wrapper" => true,
					"std" => '',
					"type" => "image"
					),
				'front_page_woocommerce_bg_color' => array(
					"title" => esc_html__('Background color', 'maxinet'),
					"desc" => wp_kses_data( __('Background color for this section', 'maxinet') ),
					"std" => '',
					"refresh" => false,
					"type" => "color"
					),
				'front_page_woocommerce_bg_mask' => array(
					"title" => esc_html__('Background mask', 'maxinet'),
					"desc" => wp_kses_data( __('Use Background color as section mask with specified opacity. If 0 - mask is not used', 'maxinet') ),
					"std" => 1,
					"max" => 1,
					"step" => 0.1,
					"refresh" => false,
					"type" => "slider"
					),
				'front_page_woocommerce_anchor_info' => array(
					"title" => esc_html__('Anchor', 'maxinet'),
					"desc" => wp_kses_data( __('You can select icon and/or specify a text to create anchor for this section and show it in the side menu (if selected in the section "Header - Menu".', 'maxinet'))
								. '<br>'
								. wp_kses_data(__('Attention! Anchors available only if plugin "ThemeREX Addons is installed and activated!', 'maxinet')),
					"type" => "info",
					),
				'front_page_woocommerce_anchor_icon' => array(
					"title" => esc_html__('Anchor icon', 'maxinet'),
					"desc" => '',
					"std" => '',
					"type" => "icon"
					),
				'front_page_woocommerce_anchor_text' => array(
					"title" => esc_html__('Anchor text', 'maxinet'),
					"desc" => '',
					"std" => '',
					"type" => "text"
					)
			));
		}
		return $options;
	}
}

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if (!function_exists('maxinet_woocommerce_theme_setup9')) {
	add_action( 'after_setup_theme', 'maxinet_woocommerce_theme_setup9', 9 );
	function maxinet_woocommerce_theme_setup9() {

		if (maxinet_exists_woocommerce()) {
			add_action( 'wp_enqueue_scripts', 								'maxinet_woocommerce_frontend_scripts', 1100 );
			add_filter( 'maxinet_filter_merge_styles',						'maxinet_woocommerce_merge_styles' );
			add_filter( 'maxinet_filter_merge_scripts',						'maxinet_woocommerce_merge_scripts');
			add_filter( 'maxinet_filter_get_post_info',		 				'maxinet_woocommerce_get_post_info');
			add_filter( 'maxinet_filter_post_type_taxonomy',				'maxinet_woocommerce_post_type_taxonomy', 10, 2 );
			add_action( 'maxinet_action_override_theme_options',			'maxinet_woocommerce_override_theme_options');
			if (!is_admin()) {
				add_filter( 'maxinet_filter_detect_blog_mode',				'maxinet_woocommerce_detect_blog_mode');
				add_filter( 'maxinet_filter_get_post_categories', 			'maxinet_woocommerce_get_post_categories');
				add_filter( 'maxinet_filter_allow_override_header_image',	'maxinet_woocommerce_allow_override_header_image');
				add_filter( 'maxinet_filter_get_blog_title',				'maxinet_woocommerce_get_blog_title');
				add_action( 'maxinet_action_before_post_meta',				'maxinet_woocommerce_action_before_post_meta');
				add_action( 'pre_get_posts',								'maxinet_woocommerce_pre_get_posts');
				add_filter( 'maxinet_filter_localize_script',				'maxinet_woocommerce_localize_script');
			}
		}
		if (is_admin()) {
			add_filter( 'maxinet_filter_tgmpa_required_plugins',			'maxinet_woocommerce_tgmpa_required_plugins' );
		}

		// Add wrappers and classes to the standard WooCommerce output
		if (maxinet_exists_woocommerce()) {

			// Remove WOOC sidebar
			remove_action( 'woocommerce_sidebar', 						'woocommerce_get_sidebar', 10 );

			// Remove link around product item
			remove_action('woocommerce_before_shop_loop_item',			'woocommerce_template_loop_product_link_open', 10);
			remove_action('woocommerce_after_shop_loop_item',			'woocommerce_template_loop_product_link_close', 5);

			// Remove add_to_cart button

			// Remove link around product category
			remove_action('woocommerce_before_subcategory',				'woocommerce_template_loop_category_link_open', 10);
			remove_action('woocommerce_after_subcategory',				'woocommerce_template_loop_category_link_close', 10);

			// Open main content wrapper - <article>
			remove_action( 'woocommerce_before_main_content',			'woocommerce_output_content_wrapper', 10);
			add_action(    'woocommerce_before_main_content',			'maxinet_woocommerce_wrapper_start', 10);
			// Close main content wrapper - </article>
			remove_action( 'woocommerce_after_main_content',			'woocommerce_output_content_wrapper_end', 10);
			add_action(    'woocommerce_after_main_content',			'maxinet_woocommerce_wrapper_end', 10);

			// Close header section
			add_action(    'woocommerce_after_main_content',			'maxinet_woocommerce_archive_description', 1);
			add_action(    'woocommerce_before_shop_loop',				'maxinet_woocommerce_archive_description', 5 );
			add_action(    'woocommerce_no_products_found',				'maxinet_woocommerce_archive_description', 5 );

			// Add theme specific search form
			add_filter(    'get_product_search_form',					'maxinet_woocommerce_get_product_search_form' );

			// Change text on 'Add to cart' button
			add_filter(    'woocommerce_product_add_to_cart_text',		'maxinet_woocommerce_add_to_cart_text' );
			add_filter(    'woocommerce_product_single_add_to_cart_text','maxinet_woocommerce_add_to_cart_text' );

			// Add list mode buttons
			add_action(    'woocommerce_before_shop_loop', 				'maxinet_woocommerce_before_shop_loop', 10 );

			// Set columns number for the products loop
			add_filter(    'loop_shop_columns',							'maxinet_woocommerce_loop_shop_columns' );
			add_filter(    'post_class',								'maxinet_woocommerce_loop_shop_columns_class' );
			add_filter(    'product_cat_class',							'maxinet_woocommerce_loop_shop_columns_class', 10, 3 );
			// Open product/category item wrapper
			add_action(    'woocommerce_before_subcategory_title',		'maxinet_woocommerce_item_wrapper_start', 9 );
			add_action(    'woocommerce_before_shop_loop_item_title',	'maxinet_woocommerce_item_wrapper_start', 9 );
			// Close featured image wrapper and open title wrapper
			add_action(    'woocommerce_before_subcategory_title',		'maxinet_woocommerce_title_wrapper_start', 20 );
			add_action(    'woocommerce_before_shop_loop_item_title',	'maxinet_woocommerce_title_wrapper_start', 20 );

			// Add tags before title
			//add_action(    'woocommerce_before_shop_loop_item_title',	'maxinet_woocommerce_title_tags', 30 );

			// Wrap product title into link
			add_action(    'the_title',									'maxinet_woocommerce_the_title');
			// Wrap category title to the link
			// Old way: before WooCommerce 3.2.2
			//add_filter(	'woocommerce_shop_loop_subcategory_title',  'basekit_woocommerce_shop_loop_subcategory_title', 9, 1);
			// New way: WooCommerce 3.2.2+
			add_action(		'woocommerce_before_subcategory_title',		'maxinet_woocommerce_before_subcategory_title', 22, 1 );
			add_action(		'woocommerce_after_subcategory_title',		'maxinet_woocommerce_after_subcategory_title', 2, 1 );

			// Close title wrapper and add description in the list mode
			add_action(    'woocommerce_after_shop_loop_item_title',	'maxinet_woocommerce_title_wrapper_end', 7);
			add_action(    'woocommerce_after_subcategory_title',		'maxinet_woocommerce_title_wrapper_end2', 10 );
			// Close product/category item wrapper
			add_action(    'woocommerce_after_subcategory',				'maxinet_woocommerce_item_wrapper_end', 20 );
			add_action(    'woocommerce_after_shop_loop_item',			'maxinet_woocommerce_item_wrapper_end', 20 );

			// Add product ID into product meta section (after categories and tags)
			add_action(    'woocommerce_product_meta_end',				'maxinet_woocommerce_show_product_id', 10);

			// Set columns number for the product's thumbnails
			add_filter(    'woocommerce_product_thumbnails_columns',	'maxinet_woocommerce_product_thumbnails_columns' );

			// Decorate price
			add_filter(    'woocommerce_get_price_html',				'maxinet_woocommerce_get_price_html' );


			// Detect current shop mode
			if (!is_admin()) {
				$shop_mode = maxinet_get_value_gpc('maxinet_shop_mode');
				if (empty($shop_mode) && maxinet_check_theme_option('shop_mode'))
					$shop_mode = maxinet_get_theme_option('shop_mode');
				if (empty($shop_mode))
					$shop_mode = 'thumbs';
				maxinet_storage_set('shop_mode', $shop_mode);
			}
		}
	}
}

// Theme init priorities:
// Action 'wp'
// 1 - detect override mode. Attention! Only after this step you can use overriden options (separate values for the shop, courses, etc.)
if (!function_exists('maxinet_woocommerce_theme_setup_wp')) {
	add_action( 'wp', 'maxinet_woocommerce_theme_setup_wp' );
	function maxinet_woocommerce_theme_setup_wp() {
		if (maxinet_exists_woocommerce()) {
			// Set columns number for the related products
			if ((int) maxinet_get_theme_option('show_related_posts') == 0 || (int) maxinet_get_theme_option('related_posts') == 0) {
				remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
			} else {
				add_filter(    'woocommerce_output_related_products_args',	'maxinet_woocommerce_output_related_products_args' );
				add_filter(    'woocommerce_related_products_columns',		'maxinet_woocommerce_related_products_columns' );
			}
		}
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'maxinet_woocommerce_tgmpa_required_plugins' ) ) {
	//Handler of the add_filter('maxinet_filter_tgmpa_required_plugins',	'maxinet_woocommerce_tgmpa_required_plugins');
	function maxinet_woocommerce_tgmpa_required_plugins($list=array()) {
		if (maxinet_storage_isset('required_plugins', 'woocommerce')) {
			$list[] = array(
					'name' 		=> maxinet_storage_get_array('required_plugins', 'woocommerce'),
					'slug' 		=> 'woocommerce',
					'required' 	=> false
				);
		}
		return $list;
	}
}


// Check if WooCommerce installed and activated
if ( !function_exists( 'maxinet_exists_woocommerce' ) ) {
	function maxinet_exists_woocommerce() {
		return class_exists('Woocommerce');
		//return function_exists('is_woocommerce');
	}
}

// Return true, if current page is any woocommerce page
if ( !function_exists( 'maxinet_is_woocommerce_page' ) ) {
	function maxinet_is_woocommerce_page() {
		$rez = false;
		if (maxinet_exists_woocommerce())
			$rez = is_woocommerce() || is_shop() || is_product() || is_product_category() || is_product_tag() || is_product_taxonomy() || is_cart() || is_checkout() || is_account_page();
		return $rez;
	}
}

// Detect current blog mode
if ( !function_exists( 'maxinet_woocommerce_detect_blog_mode' ) ) {
	//Handler of the add_filter( 'maxinet_filter_detect_blog_mode', 'maxinet_woocommerce_detect_blog_mode' );
	function maxinet_woocommerce_detect_blog_mode($mode='') {
		if (is_shop() || is_product_category() || is_product_tag() || is_product_taxonomy())
			$mode = 'shop';
		else if (is_product() || is_cart() || is_checkout() || is_account_page())
			$mode = 'shop';
		return $mode;
	}
}

// Override options with stored page meta on 'Shop' pages
if ( !function_exists('maxinet_woocommerce_override_theme_options') ) {
	//Handler of the add_action( 'maxinet_action_override_theme_options', 'maxinet_woocommerce_override_theme_options');
	function maxinet_woocommerce_override_theme_options() {
		// Remove ' || is_product()' from the condition in the next row
		// if you don't need to override theme options from the page 'Shop' on single products
		if (is_shop() || is_product_category() || is_product_tag() || is_product_taxonomy() || is_product()) {
			if (($id = maxinet_woocommerce_get_shop_page_id()) > 0)
				maxinet_storage_set('options_meta', get_post_meta($id, 'maxinet_options', true));
		}
	}
}

// Return current page title
if ( !function_exists( 'maxinet_woocommerce_get_blog_title' ) ) {
	//Handler of the add_filter( 'maxinet_filter_get_blog_title', 'maxinet_woocommerce_get_blog_title');
	function maxinet_woocommerce_get_blog_title($title='') {
		if (!maxinet_exists_trx_addons() && maxinet_exists_woocommerce() && maxinet_is_woocommerce_page() && is_shop()) {
			$id = maxinet_woocommerce_get_shop_page_id();
			$title = $id ? get_the_title($id) : esc_html__('Shop', 'maxinet');
		}
		return $title;
	}
}


// Return taxonomy for current post type
if ( !function_exists( 'maxinet_woocommerce_post_type_taxonomy' ) ) {
	//Handler of the add_filter( 'maxinet_filter_post_type_taxonomy',	'maxinet_woocommerce_post_type_taxonomy', 10, 2 );
	function maxinet_woocommerce_post_type_taxonomy($tax='', $post_type='') {
		if ($post_type == 'product')
			$tax = 'product_cat';
		return $tax;
	}
}

// Return true if page title section is allowed
if ( !function_exists( 'maxinet_woocommerce_allow_override_header_image' ) ) {
	//Handler of the add_filter( 'maxinet_filter_allow_override_header_image', 'maxinet_woocommerce_allow_override_header_image' );
	function maxinet_woocommerce_allow_override_header_image($allow=true) {
		return is_product() ? false : $allow;
	}
}

// Return shop page ID
if ( !function_exists( 'maxinet_woocommerce_get_shop_page_id' ) ) {
	function maxinet_woocommerce_get_shop_page_id() {
		return get_option('woocommerce_shop_page_id');
	}
}

// Return shop page link
if ( !function_exists( 'maxinet_woocommerce_get_shop_page_link' ) ) {
	function maxinet_woocommerce_get_shop_page_link() {
		$url = '';
		$id = maxinet_woocommerce_get_shop_page_id();
		if ($id) $url = get_permalink($id);
		return $url;
	}
}

// Show categories of the current product
if ( !function_exists( 'maxinet_woocommerce_get_post_categories' ) ) {
	//Handler of the add_filter( 'maxinet_filter_get_post_categories', 		'maxinet_woocommerce_get_post_categories');
	function maxinet_woocommerce_get_post_categories($cats='') {
		if (get_post_type()=='product') {
			$cats = maxinet_get_post_terms(', ', get_the_ID(), 'product_cat');
		}
		return $cats;
	}
}

// Add 'product' to the list of the supported post-types
if ( !function_exists( 'maxinet_woocommerce_list_post_types' ) ) {
	//Handler of the add_filter( 'maxinet_filter_list_posts_types', 'maxinet_woocommerce_list_post_types');
	function maxinet_woocommerce_list_post_types($list=array()) {
		$list['product'] = esc_html__('Products', 'maxinet');
		return $list;
	}
}

// Show price of the current product in the widgets and search results
if ( !function_exists( 'maxinet_woocommerce_get_post_info' ) ) {
	//Handler of the add_filter( 'maxinet_filter_get_post_info', 'maxinet_woocommerce_get_post_info');
	function maxinet_woocommerce_get_post_info($post_info='') {
		if (get_post_type()=='product') {
			global $product;
			if ( $price_html = $product->get_price_html() ) {
				$post_info = '<div class="post_price product_price price">' . trim($price_html) . '</div>' . $post_info;
			}
		}
		return $post_info;
	}
}

// Show price of the current product in the search results streampage
if ( !function_exists( 'maxinet_woocommerce_action_before_post_meta' ) ) {
	//Handler of the add_action( 'maxinet_action_before_post_meta', 'maxinet_woocommerce_action_before_post_meta');
	function maxinet_woocommerce_action_before_post_meta() {
		if (!is_single() && get_post_type()=='product') {
			global $product;
			if ( $price_html = $product->get_price_html() ) {
				?><div class="post_price product_price price"><?php maxinet_show_layout($price_html); ?></div><?php
			}
		}
	}
}

// Enqueue WooCommerce custom styles
if ( !function_exists( 'maxinet_woocommerce_frontend_scripts' ) ) {
	//Handler of the add_action( 'wp_enqueue_scripts', 'maxinet_woocommerce_frontend_scripts', 1100 );
	function maxinet_woocommerce_frontend_scripts() {
			if (maxinet_is_on(maxinet_get_theme_option('debug_mode')) && maxinet_get_file_dir('plugins/woocommerce/woocommerce.css')!='')
				wp_enqueue_style( 'woocommerce',  maxinet_get_file_url('plugins/woocommerce/woocommerce.css'), array(), null );
			if (maxinet_is_on(maxinet_get_theme_option('debug_mode')) && maxinet_get_file_dir('plugins/woocommerce/woocommerce.js')!='')
				wp_enqueue_script( 'woocommerce', maxinet_get_file_url('plugins/woocommerce/woocommerce.js'), array('jquery'), null, true );
	}
}

// Merge custom styles
if ( !function_exists( 'maxinet_woocommerce_merge_styles' ) ) {
	//Handler of the add_filter('maxinet_filter_merge_styles', 'maxinet_woocommerce_merge_styles');
	function maxinet_woocommerce_merge_styles($list) {
		$list[] = 'plugins/woocommerce/woocommerce.css';
		return $list;
	}
}

// Merge custom scripts
if ( !function_exists( 'maxinet_woocommerce_merge_scripts' ) ) {
	//Handler of the add_filter('maxinet_filter_merge_scripts', 'maxinet_woocommerce_merge_scripts');
	function maxinet_woocommerce_merge_scripts($list) {
		$list[] = 'plugins/woocommerce/woocommerce.js';
		return $list;
	}
}



// Add WooCommerce specific items into lists
//------------------------------------------------------------------------

// Add sidebar
if ( !function_exists( 'maxinet_woocommerce_list_sidebars' ) ) {
	//Handler of the add_filter( 'maxinet_filter_list_sidebars', 'maxinet_woocommerce_list_sidebars' );
	function maxinet_woocommerce_list_sidebars($list=array()) {
		$list['woocommerce_widgets'] = array(
											'name' => esc_html__('WooCommerce Widgets', 'maxinet'),
											'description' => esc_html__('Widgets to be shown on the WooCommerce pages', 'maxinet')
											);
		return $list;
	}
}




// Decorate WooCommerce output: Loop
//------------------------------------------------------------------------

// Add query vars to set products per page
if (!function_exists('maxinet_woocommerce_pre_get_posts')) {
	//Handler of the add_action( 'pre_get_posts', 'maxinet_woocommerce_pre_get_posts' );
	function maxinet_woocommerce_pre_get_posts($query) {
		if (!$query->is_main_query()) return;
		if ($query->get('post_type') == 'product') {
			$ppp = get_theme_mod('posts_per_page_shop', 0);
			if ($ppp > 0)
				$query->set('posts_per_page', $ppp);
		}
	}
}


// Before main content
if ( !function_exists( 'maxinet_woocommerce_wrapper_start' ) ) {
	//remove_action( 'woocommerce_before_main_content', 'maxinet_woocommerce_wrapper_start', 10);
	//Handler of the add_action('woocommerce_before_main_content', 'maxinet_woocommerce_wrapper_start', 10);
	function maxinet_woocommerce_wrapper_start() {
		if (is_product() || is_cart() || is_checkout() || is_account_page()) {
			?>
			<article class="post_item_single post_type_product">
			<?php
		} else {
			?>
			<div class="list_products shop_mode_<?php echo esc_attr(!maxinet_storage_empty('shop_mode') ? maxinet_storage_get('shop_mode') : 'thumbs'); ?>">
				<div class="list_products_header">
			<?php
			maxinet_storage_set('woocommerce_list_products_header', true);
		}
	}
}

// After main content
if ( !function_exists( 'maxinet_woocommerce_wrapper_end' ) ) {
	//remove_action( 'woocommerce_after_main_content', 'maxinet_woocommerce_wrapper_end', 10);
	//Handler of the add_action('woocommerce_after_main_content', 'maxinet_woocommerce_wrapper_end', 10);
	function maxinet_woocommerce_wrapper_end() {
		if (is_product() || is_cart() || is_checkout() || is_account_page()) {
			?>
			</article><!-- /.post_item_single -->
			<?php
		} else {
			?>
			</div><!-- /.list_products -->
			<?php
		}
	}
}

// Close header section
if ( !function_exists( 'maxinet_woocommerce_archive_description' ) ) {
	//Handler of the add_action('woocommerce_after_main_content', 'maxinet_woocommerce_archive_description', 1);
	//Handler of the add_action( 'woocommerce_before_shop_loop',	'maxinet_woocommerce_archive_description', 5 );
	//Handler of the add_action( 'woocommerce_no_products_found',	'maxinet_woocommerce_archive_description', 5 );
	function maxinet_woocommerce_archive_description() {
		if (maxinet_storage_get('woocommerce_list_products_header')) {
			?>
			</div><!-- /.list_products_header -->
			<?php
			maxinet_storage_set('woocommerce_list_products_header', false);
			remove_action('woocommerce_after_main_content', 'maxinet_woocommerce_archive_description', 1);
		} else if (!is_singular())
			get_template_part( 'content', 'none-search' );
	}
}

// Add list mode buttons
if ( !function_exists( 'maxinet_woocommerce_before_shop_loop' ) ) {
	//Handler of the add_action( 'woocommerce_before_shop_loop', 'maxinet_woocommerce_before_shop_loop', 10 );
	function maxinet_woocommerce_before_shop_loop() {
		?>
		<div class="maxinet_shop_mode_buttons"><form action="<?php echo esc_url(maxinet_get_current_url()); ?>" method="post"><input type="hidden" name="maxinet_shop_mode" value="<?php echo esc_attr(maxinet_storage_get('shop_mode')); ?>" /><a href="#" class="woocommerce_thumbs icon-th" title="<?php esc_attr_e('Show products as thumbs', 'maxinet'); ?>"></a><a href="#" class="woocommerce_list icon-th-list" title="<?php esc_attr_e('Show products as list', 'maxinet'); ?>"></a></form></div><!-- /.maxinet_shop_mode_buttons -->
		<?php
	}
}

// Number of columns for the shop streampage
if ( !function_exists( 'maxinet_woocommerce_loop_shop_columns' ) ) {
	//Handler of the add_filter( 'loop_shop_columns', 'maxinet_woocommerce_loop_shop_columns' );
	function maxinet_woocommerce_loop_shop_columns($cols) {
		return max(2, min(4, maxinet_get_theme_option('blog_columns')));
	}
}

// Add column class into product item in shop streampage
if ( !function_exists( 'maxinet_woocommerce_loop_shop_columns_class' ) ) {
	//Handler of the add_filter( 'post_class', 'maxinet_woocommerce_loop_shop_columns_class' );
	//Handler of the add_filter( 'product_cat_class', 'maxinet_woocommerce_loop_shop_columns_class', 10, 3 );
	function maxinet_woocommerce_loop_shop_columns_class($classes, $class='', $cat='') {
		global $woocommerce_loop;
		if (is_product()) {
			if (!empty($woocommerce_loop['columns'])) {
				$classes[] = ' column-1_'.esc_attr($woocommerce_loop['columns']);
			}
		} else if (is_shop() || is_product_category() || is_product_tag() || is_product_taxonomy()) {
			$classes[] = ' column-1_'.esc_attr(max(2, min(4, maxinet_get_theme_option('blog_columns'))));
		}
		return $classes;
	}
}


// Open item wrapper for categories and products
if ( !function_exists( 'maxinet_woocommerce_item_wrapper_start' ) ) {
	//Handler of the add_action( 'woocommerce_before_subcategory_title', 'maxinet_woocommerce_item_wrapper_start', 9 );
	//Handler of the add_action( 'woocommerce_before_shop_loop_item_title', 'maxinet_woocommerce_item_wrapper_start', 9 );
	function maxinet_woocommerce_item_wrapper_start($cat='') {
		maxinet_storage_set('in_product_item', true);
		$hover = maxinet_get_theme_option('shop_hover');
		?>
		<div class="post_item post_layout_<?php echo esc_attr(is_shop() || is_product_category() || is_product_tag() || is_product_taxonomy() ? maxinet_storage_get('shop_mode') : 'thumbs'); ?>">
			<div class="post_featured hover_<?php echo esc_attr($hover); ?>">
				<?php do_action('maxinet_action_woocommerce_item_featured_start'); ?>
				<a href="<?php echo esc_url(is_object($cat) ? get_term_link($cat->slug, 'product_cat') : get_permalink()); ?>">
				<?php
	}
}

// Open item wrapper for categories and products
if ( !function_exists( 'maxinet_woocommerce_open_item_wrapper' ) ) {
	//Handler of the add_action( 'woocommerce_before_subcategory_title', 'maxinet_woocommerce_title_wrapper_start', 20 );
	//Handler of the add_action( 'woocommerce_before_shop_loop_item_title', 'maxinet_woocommerce_title_wrapper_start', 20 );
	function maxinet_woocommerce_title_wrapper_start($cat='') {
				?></a><?php
				if (($hover = maxinet_get_theme_option('shop_hover')) != 'none') {
					?><div class="mask"></div><?php
					maxinet_hovers_add_icons($hover, array('cat'=>$cat));
				}
				do_action('maxinet_action_woocommerce_item_featured_end');
				?>
			</div><!-- /.post_featured -->
			<div class="post_data">
				<div class="post_data_inner">
					<div class="post_header entry-header">
					<?php
	}
}


// Display product's tags before the title
if ( !function_exists( 'maxinet_woocommerce_title_tags' ) ) {
	//Handler of the add_action( 'woocommerce_before_shop_loop_item_title', 'maxinet_woocommerce_title_tags', 30 );
	function maxinet_woocommerce_title_tags() {
		global $product;
		maxinet_show_layout(wc_get_product_tag_list( $product->get_id(), ', ', '<div class="post_tags product_tags">', '</div>' ));
	}
}

// Wrap product title into link
if ( !function_exists( 'maxinet_woocommerce_the_title' ) ) {
	//Handler of the add_filter( 'the_title', 'maxinet_woocommerce_the_title' );
	function maxinet_woocommerce_the_title($title) {
		if (maxinet_storage_get('in_product_item') && get_post_type()=='product') {
			$title = '<a href="'.esc_url(get_permalink()).'">'.esc_html($title).'</a>';
		}
		return $title;
	}
}

// Wrap category title to the link: open tag
if ( !function_exists( 'maxinet_woocommerce_before_subcategory_title' ) ) {
	//Handler of the add_action( 'woocommerce_before_subcategory_title', 'maxinet_woocommerce_before_subcategory_title', 10, 1 );
	function maxinet_woocommerce_before_subcategory_title($cat) {
		if (maxinet_storage_get('in_product_item') && is_object($cat)) {
			?><a href="<?php echo esc_url(get_term_link($cat->slug, 'product_cat')); ?>"><?php
		}
	}
}

// Wrap category title to the link: close tag
if ( !function_exists( 'maxinet_woocommerce_after_subcategory_title' ) ) {
	//Handler of the add_action( 'woocommerce_after_subcategory_title', 'maxinet_woocommerce_after_subcategory_title', 10, 1 );
	function maxinet_woocommerce_after_subcategory_title($cat) {
		if (maxinet_storage_get('in_product_item') && is_object($cat)) {
			?></a><?php
		}
	}
}

// Add excerpt in output for the product in the list mode
if ( !function_exists( 'maxinet_woocommerce_title_wrapper_end' ) ) {
	//Handler of the add_action( 'woocommerce_after_shop_loop_item_title', 'maxinet_woocommerce_title_wrapper_end', 7);
	function maxinet_woocommerce_title_wrapper_end() {
			?>
			</div><!-- /.post_header -->
		<?php
			?>
			<div class="post_content entry-content"><?php echo wp_trim_words( get_the_excerpt(), 30 ); ?></div>
			<?php
	}
}

// Add excerpt in output for the product in the list mode
if ( !function_exists( 'maxinet_woocommerce_title_wrapper_end2' ) ) {
	//Handler of the add_action( 'woocommerce_after_subcategory_title', 'maxinet_woocommerce_title_wrapper_end2', 10 );
	function maxinet_woocommerce_title_wrapper_end2($category) {
			?>
			</div><!-- /.post_header -->
		<?php
		if ((maxinet_storage_get('shop_mode') == 'list' && is_shop() && !is_product())  ) {
			?>
			<div class="post_content entry-content"><?php maxinet_show_layout($category->description); ?></div><!-- /.post_content -->
			<?php
		}
	}
}

// Close item wrapper for categories and products
if ( !function_exists( 'maxinet_woocommerce_close_item_wrapper' ) ) {
	//Handler of the add_action( 'woocommerce_after_subcategory', 'maxinet_woocommerce_item_wrapper_end', 20 );
	//Handler of the add_action( 'woocommerce_after_shop_loop_item', 'maxinet_woocommerce_item_wrapper_end', 20 );
	function maxinet_woocommerce_item_wrapper_end($cat='') {
				?>
				</div><!-- /.post_data_inner -->
			</div><!-- /.post_data -->
		</div><!-- /.post_item -->
		<?php
		maxinet_storage_set('in_product_item', false);
	}
}

// Change text on 'Add to cart' button
if ( !function_exists( 'maxinet_woocommerce_add_to_cart_text' ) ) {
    //Handler of the add_filter( 'woocommerce_product_add_to_cart_text', 'maxinet_woocommerce_add_to_cart_text' );
    //Handler of the add_filter( 'woocommerce_product_single_add_to_cart_text','maxinet_woocommerce_add_to_cart_text' );
    function maxinet_woocommerce_add_to_cart_text($text='') {
        global $product;
        $product_type = $product->get_type();
        switch ( $product_type ) {
            case 'external':
                return $product->get_button_text();
                break;
            default:
                return esc_html__('Buy now', 'maxinet');
        }
    }
}

// Decorate price
if ( !function_exists( 'maxinet_woocommerce_get_price_html' ) ) {
	//Handler of the add_filter(    'woocommerce_get_price_html',	'maxinet_woocommerce_get_price_html' );
	function maxinet_woocommerce_get_price_html($price='') {
		if (!is_admin() && !empty($price)) {
			$sep = get_option('woocommerce_price_decimal_sep');
			if (empty($sep)) $sep = '.';
			$price = preg_replace('/([0-9,]+)(\\'.trim($sep).')([0-9]{2})/', '\\1<span class="decimals">\\3</span>', $price);
		}
		return $price;
	}
}



// Decorate WooCommerce output: Single product
//------------------------------------------------------------------------

// Add WooCommerce specific vars into localize array
if (!function_exists('maxinet_woocommerce_localize_script')) {
	//Handler of the add_filter( 'maxinet_filter_localize_script','maxinet_woocommerce_localize_script' );
	function maxinet_woocommerce_localize_script($arr) {
		$arr['stretch_tabs_area'] = !maxinet_sidebar_present() ? maxinet_get_theme_option('stretch_tabs_area') : 0;
		return $arr;
	}
}

// Add Product ID for the single product
if ( !function_exists( 'maxinet_woocommerce_show_product_id' ) ) {
	//Handler of the add_action( 'woocommerce_product_meta_end', 'maxinet_woocommerce_show_product_id', 10);
	function maxinet_woocommerce_show_product_id() {
		$authors = wp_get_post_terms(get_the_ID(), 'pa_product_author');
		if (is_array($authors) && count($authors)>0) {
			echo '<span class="product_author">'.esc_html__('Author: ', 'maxinet');
			$delim = '';
			foreach ($authors as $author) {
				echo  esc_html($delim) . '<span>' . esc_html($author->name) . '</span>';
				$delim = ', ';
			}
			echo '</span>';
		}
		echo '<span class="product_id">'.esc_html__('Product ID: ', 'maxinet') . '<span>' . get_the_ID() . '</span></span>';
	}
}

// Number columns for the product's thumbnails
if ( !function_exists( 'maxinet_woocommerce_product_thumbnails_columns' ) ) {
	//Handler of the add_filter( 'woocommerce_product_thumbnails_columns', 'maxinet_woocommerce_product_thumbnails_columns' );
	function maxinet_woocommerce_product_thumbnails_columns($cols) {
		return 4;
	}
}

// Set products number for the related products
if ( !function_exists( 'maxinet_woocommerce_output_related_products_args' ) ) {
	//Handler of the add_filter( 'woocommerce_output_related_products_args', 'maxinet_woocommerce_output_related_products_args' );
	function maxinet_woocommerce_output_related_products_args($args) {
		$args['posts_per_page'] = (int) maxinet_get_theme_option('show_related_posts')
										? max(0, min(9, maxinet_get_theme_option('related_posts')))
										: 0;
		$args['columns'] = max(1, min(4, maxinet_get_theme_option('related_columns')));
		return $args;
	}
}

// Set columns number for the related products
if ( !function_exists( 'maxinet_woocommerce_related_products_columns' ) ) {
	//Handler of the add_filter('woocommerce_related_products_columns', 'maxinet_woocommerce_related_products_columns' );
	function maxinet_woocommerce_related_products_columns($columns) {
		$columns = max(1, min(4, maxinet_get_theme_option('related_columns')));
		return $columns;
	}
}



// Decorate WooCommerce output: Widgets
//------------------------------------------------------------------------

// Search form
if ( !function_exists( 'maxinet_woocommerce_get_product_search_form' ) ) {
	//Handler of the add_filter( 'get_product_search_form', 'maxinet_woocommerce_get_product_search_form' );
	function maxinet_woocommerce_get_product_search_form($form) {
		return '
		<form role="search" method="get" class="search_form" action="' . esc_url(home_url('/')) . '">
			<input type="text" class="search_field" placeholder="' . esc_attr__('Search for products &hellip;', 'maxinet') . '" value="' . get_search_query() . '" name="s" /><button class="search_button" type="submit">' . esc_html__('Search', 'maxinet') . '</button>
			<input type="hidden" name="post_type" value="product" />
		</form>
		';
	}
}


// Add plugin-specific colors and fonts to the custom CSS
if (maxinet_exists_woocommerce()) { require_once MAXINET_THEME_DIR . 'plugins/woocommerce/woocommerce.styles.php'; }
?>