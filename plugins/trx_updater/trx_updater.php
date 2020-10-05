<?php
/* TRX Updater support functions
------------------------------------------------------------------------------- */

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if (!function_exists('muji_trx_updater_theme_setup9')) {
	add_action( 'after_setup_theme', 'muji_trx_updater_theme_setup9', 9 );
	function muji_trx_updater_theme_setup9() {

		if (is_admin()) {
			add_filter( 'muji_filter_tgmpa_required_plugins',			'muji_trx_updater_tgmpa_required_plugins' );
		}
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'muji_trx_updater_tgmpa_required_plugins' ) ) {
	//Handler of the add_filter('muji_filter_tgmpa_required_plugins',	'muji_trx_updater_tgmpa_required_plugins');
	function muji_trx_updater_tgmpa_required_plugins($list=array()) {
		if (muji_storage_isset('required_plugins', 'trx_updater')) {
			$path = muji_get_file_dir('plugins/trx_updater/trx_updater.zip');
			// TRX Updater plugin
			$list[] = array(
				'name' 		=> muji_storage_get_array('required_plugins', 'trx_updater'),
				'slug' 		=> 'trx_updater',
				'version'	=> '1.4.1',
				'source'	=> !empty($path) ? $path : 'upload://trx_updater.zip',
				'required' 	=> false
			);
		}
		return $list;
	}
}

// Check if this plugin installed and activated
if ( !function_exists( 'muji_exists_trx_updater' ) ) {
	function muji_exists_trx_updater() {
		return function_exists( 'trx_updater_load_plugin_textdomain' );
	}
}
