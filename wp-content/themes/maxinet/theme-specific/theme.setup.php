<?php
/**
 * Setup theme-specific fonts and colors
 *
 * @package WordPress
 * @subpackage MAXINET
 * @since MAXINET 1.0.22
 */

if (!defined("MAXINET_THEME_FREE")) define("MAXINET_THEME_FREE", false);
if (!defined("MAXINET_THEME_FREE_WP")) define("MAXINET_THEME_FREE_WP", false);

// Theme storage
$MAXINET_STORAGE = array(
	// Theme required plugin's slugs
	'required_plugins' => array_merge(

		// List of plugins for both - FREE and PREMIUM versions
		//-----------------------------------------------------
		array(
			// Required plugins
			// DON'T COMMENT OR REMOVE NEXT LINES!
			'trx_addons'					=> esc_html__('ThemeREX Addons', 'maxinet'),
			
			// Recommended (supported) plugins fot both (lite and full) versions
			// If plugin not need - comment (or remove) it
			'contact-form-7'				=> esc_html__('Contact Form 7', 'maxinet'),
			'woocommerce'					=> esc_html__('WooCommerce', 'maxinet'),
            'gdpr-framework'				=> esc_html__('GDPR Framework', 'maxinet')
		),

		// List of plugins for PREMIUM version only
		//-----------------------------------------------------
		MAXINET_THEME_FREE 
			? array(
					// Recommended (supported) plugins for the FREE (lite) version
					)
			: array(
					// Recommended (supported) plugins for the PRO (full) version
					// If plugin not need - comment (or remove) it
					'essential-grid'			=> esc_html__('Essential Grid', 'maxinet'),
					'revslider'					=> esc_html__('Revolution Slider', 'maxinet'),
					'js_composer'				=> esc_html__('Visual Composer', 'maxinet'),
					'vc-extensions-bundle'		=> esc_html__('Visual Composer extensions bundle', 'maxinet'),
				)
	),
	
	// Theme-specific URLs (will be escaped in place of the output)
	'theme_demo_url'	=> 'http://maxinet.themerex.net',
	'theme_doc_url'		=> 'http://maxinet.themerex.net/doc',
	'theme_download_url'=> 'https:/themerex.net/user/themerex/portfolio',

	'theme_support_url'	=> 'http://themerex.ticksy.com',

	'theme_video_url'	=> 'https://www.youtube.com/channel/UCnFisBimrK2aIE-hnY70kCA',
);

// Theme init priorities:
// Action 'after_setup_theme'
// 1 - register filters to add/remove lists items in the Theme Options
// 2 - create Theme Options
// 3 - add/remove Theme Options elements
// 5 - load Theme Options. Attention! After this step you can use only basic options (not overriden)
// 9 - register other filters (for installer, etc.)
//10 - standard Theme init procedures (not ordered)
// Action 'wp_loaded'
// 1 - detect override mode. Attention! Only after this step you can use overriden options (separate values for the shop, courses, etc.)

