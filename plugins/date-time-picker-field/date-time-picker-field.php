<?php
/* Date & Time Picker support functions
------------------------------------------------------------------------------- */

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if ( ! function_exists( 'muji_date_time_picker_field_theme_setup9' ) ) {
	add_action( 'after_setup_theme', 'muji_date_time_picker_field_theme_setup9', 9 );
	function muji_date_time_picker_field_theme_setup9() {
		if ( is_admin() ) {
			add_filter( 'muji_filter_tgmpa_required_plugins', 'muji_date_time_picker_field_tgmpa_required_plugins' );
		}
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'muji_date_time_picker_field_tgmpa_required_plugins' ) ) {
	//Handler of the add_filter('muji_filter_tgmpa_required_plugins',	'muji_date_time_picker_field_tgmpa_required_plugins');
	function muji_date_time_picker_field_tgmpa_required_plugins($list=array()) {
		if (muji_storage_isset('required_plugins', 'date-time-picker-field')) {
			$list[] = array(
				'name' 		=> muji_storage_get_array('required_plugins', 'date-time-picker-field'),
				'slug' 		=> 'date-time-picker-field',
				'required' 	=> false
			);
		}
		return $list;
	}
}

// Check if this plugin installed and activated
if ( ! function_exists( 'muji_exists_date_time_picker_field' ) ) {
	function muji_exists_date_time_picker_field() {
		return class_exists( 'CMoreira\\Plugins\\DateTimePicker\\Init' );
	}
}

// Set plugin's specific importer options
if ( !function_exists( 'muji_date_time_picker_field_importer_set_options' ) ) {
	add_filter( 'trx_addons_filter_importer_options',	'muji_date_time_picker_field_importer_set_options' );
	function muji_date_time_picker_field_importer_set_options($options=array()) {
		if ( muji_exists_date_time_picker_field() && in_array('date-time-picker-field', $options['required_plugins']) ) {
			if (is_array($options)) {
				$options['additional_options'][] = 'dtpicker';
			}
		}
		return $options;
	}
}
