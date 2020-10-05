<?php
/* Booked Appointments support functions
------------------------------------------------------------------------------- */

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if (!function_exists('muji_booked_theme_setup9')) {
	add_action( 'after_setup_theme', 'muji_booked_theme_setup9', 9 );
	function muji_booked_theme_setup9() {
		add_filter( 'muji_filter_merge_styles', 						'muji_booked_merge_styles' );
		if (is_admin()) {
			add_filter( 'muji_filter_tgmpa_required_plugins',		'muji_booked_tgmpa_required_plugins' );
		}
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'muji_booked_tgmpa_required_plugins' ) ) {
	//Handler of the add_filter('muji_filter_tgmpa_required_plugins',	'muji_booked_tgmpa_required_plugins');
	function muji_booked_tgmpa_required_plugins($list=array()) {
		if (muji_storage_isset('required_plugins', 'booked')) {
			$path = muji_get_file_dir('plugins/booked/booked.zip');
			if (!empty($path) || muji_get_theme_setting('tgmpa_upload')) {
				$list[] = array(
					'name' 		=> muji_storage_get_array('required_plugins', 'booked'),
					'slug' 		=> 'booked',
					'version'	=> '2.2.4',
					'source' 	=> !empty($path) ? $path : 'upload://booked.zip',
					'required' 	=> false
				);
			}
		}
		return $list;
	}
}

// Check if plugin installed and activated
if ( !function_exists( 'muji_exists_booked' ) ) {
	function muji_exists_booked() {
		return class_exists('booked_plugin');
	}
}
	
// Merge custom styles
if ( !function_exists( 'muji_booked_merge_styles' ) ) {
	//Handler of the add_filter('muji_filter_merge_styles', 'muji_booked_merge_styles');
	function muji_booked_merge_styles($list) {
		if (muji_exists_booked()) {
			$list[] = 'plugins/booked/_booked.scss';
		}
		return $list;
	}
}


// Add plugin-specific colors and fonts to the custom CSS
if (muji_exists_booked()) { require_once MUJI_THEME_DIR . 'plugins/booked/booked-styles.php'; }
?>