if ( !function_exists('maxinet_customizer_theme_setup1') ) {
	add_action( 'after_setup_theme', 'maxinet_customizer_theme_setup1', 1 );
	function maxinet_customizer_theme_setup1() {

		// -----------------------------------------------------------------
		// -- ONLY FOR PROGRAMMERS, NOT FOR CUSTOMER
		// -- Internal theme settings
		// -----------------------------------------------------------------
		maxinet_storage_set('settings', array(
			
			'duplicate_options'		=> 'child',		// none  - use separate options for template and child-theme
													// child - duplicate theme options from the main theme to the child-theme only
													// both  - sinchronize changes in the theme options between main and child themes
			
			'custmize_refresh'		=> 'auto',		// Refresh method for preview area in the Appearance - Customize:
													// auto - refresh preview area on change each field with Theme Options
													// manual - refresh only obn press button 'Refresh' at the top of Customize frame
		
			'max_load_fonts'		=> 5,			// Max fonts number to load from Google fonts or from uploaded fonts
		
			'comment_maxlength'		=> 1000,		// Max length of the message from contact form

			'comment_after_name'	=> true,		// Place 'comment' field before the 'name' and 'email'
			
			'socials_type'			=> 'icons',		// Type of socials:
													// icons - use font icons to present social networks
													// images - use images from theme's folder trx_addons/css/icons.png
			
			'icons_type'			=> 'icons',		// Type of other icons:
													// icons - use font icons to present icons
													// images - use images from theme's folder trx_addons/css/icons.png
			
			'icons_selector'		=> 'internal',	// Icons selector in the shortcodes:
													// vc (default) - standard VC icons selector (very slow and don't support images)
													// internal - internal popup with plugin's or theme's icons list (fast)
			'check_min_version'		=> true,		// Check if exists a .min version of .css and .js and return path to it
													// instead the path to the original file
													// (if debug_mode is off and modification time of the original file < time of the .min file)
			'autoselect_menu'		=> false,		// Show any menu if no menu selected in the location 'main_menu'
													// (for example, the theme is just activated)
			'disable_jquery_ui'		=> false,		// Prevent loading custom jQuery UI libraries in the third-party plugins
		
			'use_mediaelements'		=> true,		// Load script "Media Elements" to play video and audio
			
			'tgmpa_upload'			=> false		// Allow upload not pre-packaged plugins via TGMPA
		));


		// -----------------------------------------------------------------
		// -- Theme fonts (Google and/or custom fonts)
		// -----------------------------------------------------------------
		
		// Fonts to load when theme start
		// It can be Google fonts or uploaded fonts, placed in the folder /css/font-face/font-name inside the theme folder
		// Attention! Font's folder must have name equal to the font's name, with spaces replaced on the dash '-'
		// For example: font name 'TeX Gyre Termes', folder 'TeX-Gyre-Termes'
		maxinet_storage_set('load_fonts', array(
			// Google font
			array(
				'name'	 => 'Roboto',
				'family' => 'sans-serif',
				'styles' => '300,300italic,400,400italic,700,700italic'		// Parameter 'style' used only for the Google fonts
				),
			// Font-face packed with theme
			array(
				'name'   => 'Metropolis',
				'family' => 'sans-serif'
			),
		));
		
		// Characters subset for the Google fonts. Available values are: latin,latin-ext,cyrillic,cyrillic-ext,greek,greek-ext,vietnamese
		maxinet_storage_set('load_fonts_subset', 'latin,latin-ext');
		
		// Settings of the main tags
		maxinet_storage_set('theme_fonts', array(
			'p' => array(
				'title'				=> esc_html__('Main text', 'maxinet'),
				'description'		=> esc_html__('Font settings of the main text of the site', 'maxinet'),
				'font-family'		=> '"Metropolis",sans-serif',
				'font-size' 		=> '1rem',
				'font-weight'		=> '400',
				'font-style'		=> 'normal',
				'line-height'		=> '1.75em',
				'text-decoration'	=> 'none',
				'text-transform'	=> 'none',
				'letter-spacing'	=> '0px',
				'margin-top'		=> '0em',
				'margin-bottom'		=> '1.2em'
				),
			'h1' => array(
				'title'				=> esc_html__('Heading 1', 'maxinet'),
				'font-family'		=> '"Metropolis",sans-serif',
				'font-size' 		=> '3.938em',
				'font-weight'		=> '700',
				'font-style'		=> 'normal',
				'line-height'		=> '1.19em',
				'text-decoration'	=> 'none',
				'text-transform'	=> 'none',
				'letter-spacing'	=> '0px',
				'margin-top'		=> '1.5237em',
				'margin-bottom'		=> '1.238em'
				),
			'h2' => array(
				'title'				=> esc_html__('Heading 2', 'maxinet'),
				'font-family'		=> '"Metropolis",sans-serif',
				'font-size' 		=> '3.438em',
				'font-weight'		=> '700',
				'font-style'		=> 'normal',
				'line-height'		=> '1.2em',
				'text-decoration'	=> 'none',
				'text-transform'	=> 'none',
				'letter-spacing'	=> '0px',
				'margin-top'		=> '1.7818em',
				'margin-bottom'		=> '1.091em'
				),
			'h3' => array(
				'title'				=> esc_html__('Heading 3', 'maxinet'),
				'font-family'		=> '"Metropolis",sans-serif',
				'font-size' 		=> '2.688em',
				'font-weight'		=> '700',
				'font-style'		=> 'normal',
				'line-height'		=> '1.209em',
				'text-decoration'	=> 'none',
				'text-transform'	=> 'none',
				'letter-spacing'	=> '0px',
				'margin-top'		=> '1.907em',
				'margin-bottom'		=> '1.2092em'
				),
			'h4' => array(
				'title'				=> esc_html__('Heading 4', 'maxinet'),
				'font-family'		=> '"Metropolis",sans-serif',
				'font-size' 		=> '2em',
				'font-weight'		=> '700',
				'font-style'		=> 'normal',
				'line-height'		=> '1.313em',
				'text-decoration'	=> 'none',
				'text-transform'	=> 'none',
				'letter-spacing'	=> '0px',
				'margin-top'		=> '1.9375em',
				'margin-bottom'		=> '1.3125em'
				),
			'h5' => array(
				'title'				=> esc_html__('Heading 5', 'maxinet'),
				'font-family'		=> '"Metropolis",sans-serif',
				'font-size' 		=> '1.563em',
				'font-weight'		=> '700',
				'font-style'		=> 'normal',
				'line-height'		=> '1.35em',
				'text-decoration'	=> 'none',
				'text-transform'	=> 'none',
				'letter-spacing'	=> '0px',
				'margin-top'		=> '2.56em',
				'margin-bottom'		=> '1.76em'
				),
			'h6' => array(
				'title'				=> esc_html__('Heading 6', 'maxinet'),
				'font-family'		=> '"Metropolis",sans-serif',
				'font-size' 		=> '1.25em',
				'font-weight'		=> '700',
				'font-style'		=> 'normal',
				'line-height'		=> '1.3em',
				'text-decoration'	=> 'none',
				'text-transform'	=> 'none',
				'letter-spacing'	=> '0px',
				'margin-top'		=> '3.4em',
				'margin-bottom'		=> '2.35em'
				),
			'logo' => array(
				'title'				=> esc_html__('Logo text', 'maxinet'),
				'description'		=> esc_html__('Font settings of the text case of the logo', 'maxinet'),
				'font-family'		=> '"Metropolis",sans-serif',
				'font-size' 		=> '1.8em',
				'font-weight'		=> '700',
				'font-style'		=> 'normal',
				'line-height'		=> '1.25em',
				'text-decoration'	=> 'none',
				'text-transform'	=> 'uppercase',
				'letter-spacing'	=> '0px'
				),
			'button' => array(
				'title'				=> esc_html__('Buttons', 'maxinet'),
				'font-family'		=> '"Metropolis",sans-serif',
				'font-size' 		=> '14px',
				'font-weight'		=> '700',
				'font-style'		=> 'normal',
				'line-height'		=> '1.125em',
				'text-decoration'	=> 'none',
				'text-transform'	=> 'uppercase',
				'letter-spacing'	=> '0.06em'
				),
			'input' => array(
				'title'				=> esc_html__('Input fields', 'maxinet'),
				'description'		=> esc_html__('Font settings of the input fields, dropdowns and textareas', 'maxinet'),
				'font-family'		=> 'inherit',
				'font-size' 		=> '1em',
				'font-weight'		=> '400',
				'font-style'		=> 'normal',
				'line-height'		=> '1.5em',	// Attention! Firefox don't allow line-height less then 1.5em in the select
				'text-decoration'	=> 'none',
				'text-transform'	=> 'none',
				'letter-spacing'	=> '0px'
				),
			'info' => array(
				'title'				=> esc_html__('Post meta', 'maxinet'),
				'description'		=> esc_html__('Font settings of the post meta: date, counters, share, etc.', 'maxinet'),
				'font-family'		=> 'inherit',
				'font-size' 		=> '13px',
				'font-weight'		=> '400',
				'font-style'		=> 'normal',
				'line-height'		=> '1.5em',
				'text-decoration'	=> 'none',
				'text-transform'	=> 'none',
				'letter-spacing'	=> '0px',
				'margin-top'		=> '0.75em',
				'margin-bottom'		=> ''
				),
			'menu' => array(
				'title'				=> esc_html__('Main menu', 'maxinet'),
				'description'		=> esc_html__('Font settings of the main menu items', 'maxinet'),
				'font-family'		=> '"Metropolis",sans-serif',
				'font-size' 		=> '16px',
				'font-weight'		=> '700',
				'font-style'		=> 'normal',
				'line-height'		=> '1.5em',
				'text-decoration'	=> 'none',
				'text-transform'	=> 'none',
				'letter-spacing'	=> '0px'
				),
			'submenu' => array(
				'title'				=> esc_html__('Dropdown menu', 'maxinet'),
				'description'		=> esc_html__('Font settings of the dropdown menu items', 'maxinet'),
				'font-family'		=> '"Metropolis",sans-serif',
				'font-size' 		=> '13px',
				'font-weight'		=> '700',
				'font-style'		=> 'normal',
				'line-height'		=> '1.5em',
				'text-decoration'	=> 'none',
				'text-transform'	=> 'none',
				'letter-spacing'	=> '0px'
				)
		));
		
		
		// -----------------------------------------------------------------
		// -- Theme colors for customizer
		// -- Attention! Inner scheme must be last in the array below
		// -----------------------------------------------------------------
		maxinet_storage_set('scheme_color_groups', array(
			'main'	=> array(
							'title'			=> esc_html__('Main', 'maxinet'),
							'description'	=> esc_html__('Colors of the main content area', 'maxinet')
							),
			'alter'	=> array(
							'title'			=> esc_html__('Alter', 'maxinet'),
							'description'	=> esc_html__('Colors of the alternative blocks (sidebars, etc.)', 'maxinet')
							),
			'extra'	=> array(
							'title'			=> esc_html__('Extra', 'maxinet'),
							'description'	=> esc_html__('Colors of the extra blocks (dropdowns, price blocks, table headers, etc.)', 'maxinet')
							),
			'inverse' => array(
							'title'			=> esc_html__('Inverse', 'maxinet'),
							'description'	=> esc_html__('Colors of the inverse blocks - when link color used as background of the block (dropdowns, blockquotes, etc.)', 'maxinet')
							),
			'input'	=> array(
							'title'			=> esc_html__('Input', 'maxinet'),
							'description'	=> esc_html__('Colors of the form fields (text field, textarea, select, etc.)', 'maxinet')
							),
			)
		);
		maxinet_storage_set('scheme_color_names', array(
			'bg_color'	=> array(
							'title'			=> esc_html__('Background color', 'maxinet'),
							'description'	=> esc_html__('Background color of this block in the normal state', 'maxinet')
							),
			'bg_hover'	=> array(
							'title'			=> esc_html__('Background hover', 'maxinet'),
							'description'	=> esc_html__('Background color of this block in the hovered state', 'maxinet')
							),
			'bd_color'	=> array(
							'title'			=> esc_html__('Border color', 'maxinet'),
							'description'	=> esc_html__('Border color of this block in the normal state', 'maxinet')
							),
			'bd_hover'	=>  array(
							'title'			=> esc_html__('Border hover', 'maxinet'),
							'description'	=> esc_html__('Border color of this block in the hovered state', 'maxinet')
							),
			'text'		=> array(
							'title'			=> esc_html__('Text', 'maxinet'),
							'description'	=> esc_html__('Color of the plain text inside this block', 'maxinet')
							),
			'text_dark'	=> array(
							'title'			=> esc_html__('Text dark', 'maxinet'),
							'description'	=> esc_html__('Color of the dark text (bold, header, etc.) inside this block', 'maxinet')
							),
			'text_light'=> array(
							'title'			=> esc_html__('Text light', 'maxinet'),
							'description'	=> esc_html__('Color of the light text (post meta, etc.) inside this block', 'maxinet')
							),
			'text_link'	=> array(
							'title'			=> esc_html__('Link', 'maxinet'),
							'description'	=> esc_html__('Color of the links inside this block', 'maxinet')
							),
			'text_hover'=> array(
							'title'			=> esc_html__('Link hover', 'maxinet'),
							'description'	=> esc_html__('Color of the hovered state of links inside this block', 'maxinet')
							),
			'text_link2'=> array(
							'title'			=> esc_html__('Link 2', 'maxinet'),
							'description'	=> esc_html__('Color of the accented texts (areas) inside this block', 'maxinet')
							),
			'text_hover2'=> array(
							'title'			=> esc_html__('Link 2 hover', 'maxinet'),
							'description'	=> esc_html__('Color of the hovered state of accented texts (areas) inside this block', 'maxinet')
							),
			'text_link3'=> array(
							'title'			=> esc_html__('Link 3', 'maxinet'),
							'description'	=> esc_html__('Color of the other accented texts (buttons) inside this block', 'maxinet')
							),
			'text_hover3'=> array(
							'title'			=> esc_html__('Link 3 hover', 'maxinet'),
							'description'	=> esc_html__('Color of the hovered state of other accented texts (buttons) inside this block', 'maxinet')
							)
			)
		);
		maxinet_storage_set('schemes', array(
		
			// Color scheme: 'default'
			'default' => array(
				'title'	 => esc_html__('Default', 'maxinet'),
				'colors' => array(
					
					// Whole block border and background
					'bg_color'			=> '#ffffff',
					'bd_color'			=> '#f0f0f0',
		
					// Text and links colors
					'text'				=> '#737373',
					'text_light'		=> '#9d9d9d',
					'text_dark'			=> '#181818',
					'text_link'			=> '#dc1091',
					'text_hover'		=> '#3a1564',
					'text_link2'		=> '#b5cc00',
					'text_hover2'		=> '#8be77c',
					'text_link3'		=> '#ffb614',
					'text_hover3'		=> '#ffffff',
		
					// Alternative blocks (sidebar, tabs, alternative blocks, etc.)
					'alter_bg_color'	=> '#f5f4f0',
					'alter_bg_hover'	=> '#f0efe8',
					'alter_bd_color'	=> '#e6e5e1',
					'alter_bd_hover'	=> '#dadada',
					'alter_text'		=> '#737373',
					'alter_light'		=> '#9d9d9d',
					'alter_dark'		=> '#181818',
					'alter_link'		=> '#3a1564',
					'alter_hover'		=> '#dc1091',
					'alter_link2'		=> '#8be77c',
					'alter_hover2'		=> '#80d572',
					'alter_link3'		=> '#eec432',
					'alter_hover3'		=> '#ddb837',
		
					// Extra blocks (submenu, tabs, color blocks, etc.)
					'extra_bg_color'	=> '#3a1564',
					'extra_bg_hover'	=> '#3a1564',
					'extra_bd_color'	=> '#4e2d74',
					'extra_bd_hover'	=> '#3d3d3d',
					'extra_text'		=> '#898989',
					'extra_light'		=> '#afafaf',
					'extra_dark'		=> '#ffffff',
					'extra_link'		=> '#ffb614',
					'extra_hover'		=> '#fe7259',
					'extra_link2'		=> '#80d572',
					'extra_hover2'		=> '#8be77c',
					'extra_link3'		=> '#ddb837',
					'extra_hover3'		=> '#eec432',
		
					// Input fields (form's fields and textarea)
					'input_bg_color'	=> '#ebe9e2',
					'input_bg_hover'	=> '#ffffff',
					'input_bd_color'	=> '#ebe9e2',
					'input_bd_hover'	=> '#d8d7d1',
					'input_text'		=> '#898989',
					'input_light'		=> '#a7a7a7',
					'input_dark'		=> '#181818',
					
					// Inverse blocks (text and links on the 'text_link' background)
					'inverse_bd_color'	=> '#67bcc1',
					'inverse_bd_hover'	=> '#5aa4a9',
					'inverse_text'		=> '#ffffff',
					'inverse_light'		=> '#4e2d74',
					'inverse_dark'		=> '#1d1d1d',
					'inverse_link'		=> '#ffffff',
					'inverse_hover'		=> '#ffffff'
				)
			),
		
			// Color scheme: 'dark'
			'dark' => array(
				'title'  => esc_html__('Dark', 'maxinet'),
				'colors' => array(
					
					// Whole block border and background
					'bg_color'			=> '#1c0e2b',
					'bd_color'			=> '#2d1843',
		
					// Text and links colors
					'text'				=> '#ab9abe',
					'text_light'		=> '#8976a1',
					'text_dark'			=> '#ffffff',
					'text_link'			=> '#ffb614',
					'text_hover'		=> '#dc1091',
					'text_link2'		=> '#80d572',
					'text_hover2'		=> '#8be77c',
					'text_link3'		=> '#ffb614',
					'text_hover3'		=> '#ffffff',

					// Alternative blocks (sidebar, tabs, alternative blocks, etc.)
					'alter_bg_color'	=> '#3a1564',
					'alter_bg_hover'	=> '#341359',
					'alter_bd_color'	=> '#4e2d74',
					'alter_bd_hover'	=> '#4a4a4a',
					'alter_text'		=> '#ab9abe',
					'alter_light'		=> '#8976a1',
					'alter_dark'		=> '#ffffff',
					'alter_link'		=> '#ffb614',
					'alter_hover'		=> '#dc1091',
					'alter_link2'		=> '#8be77c',
					'alter_hover2'		=> '#80d572',
					'alter_link3'		=> '#eec432',
					'alter_hover3'		=> '#ddb837',

					// Extra blocks (submenu, tabs, color blocks, etc.)
					'extra_bg_color'	=> '#ffffff',
					'extra_bg_hover'	=> '#3a1564',
					'extra_bd_color'	=> '#e5e5e5',
					'extra_bd_hover'	=> '#4a4a4a',
					'extra_text'		=> '#333333',
					'extra_light'		=> '#b7b7b7',
					'extra_dark'		=> '#2e2d32',
					'extra_link'		=> '#ffaa5f',
					'extra_hover'		=> '#fe7259',
					'extra_link2'		=> '#80d572',
					'extra_hover2'		=> '#8be77c',
					'extra_link3'		=> '#ddb837',
					'extra_hover3'		=> '#eec432',

					// Input fields (form's fields and textarea)
					'input_bg_color'	=> '#1c0e2b',
					'input_bg_hover'	=> '#1c0e2b',
					'input_bd_color'	=> '#3a1564',
					'input_bd_hover'	=> '#633f8c',
					'input_text'		=> '#ab9abe',
					'input_light'		=> '#5f5f5f',
					'input_dark'		=> '#ffffff',
					
					// Inverse blocks (text and links on the 'text_link' background)
					'inverse_bd_color'	=> '#e36650',
					'inverse_bd_hover'	=> '#cb5b47',
					'inverse_text'		=> '#ffffff',
					'inverse_light'		=> '#e340a7',
					'inverse_dark'		=> '#1d1d1d',
					'inverse_link'		=> '#ffffff',
					'inverse_hover'		=> '#ffffff'
				)
			)
		
		));
		
		// Simple schemes substitution
		maxinet_storage_set('schemes_simple', array(
			// Main color	// Slave elements and it's darkness koef.
			'text_link'		=> array('alter_hover' => 1,	'extra_link' => 1, 'inverse_bd_color' => 0.85, 'inverse_bd_hover' => 0.7),
			'text_hover'	=> array('alter_link' => 1,		'extra_hover' => 1),
			'text_link2'	=> array('alter_hover2' => 1,	'extra_link2' => 1),
			'text_hover2'	=> array('alter_link2' => 1,	'extra_hover2' => 1),
			'text_link3'	=> array('alter_hover3' => 1,	'extra_link3' => 1),
			'text_hover3'	=> array('alter_link3' => 1,	'extra_hover3' => 1)
		));

		// Additional colors for each scheme
		// Parameters:	'color' - name of the color from the scheme that should be used as source for the transformation
		//				'alpha' - to make color transparent (0.0 - 1.0)
		//				'hue', 'saturation', 'brightness' - inc/dec value for each color's component
		maxinet_storage_set('scheme_colors_add', array(
			'bg_color_0'		=> array('color' => 'bg_color',			'alpha' => 0),
			'bg_color_02'		=> array('color' => 'bg_color',			'alpha' => 0.2),
			'bg_color_07'		=> array('color' => 'bg_color',			'alpha' => 0.7),
			'bg_color_08'		=> array('color' => 'bg_color',			'alpha' => 0.8),
			'bg_color_09'		=> array('color' => 'bg_color',			'alpha' =>  0.9),
			'alter_bg_color_07'	=> array('color' => 'alter_bg_color',	'alpha' => 0.7),
			'alter_bg_color_04'	=> array('color' => 'alter_bg_color',	'alpha' => 0.4),
			'alter_bg_color_02'	=> array('color' => 'alter_bg_color',	'alpha' => 0.2),
			'alter_bd_color_02'	=> array('color' => 'alter_bd_color',	'alpha' => 0.2),
			'extra_bg_color_07'	=> array('color' => 'extra_bg_color',	'alpha' => 0.7),
			'text_dark_07'		=> array('color' => 'text_dark',		'alpha' => 0.7),
			'text_link_02'		=> array('color' => 'text_link',		'alpha' => 0.2),
			'text_hover_02'		=> array('color' => 'text_hover',		'alpha' => 0.2),
			'text_link_07'		=> array('color' => 'text_link',		'alpha' => 0.7),
			'inverse_text_02'	=> array('color' => 'inverse_text',		'alpha' => 0.2),
			'inverse_link_03'	=> array('color' => 'inverse_link',		'alpha' => 0.3),
			'text_link_blend'	=> array('color' => 'text_link',		'hue' => 2, 'saturation' => -5, 'brightness' => 5),
			'alter_link_blend'	=> array('color' => 'alter_link',		'hue' => 2, 'saturation' => -5, 'brightness' => 5)
		));
		
		
		// -----------------------------------------------------------------
		// -- Theme specific thumb sizes
		// -----------------------------------------------------------------
		maxinet_storage_set('theme_thumbs', apply_filters('maxinet_filter_add_thumb_sizes', array(
			'maxinet-thumb-huge'		=> array(
												'size'	=> array(1170, 658, true),
												'title' => esc_html__( 'Huge image', 'maxinet' ),
												'subst'	=> 'trx_addons-thumb-huge'
												),
			'maxinet-thumb-big' 		=> array(
												'size'	=> array( 760, 428, true),
												'title' => esc_html__( 'Large image', 'maxinet' ),
												'subst'	=> 'trx_addons-thumb-big'
												),


			'maxinet-thumb-med' 		=> array(
												'size'	=> array( 370, 208, true),
												'title' => esc_html__( 'Medium image', 'maxinet' ),
												'subst'	=> 'trx_addons-thumb-medium'
												),

			'maxinet-thumb-tiny' 		=> array(
												'size'	=> array(  90,  90, true),
												'title' => esc_html__( 'Small square avatar', 'maxinet' ),
												'subst'	=> 'trx_addons-thumb-tiny'
												),

			'maxinet-thumb-masonry-big' => array(
												'size'	=> array( 760,   0, false),		// Only downscale, not crop
												'title' => esc_html__( 'Masonry Large (scaled)', 'maxinet' ),
												'subst'	=> 'trx_addons-thumb-masonry-big'
												),

			'maxinet-thumb-masonry'		=> array(
												'size'	=> array( 370,   0, false),		// Only downscale, not crop
												'title' => esc_html__( 'Masonry (scaled)', 'maxinet' ),
												'subst'	=> 'trx_addons-thumb-masonry'
												),
				'maxinet-thumb-extra' 		=> array(
													'size'	=> array( 800, 600, true),
													'title' => esc_html__( 'Extra image', 'maxinet' ),
													'subst'	=> 'trx_addons-thumb-extra'
												)

			))
		);
	}
}




