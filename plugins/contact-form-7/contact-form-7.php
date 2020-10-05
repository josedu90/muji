<?php
/* Contact Form 7 support functions
------------------------------------------------------------------------------- */

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if (!function_exists('muji_cf7_theme_setup9')) {
	add_action( 'after_setup_theme', 'muji_cf7_theme_setup9', 9 );
	function muji_cf7_theme_setup9() {

		add_filter( 'muji_filter_merge_scripts', 'muji_cf7_merge_scripts' );
		add_filter( 'muji_filter_merge_styles', 'muji_cf7_merge_styles' );

		if ( muji_exists_cf7() ) {
			add_action( 'wp_enqueue_scripts', 'muji_cf7_frontend_scripts', 1100 );
		}

		if (is_admin()) {
			add_filter( 'muji_filter_tgmpa_required_plugins',			'muji_cf7_tgmpa_required_plugins' );
		}
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'muji_cf7_tgmpa_required_plugins' ) ) {
	//Handler of the add_filter('muji_filter_tgmpa_required_plugins',	'muji_cf7_tgmpa_required_plugins');
	function muji_cf7_tgmpa_required_plugins($list=array()) {
		if (muji_storage_isset('required_plugins', 'contact-form-7')) {
			// CF7 plugin
			$list[] = array(
					'name' 		=> muji_storage_get_array('required_plugins', 'contact-form-7'),
					'slug' 		=> 'contact-form-7',
					'required' 	=> false
			);
		}
		return $list;
	}
}

// Check if cf7 installed and activated
if ( !function_exists( 'muji_exists_cf7' ) ) {
	function muji_exists_cf7() {
		return class_exists('WPCF7');
	}
}

// Enqueue custom scripts
if ( ! function_exists( 'muji_cf7_frontend_scripts' ) ) {
	//Handler of the add_action( 'wp_enqueue_scripts', 'muji_cf7_frontend_scripts', 1100 );
	function muji_cf7_frontend_scripts() {
		if ( muji_exists_cf7() ) {
			if ( muji_is_on( muji_get_theme_option( 'debug_mode' ) ) ) {
				$muji_url = muji_get_file_url( 'plugins/contact-form-7/contact-form-7.js' );
				if ( '' != $muji_url ) {
					wp_enqueue_script( 'muji-cf7', $muji_url, array( 'jquery' ), null, true );
				}
			}
		}
	}
}

// Merge custom scripts
if ( ! function_exists( 'muji_cf7_merge_scripts' ) ) {
	//Handler of the add_filter('muji_filter_merge_scripts', 'muji_cf7_merge_scripts');
	function muji_cf7_merge_scripts( $list ) {
		if ( muji_exists_cf7() ) {
			$list[] = 'plugins/contact-form-7/contact-form-7.js';
		}
		return $list;
	}
}
	
// Merge custom styles
if ( !function_exists( 'muji_cf7_merge_styles' ) ) {
	//Handler of the add_filter('muji_filter_merge_styles', 'muji_cf7_merge_styles');
	function muji_cf7_merge_styles($list) {
		if (muji_exists_cf7()) {
			$list[] = 'plugins/contact-form-7/_contact-form-7.scss';
		}
		return $list;
	}
}
?>