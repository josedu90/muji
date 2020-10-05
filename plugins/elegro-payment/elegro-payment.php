<?php
/* Elegro Crypto Payment support functions
------------------------------------------------------------------------------- */

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if ( ! function_exists( 'muji_elegro_payment_theme_setup9' ) ) {
	add_action( 'after_setup_theme', 'muji_elegro_payment_theme_setup9', 9 );
	function muji_elegro_payment_theme_setup9() {
		if ( muji_exists_elegro_payment() ) {
			add_filter( 'muji_filter_merge_styles', 'muji_elegro_payment_merge_styles' );
		}
		if ( is_admin() ) {
			add_filter( 'muji_filter_tgmpa_required_plugins', 'muji_elegro_payment_tgmpa_required_plugins' );
		}
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'muji_elegro_payment_tgmpa_required_plugins' ) ) {
	//Handler of the add_filter('muji_filter_tgmpa_required_plugins',	'muji_elegro_payment_tgmpa_required_plugins');
	function muji_elegro_payment_tgmpa_required_plugins($list=array()) {
		if (muji_storage_isset('required_plugins', 'elegro-payment')) {
			$list[] = array(
				'name' 		=> muji_storage_get_array('required_plugins', 'elegro-payment'),
				'slug' 		=> 'elegro-payment',
				'required' 	=> false
			);
		}
		return $list;
	}
}

// Check if this plugin installed and activated
if ( ! function_exists( 'muji_exists_elegro_payment' ) ) {
	function muji_exists_elegro_payment() {
		return class_exists( 'WC_Elegro_Payment' );
	}
}

// Merge custom styles
if ( ! function_exists( 'muji_elegro_payment_merge_styles' ) ) {
	function muji_elegro_payment_merge_styles( $list ) {
		$list[] = 'plugins/elegro-payment/_elegro-payment.scss';
		return $list;
	}
}

?>