//------------------------------------------------------------------------
// One-click import support
//------------------------------------------------------------------------

// Set theme specific importer options
if ( !function_exists( 'maxinet_importer_set_options' ) ) {
	add_filter( 'trx_addons_filter_importer_options', 'maxinet_importer_set_options', 9 );
	function maxinet_importer_set_options($options=array()) {
		if (is_array($options)) {
			// Save or not installer's messages to the log-file
			$options['debug'] = false;
			// Prepare demo data
			$options['demo_url'] = esc_url(maxinet_get_protocol() . '://demofiles.themerex.net/maxinet/');
			// Required plugins
			$options['required_plugins'] = array_keys(maxinet_storage_get('required_plugins'));
			// Set number of thumbnails to regenerate when its imported (if demo data was zipped without cropped images)
			// Set 0 to prevent regenerate thumbnails (if demo data archive is already contain cropped images)
			$options['regenerate_thumbnails'] = 3;
			// Default demo
			$options['files']['default']['title'] = esc_html__('MaxiNet Demo', 'maxinet');
			$options['files']['default']['domain_dev'] = '';		// Developers domain
			$options['files']['default']['domain_demo']= esc_url(maxinet_get_protocol().'://maxinet.themerex.net');		// Demo-site domain
			// If theme need more demo - just copy 'default' and change required parameter
			// Banners
			$options['banners'] = array(
				array(
					'image' => maxinet_get_file_url('theme-specific/theme.about/images/frontpage.png'),
					'title' => esc_html__('Front Page Builder', 'maxinet'),
					'content' => wp_kses_post(__('Create your front page right in the WordPress Customizer. There\'s no need in Visual Composer or any other builder. Simply enable/disable sections, fill them out with content, and customize to your liking.', 'maxinet')),
					'link_url' => esc_url('//www.youtube.com/watch?v=VT0AUbMl_KA'),
					'link_caption' => esc_html__('More about Front Page Builder', 'maxinet'),
					'duration' => 20
					),
				array(
					'image' => maxinet_get_file_url('theme-specific/theme.about/images/layouts.png'),
					'title' => esc_html__('Custom Layouts', 'maxinet'),
					'content' => wp_kses_post(__('Use Layouts Builder to create and customize header and footer styles for your website. With a flexible page builder interface and custom shortcodes, you can create as many header and footer layouts as you want with ease.', 'maxinet')),
					'link_url' => esc_url('//www.youtube.com/watch?v=pYhdFVLd7y4'),
					'link_caption' => esc_html__('More about Custom Layouts', 'maxinet'),
					'duration' => 20
					),
				array(
					'image' => maxinet_get_file_url('theme-specific/theme.about/images/documentation.png'),
					'title' => esc_html__('Read Full Documentation', 'maxinet'),
					'content' => wp_kses_post(__('Need more details? Please check our full online documentation for detailed information on how to use MaxiNet.', 'maxinet')),
					'link_url' => esc_url(maxinet_storage_get('theme_doc_url')),
					'link_caption' => esc_html__('Online Documentation', 'maxinet'),
					'duration' => 15
					),
				array(
					'image' => maxinet_get_file_url('theme-specific/theme.about/images/video-tutorials.png'),
					'title' => esc_html__('Video Tutorials', 'maxinet'),
					'content' => wp_kses_post(__('No time for reading documentation? Check out our video tutorials and learn how to customize MaxiNet in detail.', 'maxinet')),
					'link_url' => esc_url(maxinet_storage_get('theme_video_url')),
					'link_caption' => esc_html__('Video Tutorials', 'maxinet'),
					'duration' => 15
					),
				array(
					'image' => maxinet_get_file_url('theme-specific/theme.about/images/studio.png'),
					'title' => esc_html__('Mockingbird Website Customization Studio', 'maxinet'),
					'content' => wp_kses_post(__('Need a website fast? Order our custom service, and we\'ll build a website based on this theme for a very fair price.
					We can also implement additional functionality such as website translation, setting up WPML, and much more.', 'maxinet')),
					'link_url' => esc_url('//themerex.net/offers/?utm_source=offers&utm_medium=click&utm_campaign=themeinstall'),
					'link_caption' => esc_html__('Contact Us', 'maxinet'),
					'duration' => 25
					)
				);
		}
		return $options;
	}
}




// -----------------------------------------------------------------
// -- Theme options for customizer
// -----------------------------------------------------------------
if (!function_exists('maxinet_create_theme_options')) {

	function maxinet_create_theme_options() {

		// Message about options override. 
		// Attention! Not need esc_html() here, because this message put in wp_kses_data() below
		$msg_override = __('<b>Attention!</b> Some of these options can be overridden in the following sections (Blog, Plugins settings, etc.) or in the settings of individual pages', 'maxinet');

		maxinet_storage_set('options', array(
		
			// 'Logo & Site Identity'
			'title_tagline' => array(
				"title" => esc_html__('Logo & Site Identity', 'maxinet'),
				"desc" => '',
				"priority" => 10,
				"type" => "section"
				),
			'logo_info' => array(
				"title" => esc_html__('Logo in the header', 'maxinet'),
				"desc" => '',
				"priority" => 20,
				"type" => "info",
				),
			'logo_text' => array(
				"title" => esc_html__('Use Site Name as Logo', 'maxinet'),
				"desc" => wp_kses_data( __('Use the site title and tagline as a text logo if no image is selected', 'maxinet') ),
				"class" => "maxinet_column-1_2 maxinet_new_row",
				"priority" => 30,
				"std" => 1,
				"type" => MAXINET_THEME_FREE ? "hidden" : "checkbox"
				),
			'logo_retina_enabled' => array(
				"title" => esc_html__('Allow retina display logo', 'maxinet'),
				"desc" => wp_kses_data( __('Show fields to select logo images for Retina display', 'maxinet') ),
				"class" => "maxinet_column-1_2",
				"priority" => 40,
				"refresh" => false,
				"std" => 0,
				"type" => MAXINET_THEME_FREE ? "hidden" : "checkbox"
				),
			'logo_max_height' => array(
				"title" => esc_html__('Logo max. height', 'maxinet'),
				"desc" => wp_kses_data( __("Max. height of the logo image (in pixels). Maximum size of logo depends on the actual size of the picture", 'maxinet') ),
				"std" => 80,
				"min" => 20,
				"max" => 160,
				"step" => 1,
				"refresh" => false,
				"type" => MAXINET_THEME_FREE ? "hidden" : "slider"
				),
			// Parameter 'logo' was replaced with standard WordPress 'custom_logo'
			'logo_retina' => array(
				"title" => esc_html__('Logo for Retina', 'maxinet'),
				"desc" => wp_kses_data( __('Select or upload site logo used on Retina displays (if empty - use default logo from the field above)', 'maxinet') ),
				"class" => "maxinet_column-1_2",
				"priority" => 70,
				"dependency" => array(
					'logo_retina_enabled' => array(1)
				),
				"std" => '',
				"type" => MAXINET_THEME_FREE ? "hidden" : "image"
				),
			'logo_mobile_header' => array(
				"title" => esc_html__('Logo for the mobile header', 'maxinet'),
				"desc" => wp_kses_data( __('Select or upload site logo to display it in the mobile header (if enabled in the section "Header - Header mobile"', 'maxinet') ),
				"class" => "maxinet_column-1_2 maxinet_new_row",
				"std" => '',
				"type" => "image"
				),
			'logo_mobile_header_retina' => array(
				"title" => esc_html__('Logo for the mobile header for Retina', 'maxinet'),
				"desc" => wp_kses_data( __('Select or upload site logo used on Retina displays (if empty - use default logo from the field above)', 'maxinet') ),
				"class" => "maxinet_column-1_2",
				"dependency" => array(
					'logo_retina_enabled' => array(1)
				),
				"std" => '',
				"type" => MAXINET_THEME_FREE ? "hidden" : "image"
				),
			'logo_mobile' => array(
				"title" => esc_html__('Logo mobile', 'maxinet'),
				"desc" => wp_kses_data( __('Select or upload site logo to display it in the mobile menu', 'maxinet') ),
				"class" => "maxinet_column-1_2 maxinet_new_row",
				"std" => '',
				"type" => "image"
				),
			'logo_mobile_retina' => array(
				"title" => esc_html__('Logo mobile for Retina', 'maxinet'),
				"desc" => wp_kses_data( __('Select or upload site logo used on Retina displays (if empty - use default logo from the field above)', 'maxinet') ),
				"class" => "maxinet_column-1_2",
				"dependency" => array(
					'logo_retina_enabled' => array(1)
				),
				"std" => '',
				"type" => MAXINET_THEME_FREE ? "hidden" : "image"
				),
			'logo_side' => array(
				"title" => esc_html__('Logo side', 'maxinet'),
				"desc" => wp_kses_data( __('Select or upload site logo (with vertical orientation) to display it in the side menu', 'maxinet') ),
				"class" => "maxinet_column-1_2 maxinet_new_row",
				"std" => '',
				"type" => "image"
				),
			'logo_side_retina' => array(
				"title" => esc_html__('Logo side for Retina', 'maxinet'),
				"desc" => wp_kses_data( __('Select or upload site logo (with vertical orientation) to display it in the side menu on Retina displays (if empty - use default logo from the field above)', 'maxinet') ),
				"class" => "maxinet_column-1_2",
				"dependency" => array(
					'logo_retina_enabled' => array(1)
				),
				"std" => '',
				"type" => MAXINET_THEME_FREE ? "hidden" : "image"
				),
			
		
		
			// 'General settings'
			'general' => array(
				"title" => esc_html__('General Settings', 'maxinet'),
				"desc" => wp_kses_data( $msg_override ),
				"priority" => 20,
				"type" => "section",
				),

			'general_layout_info' => array(
				"title" => esc_html__('Layout', 'maxinet'),
				"desc" => '',
				"type" => "info",
				),
			'body_style' => array(
				"title" => esc_html__('Body style', 'maxinet'),
				"desc" => wp_kses_data( __('Select width of the body content', 'maxinet') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Content', 'maxinet')
				),
				"refresh" => false,
				"std" => 'wide',
				"options" => maxinet_get_list_body_styles(),
				"type" => "select"
				),
			'boxed_bg_image' => array(
				"title" => esc_html__('Boxed bg image', 'maxinet'),
				"desc" => wp_kses_data( __('Select or upload image, used as background in the boxed body', 'maxinet') ),
				"dependency" => array(
					'body_style' => array('boxed')
				),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Content', 'maxinet')
				),
				"std" => '',
				"hidden" => true,
				"type" => "image"
				),
			'remove_margins' => array(
				"title" => esc_html__('Remove margins', 'maxinet'),
				"desc" => wp_kses_data( __('Remove margins above and below the content area', 'maxinet') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Content', 'maxinet')
				),
				"refresh" => false,
				"std" => 0,
				"type" => "checkbox"
				),

			'general_sidebar_info' => array(
				"title" => esc_html__('Sidebar', 'maxinet'),
				"desc" => '',
				"type" => "info",
				),
			'sidebar_position' => array(
				"title" => esc_html__('Sidebar position', 'maxinet'),
				"desc" => wp_kses_data( __('Select position to show sidebar', 'maxinet') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Widgets', 'maxinet')
				),
				"std" => 'right',
				"options" => array(),
				"type" => "switch"
				),
			'sidebar_widgets' => array(
				"title" => esc_html__('Sidebar widgets', 'maxinet'),
				"desc" => wp_kses_data( __('Select default widgets to show in the sidebar', 'maxinet') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Widgets', 'maxinet')
				),
				"dependency" => array(
					'sidebar_position' => array('left', 'right')
				),
				"std" => 'sidebar_widgets',
				"options" => array(),
				"type" => "select"
				),
			'expand_content' => array(
				"title" => esc_html__('Expand content', 'maxinet'),
				"desc" => wp_kses_data( __('Expand the content width if the sidebar is hidden', 'maxinet') ),
				"refresh" => false,
				"std" => 1,
				"type" => "checkbox"
				),


			'general_widgets_info' => array(
				"title" => esc_html__('Additional widgets', 'maxinet'),
				"desc" => '',
				"type" => MAXINET_THEME_FREE ? "hidden" : "info",
				),
			'widgets_above_page' => array(
				"title" => esc_html__('Widgets at the top of the page', 'maxinet'),
				"desc" => wp_kses_data( __('Select widgets to show at the top of the page (above content and sidebar)', 'maxinet') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Widgets', 'maxinet')
				),
				"std" => 'hide',
				"options" => array(),
				"type" => MAXINET_THEME_FREE ? "hidden" : "select"
				),
			'widgets_above_content' => array(
				"title" => esc_html__('Widgets above the content', 'maxinet'),
				"desc" => wp_kses_data( __('Select widgets to show at the beginning of the content area', 'maxinet') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Widgets', 'maxinet')
				),
				"std" => 'hide',
				"options" => array(),
				"type" => MAXINET_THEME_FREE ? "hidden" : "select"
				),
			'widgets_below_content' => array(
				"title" => esc_html__('Widgets below the content', 'maxinet'),
				"desc" => wp_kses_data( __('Select widgets to show at the ending of the content area', 'maxinet') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Widgets', 'maxinet')
				),
				"std" => 'hide',
				"options" => array(),
				"type" => MAXINET_THEME_FREE ? "hidden" : "select"
				),
			'widgets_below_page' => array(
				"title" => esc_html__('Widgets at the bottom of the page', 'maxinet'),
				"desc" => wp_kses_data( __('Select widgets to show at the bottom of the page (below content and sidebar)', 'maxinet') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Widgets', 'maxinet')
				),
				"std" => 'hide',
				"options" => array(),
				"type" => MAXINET_THEME_FREE ? "hidden" : "select"
				),

			'general_effects_info' => array(
				"title" => esc_html__('Design & Effects', 'maxinet'),
				"desc" => '',
				"type" => "info",
				),
			'border_radius' => array(
				"title" => esc_html__('Border radius', 'maxinet'),
				"desc" => wp_kses_data( __('Specify the border radius of the form fields and buttons in pixels or other valid CSS units', 'maxinet') ),
				"std" => 0,
				"type" => "hidden"
				),

			'general_misc_info' => array(
				"title" => esc_html__('Miscellaneous', 'maxinet'),
				"desc" => '',
				"type" => MAXINET_THEME_FREE ? "hidden" : "info",
				),
			'seo_snippets' => array(
				"title" => esc_html__('SEO snippets', 'maxinet'),
				"desc" => wp_kses_data( __('Add structured data markup to the single posts and pages', 'maxinet') ),
				"std" => 0,
				"type" => MAXINET_THEME_FREE ? "hidden" : "checkbox"
				),
            'privacy_text' => array(
                "title" => esc_html__("Text with Privacy Policy link", 'maxinet'),
                "desc"  => wp_kses_data( __("Specify text with Privacy Policy link for the checkbox 'I agree ...'", 'maxinet') ),
                "std"   => wp_kses_post( __( 'I agree that my submitted data is being collected and stored.', 'maxinet') ),
                "type"  => "text"
            ),
		
		
			// 'Header'
			'header' => array(
				"title" => esc_html__('Header', 'maxinet'),
				"desc" => wp_kses_data( $msg_override ),
				"priority" => 30,
				"type" => "section"
				),

			'header_style_info' => array(
				"title" => esc_html__('Header style', 'maxinet'),
				"desc" => '',
				"type" => "info"
				),
			'header_type' => array(
				"title" => esc_html__('Header style', 'maxinet'),
				"desc" => wp_kses_data( __('Choose whether to use the default header or header Layouts (available only if the ThemeREX Addons is activated)', 'maxinet') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Header', 'maxinet')
				),
				"std" => 'default',
				"options" => maxinet_get_list_header_footer_types(),
				"type" => MAXINET_THEME_FREE ? "hidden" : "switch"
				),
			'header_style' => array(
				"title" => esc_html__('Select custom layout', 'maxinet'),
				"desc" => wp_kses_post( __("Select custom header from Layouts Builder", 'maxinet') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Header', 'maxinet')
				),
				"dependency" => array(
					'header_type' => array('custom')
				),
				"std" => MAXINET_THEME_FREE ? 'header-custom-sow-header-default' : 'header-custom-header-default',
				"options" => array(),
				"type" => "select"
				),
			'header_position' => array(
				"title" => esc_html__('Header position', 'maxinet'),
				"desc" => wp_kses_data( __('Select position to display the site header', 'maxinet') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Header', 'maxinet')
				),
				"std" => 'default',
				"options" => array(),
				"type" => MAXINET_THEME_FREE ? "hidden" : "switch"
				),
			'header_fullheight' => array(
				"title" => esc_html__('Header fullheight', 'maxinet'),
				"desc" => wp_kses_data( __("Enlarge header area to fill whole screen. Used only if header have a background image", 'maxinet') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Header', 'maxinet')
				),
				"std" => 0,
				"type" => MAXINET_THEME_FREE ? "hidden" : "checkbox"
				),
			'header_zoom' => array(
				"title" => esc_html__('Header zoom', 'maxinet'),
				"desc" => wp_kses_data( __("Zoom the header title. 1 - original size", 'maxinet') ),
				"std" => 1,
				"min" => 0.3,
				"max" => 2,
				"step" => 0.1,
				"refresh" => false,
				"type" => MAXINET_THEME_FREE ? "hidden" : "slider"
				),
			'header_wide' => array(
				"title" => esc_html__('Header fullwide', 'maxinet'),
				"desc" => wp_kses_data( __('Do you want to stretch the header widgets area to the entire window width?', 'maxinet') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Header', 'maxinet')
				),
				"dependency" => array(
					'header_type' => array('default')
				),
				"std" => 1,
				"type" => MAXINET_THEME_FREE ? "hidden" : "checkbox"
				),

			'header_widgets_info' => array(
				"title" => esc_html__('Header widgets', 'maxinet'),
				"desc" => wp_kses_data( __('Here you can place a widget slider, advertising banners, etc.', 'maxinet') ),
				"type" => "info"
				),
			'header_widgets' => array(
				"title" => esc_html__('Header widgets', 'maxinet'),
				"desc" => wp_kses_data( __('Select set of widgets to show in the header on each page', 'maxinet') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Header', 'maxinet'),
					"desc" => wp_kses_data( __('Select set of widgets to show in the header on this page', 'maxinet') ),
				),
				"std" => 'hide',
				"options" => array(),
				"type" => "select"
				),
			'header_columns' => array(
				"title" => esc_html__('Header columns', 'maxinet'),
				"desc" => wp_kses_data( __('Select number columns to show widgets in the Header. If 0 - autodetect by the widgets count', 'maxinet') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Header', 'maxinet')
				),
				"dependency" => array(
					'header_type' => array('default'),
					'header_widgets' => array('^hide')
				),
				"std" => 0,
				"options" => maxinet_get_list_range(0,6),
				"type" => "select"
				),

			'menu_info' => array(
				"title" => esc_html__('Main menu', 'maxinet'),
				"desc" => wp_kses_data( __('Select main menu style, position, color scheme and other parameters', 'maxinet') ),
				"type" => MAXINET_THEME_FREE ? "hidden" : "info"
				),
			'menu_style' => array(
				"title" => esc_html__('Menu position', 'maxinet'),
				"desc" => wp_kses_data( __('Select position of the main menu', 'maxinet') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Header', 'maxinet')
				),
				"std" => 'top',
				"options" => array(
					'top'	=> esc_html__('Top',	'maxinet')
				),
				"type" => MAXINET_THEME_FREE ? "hidden" : "switch"
				),
			'menu_side_stretch' => array(
				"title" => esc_html__('Stretch sidemenu', 'maxinet'),
				"desc" => wp_kses_data( __('Stretch sidemenu to window height (if menu items number >= 5)', 'maxinet') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Header', 'maxinet')
				),
				"dependency" => array(
					'menu_style' => array('left', 'right')
				),
				"std" => 0,
				"type" => MAXINET_THEME_FREE ? "hidden" : "checkbox"
				),
			'menu_side_icons' => array(
				"title" => esc_html__('Iconed sidemenu', 'maxinet'),
				"desc" => wp_kses_data( __('Get icons from anchors and display it in the sidemenu or mark sidemenu items with simple dots', 'maxinet') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Header', 'maxinet')
				),
				"dependency" => array(
					'menu_style' => array('left', 'right')
				),
				"std" => 1,
				"type" => MAXINET_THEME_FREE ? "hidden" : "checkbox"
				),
			'menu_mobile_fullscreen' => array(
				"title" => esc_html__('Mobile menu fullscreen', 'maxinet'),
				"desc" => wp_kses_data( __('Display mobile and side menus on full screen (if checked) or slide narrow menu from the left or from the right side (if not checked)', 'maxinet') ),
				"std" => 1,
				"type" => MAXINET_THEME_FREE ? "hidden" : "checkbox"
				),

			'header_image_info' => array(
				"title" => esc_html__('Header image', 'maxinet'),
				"desc" => '',
				"type" => MAXINET_THEME_FREE ? "hidden" : "info"
				),
			'header_image_override' => array(
				"title" => esc_html__('Header image override', 'maxinet'),
				"desc" => wp_kses_data( __("Allow override the header image with the page's/post's/product's/etc. featured image", 'maxinet') ),
				"override" => array(
					'mode' => 'page',
					'section' => esc_html__('Header', 'maxinet')
				),
				"std" => 0,
				"type" => MAXINET_THEME_FREE ? "hidden" : "checkbox"
				),

			'header_mobile_info' => array(
				"title" => esc_html__('Mobile header', 'maxinet'),
				"desc" => wp_kses_data( __("Configure the mobile version of the header", 'maxinet') ),
				"priority" => 500,
				"type" => MAXINET_THEME_FREE ? "hidden" : "info"
				),
			'header_mobile_enabled' => array(
				"title" => esc_html__('Enable the mobile header', 'maxinet'),
				"desc" => wp_kses_data( __("Use the mobile version of the header (if checked) or relayout the current header on mobile devices", 'maxinet') ),
				"std" => 0,
				"type" => MAXINET_THEME_FREE ? "hidden" : "checkbox"
				),
			'header_mobile_additional_info' => array(
				"title" => esc_html__('Additional info', 'maxinet'),
				"desc" => wp_kses_data( __('Additional info to show at the top of the mobile header', 'maxinet') ),
				"std" => '',
				"dependency" => array(
					'header_mobile_enabled' => array(1)
				),
				"refresh" => false,
				"teeny" => false,
				"rows" => 20,
				"type" => MAXINET_THEME_FREE ? "hidden" : "text_editor"
				),
			'header_mobile_hide_info' => array(
				"title" => esc_html__('Hide additional info', 'maxinet'),
				"std" => 0,
				"dependency" => array(
					'header_mobile_enabled' => array(1)
				),
				"type" => MAXINET_THEME_FREE ? "hidden" : "checkbox"
				),
			'header_mobile_hide_logo' => array(
				"title" => esc_html__('Hide logo', 'maxinet'),
				"std" => 0,
				"dependency" => array(
					'header_mobile_enabled' => array(1)
				),
				"type" => MAXINET_THEME_FREE ? "hidden" : "checkbox"
				),
			'header_mobile_hide_login' => array(
				"title" => esc_html__('Hide login/logout', 'maxinet'),
				"std" => 0,
				"dependency" => array(
					'header_mobile_enabled' => array(1)
				),
				"type" => MAXINET_THEME_FREE ? "hidden" : "checkbox"
				),
			'header_mobile_hide_search' => array(
				"title" => esc_html__('Hide search', 'maxinet'),
				"std" => 0,
				"dependency" => array(
					'header_mobile_enabled' => array(1)
				),
				"type" => MAXINET_THEME_FREE ? "hidden" : "checkbox"
				),
			'header_mobile_hide_cart' => array(
				"title" => esc_html__('Hide cart', 'maxinet'),
				"std" => 0,
				"dependency" => array(
					'header_mobile_enabled' => array(1)
				),
				"type" => MAXINET_THEME_FREE ? "hidden" : "checkbox"
				),


		
			// 'Footer'
			'footer' => array(
				"title" => esc_html__('Footer', 'maxinet'),
				"desc" => wp_kses_data( $msg_override ),
				"priority" => 50,
				"type" => "section"
				),
			'footer_type' => array(
				"title" => esc_html__('Footer style', 'maxinet'),
				"desc" => wp_kses_data( __('Choose whether to use the default footer or footer Layouts (available only if the ThemeREX Addons is activated)', 'maxinet') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Footer', 'maxinet')
				),
				"std" => 'default',
				"options" => maxinet_get_list_header_footer_types(),
				"type" => MAXINET_THEME_FREE ? "hidden" : "switch"
				),
			'footer_style' => array(
				"title" => esc_html__('Select custom layout', 'maxinet'),
				"desc" => wp_kses_post( __("Select custom footer from Layouts Builder", 'maxinet') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Footer', 'maxinet')
				),
				"dependency" => array(
					'footer_type' => array('custom')
				),
				"std" => MAXINET_THEME_FREE ? 'footer-custom-sow-footer-default' : 'footer-custom-footer-default',
				"options" => array(),
				"type" => "select"
				),
			'footer_widgets' => array(
				"title" => esc_html__('Footer widgets', 'maxinet'),
				"desc" => wp_kses_data( __('Select set of widgets to show in the footer', 'maxinet') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Footer', 'maxinet')
				),
				"dependency" => array(
					'footer_type' => array('default')
				),
				"std" => 'footer_widgets',
				"options" => array(),
				"type" => "select"
				),
			'footer_columns' => array(
				"title" => esc_html__('Footer columns', 'maxinet'),
				"desc" => wp_kses_data( __('Select number columns to show widgets in the footer. If 0 - autodetect by the widgets count', 'maxinet') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Footer', 'maxinet')
				),
				"dependency" => array(
					'footer_type' => array('default'),
					'footer_widgets' => array('^hide')
				),
				"std" => 0,
				"options" => maxinet_get_list_range(0,6),
				"type" => "select"
				),
			'footer_wide' => array(
				"title" => esc_html__('Footer fullwide', 'maxinet'),
				"desc" => wp_kses_data( __('Do you want to stretch the footer to the entire window width?', 'maxinet') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Footer', 'maxinet')
				),
				"dependency" => array(
					'footer_type' => array('default')
				),
				"std" => 0,
				"type" => "checkbox"
				),
			'logo_in_footer' => array(
				"title" => esc_html__('Show logo', 'maxinet'),
				"desc" => wp_kses_data( __('Show logo in the footer', 'maxinet') ),
				'refresh' => false,
				"dependency" => array(
					'footer_type' => array('default')
				),
				"std" => 0,
				"type" => "checkbox"
				),
			'logo_footer' => array(
				"title" => esc_html__('Logo for footer', 'maxinet'),
				"desc" => wp_kses_data( __('Select or upload site logo to display it in the footer', 'maxinet') ),
				"dependency" => array(
					'footer_type' => array('default'),
					'logo_in_footer' => array(1)
				),
				"std" => '',
				"type" => "image"
				),
			'logo_footer_retina' => array(
				"title" => esc_html__('Logo for footer (Retina)', 'maxinet'),
				"desc" => wp_kses_data( __('Select or upload logo for the footer area used on Retina displays (if empty - use default logo from the field above)', 'maxinet') ),
				"dependency" => array(
					'footer_type' => array('default'),
					'logo_in_footer' => array(1),
					'logo_retina_enabled' => array(1)
				),
				"std" => '',
				"type" => MAXINET_THEME_FREE ? "hidden" : "image"
				),
			'socials_in_footer' => array(
				"title" => esc_html__('Show social icons', 'maxinet'),
				"desc" => wp_kses_data( __('Show social icons in the footer (under logo or footer widgets)', 'maxinet') ),
				"dependency" => array(
					'footer_type' => array('default')
				),
				"std" => 0,
				"type" => "checkbox"
				),
			'copyright' => array(
				"title" => esc_html__('Copyright', 'maxinet'),
				"desc" => wp_kses_data( __('Copyright text in the footer. Use {Y} to insert current year and press "Enter" to create a new line', 'maxinet') ),
				"std" => esc_html__('Copyright &copy; {Y} by ThemeREX. All rights reserved.', 'maxinet'),
				"dependency" => array(
					'footer_type' => array('default')
				),
				"refresh" => false,
				"type" => "textarea"
				),
			
		
		
			// 'Blog'
			'blog' => array(
				"title" => esc_html__('Blog', 'maxinet'),
				"desc" => wp_kses_data( __('Options of the the blog archive', 'maxinet') ),
				"priority" => 70,
				"type" => "panel",
				),
		
				// Blog - Posts page
				'blog_general' => array(
					"title" => esc_html__('Posts page', 'maxinet'),
					"desc" => wp_kses_data( __('Style and components of the blog archive', 'maxinet') ),
					"type" => "section",
					),
				'blog_general_info' => array(
					"title" => esc_html__('General settings', 'maxinet'),
					"desc" => '',
					"type" => "info",
					),
				'blog_style' => array(
					"title" => esc_html__('Blog style', 'maxinet'),
					"desc" => '',
					"override" => array(
						'mode' => 'page',
						'section' => esc_html__('Content', 'maxinet')
					),
					"dependency" => array(
						'#page_template' => array( 'blog.php' ),
						'.editor-page-attributes__template select' => array( 'blog.php' ),
					),
					"std" => 'excerpt',
					"options" => array(),
					"type" => "select"
					),
				'first_post_large' => array(
					"title" => esc_html__('First post large', 'maxinet'),
					"desc" => wp_kses_data( __('Make your first post stand out by making it bigger', 'maxinet') ),
					"override" => array(
						'mode' => 'page',
						'section' => esc_html__('Content', 'maxinet')
					),
					"dependency" => array(
						'#page_template' => array( 'blog.php' ),
						'.editor-page-attributes__template select' => array( 'blog.php' ),
						'blog_style' => array('classic', 'masonry')
					),
					"std" => 0,
					"type" => "checkbox"
					),
				"blog_content" => array( 
					"title" => esc_html__('Posts content', 'maxinet'),
					"desc" => wp_kses_data( __("Display either post excerpts or the full post content", 'maxinet') ),
					"std" => "excerpt",
					"dependency" => array(
						'blog_style' => array('excerpt')
					),
					"options" => array(
						'excerpt'	=> esc_html__('Excerpt',	'maxinet'),
						'fullpost'	=> esc_html__('Full post',	'maxinet')
					),
					"type" => "switch"
					),
				'excerpt_length' => array(
					"title" => esc_html__('Excerpt length', 'maxinet'),
					"desc" => wp_kses_data( __("Length (in words) to generate excerpt from the post content. Attention! If the post excerpt is explicitly specified - it appears unchanged", 'maxinet') ),
					"std" => 60,
					"type" => "text"
					),
				'blog_columns' => array(
					"title" => esc_html__('Blog columns', 'maxinet'),
					"desc" => wp_kses_data( __('How many columns should be used in the blog archive (from 2 to 4)?', 'maxinet') ),
					"std" => 2,
					"options" => maxinet_get_list_range(2,4),
					"type" => "hidden"
					),
				'post_type' => array(
					"title" => esc_html__('Post type', 'maxinet'),
					"desc" => wp_kses_data( __('Select post type to show in the blog archive', 'maxinet') ),
					"override" => array(
						'mode' => 'page',
						'section' => esc_html__('Content', 'maxinet')
					),
					"dependency" => array(
						'#page_template' => array( 'blog.php' ),
						'.editor-page-attributes__template select' => array( 'blog.php' ),
					),
					"linked" => 'parent_cat',
					"refresh" => false,
					"hidden" => true,
					"std" => 'post',
					"options" => array(),
					"type" => "select"
					),
				'parent_cat' => array(
					"title" => esc_html__('Category to show', 'maxinet'),
					"desc" => wp_kses_data( __('Select category to show in the blog archive', 'maxinet') ),
					"override" => array(
						'mode' => 'page',
						'section' => esc_html__('Content', 'maxinet')
					),
					"dependency" => array(
						'#page_template' => array( 'blog.php' ),
						'.editor-page-attributes__template select' => array( 'blog.php' ),
					),
					"refresh" => false,
					"hidden" => true,
					"std" => '0',
					"options" => array(),
					"type" => "select"
					),
				'posts_per_page' => array(
					"title" => esc_html__('Posts per page', 'maxinet'),
					"desc" => wp_kses_data( __('How many posts will be displayed on this page', 'maxinet') ),
					"override" => array(
						'mode' => 'page',
						'section' => esc_html__('Content', 'maxinet')
					),
					"dependency" => array(
						'#page_template' => array( 'blog.php' ),
						'.editor-page-attributes__template select' => array( 'blog.php' ),
					),
					"hidden" => true,
					"std" => '',
					"type" => "text"
					),
				"blog_pagination" => array( 
					"title" => esc_html__('Pagination style', 'maxinet'),
					"desc" => wp_kses_data( __('Show Older/Newest posts or Page numbers below the posts list', 'maxinet') ),
					"override" => array(
						'mode' => 'page',
						'section' => esc_html__('Content', 'maxinet')
					),
					"std" => "pages",
					"dependency" => array(
						'#page_template' => array( 'blog.php' ),
						'.editor-page-attributes__template select' => array( 'blog.php' ),
					),
					"options" => array(
						'pages'	=> esc_html__("Page numbers", 'maxinet'),
						'links'	=> esc_html__("Older/Newest", 'maxinet'),
						'more'	=> esc_html__("Load more", 'maxinet'),
						'infinite' => esc_html__("Infinite scroll", 'maxinet')
					),
					"type" => "select"
					),
				'show_filters' => array(
					"title" => esc_html__('Show filters', 'maxinet'),
					"desc" => wp_kses_data( __('Show categories as tabs to filter posts', 'maxinet') ),
					"override" => array(
						'mode' => 'page',
						'section' => esc_html__('Content', 'maxinet')
					),
					"dependency" => array(
						'#page_template' => array( 'blog.php' ),
						'.editor-page-attributes__template select' => array( 'blog.php' ),
						'blog_style' => array('portfolio', 'gallery')
					),
					"hidden" => true,
					"std" => 0,
					"type" => MAXINET_THEME_FREE ? "hidden" : "checkbox"
					),
	
				'blog_sidebar_info' => array(
					"title" => esc_html__('Sidebar', 'maxinet'),
					"desc" => '',
					"type" => "info",
					),
				'sidebar_position_blog' => array(
					"title" => esc_html__('Sidebar position', 'maxinet'),
					"desc" => wp_kses_data( __('Select position to show sidebar', 'maxinet') ),
					"std" => 'right',
					"options" => array(),
					"type" => "switch"
					),
				'sidebar_widgets_blog' => array(
					"title" => esc_html__('Sidebar widgets', 'maxinet'),
					"desc" => wp_kses_data( __('Select default widgets to show in the sidebar', 'maxinet') ),
					"dependency" => array(
						'sidebar_position_blog' => array('left', 'right')
					),
					"std" => 'sidebar_widgets',
					"options" => array(),
					"type" => "select"
					),
				'expand_content_blog' => array(
					"title" => esc_html__('Expand content', 'maxinet'),
					"desc" => wp_kses_data( __('Expand the content width if the sidebar is hidden', 'maxinet') ),
					"refresh" => false,
					"std" => 1,
					"type" => "checkbox"
					),
	
	
				'blog_widgets_info' => array(
					"title" => esc_html__('Additional widgets', 'maxinet'),
					"desc" => '',
					"type" => MAXINET_THEME_FREE ? "hidden" : "info",
					),
				'widgets_above_page_blog' => array(
					"title" => esc_html__('Widgets at the top of the page', 'maxinet'),
					"desc" => wp_kses_data( __('Select widgets to show at the top of the page (above content and sidebar)', 'maxinet') ),
					"std" => 'hide',
					"options" => array(),
					"type" => MAXINET_THEME_FREE ? "hidden" : "select"
					),
				'widgets_above_content_blog' => array(
					"title" => esc_html__('Widgets above the content', 'maxinet'),
					"desc" => wp_kses_data( __('Select widgets to show at the beginning of the content area', 'maxinet') ),
					"std" => 'hide',
					"options" => array(),
					"type" => MAXINET_THEME_FREE ? "hidden" : "select"
					),
				'widgets_below_content_blog' => array(
					"title" => esc_html__('Widgets below the content', 'maxinet'),
					"desc" => wp_kses_data( __('Select widgets to show at the ending of the content area', 'maxinet') ),
					"std" => 'hide',
					"options" => array(),
					"type" => MAXINET_THEME_FREE ? "hidden" : "select"
					),
				'widgets_below_page_blog' => array(
					"title" => esc_html__('Widgets at the bottom of the page', 'maxinet'),
					"desc" => wp_kses_data( __('Select widgets to show at the bottom of the page (below content and sidebar)', 'maxinet') ),
					"std" => 'hide',
					"options" => array(),
					"type" => MAXINET_THEME_FREE ? "hidden" : "select"
					),

				'blog_advanced_info' => array(
					"title" => esc_html__('Advanced settings', 'maxinet'),
					"desc" => '',
					"type" => "info",
					),
				'no_image' => array(
					"title" => esc_html__('Image placeholder', 'maxinet'),
					"desc" => wp_kses_data( __('Select or upload an image used as placeholder for posts without a featured image', 'maxinet') ),
					"std" => '',
					"type" => "image"
					),
				'time_diff_before' => array(
					"title" => esc_html__('Easy Readable Date Format', 'maxinet'),
					"desc" => wp_kses_data( __("For how many days to show the easy-readable date format (e.g. '3 days ago') instead of the standard publication date", 'maxinet') ),
					"std" => 5,
					"type" => "text"
					),
				'sticky_style' => array(
					"title" => esc_html__('Sticky posts style', 'maxinet'),
					"desc" => wp_kses_data( __('Select style of the sticky posts output', 'maxinet') ),
					"std" => 'inherit',
					"options" => array(
						'inherit' => esc_html__('Decorated posts', 'maxinet'),
						'columns' => esc_html__('Mini-cards',	'maxinet')
					),
					"type" => MAXINET_THEME_FREE ? "hidden" : "select"
					),
				"blog_animation" => array( 
					"title" => esc_html__('Animation for the posts', 'maxinet'),
					"desc" => wp_kses_data( __('Select animation to show posts in the blog. Attention! Do not use any animation on pages with the "wheel to the anchor" behaviour (like a "Chess 2 columns")!', 'maxinet') ),
					"override" => array(
						'mode' => 'page',
						'section' => esc_html__('Content', 'maxinet')
					),
					"dependency" => array(
						'#page_template' => array( 'blog.php' ),
						'.editor-page-attributes__template select' => array( 'blog.php' ),
					),
					"std" => "none",
					"options" => array(),
					"type" => MAXINET_THEME_FREE ? "hidden" : "select"
					),
				'meta_parts' => array(
					"title" => esc_html__('Post meta', 'maxinet'),
					"desc" => wp_kses_data( __("If your blog page is created using the 'Blog archive' page template, set up the 'Post Meta' settings in the 'Theme Options' section of that page.", 'maxinet') )
								. '<br>'
								. wp_kses_data( __("<b>Tip:</b> Drag items to change their order.", 'maxinet') ),
					"override" => array(
						'mode' => 'page',
						'section' => esc_html__('Content', 'maxinet')
					),
					"dependency" => array(
						'#page_template' => array( 'blog.php' ),
						'.editor-page-attributes__template select' => array( 'blog.php' ),
					),
					"dir" => 'vertical',
					"sortable" => true,
					"std" => 'categories=1|date=1|counters=0|author=0|share=0|edit=0',
					"options" => array(
						'categories' => esc_html__('Categories', 'maxinet'),
						'date'		 => esc_html__('Post date', 'maxinet'),
						'author'	 => esc_html__('Post author', 'maxinet'),
						'counters'	 => esc_html__('Views, Likes and Comments', 'maxinet'),
						'share'		 => esc_html__('Share links', 'maxinet'),
						'edit'		 => esc_html__('Edit link', 'maxinet')
					),
					"type" => MAXINET_THEME_FREE ? "hidden" : "checklist"
				),
				'counters' => array(
					"title" => esc_html__('Views, Likes and Comments', 'maxinet'),
					"desc" => wp_kses_data( __("Likes and Views are available only if ThemeREX Addons is active", 'maxinet') ),
					"override" => array(
						'mode' => 'page',
						'section' => esc_html__('Content', 'maxinet')
					),
					"dependency" => array(
						'#page_template' => array( 'blog.php' ),
						'.editor-page-attributes__template select' => array( 'blog.php' ),
					),
					"dir" => 'vertical',
					"sortable" => true,
					"std" => 'views=1|likes=1|comments=1',
					"options" => array(
						'views' => esc_html__('Views', 'maxinet'),
						'likes' => esc_html__('Likes', 'maxinet'),
						'comments' => esc_html__('Comments', 'maxinet')
					),
					"type" => MAXINET_THEME_FREE ? "hidden" : "checklist"
				),

				
				// Blog - Single posts
				'blog_single' => array(
					"title" => esc_html__('Single posts', 'maxinet'),
					"desc" => wp_kses_data( __('Settings of the single post', 'maxinet') ),
					"type" => "section",
					),
				'hide_featured_on_single' => array(
					"title" => esc_html__('Hide featured image on the single post', 'maxinet'),
					"desc" => wp_kses_data( __("Hide featured image on the single post's pages", 'maxinet') ),
					"override" => array(
						'mode' => 'page,post',
						'section' => esc_html__('Content', 'maxinet')
					),
					"std" => 0,
					"type" => "checkbox"
					),
				'hide_sidebar_on_single' => array(
					"title" => esc_html__('Hide sidebar on the single post', 'maxinet'),
					"desc" => wp_kses_data( __("Hide sidebar on the single post's pages", 'maxinet') ),
					"std" => 1,
					"type" => "checkbox"
					),
				'expand_content_post' => array(
					"title" => esc_html__('Expand content', 'maxinet'),
					"desc" => wp_kses_data( __('Expand the content width if the sidebar is hidden', 'maxinet') ),
					"refresh" => false,
					"std" => 0,
					"type" => "checkbox"
					),
				'show_post_meta' => array(
					"title" => esc_html__('Show post meta', 'maxinet'),
					"desc" => wp_kses_data( __("Display block with post's meta: date, categories, counters, etc.", 'maxinet') ),
					"std" => 1,
					"type" => "checkbox"
					),
				'meta_parts_post' => array(
					"title" => esc_html__('Post meta', 'maxinet'),
					"desc" => wp_kses_data( __("Meta parts for single posts.", 'maxinet') ),
					"dependency" => array(
						'show_post_meta' => array(1)
					),
					"dir" => 'vertical',
					"sortable" => true,
					"std" => 'categories=1|date=1|counters=1|author=0|share=0|edit=1',
					"options" => array(
						'categories' => esc_html__('Categories', 'maxinet'),
						'date'		 => esc_html__('Post date', 'maxinet'),
						'author'	 => esc_html__('Post author', 'maxinet'),
						'counters'	 => esc_html__('Views, Likes and Comments', 'maxinet'),
						'share'		 => esc_html__('Share links', 'maxinet'),
						'edit'		 => esc_html__('Edit link', 'maxinet')
					),
					"type" => MAXINET_THEME_FREE ? "hidden" : "checklist"
				),
				'counters_post' => array(
					"title" => esc_html__('Views, Likes and Comments', 'maxinet'),
					"desc" => wp_kses_data( __("Likes and Views are available only if ThemeREX Addons is active", 'maxinet') ),
					"dependency" => array(
						'show_post_meta' => array(1)
					),
					"dir" => 'vertical',
					"sortable" => true,
					"std" => 'views=1|likes=1|comments=1',
					"options" => array(
						'views' => esc_html__('Views', 'maxinet'),
						'likes' => esc_html__('Likes', 'maxinet'),
						'comments' => esc_html__('Comments', 'maxinet')
					),
					"type" => MAXINET_THEME_FREE ? "hidden" : "checklist"
				),
				'show_share_links' => array(
					"title" => esc_html__('Show share links', 'maxinet'),
					"desc" => wp_kses_data( __("Display share links on the single post", 'maxinet') ),
					"std" => 1,
					"type" => "checkbox"
					),
				'show_author_info' => array(
					"title" => esc_html__('Show author info', 'maxinet'),
					"desc" => wp_kses_data( __("Display block with information about post's author", 'maxinet') ),
					"std" => 1,
					"type" => "checkbox"
					),
				'blog_single_related_info' => array(
					"title" => esc_html__('Related posts', 'maxinet'),
					"desc" => '',
					"type" => "info",
					),
				'show_related_posts' => array(
					"title" => esc_html__('Show related posts', 'maxinet'),
					"desc" => wp_kses_data( __("Show section 'Related posts' on the single post's pages", 'maxinet') ),
					"override" => array(
						'mode' => 'page,post',
						'section' => esc_html__('Content', 'maxinet')
					),
					"std" => 1,
					"type" => "checkbox"
					),
				'related_posts' => array(
					"title" => esc_html__('Related posts', 'maxinet'),
					"desc" => wp_kses_data( __('How many related posts should be displayed in the single post? If 0 - no related posts showed.', 'maxinet') ),
					"dependency" => array(
						'show_related_posts' => array(1)
					),
					"std" => 2,
					"options" => maxinet_get_list_range(1,9),
					"type" => MAXINET_THEME_FREE ? "hidden" : "select"
					),
				'related_columns' => array(
					"title" => esc_html__('Related columns', 'maxinet'),
					"desc" => wp_kses_data( __('How many columns should be used to output related posts in the single page (from 2 to 4)?', 'maxinet') ),
					"dependency" => array(
						'show_related_posts' => array(1)
					),
					"std" => 2,
					"options" => maxinet_get_list_range(1,4),
					"type" => MAXINET_THEME_FREE ? "hidden" : "switch"
					),
				'related_style' => array(
					"title" => esc_html__('Related posts style', 'maxinet'),
					"desc" => wp_kses_data( __('Select style of the related posts output', 'maxinet') ),
					"dependency" => array(
						'show_related_posts' => array(1)
					),
					"std" => 1,
					"options" => maxinet_get_list_styles(1,2),
					"type" => MAXINET_THEME_FREE ? "hidden" : "switch"
					),
			'blog_end' => array(
				"type" => "panel_end",
				),
			

			// 'Colors'
			'panel_colors' => array(
				"title" => esc_html__('Colors', 'maxinet'),
				"desc" => '',
				"priority" => 300,
				"type" => "section"
				),

			'color_schemes_info' => array(
				"title" => esc_html__('Color schemes', 'maxinet'),
				"desc" => wp_kses_data( __('Color schemes for various parts of the site. "Inherit" means that this block is used the Site color scheme (the first parameter)', 'maxinet') ),
				"type" => "info",
				),
			'color_scheme' => array(
				"title" => esc_html__('Site Color Scheme', 'maxinet'),
				"desc" => '',
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Colors', 'maxinet')
				),
				"std" => 'default',
				"options" => array(),
				"refresh" => false,
				"type" => "switch"
				),
			'header_scheme' => array(
				"title" => esc_html__('Header Color Scheme', 'maxinet'),
				"desc" => '',
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Colors', 'maxinet')
				),
				"std" => 'inherit',
				"options" => array(),
				"refresh" => false,
				"type" => "switch"
				),
			'menu_scheme' => array(
				"title" => esc_html__('Sidemenu Color Scheme', 'maxinet'),
				"desc" => '',
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Colors', 'maxinet')
				),
				"std" => 'inherit',
				"options" => array(),
				"refresh" => false,
				"type" => MAXINET_THEME_FREE ? "hidden" : "hidden"
				),
			'sidebar_scheme' => array(
				"title" => esc_html__('Sidebar Color Scheme', 'maxinet'),
				"desc" => '',
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Colors', 'maxinet')
				),
				"std" => 'dark',
				"options" => array(),
				"refresh" => false,
				"type" => "switch"
				),
			'footer_scheme' => array(
				"title" => esc_html__('Footer Color Scheme', 'maxinet'),
				"desc" => '',
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Colors', 'maxinet')
				),
				"std" => 'dark',
				"options" => array(),
				"refresh" => false,
				"type" => "switch"
				),

			'color_scheme_editor_info' => array(
				"title" => esc_html__('Color scheme editor', 'maxinet'),
				"desc" => wp_kses_data(__('Select color scheme to modify. Attention! Only those sections in the site will be changed which this scheme was assigned to', 'maxinet') ),
				"type" => "info",
				),
			'scheme_storage' => array(
				"title" => esc_html__('Color scheme editor', 'maxinet'),
				"desc" => '',
				"std" => '$maxinet_get_scheme_storage',
				"refresh" => false,
				"colorpicker" => "tiny",
				"type" => "scheme_editor"
				),


			// 'Hidden'
			'media_title' => array(
				"title" => esc_html__('Media title', 'maxinet'),
				"desc" => wp_kses_data( __('Used as title for the audio and video item in this post', 'maxinet') ),
				"override" => array(
					'mode' => 'post',
					'section' => esc_html__('Content', 'maxinet')
				),
				"hidden" => true,
				"std" => '',
				"type" => MAXINET_THEME_FREE ? "hidden" : "text"
				),
			'media_author' => array(
				"title" => esc_html__('Media author', 'maxinet'),
				"desc" => wp_kses_data( __('Used as author name for the audio and video item in this post', 'maxinet') ),
				"override" => array(
					'mode' => 'post',
					'section' => esc_html__('Content', 'maxinet')
				),
				"hidden" => true,
				"std" => '',
				"type" => MAXINET_THEME_FREE ? "hidden" : "text"
				),


			// Internal options.
			// Attention! Don't change any options in the section below!
			// Use huge priority to call render this elements after all options!
			'reset_options' => array(
				"title" => '',
				"desc" => '',
				"std" => '0',
				"priority" => 10000,
				"type" => "hidden",
				),

			'last_option' => array(		// Need to manually call action to include Tiny MCE scripts
				"title" => '',
				"desc" => '',
				"std" => 1,
				"type" => "hidden",
				),

		));


		// Prepare panel 'Fonts'
		$fonts = array(
		
			// 'Fonts'
			'fonts' => array(
				"title" => esc_html__('Typography', 'maxinet'),
				"desc" => '',
				"priority" => 200,
				"type" => "panel"
				),

			// Fonts - Load_fonts
			'load_fonts' => array(
				"title" => esc_html__('Load fonts', 'maxinet'),
				"desc" => wp_kses_data( __('Specify fonts to load when theme start. You can use them in the base theme elements: headers, text, menu, links, input fields, etc.', 'maxinet') )
						. '<br>'
						. wp_kses_data( __('<b>Attention!</b> Press "Refresh" button to reload preview area after the all fonts are changed', 'maxinet') ),
				"type" => "section"
				),
			'load_fonts_subset' => array(
				"title" => esc_html__('Google fonts subsets', 'maxinet'),
				"desc" => wp_kses_data( __('Specify comma separated list of the subsets which will be load from Google fonts', 'maxinet') )
						. '<br>'
						. wp_kses_data( __('Available subsets are: latin,latin-ext,cyrillic,cyrillic-ext,greek,greek-ext,vietnamese', 'maxinet') ),
				"class" => "maxinet_column-1_3 maxinet_new_row",
				"refresh" => false,
				"std" => '$maxinet_get_load_fonts_subset',
				"type" => "text"
				)
		);

		for ($i=1; $i<=maxinet_get_theme_setting('max_load_fonts'); $i++) {
			if (maxinet_get_value_gp('page') != 'theme_options') {
				$fonts["load_fonts-{$i}-info"] = array(
					// Translators: Add font's number - 'Font 1', 'Font 2', etc
					"title" => esc_html(sprintf(__('Font %s', 'maxinet'), $i)),
					"desc" => '',
					"type" => "info",
					);
			}
			$fonts["load_fonts-{$i}-name"] = array(
				"title" => esc_html__('Font name', 'maxinet'),
				"desc" => '',
				"class" => "maxinet_column-1_3 maxinet_new_row",
				"refresh" => false,
				"std" => '$maxinet_get_load_fonts_option',
				"type" => "text"
				);
			$fonts["load_fonts-{$i}-family"] = array(
				"title" => esc_html__('Font family', 'maxinet'),
				"desc" => $i==1 
							? wp_kses_data( __('Select font family to use it if font above is not available', 'maxinet') )
							: '',
				"class" => "maxinet_column-1_3",
				"refresh" => false,
				"std" => '$maxinet_get_load_fonts_option',
				"options" => array(
					'inherit' => esc_html__("Inherit", 'maxinet'),
					'serif' => esc_html__('serif', 'maxinet'),
					'sans-serif' => esc_html__('sans-serif', 'maxinet'),
					'monospace' => esc_html__('monospace', 'maxinet'),
					'cursive' => esc_html__('cursive', 'maxinet'),
					'fantasy' => esc_html__('fantasy', 'maxinet')
				),
				"type" => "select"
				);
			$fonts["load_fonts-{$i}-styles"] = array(
				"title" => esc_html__('Font styles', 'maxinet'),
				"desc" => $i==1 
							? wp_kses_data( __('Font styles used only for the Google fonts. This is a comma separated list of the font weight and styles. For example: 400,400italic,700', 'maxinet') )
								. '<br>'
								. wp_kses_data( __('<b>Attention!</b> Each weight and style increase download size! Specify only used weights and styles.', 'maxinet') )
							: '',
				"class" => "maxinet_column-1_3",
				"refresh" => false,
				"std" => '$maxinet_get_load_fonts_option',
				"type" => "text"
				);
		}
		$fonts['load_fonts_end'] = array(
			"type" => "section_end"
			);

		// Fonts - H1..6, P, Info, Menu, etc.
		$theme_fonts = maxinet_get_theme_fonts();
		foreach ($theme_fonts as $tag=>$v) {
			$fonts["{$tag}_section"] = array(
				"title" => !empty($v['title']) 
								? $v['title'] 
								// Translators: Add tag's name to make title 'H1 settings', 'P settings', etc.
								: esc_html(sprintf(__('%s settings', 'maxinet'), $tag)),
				"desc" => !empty($v['description']) 
								? $v['description'] 
								// Translators: Add tag's name to make description
								: wp_kses_post( sprintf(__('Font settings of the "%s" tag.', 'maxinet'), $tag) ),
				"type" => "section",
				);
	
			foreach ($v as $css_prop=>$css_value) {
				if (in_array($css_prop, array('title', 'description'))) continue;
				$options = '';
				$type = 'text';
				$title = ucfirst(str_replace('-', ' ', $css_prop));
				if ($css_prop == 'font-family') {
					$type = 'select';
					$options = array();
				} else if ($css_prop == 'font-weight') {
					$type = 'select';
					$options = array(
						'inherit' => esc_html__("Inherit", 'maxinet'),
						'100' => esc_html__('100 (Light)', 'maxinet'), 
						'200' => esc_html__('200 (Light)', 'maxinet'), 
						'300' => esc_html__('300 (Thin)',  'maxinet'),
						'400' => esc_html__('400 (Normal)', 'maxinet'),
						'500' => esc_html__('500 (Semibold)', 'maxinet'),
						'600' => esc_html__('600 (Semibold)', 'maxinet'),
						'700' => esc_html__('700 (Bold)', 'maxinet'),
						'800' => esc_html__('800 (Black)', 'maxinet'),
						'900' => esc_html__('900 (Black)', 'maxinet')
					);
				} else if ($css_prop == 'font-style') {
					$type = 'select';
					$options = array(
						'inherit' => esc_html__("Inherit", 'maxinet'),
						'normal' => esc_html__('Normal', 'maxinet'), 
						'italic' => esc_html__('Italic', 'maxinet')
					);
				} else if ($css_prop == 'text-decoration') {
					$type = 'select';
					$options = array(
						'inherit' => esc_html__("Inherit", 'maxinet'),
						'none' => esc_html__('None', 'maxinet'), 
						'underline' => esc_html__('Underline', 'maxinet'),
						'overline' => esc_html__('Overline', 'maxinet'),
						'line-through' => esc_html__('Line-through', 'maxinet')
					);
				} else if ($css_prop == 'text-transform') {
					$type = 'select';
					$options = array(
						'inherit' => esc_html__("Inherit", 'maxinet'),
						'none' => esc_html__('None', 'maxinet'), 
						'uppercase' => esc_html__('Uppercase', 'maxinet'),
						'lowercase' => esc_html__('Lowercase', 'maxinet'),
						'capitalize' => esc_html__('Capitalize', 'maxinet')
					);
				}
				$fonts["{$tag}_{$css_prop}"] = array(
					"title" => $title,
					"desc" => '',
					"class" => "maxinet_column-1_5",
					"refresh" => false,
					"std" => '$maxinet_get_theme_fonts_option',
					"options" => $options,
					"type" => $type
				);
			}
			
			$fonts["{$tag}_section_end"] = array(
				"type" => "section_end"
				);
		}

		$fonts['fonts_end'] = array(
			"type" => "panel_end"
			);

		// Add fonts parameters to Theme Options
		maxinet_storage_set_array_before('options', 'panel_colors', $fonts);

		// Add Header Video if WP version < 4.7
		if (!function_exists('get_header_video_url')) {
			maxinet_storage_set_array_after('options', 'header_image_override', 'header_video', array(
				"title" => esc_html__('Header video', 'maxinet'),
				"desc" => wp_kses_data( __("Select video to use it as background for the header", 'maxinet') ),
				"override" => array(
					'mode' => 'page',
					'section' => esc_html__('Header', 'maxinet')
				),
				"std" => '',
				"type" => "video"
				)
			);
		}

		// Add option 'logo' if WP version < 4.5
		// or 'custom_logo' if current page is 'Theme Options'
		if (!function_exists('the_custom_logo') || (isset($_REQUEST['page']) && $_REQUEST['page']=='theme_options')) {
			maxinet_storage_set_array_before('options', 'logo_retina', function_exists('the_custom_logo') ? 'custom_logo' : 'logo', array(
				"title" => esc_html__('Logo', 'maxinet'),
				"desc" => wp_kses_data( __('Select or upload the site logo', 'maxinet') ),
				"class" => "maxinet_column-1_2 maxinet_new_row",
				"priority" => 60,
				"std" => '',
				"type" => "image"
				)
			);
		}
	}
}


