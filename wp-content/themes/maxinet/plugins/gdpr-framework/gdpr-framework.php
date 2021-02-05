<?php
/* Mail Chimp support functions
------------------------------------------------------------------------------- */

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if (!function_exists('maxinet_gdpr_theme_setup9')) {
	add_action( 'after_setup_theme', 'maxinet_gdpr_theme_setup9', 9 );
	function maxinet_gdpr_theme_setup9() {
		if (maxinet_exists_gdpr()) {
			add_filter( 'maxinet_filter_merge_styles',					'maxinet_gdpr_merge_styles');
		}
		if (is_admin()) {
			add_filter( 'maxinet_filter_tgmpa_required_plugins',		'maxinet_gdpr_tgmpa_required_plugins' );
		}
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'maxinet_gdpr_tgmpa_required_plugins' ) ) {
	//Handler of the add_filter('maxinet_filter_tgmpa_required_plugins',	'maxinet_gdpr_tgmpa_required_plugins');
	function maxinet_gdpr_tgmpa_required_plugins($list=array()) {
		if (maxinet_storage_isset('required_plugins', 'gdpr-framework')) {
			$list[] = array(
				'name' 		=> maxinet_storage_get_array('required_plugins', 'gdpr-framework'),
				'slug' 		=> 'gdpr-framework',
				'required' 	=> false
			);
		}
		return $list;
	}
}

// Check if plugin installed and activated
if ( !function_exists( 'maxinet_exists_gdpr' ) ) {
	function maxinet_exists_gdpr() {
		return function_exists('__gdpr_load_plugin') || defined('GDPR_VERSION');
	}
}


