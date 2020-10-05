<?php
/* Revolution Slider support functions
------------------------------------------------------------------------------- */

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if (!function_exists('muji_revslider_theme_setup9')) {
	add_action( 'after_setup_theme', 'muji_revslider_theme_setup9', 9 );
	function muji_revslider_theme_setup9() {

		add_filter( 'muji_filter_merge_styles',				'muji_revslider_merge_styles' );
		
		if (is_admin()) {
			add_filter( 'muji_filter_tgmpa_required_plugins','muji_revslider_tgmpa_required_plugins' );
		}
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'muji_revslider_tgmpa_required_plugins' ) ) {
	//Handler of the add_filter('muji_filter_tgmpa_required_plugins',	'muji_revslider_tgmpa_required_plugins');
	function muji_revslider_tgmpa_required_plugins($list=array()) {
		if (muji_storage_isset('required_plugins', 'revslider')) {
			$path = muji_get_file_dir('plugins/revslider/revslider.zip');
			if (!empty($path) || muji_get_theme_setting('tgmpa_upload')) {
				$list[] = array(
					'name' 		=> muji_storage_get_array('required_plugins', 'revslider'),
					'slug' 		=> 'revslider',
					'version'	=> '6.2.23',
					'source'	=> !empty($path) ? $path : 'upload://revslider.zip',
					'required' 	=> false
				);
			}
		}
		return $list;
	}
}

// Check if RevSlider installed and activated
if ( !function_exists( 'muji_exists_revslider' ) ) {
	function muji_exists_revslider() {
		return function_exists('rev_slider_shortcode');
	}
}
	
// Merge custom styles
if ( !function_exists( 'muji_revslider_merge_styles' ) ) {
	//Handler of the add_filter('muji_filter_merge_styles', 'muji_revslider_merge_styles');
	function muji_revslider_merge_styles($list) {
		if (muji_exists_revslider()) {
			$list[] = 'plugins/revslider/_revslider.scss';
		}
		return $list;
	}
}
?>