// Returns a list of options that can be overridden for CPT
if (!function_exists('maxinet_options_get_list_cpt_options')) {
	function maxinet_options_get_list_cpt_options($cpt, $title='') {
		if (empty($title)) $title = ucfirst($cpt);
		return array(
					"header_info_{$cpt}" => array(
						"title" => esc_html__('Header', 'maxinet'),
						"desc" => '',
						"type" => "info",
						),
					"header_type_{$cpt}" => array(
						"title" => esc_html__('Header style', 'maxinet'),
						"desc" => wp_kses_data( __('Choose whether to use the default header or header Layouts (available only if the ThemeREX Addons is activated)', 'maxinet') ),
						"std" => 'inherit',
						"options" => maxinet_get_list_header_footer_types(true),
						"type" => MAXINET_THEME_FREE ? "hidden" : "switch"
						),
					"header_style_{$cpt}" => array(
						"title" => esc_html__('Select custom layout', 'maxinet'),
						// Translators: Add CPT name to the description
						"desc" => wp_kses_data( sprintf(__('Select custom layout to display the site header on the %s pages', 'maxinet'), $title) ),
						"dependency" => array(
							"header_type_{$cpt}" => array('custom')
						),
						"std" => 'inherit',
						"options" => array(),
						"type" => MAXINET_THEME_FREE ? "hidden" : "select"
						),
					"header_position_{$cpt}" => array(
						"title" => esc_html__('Header position', 'maxinet'),
						// Translators: Add CPT name to the description
						"desc" => wp_kses_data( sprintf(__('Select position to display the site header on the %s pages', 'maxinet'), $title) ),
						"std" => 'inherit',
						"options" => array(),
						"type" => MAXINET_THEME_FREE ? "hidden" : "switch"
						),
					"header_image_override_{$cpt}" => array(
						"title" => esc_html__('Header image override', 'maxinet'),
						"desc" => wp_kses_data( __("Allow override the header image with the post's featured image", 'maxinet') ),
						"std" => 0,
						"type" => MAXINET_THEME_FREE ? "hidden" : "checkbox"
						),
					"header_widgets_{$cpt}" => array(
						"title" => esc_html__('Header widgets', 'maxinet'),
						// Translators: Add CPT name to the description
						"desc" => wp_kses_data( sprintf(__('Select set of widgets to show in the header on the %s pages', 'maxinet'), $title) ),
						"std" => 'hide',
						"options" => array(),
						"type" => "select"
						),
						
					"sidebar_info_{$cpt}" => array(
						"title" => esc_html__('Sidebar', 'maxinet'),
						"desc" => '',
						"type" => "info",
						),
					"sidebar_position_{$cpt}" => array(
						"title" => esc_html__('Sidebar position', 'maxinet'),
						// Translators: Add CPT name to the description
						"desc" => wp_kses_data( sprintf(__('Select position to show sidebar on the %s pages', 'maxinet'), $title) ),
						"refresh" => false,
						"std" => 'left',
						"options" => array(),
						"type" => "switch"
						),
					"sidebar_widgets_{$cpt}" => array(
						"title" => esc_html__('Sidebar widgets', 'maxinet'),
						// Translators: Add CPT name to the description
						"desc" => wp_kses_data( sprintf(__('Select sidebar to show on the %s pages', 'maxinet'), $title) ),
						"dependency" => array(
							"sidebar_position_{$cpt}" => array('left', 'right')
						),
						"std" => 'hide',
						"options" => array(),
						"type" => "select"
						),
					"hide_sidebar_on_single_{$cpt}" => array(
						"title" => esc_html__('Hide sidebar on the single pages', 'maxinet'),
						"desc" => wp_kses_data( __("Hide sidebar on the single page", 'maxinet') ),
						"std" => 0,
						"type" => "checkbox"
						),
						
					"footer_info_{$cpt}" => array(
						"title" => esc_html__('Footer', 'maxinet'),
						"desc" => '',
						"type" => "info",
						),
					"footer_type_{$cpt}" => array(
						"title" => esc_html__('Footer style', 'maxinet'),
						"desc" => wp_kses_data( __('Choose whether to use the default footer or footer Layouts (available only if the ThemeREX Addons is activated)', 'maxinet') ),
						"std" => 'inherit',
						"options" => maxinet_get_list_header_footer_types(true),
						"type" => MAXINET_THEME_FREE ? "hidden" : "switch"
						),
					"footer_style_{$cpt}" => array(
						"title" => esc_html__('Select custom layout', 'maxinet'),
						"desc" => wp_kses_data( __('Select custom layout to display the site footer', 'maxinet') ),
						"std" => 'inherit',
						"dependency" => array(
							"footer_type_{$cpt}" => array('custom')
						),
						"options" => array(),
						"type" => MAXINET_THEME_FREE ? "hidden" : "select"
						),
					"footer_widgets_{$cpt}" => array(
						"title" => esc_html__('Footer widgets', 'maxinet'),
						"desc" => wp_kses_data( __('Select set of widgets to show in the footer', 'maxinet') ),
						"dependency" => array(
							"footer_type_{$cpt}" => array('default')
						),
						"std" => 'footer_widgets',
						"options" => array(),
						"type" => "select"
						),
					"footer_columns_{$cpt}" => array(
						"title" => esc_html__('Footer columns', 'maxinet'),
						"desc" => wp_kses_data( __('Select number columns to show widgets in the footer. If 0 - autodetect by the widgets count', 'maxinet') ),
						"dependency" => array(
							"footer_type_{$cpt}" => array('default'),
							"footer_widgets_{$cpt}" => array('^hide')
						),
						"std" => 0,
						"options" => maxinet_get_list_range(0,6),
						"type" => "select"
						),
					"footer_wide_{$cpt}" => array(
						"title" => esc_html__('Footer fullwide', 'maxinet'),
						"desc" => wp_kses_data( __('Do you want to stretch the footer to the entire window width?', 'maxinet') ),
						"dependency" => array(
							"footer_type_{$cpt}" => array('default')
						),
						"std" => 0,
						"type" => "checkbox"
						),
						
					"widgets_info_{$cpt}" => array(
						"title" => esc_html__('Additional panels', 'maxinet'),
						"desc" => '',
						"type" => MAXINET_THEME_FREE ? "hidden" : "info",
						),
					"widgets_above_page_{$cpt}" => array(
						"title" => esc_html__('Widgets at the top of the page', 'maxinet'),
						"desc" => wp_kses_data( __('Select widgets to show at the top of the page (above content and sidebar)', 'maxinet') ),
						"std" => 'hide',
						"options" => array(),
						"type" => MAXINET_THEME_FREE ? "hidden" : "select"
						),
					"widgets_above_content_{$cpt}" => array(
						"title" => esc_html__('Widgets above the content', 'maxinet'),
						"desc" => wp_kses_data( __('Select widgets to show at the beginning of the content area', 'maxinet') ),
						"std" => 'hide',
						"options" => array(),
						"type" => MAXINET_THEME_FREE ? "hidden" : "select"
						),
					"widgets_below_content_{$cpt}" => array(
						"title" => esc_html__('Widgets below the content', 'maxinet'),
						"desc" => wp_kses_data( __('Select widgets to show at the ending of the content area', 'maxinet') ),
						"std" => 'hide',
						"options" => array(),
						"type" => MAXINET_THEME_FREE ? "hidden" : "select"
						),
					"widgets_below_page_{$cpt}" => array(
						"title" => esc_html__('Widgets at the bottom of the page', 'maxinet'),
						"desc" => wp_kses_data( __('Select widgets to show at the bottom of the page (below content and sidebar)', 'maxinet') ),
						"std" => 'hide',
						"options" => array(),
						"type" => MAXINET_THEME_FREE ? "hidden" : "select"
						)
					);
	}
}


