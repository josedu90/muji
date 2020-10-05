<?php
/* WP GDPR Compliance support functions
------------------------------------------------------------------------------- */

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if ( ! function_exists( 'muji_wp_gdpr_compliance_feed_theme_setup9' ) ) {
	add_action( 'after_setup_theme', 'muji_wp_gdpr_compliance_theme_setup9', 9 );
	function muji_wp_gdpr_compliance_theme_setup9() {
		if ( is_admin() ) {
			add_filter( 'muji_filter_tgmpa_required_plugins', 'muji_wp_gdpr_compliance_tgmpa_required_plugins' );
		}
	}
}

// Filter to add in the required plugins list
if ( ! function_exists( 'muji_wp_gdpr_compliance_tgmpa_required_plugins' ) ) {
	//Handler of the add_filter('muji_filter_tgmpa_required_plugins',	'muji_wp_gdpr_compliance_tgmpa_required_plugins');
	function muji_wp_gdpr_compliance_tgmpa_required_plugins( $list = array() ) {
		if ( muji_storage_isset( 'required_plugins', 'wp-gdpr-compliance' ) ) {
			$list[] = array(
				'name'     => muji_storage_get_array( 'required_plugins', 'wp-gdpr-compliance' ),
				'slug'     => 'wp-gdpr-compliance',
				'required' => false,
			);
		}
		return $list;
	}
}

// Check if this plugin installed and activated
if ( ! function_exists( 'muji_exists_wp_gdpr_compliance' ) ) {
	function muji_exists_wp_gdpr_compliance() {
		return class_exists( 'WPGDPRC\WPGDPRC' );
	}
}


// One-click import support
//------------------------------------------------------------------------

// Check plugin in the required plugins
if ( !function_exists( 'muji_wp_gdpr_compliance_importer_required_plugins' ) ) {
	if (is_admin()) add_filter( 'trx_addons_filter_importer_required_plugins',	'muji_wp_gdpr_compliance_importer_required_plugins', 10, 2 );
	function muji_wp_gdpr_compliance_importer_required_plugins($not_installed='', $list='') {
		if (strpos($list, 'wp-gdpr-compliance')!==false && !muji_exists_wp_gdpr_compliance() )
			$not_installed .= '<br>' . esc_html__('WP GDPR Compliance', 'muji');
		return $not_installed;
	}
}

// Set plugin's specific importer options
if ( !function_exists( 'muji_wp_gdpr_compliance_importer_set_options' ) ) {
	if (is_admin()) add_filter( 'trx_addons_filter_importer_options',	'muji_wp_gdpr_compliance_importer_set_options' );
	function muji_wp_gdpr_compliance_importer_set_options($options=array()) {
		if ( muji_exists_wp_gdpr_compliance() && in_array('wp-gdpr-compliance', $options['required_plugins']) ) {
			if (is_array($options)) {
				$options['additional_options'][] = 'wpgdprc_%';
			}
		}
		return $options;
	}
}

// Prevent import plugin's specific options if plugin is not installed
if ( !function_exists( 'muji_wp_gdpr_compliance_importer_check_options' ) ) {
	if (is_admin()) add_filter( 'trx_addons_filter_import_theme_options', 'muji_wp_gdpr_compliance_importer_check_options', 10, 4 );
	function muji_wp_gdpr_compliance_importer_check_options($allow, $k, $v, $options) {
		if ($allow && strpos($k, 'wpgdprc_')===0) {
			$allow = muji_exists_wp_gdpr_compliance() && in_array('wp-gdpr-compliance', $options['required_plugins']);
		}
		return $allow;
	}
}