// Return lists with choises when its need in the admin mode
if (!function_exists('maxinet_options_get_list_choises')) {
	add_filter('maxinet_filter_options_get_list_choises', 'maxinet_options_get_list_choises', 10, 2);
	function maxinet_options_get_list_choises($list, $id) {
		if (is_array($list) && count($list)==0) {
			if (strpos($id, 'header_style')===0)
				$list = maxinet_get_list_header_styles(strpos($id, 'header_style_')===0);
			else if (strpos($id, 'header_position')===0)
				$list = maxinet_get_list_header_positions(strpos($id, 'header_position_')===0);
			else if (strpos($id, 'header_widgets')===0)
				$list = maxinet_get_list_sidebars(strpos($id, 'header_widgets_')===0, true);
			else if (substr($id, -7) == '_scheme')
				$list = maxinet_get_list_schemes($id!='color_scheme');
			else if (strpos($id, 'sidebar_widgets')===0)
				$list = maxinet_get_list_sidebars(strpos($id, 'sidebar_widgets_')===0, true);
			else if (strpos($id, 'sidebar_position')===0)
				$list = maxinet_get_list_sidebars_positions(strpos($id, 'sidebar_position_')===0);
			else if (strpos($id, 'widgets_above_page')===0)
				$list = maxinet_get_list_sidebars(strpos($id, 'widgets_above_page_')===0, true);
			else if (strpos($id, 'widgets_above_content')===0)
				$list = maxinet_get_list_sidebars(strpos($id, 'widgets_above_content_')===0, true);
			else if (strpos($id, 'widgets_below_page')===0)
				$list = maxinet_get_list_sidebars(strpos($id, 'widgets_below_page_')===0, true);
			else if (strpos($id, 'widgets_below_content')===0)
				$list = maxinet_get_list_sidebars(strpos($id, 'widgets_below_content_')===0, true);
			else if (strpos($id, 'footer_style')===0)
				$list = maxinet_get_list_footer_styles(strpos($id, 'footer_style_')===0);
			else if (strpos($id, 'footer_widgets')===0)
				$list = maxinet_get_list_sidebars(strpos($id, 'footer_widgets_')===0, true);
			else if (strpos($id, 'blog_style')===0)
				$list = maxinet_get_list_blog_styles(strpos($id, 'blog_style_')===0);
			else if (strpos($id, 'post_type')===0)
				$list = maxinet_get_list_posts_types();
			else if (strpos($id, 'parent_cat')===0)
				$list = maxinet_array_merge(array(0 => esc_html__('- Select category -', 'maxinet')), maxinet_get_list_categories());
			else if (strpos($id, 'blog_animation')===0)
				$list = maxinet_get_list_animations_in();
			else if ($id == 'color_scheme_editor')
				$list = maxinet_get_list_schemes();
			else if (strpos($id, '_font-family') > 0)
				$list = maxinet_get_list_load_fonts(true);
		}
		return $list;
	}
}
?>